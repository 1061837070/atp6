<?php
// 应用公共文件

declare (strict_types = 1);

if (!function_exists('p')) {
    /**
     * 自定义函数 打印操作
     * @param $param
     */
    function p($param)
    {
        echo "<pre>";
        print_r($param);
        echo "<pre>";
        die();
    }
}

if (!function_exists('setEncWord')) {
    /**
     * 生成加密字符串
     * @param string $a
     * @param string $b
     * @param string $c
     * @return string
     */
    function setEncWord(string $a, string $b, string $c): string
    {
        $str = md5(md5($a).md5($b).md5($c));
        return $str;
    }
}