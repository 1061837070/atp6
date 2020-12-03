<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\RoseModel;

/**
 * 后台 角色 控制器
 */
class Rose extends BaseController
{
    public function index()
    {
        return 'rose';
    }

    /**
     * [addSuperRose 后台添加超级管理员角色角色]
     */
    public function addSuperRose()
    {
        $params['name'] = '超级管理员';

        $roseModel = new RoseModel;

        $exit1 = $roseModel->getInfo(['name' => $params['name']]);
        if ($exit1) {
            return '超级管理员已存在';
        }

        $params['sort'] = 0;
        $params['rule'] = '*';
        $params['created_at'] = time();

        $res = $roseModel->addData($params);
        print_r($res);
    }

}
