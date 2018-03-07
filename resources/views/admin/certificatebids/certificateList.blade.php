@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<input type="text" id="start" class="layui-input" name="start" placeholder="选择开始日期">	
				</div>
		    </div>
		</div>
		<div class="layui-inline">
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<input type="text" id="end" class="layui-input" name="end" placeholder="选择结束日期">
				</div>
		    </div>
		</div>
		<div class="layui-inline">
		    <div class="layui-inline">
				<div class="layui-input-inline">
					<select name="zsmc" lay-filter="zsmc" id="zsmc">
				        <option value="">请选择证书名称</option>	  
				       	@foreach($zsmc_arr as $k=>$v)	  
				       	<option value="{{ $v['car_name'] }}">{{ $v['car_name'] }}</option>
				       	@endforeach
			      	</select>
				</div>
			</div>
			@if($admin_dwjb==4)
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<select name="county_if_check" lay-filter="county_if_check" id="county_if_check">
				        <option value="">请选择上报状态</option>	  	     
				       	<option value="1">已上报</option>
				       	<option value="0">未上报</option>
			      	</select>
				</div>
		    </div>
		    @endif
		    @if($admin_dwjb==3)
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<select name="city_if_check" lay-filter="city_if_check" id="city_if_check">
				        <option value="">请选择上报状态</option>	  	     
				       	<option value="1">已上报</option>
				       	<option value="0">未上报</option>
			      	</select>
				</div>
		    </div>
		    @endif
			@if($admin_dwjb==2)
			<div class="layui-input-inline">
				<div class="layui-input-inline">
					<select name="check_status" lay-filter="check_status" id="check_status">
				        <option value="">请选择审核状态</option>	  	     
				       	<option value="1">待审核</option>
				       	<option value="2">已审核</option>
				       	<option value="3">审核未通过</option>
			      	</select>
				</div>
		    </div>
		    @endif
			<div class="layui-inline" style="margin-left: 10px;">
		    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
		    </div> 
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="certificates" lay-filter="certificatetab"></table>

@endsection

@section("js")
	<script type="text/html" id="active">
	
		@{{# if(d.bz == 1){ }}
		<input type="checkbox" checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="是|否" disabled>
		@{{#  } else { }}
		<input type="checkbox" name="close" lay-skin="switch" lay-text="是|否" disabled>
		@{{# } }}
	</script>
	<script type="text/html" id="active1">
		@{{# if(d.if_check == 1){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-warm">未审核</button>
		@{{# } }}
		@{{# if(d.if_check == 2){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-normal">已审核</button>
		@{{# } }}
		@{{# if(d.if_check == 3){ }}
			<button class="layui-btn layui-btn-xs layui-btn-radius layui-btn-danger">未通过</button>
		@{{# } }}
	</script>
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-xs edit" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
		@if($admin_dwdm==100000 || $admin_dwdm==520000)
			@{{# if(d.if_check == 1){ }}
			<a class="layui-btn layui-btn-xs layui-bg-red check" lay-event="check">
				<i class="layui-icon">&#xe623;</i>
				审核
			</a>
			@{{# } }}
			@{{# if(d.if_check == 2){ }}
			<a class="layui-btn layui-btn-xs check" lay-event="checked">
				审核通过
			</a>
			@{{# } }}
			@{{# if(d.if_check == 3){ }}
			<a class="layui-btn layui-btn-xs layui-btn-danger" lay-event="nocheck">
				审核不通过
			</a>
			@{{# } }}
		@else
			@{{# if(d.county_if_check == 1 || d.city_if_check == 1){ }}
			<a class="layui-btn layui-btn-xs " lay-event="reported">
				<i class="layui-icon">&#xe623;</i>
				已上报
			</a>
			@{{#  } else { }}
			<a class="layui-btn layui-btn-xs layui-bg-red reporting" lay-event="reporting">
				<i class="layui-icon">&#xe623;</i>
				上报
			</a>
			@{{# } }}
		@endif

			
		
	</script>
	<script type="text/javascript" src="/layadmin/modul/certificatebids/certificates.js"></script>
@endsection
