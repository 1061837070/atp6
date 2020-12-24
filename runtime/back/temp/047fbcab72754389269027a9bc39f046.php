<?php /*a:1:{s:51:"D:\phpstudy_pro\WWW\atp6\app\back\view\err\err.html";i:1608714546;}*/ ?>
<!doctype html>
<html class="x-admin-sm">
    <head>
        <meta charset="UTF-8">
        <title>错误</title>
        <meta name="renderer" content="webkit|ie-comp|ie-stand">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta http-equiv="Cache-Control" content="no-siteapp" />

        <link rel="stylesheet" href="/Static/lib/xadmin/css/font.css">
        <link rel="stylesheet" href="/Static/lib/xadmin/css/xadmin.css">
    </head>
    <body>
        <div class="layui-container">
            <div class="fly-panel">
                <div class="fly-none">
                    <h5><i class="layui-icon <?php echo htmlentities($icon); ?>"></i></h5>
                    <p><?php echo htmlentities($msgstr); ?><a href="<?php echo htmlentities($urlstr); ?>"><?php echo htmlentities($btnstr); ?></a></p>
                </div>
            </div>
        </div>
    </body>
</html>