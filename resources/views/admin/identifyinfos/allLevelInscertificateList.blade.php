@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
			<div class="layui-inline">
			<label>鉴定机构级别：</label>
				<div class="layui-input-inline">
					<select name="jdzs_level" lay-filter="jdzs_level" id="jdzs_level">
				        <option value="">请选择鉴定机构级别</option>	 
				       	<option value="2">省级鉴定证书</option>
				       	<option value="3">市级鉴定证书</option>
				       	<option value="4">县级鉴定证书</option>
			      	</select>
		      	</select>
				</div>
			</div>
		</div>
		<div class="layui-inline" style="margin-left: 20px;">
	    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
	    </div> 	
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="alllevelidentifyinfos" lay-filter="alllevelidentifyinfotab"></table>
	
@endsection
	
@section("js")
	<script type="text/html" id="zspath">
		<div><img  src="@{{d.jdry_zspath}}"></div>
	</script>
	<script type="text/html" id="op">
	<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
			<i class="layui-icon">&#xe623;</i>
			查看
		</a>
		<a class="layui-btn layui-btn-xs edit_user" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
			<i class="layui-icon"></i>
			删除
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/alllevelidentifyinfos.js"></script>
@endsection
