<?php
declare(strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\RoseModel;
use app\back\model\RuleModel;
use app\back\model\AdminModel;
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
                ->leftJoin('tp6_admin ca', 'a.created_id = ca.id')
                ->leftJoin('tp6_admin ua', 'a.updated_id = ua.id')
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
    
        return view('index', ['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
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
            $url = str_replace('/', '*', '/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($roseInfo['name'] == '超级管理员') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/禁止编辑超级管理员';
            $url = str_replace('/', '*', '/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }

        return view('edit', ['roseInfo'=>$roseInfo]);
    }

    /**
     * @msg: 删除角色
     * @param {*}
     * @return {*}
     */
    public function del()
    {
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $roseId = intval($params['id']);
            
            $roseInfo = $roseModel->getInfo(['id' => $roseId]);
            if (empty($roseInfo)) {
                return json(['code' => 400, 'msg' => '删除的角色不存在']);
            }
            if ($roseInfo['name'] == '超级管理员') {
                return json(['code' => 400, 'msg' => '禁止删除超级管理员']);
            }
            
            // 删除数据
            $res = $roseModel->delData(['id' => $roseId]);
            if ($res) {
                return json(['code' => 200, 'msg' => '删除成功']);
            } else {
                return json(['code' => 400, 'msg' => '删除失败']);
            }
        }
    }

    /**
     * @msg: 角色分配权限
     * @param {*}
     * @return {*}
     */
    public function setRule()
    {
        $roseModel = new RoseModel;
        if (request()->isAjax()) {
            $params = request()->param();
            $roseid = intval($params['roseId']);
            $info = $roseModel->getInfo(['id' => $roseid]);

            if (empty($info)) {
                return json(['code' => 400, 'msg' => '角色不存在']);
            }
            if ($info['name'] == '超级管理员') {
                return json(['code' => 400, 'msg' => '禁止给超级管理员分配权限']);
            }
            if (empty($params['ids'])) {
                return json(['code' => 400, 'msg' => '功能权限不能为空']);
            }

            // 验证传输的功能id
            $arr = tree_to_two($params['ids']);
            $ruleIdArr = array_column($arr, 'id');
            
            $ruleModel = new RuleModel;
            $ruleList = $ruleModel->whereIn('id', $ruleIdArr)->select()->toArray();
            if (count($ruleIdArr) != count($ruleList)) {
                return json(['code' => 400, 'msg' => '选择了错误的功能']);
            }

            // 更新
            sort($ruleIdArr);
            $data['rule'] = implode(',', $ruleIdArr);
            $data['id'] = $roseid;
            $res = $roseModel->upData($data);
            if ($res) {
                return json(['code' => 200, 'msg' => '操作成功']);
            } else {
                return json(['code' => 400, 'msg' => '操作失败']);
            }
        }
        $params = request()->param();
        $roseId = intval($params['id']);
        $roseInfo = $roseModel->getInfo(['id' => $roseId]);
        // 角色详情
        if (empty($roseInfo)) {
            $icon = '/iconstr/layui-icon-404';
            $msgstr = '/msgstr/角色不存在，请重新确认';
            $url = str_replace('/', '*', '/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }
        if ($roseInfo['name'] == '超级管理员') {
            $icon = '/iconstr/layui-icon-close-fill';
            $msgstr = '/msgstr/禁止给超级管理员分配权限';
            $url = str_replace('/', '*', '/back/login');
            $urlstr = '/urlstr/'.$url;
            redirect('/back/err/err'.$icon.$msgstr.$urlstr)->send();
            die();
        }

        return view('setRule', ['roseInfo'=>$roseInfo]);
    }

    /**
     * @msg: 获取角色可分配的权限树
     * @param {*}
     * @return {*}
     */
    public function getRuleTree()
    {
        if (request()->isAjax()) {
            $params = request()->param();
            $roseId = intval($params['roseId']);
            $type = intval($params['type']);

            $roseModel = new RoseModel;
            $ruleModel = new RuleModel;
            // 进行权限分配的角色详情 获取进行分配的角色已有的权限
            $roseInfo = $roseModel->getInfo(['id' => $roseId]);
            if (empty($roseInfo) || $roseInfo['name'] == '超级管理员') {
                return json(['code' => 400, 'msg' => '角色错误', 'data' => []]);
            }
            $ruleIdsArr = $roseInfo['rule'] ? explode(',', $roseInfo['rule']) : [];
            $ruleList = $ruleModel->whereIn('id', $ruleIdsArr)->select()->toArray();
            $ruleTree = cats_tree($ruleList);
            // 因为layui tree组件直接勾选父级后所有子集都会勾选，故选取无子集的节点
            $noChildStr = get_no_childs_id_str($ruleTree);
            $noChildStr = trim($noChildStr, ',');
            $noChildArr = explode(',', $noChildStr);
            
            // 所有可选权限为当前登录管理员的权限
            $adminModel = new AdminModel;
            $adminInfo = $adminModel->getInfo(['id' => session('adminid')]);
            if ($adminInfo['nick_name'] == 'admin') {
                $adminRuleList = $ruleModel->getAllList();
            } else {
                $adminRule = $roseModel->getInfo(['id' => $adminInfo['rose_id']]);
                $adminRuleArr = explode(',', $adminRule['rule']);
                $adminRuleList = $ruleModel->whereIn('id', $adminRuleArr)->select()->toArray();
            }
            $list = [];
            foreach ($adminRuleList as $k => $v) {
                $list[$k]['id'] = $v['id'];
                $list[$k]['pid'] = $v['pid'];
                $list[$k]['title'] = $v['name'];
            }

            // type=1 查询roseId的功能权限 2全选 3全不选
            $tree = get_rule_tree($list, 0, $noChildArr, $type);

            return json(['code' => 200, 'msg' => '', 'data' => $tree]);
        }
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
