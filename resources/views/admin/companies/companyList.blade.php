@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-input-inline">
	    	<select class="layui-input search_input">
		        <option value="">请选择单位级别</option>
		       	<option value="2">省级单位</option>
		       	<option value="3">市级单位</option>
		       	<option value="4">县级单位</option>
	      	</select>
	    </div>   
		<div class="layui-input-inline">
	    	<a class="layui-btn search_btn">查询</a>
	    </div>
		<div class="layui-inline">
			<a class="layui-btn layui-btn-normal add_btn">添加单位</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="companies" lay-filter="companytab"></table>

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
	<script type="text/javascript" src="/layadmin/modul/companies/companies.js"></script>
@endsection
