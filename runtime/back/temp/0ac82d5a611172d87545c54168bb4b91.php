<?php /*a:2:{s:55:"D:\phpstudy_pro\WWW\atp6\app\back\view\index\index.html";i:1608804623;s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\common\header.html";i:1608780746;}*/ ?>
<!--
 * @Descripttion: 
-->
<!-- 引入模板公共头部 -->
<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>后台登录-X-admin2.2</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="/Static/lib/xadmin/css/font.css">
    <link rel="stylesheet" href="/Static/lib/xadmin/css/xadmin.css">
    <link rel="stylesheet" href="/Static/lib/xadmin/css/theme.css">
    <link rel="stylesheet" href="/Static/lib/layui/css/layui.css">
    <link rel="stylesheet" href="/Static/css/back.css">
    <script src="/Static/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="/Static/lib/xadmin/js/xadmin.js"></script>
</head>
<body class="index">
    <!-- 顶部开始 -->
    <div class="container">
        <div class="logo">
            <a href="./index.html">后台管理页面</a></div>
        <div class="left_open">
            <a><i title="展开左侧栏" class="iconfont">&#xe699;</i></a>
        </div>
        <ul class="layui-nav right" lay-filter="">
            <li class="layui-nav-item">
                <a href="javascript:;"><?php echo htmlentities($admin_name); ?></a>
                <dl class="layui-nav-child">
                    <!-- 二级菜单 -->
                    <dd>
                        <a onclick="xadmin.open('个人信息','http://www.baidu.com')">个人信息</a>
                    </dd>
                    <dd>
                        <a onclick="xadmin.open('切换帐号','http://www.baidu.com')">切换帐号</a>
                    </dd>
                    <dd>
                        <a href="javascript:;" onclick="logout()">退出</a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item to-index">
                <a href="/">前台首页</a>
            </li>
        </ul>
    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
    <!-- 左侧菜单开始 -->
    <div class="left-nav">
        <div id="side-nav">
            <ul id="nav">
                <?php foreach($listTree as $v): ?>
                <li>
                    <a href="javascript:;">
                        <cite><?php echo htmlentities($v['name']); ?></cite>
                        <i class="iconfont nav_right">&#xe697;</i>
                    </a>
                    <ul class="sub-menu">
                        <?php if($v['children']|@count != 0): foreach($v['children'] as $v2): ?>
                        <li>
                            <a onclick="xadmin.add_tab('<?php echo htmlentities($v2['name']); ?>','<?php echo htmlentities($v2['url']); ?>',true)">
                                <cite><?php echo htmlentities($v2['name']); ?></cite>
                            </a>
                        </li>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <ul class="layui-tab-title">
                <li class="home">
                    <i class="layui-icon">&#xe68e;</i>我的桌面</li></ul>
            <div class="layui-unselect layui-form-select layui-form-selected" id="tab_right">
                <dl>
                    <dd data-type="this">关闭当前</dd>
                    <dd data-type="other">关闭其它</dd>
                    <dd data-type="all">关闭全部</dd></dl>
            </div>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe src='/back/index/welcome' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
                </div>
            </div>
            <div id="tab_show"></div>
        </div>
    </div>
    <div class="page-content-bg"></div>

    <script>
        function logout() {
            $.ajax({
                dataType: 'json',
                type: 'get',
                url: '/back/logout',
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg(res.msg, {icon: 1, time: 2000}, function () {
                            window.location.href = '/back/login/login';
                        });
                    } else {
                        layer.msg(res.msg, {icon: 2, time: 3000}, function () {
                            location.reload();
                        });
                    }
                },
                error: function () {
                    layer.msg('网络错误', {icon: 5});
                }
            })
        }
    </script>
</body>
</html>