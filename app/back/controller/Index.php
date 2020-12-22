<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;
use app\back\model\RoseModel;
use app\back\model\RuleModel;

/**
 * 后台首页
 */
class Index extends BaseController
{
    /**
     * @msg: 后台左侧菜单栏
     * @param {*}
     * @return {*}
     */
    public function index()
    {
        // 当前登录的管理员详情
        $adminModel = new AdminModel;
        $adminid = session('adminid');
        $adminInfo = $adminModel->getInfo(['id' => $adminid]);

        $where = [];
        if ($adminInfo['nick_name'] != 'admin') {
            // 当前登录的管理员权限
            $roseModel = new RoseModel;
            $roseid = $adminInfo['rose_id'];
            $roseInfo = $roseModel->getInfo(['id' => $roseid]);
            $ruleArr = explode(',', $roseInfo['rule']);

            $where['id'] = ['in', $ruleArr];
        }
        $ruleModel = new RuleModel;
        $list = $ruleModel->getAllList($where);
        $listTree = cats_tree($list);
        $menu = $this->build_menu($listTree);

        return view('index', ['menu' => $menu]);
    }

    /**
     * @msg: 进入后台时默认显示页面
     * @param {*}
     * @return {*}
     */
    public function welcome()
    {
        return view();
    }

    /**
     * @msg: 将菜单数组渲染成菜单页面元素
     * @param {*}
     * @return {*}
     */
    public function build_menu($treeArr)
    {
        $html = '';
        foreach ($treeArr as $k => $v) {
            $html .= '<li>
                    <a href="javascript:;">
                        <cite>'.$v['name'].'</cite>
                        <i class="iconfont nav_right">&#xe6a7;</i>
                    </a>
                    <ul class="sub-menu">';
            if (!empty($v['children'])) {
                foreach ($v['children'] as $k2 => $v2) {
                    $html .= '<li>
                            <a onclick="xadmin.add_tab('."'".$v2["name"]."'".','."'".$v2["url"]."'".',true)">
                                <i class="iconfont left-nav-li" lay-tips="'.$v2['name'].'"></i>
                                <cite>'.$v2['name'].'</cite>
                            </a>
                        </li>';
                }
            }
            $html .= '</ul>
                </li>';
        }

        return $html;
    }

}
