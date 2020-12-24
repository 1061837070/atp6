<?php /*a:2:{s:56:"D:\phpstudy_pro\WWW\atp6\app\back\view\rose\setRule.html";i:1608787058;s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\common\header.html";i:1608780746;}*/ ?>
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
    .layui-tree-txt{
        background-color: #009688;
        padding: 0px 7px;
        border-radius: 5px;
        color: #FFFFFF;
        font-size: 14px;
        letter-spacing: 5px;
    }
    .layui-tree-entry{
        margin-top: 5px;
    }
    #save{
        margin: 40px 0;
    }
    .layui-form{
        padding-left: 50px;
    }
    .tree_test{
        width: 400px;
    }
    .show-title{
        display: block;
        font-size: 20px;
        font-weight: bold;
        margin-left: 60px;
        margin-top: 10px;
    }
</style>
<body>
    <div class="form-box">
        <div class="form-content">
            <span class="show-title"><?php echo htmlentities($roseInfo['name']); ?>--分配权限</span>
            <div class="tree_test"></div>
            <form class="layui-form">
                <input type="checkbox" title="全选" lay-skin="primary"  lay-filter="checkbox_test">
                <input type="hidden" id="roseId" value="<?php echo htmlentities($roseInfo['id']); ?>">
                <button type="button" lay-submit lay-filter="save" class="layui-btn layui-btn-normal" id="save">保存</button>
            </form>
        </div>
    </div>
    
    <script>
        layui.use(['layer', 'tree', 'form', 'element'], function () {
            var layer = layui.layer,
                tree = layui.tree,
                form = layui.form,
                element = layui.element,
                $ = layui.$;
            var roseId = $("#roseId").val();
            getRuleTree(roseId, 1);
            // 自定义树形结构数据查询 type=1 查询roseId的功能权限 2全选 3全不选
            function getRuleTree(roseId, type=1) {
                $.ajax({
                    data:{'roseId':roseId, 'type':type},
                    dataType:'json',
                    type:'POST',
                    url:'/back/rose/getRuleTree',
                    success:function(res){
                        if (res.code == 200) {
                            tree.render({
                                elem: '.tree_test',
                                id: 'mytree',
                                data: res.data,
                                showCheckbox: true,
                            });
                        }
                    }
                });
            };
            // 监听全选
            form.on('checkbox(checkbox_test)', function(data){
                if (data.elem.checked) {
                    getRuleTree(roseId, type=2);
                } else {
                    getRuleTree(roseId, type=3);
                }
            });
            // 监听保存
            form.on('submit(save)', function(data){
                var checkData = tree.getChecked('mytree'),
                    tabid = md5(window.location.pathname.split("?")[0]);//当前tab的id
                if (checkData.length == 0) {
                    layer.msg('未做任何勾选，无法提交',{icon:2});
                    return false;
                }
                $.ajax({
                    data:{'ids':checkData, 'roseId':roseId},
                    dataType:'json',
                    type:'POST',
                    url:'/back/rose/setRule',
                    success:function(res){
                        if (res.code == 200) {
                            layer.msg(res.msg, {icon: 1, time: 2000}, function(){
                                xadmin.del_data(tabid)//删除当前tab
                                parent.location.reload()//刷新左侧菜单
                            });
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error:function(res){
                        layer.msg('网络错误',{icon:2});
                    }
                });
            });
        });
    </script>
    

</body>
</html>