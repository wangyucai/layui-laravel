@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<input type="text" id="rkrq_start" class="layui-input" name="rkrq" placeholder="选择开始日期">	
				</div>
				--
				<div class="layui-input-inline">
					<input type="text" id="rkrq_end" class="layui-input" name="rkrq" placeholder="选择结束日期">
				</div>
		    </div>
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="经手人" class="layui-input jsr" id="jsr">
		    </div>
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="资产名称" class="layui-input zcmc" id="zcmc">
		    </div>
		    @if($my_dwjb<4)
			<div class="layui-input-inline">
				<select name="danwei" lay-filter="danwei" id="danwei">
			        <option value="">请选择单位</option>	  
			       	@foreach($danwei as $k=>$v)	  
			       	<option value="{{ $v->dwdm }}">{{ $v->html }}{{ $v->dwqc }}</option>
			       	@endforeach
		      	</select>
			</div>
			<div class="layui-input-inline">
				<input id="my_dwjb" type="checkbox" name="my_dwjb" title="是否包含下辖单位" value="{{ $my_dwjb  }}">
			</div>
			@endif
			@if($my_dwjb<=2)
			<div class="layui-input-inline">
				<select name="province_level" lay-filter="province_level" id="province_level">
			        <option value="">请选择全省市县级</option>	  
			       	<option value="2">所有省级和市级</option>
			       	<option value="3">所有市级</option>
		      	</select>
			</div>
			@endif
			<div class="layui-input-inline">
				<select name="if_check" lay-filter="if_check" id="if_check">
			        <option value="">请选择是否审核通过</option>	  
			       	<option value="0">待审核</option>
			       	<option value="1">审核通过</option>
			       	<option value="2">审核未通过</option>
		      	</select>
			</div>
		    <div class="layui-inline" style="margin-left: 10px;">
		    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
		    </div> 	
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="inboundassets" lay-filter="inboundassettab"></table>

@endsection

@section("js")
	<script type="text/html" id="op">
	@{{# if(d.kc_dwdm == d.my_dwdm){ }}
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="look">
			<i class="layui-icon">&#xe623;</i>
			查看设备
		</a>
		<a class="layui-btn layui-btn-xs" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
			<i class="layui-icon"></i>
			删除
		</a>
	@{{# } }}
	</script>
	<script type="text/html" id="active">
	@{{# if(d.kc_dwdm == d.my_dwdm){ }}
		@{{# if(d.if_check == 0){ }}
		<a class="layui-btn layui-btn-xs layui-bg-red check" lay-event="check">
			<i class="layui-icon">&#xe623;</i>
			审核
		</a>
		@{{# } }}
		
		@{{# if(d.if_check == 1){ }}
		<a class="layui-btn layui-btn-xs check" lay-event="checked">
			审核通过
		</a>
		@{{# } }}
		@{{# if(d.if_check == 2){ }}
		<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="nocheck">
			审核不通过
		</a>
		@{{# } }}
	@{{# } }}
	</script>
	<script type="text/javascript" src="/layadmin/modul/assetclaims/inboundassets.js"></script>
@endsection
