<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;
use app\back\validate\Vlogin;
use think\exception\ValidateException;

/**
 * Class Login 后台 登录
 * @package app\back\controller
 */
class Login extends BaseController
{
    protected $noNeedLogin = ['login'];

    function login()
    {
        if (request()->isAjax()) {
            $params = request()->param();
            try {
                validate(Vlogin::class)->check([
                    'username'  => $params['username'],
                    'password' => $params['password'],
                ]);
            } catch (ValidateException $e) {
                return json(['code' => 400, 'msg' => $e->getError()]);
            }

            $adminModel = new AdminModel;
            $adminInfo = $adminModel->getInfo(['nick_name' => $params['username']]);
            if (empty($adminInfo)) {
                return json(['code' => 401, 'msg' => '用户名不存在']);
            }
            if (set_enc_word($adminInfo['pwd_salt'], $params['password'], 'back') !=  $adminInfo['password']) {
                return json(['code' => 402, 'msg' => '密码错误']);
            }
            if ($adminInfo['status'] == 2) {
                return json(['code' => 403, 'msg' => '该账号被禁用']);
            }

            session('adminid', $adminInfo['id']);
            session('adminkey', set_enc_key($adminInfo['nick_name'],$adminInfo['phone'],$adminInfo['password'],$adminInfo['status']));

            return json(['code' => 200, 'msg' => '登录成功']);
        }
        return view();
    }
}
