<?php
declare (strict_types = 1);

namespace app\back\controller;

use app\back\BaseController;

/**
 * Class Err 后台错误自定义提示
 * @package app\back\controller
 */
class Err extends BaseController
{
    protected $noNeedLogin = ['err'];

    /**
     * @msg: 公告错误信息提示页面
     * @param {*}
     * @return {*}
     */
    function err ()
    {
        $params = request()->param();

        $icon = $params['iconstr'] ?? 'layui-icon-404';
        $msgstr = $params['msgstr'] ?? '操作错误，';
        $urlstr = str_replace('*','/',$params['urlstr']) ?? '';
        $btnstr = $params['btnstr'] ?? '';

        return view('err', ['icon' => $icon, 'msgstr' => $msgstr, 'urlstr' => $urlstr, 'btnstr' => $btnstr]);
    }

}
