<?php
declare(strict_types = 1);

namespace app\back\validate;

use think\Validate;

/**
 * Class Vrose 角色验证验证
 * @package app\back\validate
 */
class Vrose extends Validate
{
    protected $rule =   [
        'name'  => 'require',
        'sort'  => 'integer|min:0'
    ];

    protected $message  =   [
        'name.require'   => '角色名称不能为空',
        'sort.integer'   => '角色排序必须是整数',
        'sort.min'   => '角色排序不能为负数'
    ];
}
