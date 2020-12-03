<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;

/**
 * 后台首页
 */
class Index extends BaseController
{
    public function index()
    {
        return view();
    }

    public function welcome()
    {
        return view();
    }

}
