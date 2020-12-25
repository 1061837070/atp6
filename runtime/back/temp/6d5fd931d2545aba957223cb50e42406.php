<?php /*a:2:{s:54:"D:\phpstudy_pro\WWW\atp6\app\back\view\rose\index.html";i:1608867272;s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\common\header.html";i:1608780746;}*/ ?>
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
                </script>

                <!-- 右侧工具栏 -->
                <script type="text/html" id="bar">
                    <?php echo $mbtns; ?>
                </script>

                <div class="layui-card">
                    <div class="layui-card-body ">
                        <table class="layui-table" id="roseList" lay-filter="list"></table>
                    </div>
                    <input type="hidden" id="isOperate" value="<?php echo htmlentities($isOperate); ?>">
                </div>
            </div>
        </div>
    </div>

    <script>
        layui.use(['table','util'], function () {
            var table = layui.table,
                util = layui.util,
                $ = layui.$;
            var isOperate = $("#isOperate").val();
            var cols = [
                    {field: 'id', title: 'ID', width: 50, align: 'center'},
                    {field: 'name', title: '角色名称'},
                    {field: 'sort', title: '排序', width: 60},
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
                cols.push({width: 210, toolbar: '#bar', title: '操作'})
            }

            //初始化表格数据
            var tblIns = table.render({
                elem: '#roseList',
                toolbar: '#tblToolbar',
                url: '/back/rose/index',
                done: function (res, curr, count) {
                    var that = this.elem.next();
                    res.data.forEach(function (element, index) {
                        if (element.id == 1 && element.name == '超级管理员') {
                            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']");
                            tr.children("td:last-child").find('.edit').remove();
                            tr.children("td:last-child").find('.setRule').remove();
                            tr.children("td:last-child").find('.del').remove();
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
                        parent.xadmin.open('添加角色','/back/rose/add', 600, 400);
                        break;
                };
            });

            // 点击工具条
            table.on('tool(list)', function (obj) {
                var data = obj.data;
                switch (obj.event) {
                    case 'edit':
                        parent.xadmin.open('编辑角色','/back/rose/edit?id='+data.id, 600, 400);
                        break;
                    case 'del':
                        layer.confirm('确定删除？', {icon: 3}, function(){
                            $.ajax({
                                url: '/back/rose/del',
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
                    case 'setRule':
                        parent.xadmin.add_tab('分配权限','/back/rose/setRule?id='+data.id,true)
                        break;
                };
            });
        });
    </script>

</body>
</html>