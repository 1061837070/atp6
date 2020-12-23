<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\RoseModel;
use app\back\validate\Vrose;
use think\exception\ValidateException;
use think\facade\Db;

/**
 * Class Rose 后台 角色
 * @package app\back\controller
 */
class Rose extends BaseController
{
    protected $noNeedLogin = ['addSuperRose'];

    /**
     * @msg: 角色 列表
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        if (request()->isAjax()) {
            $list = Db::table('tp6_rose')
                ->alias('a')
                ->leftJoin('tp6_admin ca','a.created_id = ca.id')
                ->leftJoin('tp6_admin ua','a.updated_id = ua.id')
                ->field('a.*')
                ->field('ca.nick_name as cname')
                ->field('ua.nick_name as uname')
                ->order(['a.sort','a.id'])
                ->select()
                ->toArray();
            return json(['code' => 200, 'data' => $list]);
        }
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'setRule', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;
    
        return view('index',['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
    }

    /**
     * @msg: 添加角色
     * @param {*}
     * @return {*}
     */
    public function add()
    {
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            
            try {
                validate(Vrose::class)->check([
                    'name'  => $params['name'],
                    'sort' => $params['sort']
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            $roseModel = new RoseModel;
            $eixt = $roseModel->getInfo(['name' => $params['name']]);
            if (!empty($eixt)) {
                return json(['code' => 400, 'msg' => '角色名称已存在']);
            }

            // 添加数据
            $params['created_at'] = time();
            $params['created_id'] = session('adminid');
            
            $res = $roseModel->addData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '添加成功']);
            } else {
                return json(['code' => 400, 'msg' => '添加失败']);
            }
        }
        return view('add');
    }

    /**
     * @msg: 编辑角色
     * @param {*}
     * @return {*}
     */
    public function edit()
    {
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $params = trim_arr($params);
            
            try {
                validate(Vrose::class)->check([
                    'name'  => $params['name'],
                    'sort' => $params['sort']
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            $roseId = intval($params['id']);

            $roseInfo = $roseModel->getInfo(['id' => $roseId]);
            if ($roseInfo['name'] == '超级管理员') {
                return json(['code' => 400, 'msg' => '禁止编辑超级管理员']);
            }
            // 检测角色名称是否已存在
            $exit1 = $roseModel->checkExit(['name' => $params['name']], $roseId);
            if ($exit1) {
                return json(['code' => 400, 'msg' => '角色名称已存']);
            }
            
            // 修改数据
            $res = $roseModel->upData($params);
            if ($res) {
                return json(['code' => 200, 'msg' => '修改成功']);
            } else {
                return json(['code' => 400, 'msg' => '修改失败']);
            }
        }

        $params = request()->param();
        $roseId = intval(trim($params['id']));
        // 编辑的角色详情
        $roseInfo = $roseModel->getInfo(['id' => $roseId]);
        if (empty($roseInfo)) {
            $icon = '/iconstr/layui-icon-404';
            $msgstr = '/msgstr/编辑的角色详情不存在，请重新确认';
            $url = str_replace('/','*','/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($roseInfo['name'] == '超级管理员') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/禁止编辑超级管理员';
            $url = str_replace('/','*','/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }

        return view('edit', ['roseInfo'=>$roseInfo]);
    }


    /**
     * @msg: 后台添加超级管理员角色角色 初始化时直接浏览器地址操作  只能一次
     * @param {*}
     * @return {*}
     */
    public function addSuperRose()
    {
        $params['name'] = '超级管理员';

        $roseModel = new RoseModel;

        $exit1 = $roseModel->getInfo(['name' => $params['name']]);
        if ($exit1) {
            return '超级管理员角色已存在';
        }

        $params['sort'] = 0;
        $params['rule'] = '*';
        $params['created_at'] = time();
        $params['created_id'] = 1;

        $res = $roseModel->addData($params);
        if ($res) {
            return '超级管理员角色添加成功';
        } else {
            return '超级管理员角色添加失败';
        }
    }

}
