@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
	<form class="layui-form layui-form-pane">
		<div class="layui-inline">		
			<div class="layui-input-inline">
		    	<input name="info_myname" id="info_myname" type="text" value="" placeholder="姓名查询" class="layui-input search_input">
		    </div>
		    <div class="layui-input-inline">
		    	<input name="info_zsmc" id="info_zsmc" type="text" value="" placeholder="证书名称查询" class="layui-input search_input">
		    </div>
		    <div class="layui-inline">
			@if($my_dwjb<4)
				<div class="layui-input-inline">
					<select name="danwei" lay-filter="danwei" id="danwei">
				        <option value="">请选择单位</option>	  
				       	@foreach($danwei as $k=>$v)	  
				       	<option value="{{ $v->dwdm }}">{{ $v->html }}{{ $v->dwqc }}</option>
				       	@endforeach
			      	</select>
				</div>
			</div>
			
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input id="my_dwjb" type="checkbox" name="my_dwjb" title="是否包含下辖单位" value="{{ $my_dwjb  }}">
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
	<table id="allinformatizations" lay-filter="allinformatizationtab"></table>

@endsection

@section("js")
	<script type="text/html" id="op">
		<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
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
	<script type="text/javascript" src="/layadmin/modul/informatizations/allInformatizations.js"></script>
@endsection
