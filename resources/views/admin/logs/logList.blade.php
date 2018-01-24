@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="操作者用户名" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="logs" lay-filter="logtab"></table>

@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/logs/logs.js"></script>
@endsection
