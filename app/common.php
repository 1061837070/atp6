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

if (!function_exists('set_enc_word')) {
    /**
     * 生成加密字符串
     * @param string $a
     * @param string $b
     * @param string $c
     * @return string
     */
    function set_enc_word(string $a, string $b, string $c): string
    {
        $str = md5(md5($a).md5($b).md5($c));
        return $str;
    }
}

if (!function_exists('set_enc_key')) {
    /**
     * 生成加密key
     * @param string $a
     * @param string $b
     * @param string $c
     * @param int $d
     * @return string
     */
    function set_enc_key(string $a, string $b, string $c, int $d): string
    {
        $str = md5(md5($a).md5($b).md5($c).$d);
        return $str;
    }
}

if (!function_exists('cats_tree')) {
    /**
     * 根据父级id生成无限极树形数组
     * @param array $cats
     * @param int $pid
     * @return array
     */
    function cats_tree(array $cats, int $pid = 0)
    {
        $result = [];
        foreach ($cats as $k => $v) {
            if ($v['pid'] == $pid) {
                unset($cats[$k]);
                $v['children'] = cats_tree($cats, $v['id']);
                $result[] = $v;
            }
        }
        return $result;
    }
}

if (!function_exists('tree_to_two')) {
    /**
     * 无限极数组转二维数组
     * @param array $arr
     * @return array
     */
    function tree_to_two(array $arr)
    {
        $res = array();
        foreach($arr as $v) {
            $t = $v['children'];
            unset($v['children']);
            $res[] = $v;
            if($t) $res = array_merge($res, tree_to_two($t));
        }
        return $res;
    }
}

if (!function_exists('build_html')) {
    /**
     * 构建树形结构数组的下拉选择页面
     * @param array $arr 树形结构数组
     * @param int $isDisabled 有子级的父级是否添加disabled样式，默认0，不添加
     * @param int $mark 分类级别 默认一级 用于在级别名称前添加--样式
     * @return string
     */
    function build_html(array $arr, int $isDisabled = 0, int $mark = 1)
    {
        $html = '';
        foreach ($arr as $k => $v) {
            if ($mark == 1) {
                $sign = '';
            } else {
                $sign = '';
                for ($i=1; $i < $mark; $i++) {
                    $sign .= ' - - ';
                }
            }

            if (!empty($v['children']) && $isDisabled != 0) {
                $html .= '<option value="'.$v['id'].'" disabled>'.$sign.$v['name'].'</option>';
                $childhtml = build_html($v['children'], $isDisabled, $mark + 1);
                $html .= $childhtml;
            } else {
                $html .= '<option value="'.$v['id'].'">'.$sign.$v['name'].'</option>';
                $childhtml = build_html($v['children'], $isDisabled, $mark + 1);
                $html .= $childhtml;
            }
        }
        return $html;
    }
}

if (!function_exists('trim_arr')) {
    /**
     * @msg: 去掉数组的元素两端的空格
     * @param {array $arr}
     * @return {array}
     */    
    function trim_arr(array $arr)
    {
        foreach ($arr as $k => $v) {
            $arr[$k] = trim($v);
        }
        return $arr;
    }
}
