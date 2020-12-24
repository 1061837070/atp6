<?php
declare (strict_types = 1);

namespace app\back\validate;

use think\Validate;

/**
 * Class Vrose 管理员验证
 * @package app\back\validate
 */
class Vadmin extends Validate
{
    protected $rule =   [
        'nick_name'  => 'require',
        'phone'  => 'require',
        'rose_id'  => 'require|integer|min:0',
        'email'  => 'email'
    ];

    protected $message  =   [
        'nick_name.require'   => '管理员昵称不能为空',
        'phone.require'   => '手机号不能为空',
        'rose_id.require'   => '必须选择角色',
        'rose_id.integer'   => '选择角色错误',
        'rose_id.min'   => '选择角色错误',
        'email.email'   => '邮箱格式错误'
    ];
}