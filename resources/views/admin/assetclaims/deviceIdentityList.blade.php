@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
		    <div class="layui-inline">
				<div class="layui-input-inline">
					<select name="if_ck" lay-filter="if_ck" id="if_ck">
				        <option value="">请选择是否在库</option>  
				       	<option value="0">是</option>
				       	<option value="1">否</option>
		      	</select>
				</div>
			</div> 
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="deviceidentities" lay-filter="deviceidentitytab">
		<input type="hidden" name="kc_id" value="{{ $id }}" id="kc_id">
	</table>

@endsection

@section("js")
	<script type="text/html" id="active">
		@{{# if(d.if_ck == 0){ }}
		<input type="checkbox" checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="是|否" disabled>
		@{{#  } else { }}
		<input type="checkbox" name="close" lay-skin="switch" lay-text="是|否" disabled>
		@{{# } }}
	</script>
	<script type="text/html" id="active1">
		@{{# if(d.if_bf == 1){ }}
		<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="">已报废</a>
		@{{#  } else { }}
		<a class="layui-btn layui-btn-warm layui-btn-danger layui-btn-xs" lay-event="bf">报废</a>
		@{{# } }}
	</script>
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-xs" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/assetclaims/deviceidentities.js"></script>
@endsection
