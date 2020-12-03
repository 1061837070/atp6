<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;

/**
 * 后台 后台管理员账号
 */
class Admin extends BaseController
{
    public function index()
    {
        return 'admin';
    }

    /**
     * [addSuperAdmin 添加超级管理员]
     * @return [type] [description]
     */
    public function addSuperAdmin()
    {
        $params['nick_name'] = 'admin';
        $params['true_name'] = 'admin';

        $exit1 = model('Admin')->getInfo(['nick_name' => $params['nick_name']]);
        if ($exit1) {
            $this->jsonresponseerror(401, '昵称已存在');
        }

        $params['phone'] = '';
        $pwd = '123456';
        $params['pwd_salt'] = $this->getStr();
        $params['password'] = $this->setEncWord($params['pwd_salt'], $pwd, 'back');
        $params['rose_id'] = 1;
        $params['created_at'] = time();

        $res = model('Admin')->addData($params);
        if ($res) {
            $this->jsonresponsesuccess(200, '添加成功');
        } else {
            $this->jsonresponseerror(400, '添加失败');
        }
    }

}
