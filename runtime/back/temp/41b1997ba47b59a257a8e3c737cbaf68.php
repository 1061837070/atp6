<?php /*a:2:{s:54:"D:\phpstudy_pro\WWW\atp6\app\back\view\rule\index.html";i:1608867150;s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\common\header.html";i:1608780746;}*/ ?>
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
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <!--表格头部左侧工具栏-->
                <script type="text/html" id="tblToolbar">
                    <button class="layui-btn layui-btn-sm layui-btn-normal layui-btn-refresh" title="点击刷新" lay-event="refresh">
                        <i class="layui-icon layui-icon-refresh-3"></i>
                    </button>
                    <?php echo $hbtns; ?>
                    <button class="layui-btn  layui-btn-sm layui-btn-warm" id="btns">显示全部功能</button>
                </script>

                <!-- 右侧工具栏 -->
                <script type="text/html" id="bar">
                    <?php echo $mbtns; ?>
                </script>

                <div class="layui-card">
                    <div class="layui-card-body ">
                        <table class="layui-table" id="rulrList" lay-filter="list"></table>
                    </div>
                    <input type="hidden" id="isOperate" value="<?php echo htmlentities($isOperate); ?>">
                </div>
            </div>
        </div>
    </div>

    <script>
        layui.use(['table', 'util'], function () {
            var table = layui.table,
                util = layui.util,
                $ = layui.$;
            var isOperate = $("#isOperate").val();
            var cols = [
                    {field: 'id', title: 'ID', width: 50, align: 'center'},
                    {field: 'name', title: '名称'},
                    {field: 'url', title: 'URL路径'},
                    {field: 'sort', title: '排序', width: 60, align: 'center'},
                    {field: 'type', title: '类型', width: 70, align: 'center', templet: function(d) {
                        if (d.type == 1) {
                            return '菜单';
                        } else {
                            return '按钮';
                        }
                    }},
                    {field: 'status', title: '状态', width: 80, align: 'center', templet: function(d) {
                        if (d.status == 1) {
                            return '<span style="color: #009688;">正常</span>';
                        } else {
                            return '<span style="color: red;">禁用</span>';
                        }
                    }},
                    {field: 'cname', title: '创建', minWidth: 200, templet: function(d){
                        return util.toDateString(d.created_at*1000) + '【' + d.cname + '】'; 
                    }},
                    {field: '', title: '最后一次修改', minWidth: 200, templet: function(d){
                        if (d.updated_at) {
                            return util.toDateString(d.updated_at*1000) + '【' + d.uname + '】';
                        } else {
                            return '';
                        }
                    }},
                    {field: 'remark', title: '备注'}
                ];
            if (isOperate == 1) {
                cols.push({width: 185, toolbar: '#bar', title: '操作'})
            }

            //初始化表格数据
            var tblIns = table.render({
                elem: '#rulrList',
                toolbar: '#tblToolbar',
                url: '/back/rule/index',
                done: function (res, curr, count) {
                    var that = this.elem.next();
                    res.data.forEach(function (element, index) {
                        if (element.type == 2) {
                            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']");
                            tr.css("color", "#adb0b3");
                            tr.css("display", "none");
                            tr.addClass("myBtns");
                        }
                        if (element.status == 1) {
                            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']");
                            tr.children("td:last-child").find('.start').remove();
                        }
                        if (element.status == 2) {
                            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']");
                            tr.children("td:last-child").find('.stop').remove();
                        }
                    });
                },
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        "code": res.code, //解析接口状态
                        "msg": res.msg, //解析提示文本
                        "count": res.total, //解析数据长度
                        "data": res.data, //解析数据列表
                    };
                },
                cols: [cols],
                response: {
                    statusCode: 200 //规定成功的状态码味200
                }
            });

            //监听左侧工具栏
            table.on('toolbar(list)', function (obj) {
                switch (obj.event) {
                    case 'refresh':
                        location.reload();
                        break;
                    case 'add':
                        parent.xadmin.open('添加功能','/back/rule/add', 600, 600);
                        break;
                };
            });
            // 点击显示全部
            $("#btns").click(function () {
                var t = $("#btns").text();
                if (t == '显示全部功能') {
                    $("#btns").text('隐藏功能按钮');
                    $(".myBtns").show();
                } else {
                    $("#btns").text('显示全部功能');
                    $(".myBtns").css("display", "none");
                }
            })
            // 点击工具条
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                switch (obj.event) {
                    case 'edit':
                        parent.xadmin.open('编辑功能','/back/rule/edit?id='+data.id, 600, 600);
                        break;
                    case 'del':
                        layer.confirm('确定删除？存在下级功能的功能不能直接删除', {icon: 3}, function(){
                            $.ajax({
                                url: '/back/rule/del',
                                type: 'POST',
                                data: {'id': data.id},
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code == 200) {
                                        layer.msg(res.msg, {icon: 1, time: 2000}, function(){
                                            location.reload();
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                },
                                error: function () {
                                    layer.msg('网络错误', {icon: 5});
                                }
                            });
                        })
                        break;
                    case 'stop':
                        layer.confirm('禁用后功能无法使用，当禁用功能有下级功能时，下级功能亦被禁用，确认禁用？', {icon: 3}, function(){
                            $.ajax({
                                url: '/back/rule/stop',
                                type: 'POST',
                                data: {'id': data.id},
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code == 200) {
                                        layer.msg(res.msg, {icon: 1, time: 2000}, function(){
                                            location.reload();
                                        });
                                    } else {
                                        layer.msg(res.msg, {icon: 2});
                                    }
                                },
                                error: function () {
                                    layer.msg('网络错误', {icon: 5});
                                }
                            });
                        })
                        break;
                    case 'start':
                        $.ajax({
                            url: '/back/rule/start',
                            type: 'POST',
                            data: {'id': data.id},
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    layer.msg(res.msg, {icon: 1, time: 2000}, function(){
                                        location.reload();
                                    });
                                } else {
                                    layer.msg(res.msg, {icon: 2});
                                }
                            },
                            error: function () {
                                layer.msg('网络错误', {icon: 5});
                            }
                        });
                        break;
                };
            });
        });
    </script>

</body>
</html>