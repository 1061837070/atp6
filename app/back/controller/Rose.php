<?php
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
    public function index()
    {
        return 'rose';
    }

    /**
     * 后台添加超级管理员角色角色 初始化时直接浏览器地址操作  只能一次
     * @return RoseModel|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
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
        return $res;
    }

}
