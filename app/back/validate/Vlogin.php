<?php
declare (strict_types = 1);

namespace app\back\validate;

use think\Validate;

/**
 * Class Login 登录控制器的验证器类
 * @package app\back\validate
 */
class Vlogin extends Validate
{
    protected $rule =   [
        'username'  => 'require',
        'password'  => 'require|min:6|alphaDash'
    ];

    protected $message  =   [
        'username.require'   => '用户名必须',
        'password.length'    => '密码必须',
        'password.min'       => '密码不能少于6个字符',
        'password.alphaDash' => '密码只能包含数字，字母，下划线(_)及破折号(-)'
    ];

}