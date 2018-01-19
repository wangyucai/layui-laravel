@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
		    <div class="layui-inline">
			<label>选择机构：</label>
				<div class="layui-input-inline">
					<select name="jdjg_dwdm" lay-filter="jdjg_dwdm" id="jdjg_dwdm">
					@if($my_dwjb<=2)
				        <option value="">请选择司法鉴定机构</option>
				        @foreach($institutioncodes as $institutioncode)
				       	<option value="{{ $institutioncode->jdjg_dwdm }}">{{ $institutioncode->jdjg_name  }}</option>
			       		@endforeach
			       	@else
			       		<option value="">省级以下单位无需选择</option>
			       	@endif
		      	</select>
				</div>
			</div> 
			<div class="layui-inline">
			<label>业务门类：</label>
				<div class="layui-input-inline">
					<select name="jdywfw_code" lay-filter="jdywfw_code" id="jdywfw_code">
				        <option value="">请选择鉴定业务</option>
				        @foreach($businesses as $business)
				       	<option value="{{$business->jdywfw_code}}">{{$business->jdywfw_name}}</option>
				       	@endforeach
			      	</select>
		      	</select>
				</div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input id="my_dwjb" type="checkbox" name="my_dwjb" title="是否包含子机构" value="{{ $my_dwjb  }}">
				</div>
			</div> 
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="province_level" lay-filter="province_level" id="province_level">
				        <option value="">请选择全省市县级</option>	  
				       	<option value="3">全省所有市级鉴定人</option>
				       	<option value="4">全省所有县级鉴定人</option>
			      	</select>
				</div>
			</div>
			<div class="layui-inline" style="margin-left: 20px;">
		    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
		    </div> 	
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="appraiserstatistics" lay-filter="appraiserstatistictab"></table>
	
@endsection
	
@section("js")
	<script type="text/html" id="zspath">
		<div><img  src="@{{d.jdry_zspath}}"></div>
	</script>
	<script type="text/html" id="op">
	<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
			<i class="layui-icon">&#xe623;</i>
			查看此人证书
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/appraiserstatistics.js"></script>
@endsection
