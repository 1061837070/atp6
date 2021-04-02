<?php
declare(strict_types = 1);

namespace app\back\validate;

use think\Validate;

// 轮播图验证
class Vcarouse extends Validate
{
    protected $rule =   [
        'url'  => 'require|url',
        'car_pic'  => 'require'
    ];

    protected $message  =   [
        'url.require'   => 'url不能为空',
        'url.url'   => 'url格式错误',
        'car_pic.require'   => '轮播图不能为空'
    ];
}
