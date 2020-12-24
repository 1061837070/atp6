<?php /*a:1:{s:52:"D:\phpstudy_pro\WWW\atp6\app\back\view\rose\add.html";i:1608709873;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加角色</title>
	<link rel="stylesheet" href="/Static/lib/layui/css/layui.css">
	<link rel="stylesheet" href="/Static/css/back.css">
	<style>
		.layui-form{
			width: 95%;
			margin-top: 30px;
		}
		.end_btn{
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<form class="layui-form">
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>角色名称：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="name" autocomplete="off" placeholder="请输入功能名称" class="layui-input" lay-verify="required">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label">角色排序：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="sort" autocomplete="off" placeholder="请输入角色排序" class="layui-input" lay-verify="my_num">
		    </div>
		</div>
		<div class="layui-form-item layui-form-text">
		    <label class="layui-form-label">备注说明：</label>
		    <div class="layui-input-block">
		      	<textarea placeholder="请输入内容" class="layui-textarea" name="remark"></textarea>
			</div>
		</div>
		<div class="layui-form-item end_btn">
		    <div class="layui-input-block">
		    	<button class="layui-btn" lay-submit="" lay-filter="add">添加</button>
		      	<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>

	<script src="/Static/lib/layui/layui.js" charset="utf-8"></script>
	<script src="/Static/lib/xadmin/js/xadmin.js" charset="utf-8"></script>
	<script>
		layui.use(['form', 'layer'], function () {
			var $ = layui.jquery,
				form = layui.form,
				layer = layui.layer;
            //自定义验证
            form.verify({
                my_num: function (value) {
                    var patnum = /^\d+$/;
                    if (value.length != 0) {
                        if(!patnum.test(value)){
                            return '功能排序只能为非负整数';
                        }
                    }
                }
            });
	        // 监听提交
	        form.on('submit(add)', function (obj) {
	        	var data = obj.field;
	        	$.ajax({
	                url: '/back/rose/add',
	                type: 'POST',
	                data: data,
	                dataType: 'json',
	                success: function (res) {
	                    if (res.code == 200) {
	                    	layer.msg(res.msg, {icon: 1, time: 2000}, function(){
	                    		xadmin.close();
	                    		xadmin.father_reload();
	                    	});
	                    } else {
	                        layer.msg(res.msg, {icon: 2});
	                    }
	                },
	                error: function () {
	                    layer.msg('网络错误', {icon: 5});
	                }
	            });
	            return false;
	        })
		})
	</script>
</body>
</html>