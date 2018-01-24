@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">
			<div class="layui-input-inline">
		    	<label class="layui-form-label">开始日期：</label>
				<div class="layui-input-inline">
					<input type="text" id="pxrq_start" class="layui-input" name="pxrq_start" placeholder="选择开始日期">	
				</div>
		    </div>
		</div>
		<div class="layui-inline">
			<div class="layui-input-inline">
		    	<label class="layui-form-label">结束日期：</label>
				<div class="layui-input-inline">
					<input type="text" id="pxrq_end" class="layui-input" name="pxrq_end" placeholder="选择结束日期">
				</div>
		    </div>
		</div>
		
		<div class="layui-inline"> 
		    <div class="layui-inline">
			<label class="layui-form-label">培训方向：</label>
				<div class="layui-input-inline">
					<select name="pxfx" lay-filter="pxfx" id="pxfx">
				        <option value="">请选择培训方向</option>
				        @foreach($fx_data as $fx)
				       	<option value="{{ $fx->pxfx_code }}">{{ $fx->pxfx_name }}</option>
			       		@endforeach
		      	</select>
				</div>
			</div> 
		</div>
		@if($my_dwdm != 100000)
		<div class="layui-inline">
			<div class="layui-inline">
				<div class="layui-inline" style="margin-left: 20px;">
					<a class="layui-btn layui-btn-normal add_btn" style="height: 32px;line-height: 32px;">添加</a>
				</div>
			</div>
		</div>
		@endif
		<div class="layui-inline">
			<div class="layui-input-inline">
			<label class="layui-form-label">培训标题：</label>
			    <div class="layui-input-inline">
			    	<input type="text" value="" placeholder="输入培训项目名称的相关词" class="layui-input search_input" id="pxbt">
			    </div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-input-inline">
			<label class="layui-form-label">培训地点：</label>
			    <div class="layui-input-inline">
			    	<input type="text" value="" placeholder="输入培训地点的相关词" class="layui-input search_input" id="pxdd">
			    </div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-inline">
			<label class="layui-form-label">主办单位：</label>
				<div class="layui-input-inline">
					<select name="zbdw" lay-filter="zbdw" id="zbdw">
				        <option value="">请选择主办单位</option>
				        @foreach($zhuban as $zbdw)
				       	<option value="{{$zbdw->id}}">{{$zbdw->name}}</option>
				       	@endforeach
			      	</select>
		      	</select>
				</div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-inline">
				<div class="layui-inline" style="margin-left: 20px;">
			    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
			    </div> 	
			</div>
		</div>
		    
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="trains" lay-filter="traintab"></table>
	
@endsection
	
@section("js")
	<script type="text/html" id="active">
		@{{# if(d.if_expire == 1){ }}
		<input type="checkbox" checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="是|否" disabled>
		@{{#  } else { }}
		<input type="checkbox" name="close" lay-skin="switch" lay-text="是|否" disabled>
		@{{# } }}
	</script>
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-xs edit_user" lay-event="edit">
			<i class="layui-icon">&#xe642;</i>
			编辑
		</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">
			<i class="layui-icon"></i>
			删除
		</a>
	</script>
	<script type="text/html" id="op1">
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="sednotice">
			<i class="layui-icon">&#xe623;</i>
			通知
		</a>
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="bmuser">
			<i class="layui-icon">&#xe623;</i>
			报名人员
		</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/trains/trains.js"></script>
@endsection
