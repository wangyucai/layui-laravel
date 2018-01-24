@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-btn-group">
		    <button class="layui-btn notice_all">全部</button>
		    <button class="layui-btn notice_yes">已读</button>
		    <button class="layui-btn notice_no">未读</button>
	  	</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote>
	<table id="mynotices" lay-filter="mynoticetab"></table>

@endsection

@section("js")
	<script type="text/html" id="active">
	@{{# if(d.if_read == 0){ }}
		<a class="layui-btn layui-btn-xs" lay-event="biaoji">
			<i class="layui-icon">&#xe6c6;</i>
			标记已读
		</a>
	@{{#  } else { }}
		<a class="layui-btn layui-btn-xs" lay-event="">
			<i class="layui-icon">&#xe6c6;</i>
			已读
		</a>
	@{{# } }}
	</script>
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
			<i class="layui-icon">&#xe623;</i>
			查看
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/notices/mynotices.js"></script>
@endsection
