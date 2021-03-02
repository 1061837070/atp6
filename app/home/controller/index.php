<?php
declare(strict_types = 1);

namespace app\home\controller;

use app\home\BaseController;

/**
 * Class Index  前端首页
 * @package app\home\controller
 */
class Index extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function index2()
    {
        return view('index2');
    }
}
