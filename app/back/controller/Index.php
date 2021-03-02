<?php
declare(strict_types = 1);

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
        $roseModel = new RoseModel;
        $ruleModel = new RuleModel;
        if ($adminInfo['nick_name'] != 'admin') {
            // 当前登录的管理员权限
            $roseid = $adminInfo['rose_id'];
            $roseInfo = $roseModel->getInfo(['id' => $roseid]);
            $ruleArr = explode(',', $roseInfo['rule']);
            
            $list = $ruleModel->where(['status' => 1])->whereIn('id', $ruleArr)->select()->toArray();
        } else {
            $list = $ruleModel->getAllList(['status' => 1]);
        }

        $listTree = cats_tree($list);

        return view('index', ['listTree' => $listTree, 'admin_name' => $adminInfo['nick_name']]);
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
}
