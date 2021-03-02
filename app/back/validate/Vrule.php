<?php
declare(strict_types = 1);

namespace app\back\validate;

use think\Validate;

/**
 * Class Vrule 功能规则菜单验证
 * @package app\back\validate
 */
class Vrule extends Validate
{
    protected $rule =   [
        'name'  => 'require',
        'pid'  => 'require|integer|min:0',
        'url'  => 'require|checkUrl',
        'sort'  => 'integer|min:0',
        'type'  => 'in:1,2',
    ];

    protected $message  =   [
        'name.require'   => '功能名称不能为空',
        'pid.require'   => '必须选择父级分类',
        'pid.integer'   => '父级分类id必须是整数',
        'pid.min'   => '父级分类id不能为负数',
        'url.require'   => '功能URL不能为空',
        'url.checkUrl'   => '功能URL格式错误',
        'sort.integer'   => '功能排序必须是整数',
        'sort.min'   => '功能排序不能为负数',
        'type.in'   => '功能类型值错误',
    ];

    /**
     * 自定义验证规则 验证Url
     * @param string $value
     * @return bool|string
     */
    protected function checkUrl(string $value)
    {
        if (!preg_match('/^(\/)$|^(\/back\/([a-zA-Z]+)\/([a-zA-Z]+))$/i', $value)) {
            return 'URL格式错误';
        } else {
            return true;
        }
    }
}
