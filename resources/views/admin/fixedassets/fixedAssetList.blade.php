@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
			<div class="layui-input-inline">
		    	<input type="text" value="" placeholder="姓名" class="layui-input real_name" id="real_name">
		    </div>
		    <div class="layui-input-inline">
		    	<select name="gdzc_bmdm" lay-filter="gdzc_bmdm" id="gdzc_bmdm">
			        <option value="">请选择所在部门</option>
			        @foreach($bm_arr as $k=>$v)
			       	<option value="{{ $k }}">{{ $v }}</option>
			       	@endforeach
		      	</select>
		    </div>
		    <div class="layui-input-inline">
				<div class="layui-input-inline">
					<input type="text" id="lqrq_start" class="layui-input" name="gdzc_lqrq" placeholder="选择开始日期">	
				</div>
				--
				<div class="layui-input-inline">
					<input type="text" id="lqrq_end" class="layui-input" name="gdzc_lqrq" placeholder="选择结束日期">
				</div>
		    </div>
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="固定资产名称" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="fixedassets" lay-filter="fixedassettab"></table>

@endsection

@section("js")
	<script type="text/html" id="active">
		@{{# if(d.if_back == 1){ }}
		<input type="checkbox" checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="是|否" disabled>
		@{{#  } else { }}
		<input type="checkbox" name="close" lay-skin="switch" lay-text="是|否" disabled>
		@{{# } }}
	</script>
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="look">
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
	<script type="text/javascript" src="/layadmin/modul/fixedassets/fixedassets.js"></script>
@endsection
