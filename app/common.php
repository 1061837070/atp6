<?php
// 应用公共文件

declare (strict_types = 1);

if (!function_exists('ccc')) {
    /**
     * [cc 测试]
     * @param  string $name  [description]
     * @param  string $pname [description]
     * @return [type]        [description]
     */
    function cc(string $name, string $pname): string
    {
        return $name . $pname;
    }
}
