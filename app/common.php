<?php
// 应用公共文件

declare(strict_types = 1);

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
        foreach ($arr as $v) {
            if (isset($v['children'])) {
                $t = $v['children'];
                unset($v['children']);
                $res = array_merge($res, tree_to_two($t));
            }
            $res[] = $v;
        }
        return $res;
    }
}

if (!function_exists('rule_tree_to_two')) {
    /**
     * 功能权限无限极数组转二维数组
     * @param array $arr
     * @return array
     */
    function rule_tree_to_two(array $arr)
    {
        $res = array();
        foreach ($arr as $v) {
            $t = $v['children'];
            unset($v['children']);
            $res[] = $v;
            if ($t) {
                $res = array_merge($res, rule_tree_to_two($t));
            }
        }
        return $res;
    }
}

if (!function_exists('build_tree_html')) {
    /**
     * 构建树形结构数组的下拉选择页面
     * @param array $arr 树形结构数组
     * @param int $isDisabled 有子级的父级是否添加disabled样式，默认0，不添加
     * @param int $mark 分类级别 默认一级 用于在级别名称前添加--样式
     * @return string
     */
    function build_tree_html(array $arr, int $isDisabled = 0, int $mark = 1)
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
                $childhtml = build_tree_html($v['children'], $isDisabled, $mark + 1);
                $html .= $childhtml;
            } else {
                $html .= '<option value="'.$v['id'].'">'.$sign.$v['name'].'</option>';
                $childhtml = build_tree_html($v['children'], $isDisabled, $mark + 1);
                $html .= $childhtml;
            }
        }
        return $html;
    }
}

if (!function_exists('build_tree_with_disabled_selected_html')) {
    /**
     * @msg: 根据禁选内容将待选内容生成下拉选择项页面元素，禁选内容添加禁选效果，
     * @param array $arr         待选内容，生成页面元素
     * @param int   $mark        级别 默认一级 用于在级别名称前添加--样式
     * @param array $idArr       添加disabled样式的内容的id
     * @param int   $selectedId  添加选中效果的内容id，默认unll
     * @return {*}
     */
    function build_tree_with_disabled_selected_html(array $arr, int $mark = 1, array $idArr, int $selectedId = null)
    {
        $html = '';
        foreach ($arr as $k => $v) {
            if (in_array($v['id'], $idArr)) {
                $disabled = 'disabled';
            } else {
                $disabled = '';
            }
            if ($mark == 1) {
                $sign = '';
            } else {
                $sign = '';
                for ($i=1; $i < $mark; $i++) {
                    $sign .= ' - - ';
                }
            }
            if ($v['id'] == $selectedId) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="'.$v['id'].'"'.$disabled.$selected.'>'.$sign.$v['name'].'</option>';
            if (!empty($v['children'])) {
                $childhtml = build_tree_with_disabled_selected_html($v['children'], $mark + 1, $idArr, $selectedId);
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

if (!function_exists('get_childs_id_str')) {
    /**
     * @msg: 获取指定pid分类的所有后代分类id
     * @param array $arr 后代分类的多维数组
     * @param int   $pid 指定的pid
     * @return {*}
     */
    function get_childs_id_str(array $arr, int $pid)
    {
        $result = '';
        foreach ($arr as $k => $v) {
            if ($v['pid'] == $pid) {
                $result .= $v['id'].',';
                if (!empty($v['children'])) {
                    $n = get_childs_id_str($v['children'], $v['id']);
                    $result .= $n;
                }
            }
        }
        return $result;
    }
}

if (!function_exists('get_no_childs_id_str')) {
    /**
     * @msg: 获取多维数组中没有后代的元素
     * @param array $arr 后代分类的多维数组
     * @return {*}
     */
    function get_no_childs_id_str(array $arr)
    {
        $result = '';
        foreach ($arr as $k => $v) {
            if (empty($v['children'])) {
                $result .= $v['id'].',';
            } else {
                $n = get_no_childs_id_str($v['children']);
                $result .= $n;
            }
        }
        return $result;
    }
}

if (!function_exists('get_rule_tree')) {
    /**
     * @msg: 生成layuiTree数据数组
     * @param array $cats 可选功能的二维数组
     * @param int   $pid  功能分类的父级id 默认0 从一级开始
     * @param array $ids  角色已有功能id数组
     * @param int   $type 1获取指定id功能的权限 2全选 3全不选
     * @return {*}
     */
    function get_rule_tree(array $cats, int $pid = 0, array $ids, int $type)
    {
        $result = [];
        foreach ($cats as $k => $v) {
            $v['spread'] = true;
            if ($v['pid'] == $pid) {
                unset($cats[$k]);
                if ($type == 1) {
                    if (in_array($v['id'], $ids)) {
                        $v['checked'] = true;
                    }
                }
                if ($type == 2) {
                    $v['checked'] = true;
                }
                if ($type == 3) {
                    $v['checked'] = false;
                }
                $v['children'] = get_rule_tree($cats, $v['id'], $ids, $type);
                $result[] = $v;
            }
        }
        return $result;
    }
}
