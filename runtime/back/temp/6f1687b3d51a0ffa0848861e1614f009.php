<?php /*a:1:{s:53:"D:\phpstudy_pro\WWW\atp6\app\back\view\admin\add.html";i:1608800704;}*/ ?>
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
	</style>
</head>
<body>
	<form class="layui-form">
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>昵称：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="nick_name" autocomplete="off" placeholder="请输入管理员昵称" class="layui-input" lay-verify="required">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>手机：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="phone" autocomplete="off" placeholder="请输入手机号" class="layui-input" lay-verify="phone">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>角色：</label>
		    <div class="layui-input-block">
		        <select name="rose_id" id="rose_id" lay-verify="required" lay-search>
					<option value="">请选择管理员角色</option>
		            <?php foreach($roseList as $v): ?>
		            <option value="<?php echo htmlentities($v['id']); ?>"><?php echo htmlentities($v['name']); ?></option>
		            <?php endforeach; ?>
		        </select> 
		    </div>
		</div>

		<div class="layui-form-item">
		    <label class="layui-form-label">邮箱：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="email" autocomplete="off" placeholder="请输入管理员邮箱" class="layui-input">
		    </div>
		</div>
		<div class="layui-form-item" style="height: 100px;">
		    <label class="layui-form-label">管理员头像：</label>
		    <div class="layui-input-block">
		    	<button type="button" class="layui-btn" id="test1">
				  	<i class="layui-icon">&#xe67c;</i>上传图片
				</button>
				<img src="" alt="" width="100px" class="header_pic">
		      	<input type="hidden" name="header_pic" id="header_pic" value="">
		    </div>
		</div>
		<div class="layui-form-item layui-form-text">
		    <label class="layui-form-label">备注：</label>
		    <div class="layui-input-block">
		      	<textarea placeholder="请输入内容" class="layui-textarea" name="remark"></textarea>
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
		layui.use(['form', 'layer', 'upload'], function () {
			var $ = layui.jquery,
				form = layui.form,
				layer = layui.layer,
				upload = layui.upload;
            // 图片上传 执行实例
			var uploadInst = upload.render({
			    elem: '#test1', //绑定元素
			    url: '/back/admin/upload', //上传接口
			    accept: 'images',
			    acceptMime: 'image/*',
			    exts: 'jpg|png|gif|bmp|jpeg',
			    field: 'header_pic',
			    size: 1024,
			    before: function(obj){
				    layer.load();
				},
			    done: function(res){
			      	layer.closeAll();
			      	if (res.code === 200) {
                        layer.msg('上传成功', {time:2000});
                        $("#header_pic").val(res.path_info);
                        $('.header_pic').attr('src', res.path_info);
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
			    },
			    error: function(){
			      	layer.closeAll();
                    layer.msg('网络异常，请稍后重试！', {icon: 5});
			    }
			});
	        // 监听提交
	        form.on('submit(add)', function (obj) {
	        	var data = obj.field;
	        	// 手机号  邮箱
	        	if (data.phone.length != 0) {
	        		var patnum = /^1\d{10}$/;
	        		if (!patnum.test(data.phone)) {
	        			layer.msg('手机号格式错误', {icon:2});
	        			return false;
	        		}
	        	}
	        	if (data.email.length != 0) {
	        		var patnum = /^[A-Za-z0-9\u4e00-\u9fa5]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/;
	        		if (!patnum.test(data.email)) {
	        			layer.msg('邮箱格式错误', {icon:2});
	        			return false;
	        		}
	        	}
	        	
	        	$.ajax({
	                url: '/back/admin/add',
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
	        // 图片放大镜
	        $(".header_pic").click(function(){
	            var url = $(this).attr('src');
	            layer.open({
	               type: 1,
	               title: false,
	               area: ['500px', '500px'],
	               resize:false,//不可拖拽缩放
	               content: "<img style='margin:10px;' alt=" + name + " title=" + name + " src=" + url + " height=480px; width=480px;" + "/>"
	            });
	        });
		})
	</script>
</body>
</html>