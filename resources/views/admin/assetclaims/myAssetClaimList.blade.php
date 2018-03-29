@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
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
		    	<input type="text" value="" placeholder="资产编号" class="layui-input zcbh" id="zcbh">
		    </div>
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="资产名称" class="layui-input zcmc" id="zcmc">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote>
	<table id="myassetclaims" lay-filter="myassetclaimtab"></table>

@endsection

@section("js")
	<script type="text/html" id="active">
		@{{# if(d.if_check == 0){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-warm">未审核</button>
		@{{# } }}
		@{{# if(d.if_check == 1){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-normal">已审核</button>
		@{{# } }}
		@{{# if(d.if_check == 2){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-danger">未通过</button>
		@{{# } }}
	</script>
	<script type="text/html" id="op">
	<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="look">
		<i class="layui-icon">&#xe623;</i>
		查看领用的设备
	</a>
	@{{# if(d.if_check == 1){ }}
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="down">
			<i class="layui-icon">&#xe623;</i>
			下载资产领用表
		</a>
	@{{# } }}
	</script>
	<script type="text/javascript" src="/layadmin/modul/assetclaims/myassetclaims.js"></script>
@endsection
