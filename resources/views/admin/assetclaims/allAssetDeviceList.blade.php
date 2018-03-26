@extends("admin.layout.main")

@section("content")
	{{-- <blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="资产名称" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote> --}}
	<table id="allassetdevices" lay-filter="allassetdevicetab">
		<input type="hidden" name="zc_id" value="{{ $id }}" id="zc_id">
	</table>

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
		@{{# if(d.if_back == 1){ }}
			@{{# if(d.if_back_inbound == 1){ }}
			<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="active">已入库</a>
			@{{#  } else { }}
			<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="back_inbound">
				<i class="layui-icon">&#xe623;</i>
				归还入库
			</a>
			@{{# } }}
		@{{# } }}
	</script>
	<script type="text/javascript" src="/layadmin/modul/assetclaims/allassetdevices.js"></script>
@endsection
