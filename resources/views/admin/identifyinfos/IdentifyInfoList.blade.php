@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	{{-- 	<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="司法鉴定机构名称" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div> --}}
		<div class="layui-inline">
			<a class="layui-btn layui-btn-normal add_btn">添加我的鉴定机构证书</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote>
	<table id="identifyinfos" lay-filter="identifyinfotab"></table>

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
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/identifyinfos.js"></script>
@endsection
