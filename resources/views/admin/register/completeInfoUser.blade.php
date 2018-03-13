@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<form class="layui-form layui-form-pane">
		<div class="layui-inline">
		    <div class="layui-inline">
				<div class="layui-input-inline">
					<select name="sex" lay-filter="sex" id="sex">
				        <option value="">请选择性别</option>	  
				       	<option value="男">男</option>
				       	<option value="女">女</option>
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="nation" lay-filter="nation" id="nation">
				        <option value="">请选择民族</option>
				        @foreach($nation_arr as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach
			      	</select>
				</div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="political_outlook" lay-filter="political_outlook" id="political_outlook">
				        <option value="">请选择政治面貌</option>
				        @foreach($political_outlook as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach	  
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input type="text" id="join_work_time" class="layui-input" name="join_work_time" placeholder="请选择参加工作时间" style="width: 212px;">	
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input type="text" id="join_procuratorate_time" class="layui-input" name="join_procuratorate_time" placeholder="请选择进入检察院工作时间" style="width: 212px;">	
				</div>
			</div>
		</div>
		<div class="layui-inline">
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="if_work" lay-filter="if_work" id="if_work">
				        <option value="">请选择是否在岗</option>	  
				       	<option value="1">是</option>
				       	<option value="0">否</option>
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="education" lay-filter="education" id="education">
				        <option value="">请选择学历</option>	  
				        @foreach($education as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach	  
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="academic_degree" lay-filter="academic_degree" id="academic_degree">
				        <option value="">请选择学位</option>	  
				       	@foreach($academic_degree as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach	  
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="procurator" lay-filter="procurator" id="procurator">
				        <option value="">请选择检察官员额</option>	  
				       	@foreach($procurator as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach	  
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="administrative_level" lay-filter="administrative_level" id="administrative_level">
				        <option value="">请选择行政级别</option>	  
				       	@foreach($administrative_level as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach
			      	</select>
				</div>
			</div>		
		</div>
		<div class="layui-inline">		
			<div class="layui-inline">
				<div class="layui-input-inline">
					<select name="technician_title" lay-filter="technician_title" id="technician_title">
				        <option value="">请选择专业技师职称</option>	  
				       	@foreach($technician_title as $k=>$v)	  
				       	<option value="{{ $k }}">{{ $v }}</option>
				       	@endforeach
			      	</select>
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input type="text" id="start_time" class="layui-input" name="start_time" placeholder="请选择开始时间" style="width: 212px;">	
				</div>
			</div>
			<div class="layui-inline">
				<div class="layui-input-inline">
					<input type="text" id="end_time" class="layui-input" name="end_time" placeholder="请选择结束时间" style="width: 212px;">	
				</div>
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
		</div>
		<div class="layui-inline">		
			<div class="layui-input-inline">
		    	<input name="like_search" id="like_search" type="text" value="" placeholder="模糊查询" class="layui-input search_input" style="width:857px;">
		    </div>
		    <div class="layui-inline" style="margin-left: 10px;">
		    	<a class="layui-btn search_btn" style="height: 32px;line-height: 32px;">查询</a>
		    </div> 	
		    <div class="layui-inline" style="margin-left: 10px;">
		    	<a class="layui-btn export_btn" style="height: 32px;line-height: 32px;">导出</a>
		    </div> 
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</form>
	</blockquote>
	<table id="completeinfousers" lay-filter="completeinfousertab"></table>

@endsection

@section("js")
	<script type="text/html" id="completeinfouseractive">
		@{{# if(d.perinfor_if_check == 1){ }}
		<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="completeinfouseractive">已审核</a>
		@{{#  } else { }}
		<a class="layui-btn layui-btn-warm layui-btn-danger layui-btn-xs" lay-event="completeinfouseractive">未审核</a>
		@{{# } }}
	</script>
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
	<script type="text/javascript" src="/layadmin/modul/register/completeinfousers.js"></script>
@endsection
