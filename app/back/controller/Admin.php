<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;

/**
 * Class Admin 后台 管理员账号
 * @package app\back\controller
 */
class Admin extends BaseController
{
    public function index()
    {
        return 'admin';
    }

    /**
     * 添加超级管理员 初始化时直接浏览器地址操作 只能添加一次
     * @return AdminModel|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function addSuperAdmin()
    {
        $params['nick_name'] = 'admin';
        $params['true_name'] = 'admin';

        $adminModel = new AdminModel;

        $exit1 = $adminModel->getInfo(['nick_name' => $params['nick_name']]);
        if ($exit1) {
            return '超级管理员已存在';
        }

        $params['phone'] = '';
        $pwd = '123456';
        $params['pwd_salt'] = bin2hex(random_bytes(3));
        $params['password'] = setEncWord($params['pwd_salt'], $pwd, $params['phone']);
        $params['rose_id'] = 1;
        $params['created_at'] = time();
        $params['created_id'] = 1;

        $res = $adminModel->addData($params);
        return $res;
    }

}
