<?php /*a:2:{s:55:"D:\phpstudy_pro\WWW\atp6\app\back\view\admin\index.html";i:1608867556;s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\common\header.html";i:1608780746;}*/ ?>
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
<style>
    .header_pic{
        cursor: pointer;
    }
</style>
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
                </script>

                <!-- 右侧工具栏 -->
                <script type="text/html" id="bar">
                    <?php echo $mbtns; ?>
                </script>

                <div class="layui-card">
                    <div class="layui-card-body ">
                        <table class="layui-table" id="adminList" lay-filter="list"></table>
                    </div>
                    <input type="hidden" id="isOperate" value="<?php echo htmlentities($isOperate); ?>">
                </div>
            </div>
        </div>
    </div>

    <script>
        layui.use(['table','util','layer'], function () {
            var table = layui.table,
                util = layui.util,
                layer = layui.layer,
                $ = layui.$;
            var isOperate = $("#isOperate").val();
            var cols = [
                    {field: 'id', title: 'ID', width: 50, align: 'center'},
                    {field: 'nick_name', title: '昵称'},
                    {field: 'rose_name', title: '角色', minWidth: 80},
                    {field: 'header_pic', title: '头像', width: 77, templet: function(d) {
                        if (d.header_pic.length == 0) {
                            return '<img src="/Static/images/header.jpg" height="40" class="header_pic" onclick="bigShow(this)" />';
                        } else {
                            return '<img src="'+d.header_pic+'" height="40" class="header_pic" onclick="bigShow(this)" />';
                        }
                    }},
                    {field: 'phone', title: '手机号', width: 110},
                    {field: 'status', title: '状态', width: 60, templet: function(d){
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
                cols.push({width: 340, toolbar: '#bar', title: '操作'})
            }

            //初始化表格数据
            var tblIns = table.render({
                elem: '#adminList',
                toolbar: '#tblToolbar',
                url: '/back/admin/index',
                done: function (res, curr, count) {
                    var that = this.elem.next();
                    res.data.forEach(function (element, index) {
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
                        tblIns.reload();
                        break;
                    case 'add':
                        parent.xadmin.open('添加管理员','/back/admin/add', 600, 600);
                        break;
                };
            });

            // 点击工具条
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                switch (obj.event) {
                    case 'edit':
                        parent.xadmin.open('编辑管理员','/back/admin/edit?id='+data.id, 600, 600);
                        break;
                    case 'del':
                        layer.confirm('确定删除？', {icon: 3}, function(){
                            $.ajax({
                                url: '/back/admin/del',
                                type: 'POST',
                                data: {'id': data.id},
                                dataType: 'json',
                                success: function (res) {
                                    if (res.code == 200) {
                                        layer.msg(res.msg, {icon: 1, time: 2000}, function(){
                                            tblIns.reload();
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
                };
            });
            
            // 图片放大镜
            window.bigShow = function (e) {
                var url = $(e).attr('src');
	            layer.open({
	               type: 1,
	               title: false,
	               area: ['500px', '500px'],
	               resize:false,//不可拖拽缩放
	               content: "<img style='margin:10px;' alt=" + name + " title=" + name + " src=" + url + " height=480px; width=480px;" + "/>"
	            });
            };
        });
    </script>

</body>
</html>