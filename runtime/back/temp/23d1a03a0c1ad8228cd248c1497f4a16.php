<?php /*a:1:{s:57:"D:\phpstudy_pro\WWW\atp6\app\back\view\admin\setRose.html";i:1609149467;}*/ ?>
<!--
 * @Descripttion: 
 * @version: 
 * @Author: sueRimn
 * @Date: 2020-12-24 13:47:44
 * @LastEditors: sueRimn
 * @LastEditTime: 2020-12-28 17:57:46
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>添加管理员</title>
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
		.header_pic{
			cursor: pointer;
		}
		.my_nick{
			display: block;
    		line-height: 35px;
			font-size: 18px;
    		font-weight: bold;
		}
	</style>
</head>
<body>
	<form class="layui-form">
		<div class="layui-form-item">
		    <label class="layui-form-label">账号昵称：</label>
		    <div class="layui-input-block">
		      	<span class="my_nick"><?php echo htmlentities($adminInfo['nick_name']); ?></span>
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>选择角色：</label>
		    <div class="layui-input-block">
		        <select name="rose_id" id="rose_id" lay-verify="required" lay-search>
					<option value="">请选择管理员角色</option>
		            <?php foreach($roseList as $v): ?>
		            <option value="<?php echo htmlentities($v['id']); ?>" <?php if($adminInfo['rose_id'] == $v['id']): ?> selected <?php endif; ?>><?php echo htmlentities($v['name']); ?></option>
		            <?php endforeach; ?>
		        </select> 
		    </div>
		</div>

		<div class="layui-form-item end_btn">
		    <div class="layui-input-block">
				<input type="hidden" id="id" value="<?php echo htmlentities($adminInfo['id']); ?>">
		    	<button class="layui-btn" lay-submit="" lay-filter="save">保存</button>
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
			var id = $("#id").val();

	        // 监听提交
	        form.on('submit(save)', function (obj) {
				var data = obj.field; 
				data.id = id;	
	        	$.ajax({
	                url: '/back/admin/setRose',
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