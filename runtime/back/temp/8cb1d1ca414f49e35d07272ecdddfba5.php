<?php /*a:1:{s:53:"D:\phpstudy_pro\WWW\atp6\app\back\view\rule\edit.html";i:1608622907;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>编辑功能</title>
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
		    <label class="layui-form-label"><span class="my_red_sign">*</span>功能名称：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="name" autocomplete="off" placeholder="请输入功能名称" class="layui-input" lay-verify="required" value="<?php echo htmlentities($ruleInfo['name']); ?>">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>父级分类：</label>
		    <div class="layui-input-block">
				<select name="pid" id="pid" lay-verify="required" lay-search>
                    <option value="">请选择父级分类</option>
					<?php echo $optionHtml; ?>
				</select>
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label"><span class="my_red_sign">*</span>功能URL：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="url" autocomplete="off" placeholder="必填，一级功能URL为/，其余 /back/xx/xx" class="layui-input" lay-verify="required|my_url" value="<?php echo htmlentities($ruleInfo['url']); ?>">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label">功能排序：</label>
		    <div class="layui-input-block">
		      	<input type="text" name="sort" autocomplete="off" placeholder="同一父级下的排序" class="layui-input" lay-verify="my_num" value="<?php echo htmlentities($ruleInfo['sort']); ?>">
		    </div>
		</div>
		<div class="layui-form-item">
		    <label class="layui-form-label">功能类型：</label>
		    <div class="layui-input-block">
		      <input type="radio" name="type" value="1" title="菜单" <?php if($ruleInfo['type'] == 1): ?>checked=""<?php endif; ?>>
		      <input type="radio" name="type" value="2" title="按钮" <?php if($ruleInfo['type'] == 2): ?>checked=""<?php endif; ?>>
		    </div>
		</div>
		<div class="layui-form-item layui-form-text">
		    <label class="layui-form-label">功能备注：</label>
		    <div class="layui-input-block">
		      	<textarea placeholder="请输入内容" class="layui-textarea" name="remark"></textarea>
			</div>
		</div>
		<div class="layui-form-item end_btn">
		    <div class="layui-input-block">
				<input type="hidden" id="id" value="<?php echo htmlentities($ruleInfo['id']); ?>">
		    	<button class="layui-btn" lay-submit="" lay-filter="edit">保存</button>
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
			var id = $("#id").val();
            //自定义验证
            form.verify({
                my_url: function (value) {
                    var paturl = /^(\/)$|^(\/back\/([a-zA-Z]+)\/([a-zA-Z]+))$/i;
                    if(!paturl.test(value)){
                        return 'URL格式错误';
                    }
                },
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
	        form.on('submit(edit)', function (obj) {
				var data = obj.field;
				data.id = id;
	        	$.ajax({
	                url: '/back/rule/edit',
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