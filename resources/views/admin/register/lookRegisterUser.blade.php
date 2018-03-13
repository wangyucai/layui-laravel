@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width:60%;">
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="username" value="{{$admin['username']}}" lay-verify="required" placeholder="请输入用户名" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">手机全号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="tel" value="{{$admin['tel']}}" lay-verify="required" placeholder="请输入手机号" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">座机号或集团短号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="tel_hm" value="{{$admin['tel_hm']}}" lay-verify="required" placeholder="请输入座机号或集团短号">
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">单位</label>
			<div class="layui-input-block">
		      	<select name="company_dwdm" lay-filter="danwei" lay-verify="required|danwei">
			       	@foreach($danwei as $v)
			       		<option 
			       		@if($v->dwdm==$admin['company_dwdm']) 
			       			selected="selected" 
			       	    @endif 
			       		value="{{ $v->dwdm }}" disabled>{{ $v->html }}{{ $v->dwqc }}</option>
			       	@endforeach
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item" id="bm">
		<label class="layui-form-label">部门</label>
			<div class="layui-input-block">
		      	<select id="bumen" name="mechanism_id" lay-filter="bumen" lay-verify="required|bumen">
		      		@foreach($bumen as $v)
			       		<option 
			       		@if($v['id']==$admin['mechanism_id']) 
			       			selected="selected" 
			       	    @endif 
			       		value="{{ $v['id'] }}" disabled>{{ $v['nsjgmc'] }}</option>
			       	@endforeach
	      	</select>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/register/editRegisterUser.js"></script>
		<script type="text/javascript" src="/layadmin/modul/register/register.js"></script>
@endsection
