<?php
declare(strict_types = 1);

namespace app\home;

use think\App;
use think\facade\Request;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 无需登录验证的操作
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * @msg: 构造方法
     * @param {$app 应用对象}
     * @return {*}
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    /**
     * @msg: 前置操作
     * @param {*}
     * @return {*}
     */
    protected function initialize()
    {
    }
}
