<?php /*a:1:{s:55:"D:\phpstudy_pro\WWW\atp6\app\back\view\login\login.html";i:1607327349;}*/ ?>
<!doctype html>
<html  class="x-admin-sm">
<head>
	<meta charset="UTF-8">
	<title>后台登录</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="/Static/lib/xadmin/css/font.css">
    <link rel="stylesheet" href="/Static/lib/xadmin/css/login.css">
    <link rel="stylesheet" href="/Static/lib/xadmin/css/xadmin.css">
</head>
<body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message">后台管理登录</div>
        <div id="darkbannerwrap"></div>

        <form class="layui-form" >
            <input name="username" placeholder="用户名"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <div id="slider"></div>
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" type="submit">
            <hr class="hr20" >
        </form>
    </div>

    <script src="/Static/lib/layui/layui.js" charset="utf-8"></script>
    <script>
        layui.config({
            base: '/Static/js/sliderVerify/'
        }).use(['sliderVerify', 'layer','form'],function () {
            var sliderVerify = layui.sliderVerify,
                layer = layui.layer,
                form  = layui.form,
                $ = layui.$;

            var slider = sliderVerify.render({
                elem: '#slider'
            })

            form.on('submit(login)', function(data){
                if (slider.isOk()) {
                    $.ajax({
                        data:data.field,
                        dataType:'json',
                        type:'POST',
                        url:'/back/login/login',
                        success:function (res) {
                            if (res.code == 200) {
                                layer.msg(res.msg, {icon: 6,time: 2000}, function(){
                                        location.href = "/back/index/index"
                                    }
                                );
                            } else {
                                layer.msg(res.msg, {icon: 5}, function () {
                                    slider.reset();
                                });
                            }
                        },
                        error:function (res) {
                            layer.msg('登录失败',{icon: 5});
                        }
                    })
                } else {
                    layer.msg("请先通过滑块验证");
                }
                return false;
            });
        })
    </script>
</body>
</html>