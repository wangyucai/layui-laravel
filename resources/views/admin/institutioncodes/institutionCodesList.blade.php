@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-input-inline">
	    	<select class="layui-input search_input">
		        <option value="">请选择鉴定机构级别</option>
		       	<option value="1">省级鉴定机构</option>
		       	<option value="2">市级鉴定机构</option>
		       	<option value="3">县级鉴定机构</option>
	      	</select>
	    </div>   
		<div class="layui-input-inline">
	    	<a class="layui-btn search_btn">查询</a>
	    </div>
		<div class="layui-inline">
			<a class="layui-btn layui-btn-normal add_btn">添加司法鉴定机构代码</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="institutioncodes" lay-filter="institutioncodetab"></table>

@endsection

@section("js")
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-xs edit_user" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
			<i class="layui-icon"></i>
			删除
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/institutioncodes/institutioncodes.js"></script>
@endsection
