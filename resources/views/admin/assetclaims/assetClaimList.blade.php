@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="资产名称" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote>
	<table id="assetclaims" lay-filter="assetclaimtab"></table>

@endsection

@section("js")
	<script type="text/html" id="op">
		{{-- <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="look">
			<i class="layui-icon">&#xe623;</i>
			查看设备
		</a> --}}
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
			<i class="layui-icon">&#xe623;</i>
			申领
		</a>
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="down">
			<i class="layui-icon">&#xe623;</i>
			下载资产入库表
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/assetclaims/assetclaims.js"></script>
@endsection
