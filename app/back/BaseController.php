<?php
declare (strict_types = 1);

namespace app\back;

use think\App;
use app\back\model\AdminModel;
use app\back\model\RuleModel;
use app\back\model\RoseModel;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 无需登录验证的操作
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * @msg: 构造方法
     * @param {$app 应用对象}
     * @return {*}
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    /**
     * @msg: 前置操作
     * @param {*}
     * @return {*}
     */
    protected function initialize()
    {
        $noNeedLoginArr = $this->noNeedLogin;
        $action = request()->action();

        //登录用户
        $adminModel = new AdminModel;
        $adminid = session('adminid');
        $adminInfo = $adminModel->getInfo(['id' => $adminid]);
        // 登录操作检测
        $isMachEnckey = false;
        if (!empty($adminInfo)) {
            $adminkey1 = session('adminkey');
            $adminkey2 = set_enc_key($adminInfo['nick_name'],$adminInfo['phone'],$adminInfo['password'],$adminInfo['status']);
            $isMachEnckey = $adminkey1 == $adminkey2 ? true : false;
        }

        if (empty($adminInfo) || !$isMachEnckey) {
            //用户未登录或登录异常
            session('adminid', null);
            session('adminkey', null);
            if (!in_array($action, $noNeedLoginArr)) {
                //需要登录验证的操作先登录再操作，不需要验证的跳过
                $icon = '/iconstr/layui-icon-face-cry';
                $msgstr = '/msgstr/您未登录，请登录后操作，';
                $url = str_replace('/','*','/back/login');
                $urlstr = '/urlstr/'.$url;
                $btnstr = '/btnstr/登录';
                if (request()->isAjax()) {
                    return json(['code' => 400, 'msg' => '您未登录，请登录后操作']);
                } else {
                    redirect('/back/err/err'.$icon.$msgstr.$urlstr.$btnstr)->send();
                }
                die();
            }
        } else {
            //用户已登录，再次登录时提醒
            if ($action == 'login') {
                $icon = '/iconstr/layui-icon-face-cry';
                $msgstr = '/msgstr/您已登录，请勿重复操作，';
                $url = str_replace('/','*','/back/index/index');
                $urlstr = '/urlstr/'.$url;
                $btnstr = '/btnstr/首页';

                redirect('/back/err/err'.$icon.$msgstr.$urlstr.$btnstr)->send();
                die();
            }
        }
    }

    /**
     * @msg: 展示的操作按钮 按钮的lay-event=""名和$action名必须一致才能生成功能按钮
     * @param {array $dbtns  默认展示的功能按钮 编辑，删除，['edit','del']}
     * @return {*}
     */
    public function show_btn($dbtns = [])
    {
        $module = 'back';
        $controller = request()->controller(true);
        $action = request()->action();
        // 当前操作
        $path = '/'.$module.'/'.$controller.'/'.$action;
        //当前操作下的按钮功能
        $ruleModel = new RuleModel;
        $ruleInfo = $ruleModel->getInfo(['url' => $path]);
        $btns =  $ruleModel->getAllList(['pid' => $ruleInfo['id'], 'type' => 2]);
        $btnIds = array_column($btns, 'id');

        // 当前登录管理员的权限
        $adminModel = new AdminModel;
        $adminid = session('adminid');
        $adminInfo = $adminModel->getInfo(['id' => $adminid]);

        // 当前登录管理员拥有该操作下的功能按钮
        if ($adminInfo['nick_name'] == 'admin') {
            $showBtnIds = $btnIds;
        } else {
            $roseModel = new RoseModel;
            $rosrInfo = $roseModel->getInfo(['id' => $adminInfo['rose_id']]);
            $rule = explode(',', $rosrInfo['rule']);

            $showBtnIds = [];
            foreach ($btnIds as $k => $v) {
                if (in_array($v, $rule)) {
                    array_push($showBtnIds, $btnIds[$k]);
                }
            }
        }
        $showBtns = RuleModel::whereIn('id',$showBtnIds)->select()->toArray();

        // 当前登录管理员拥有该操作下的功能按钮的url
        $showUrl = array_column($showBtns, 'url');

        // 当前登录管理员拥有该操作下的功能按钮的url转action add,del..  用于生成对应按钮
        $showAction = [];
        foreach ($showUrl as $k => $v) {
            array_push($showAction, substr($v, strripos($v, '/')+1));
        }

        // 存在的需要展示的功能按钮
        $makeBtns = [];
        $dbtns = empty($dbtns) ? ['edit','del'] : $dbtns;
        foreach ($dbtns as $k => $v) {
            if (in_array($v, $showAction)) {
                array_push($makeBtns, $v);
            }
        }

        // 生成页面的操作按钮
        $html = '';
        foreach ($makeBtns as $k => $v) {
            switch ($v) {
                case 'stop':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-danger stop" lay-event="stop">禁用</button>';
                    break;
                case 'del':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-danger del" lay-event="del">删除</button>';
                    break;
                case 'add':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal add" lay-event="add">添加</button>';
                    break;
                case 'edit':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal edit" lay-event="edit">编辑</button>';
                    break;
                case 'detail':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal detail" lay-event="detail">详情</button>';
                    break;
                case 'start':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal start" lay-event="start">启用</button>';
                    break;
                case 'resetPwd':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal resetPwd" lay-event="resetPwd">重置密码</button>';
                    break;
                case 'setRose':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal setRose" lay-event="setRose">分配角色</button>';
                    break;
                case 'setRule':
                    $html .= '<button class="layui-btn layui-btn-sm layui-btn-normal setRule" lay-event="setRule">分配权限</button>';
                    break;
            }
        }

        return $html;
    }

}
