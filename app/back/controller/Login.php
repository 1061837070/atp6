<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;
use app\back\model\AdminModel;

/**
 * Class Login 后台 登录
 * @package app\back\controller
 */
class Login extends BaseController
{
    function login()
    {
        return view();
    }
}
