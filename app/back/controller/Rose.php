<?php
/*
 * @Descripttion: 
 */
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\RoseModel;

/**
 * Class Rose 后台 角色
 * @package app\back\controller
 */
class Rose extends BaseController
{
    protected $noNeedLogin = ['addSuperRose'];

    public function index()
    {
        $hbtns = $this->show_btn(['add']);
        $mbtns = $this->show_btn(['edit', 'setRule', 'del']);
        $isOperate = empty($mbtns) ? 0 : 1;
    p($mbtns);
        return view('index',['hbtns'=>$hbtns,'mbtns'=>$mbtns,'isOperate'=>$isOperate]);
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
