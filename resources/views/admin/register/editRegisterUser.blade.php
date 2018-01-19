@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width:60%;">
		<div class="layui-form-item">
			<label class="layui-form-label">用户组</label>
			<div class="layui-input-block">
				@foreach($roles as $role)
					<input type="checkbox" class="user_group" name="role_id[]" title="{{$role['name']}}" value="{{$role['id']}}" @if($role['checked']) checked @endif">
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="username" value="{{$admin['username']}}" lay-verify="required" placeholder="请输入用户名">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">手机号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="tel" value="{{$admin['tel']}}" lay-verify="required" placeholder="请输入手机号">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">电话号码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="tel_hm" value="{{$admin['tel_hm']}}" lay-verify="required" placeholder="请输入电话号码">
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">单位</label>
			<div class="layui-input-block">
		      	<select name="company_dwdm" lay-filter="danwei" lay-verify="required|danwei">
			        <option value="">请选择单位</option>
			       	@foreach($danwei as $v)
			       		<option 
			       		@if($v->dwdm==$admin['company_dwdm']) 
			       			selected="selected" 
			       	    @endif 
			       		value="{{ $v->dwdm }}">{{ $v->html }}{{ $v->dwqc }}</option>
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
			       		value="{{ $v['id'] }}">{{ $v['nsjgmc'] }}</option>
			       	@endforeach
	      	</select>
		    </div>
		</div>
		<input type="hidden" name="id" value="{{$admin['id']}}">
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button type="button" class="layui-btn" lay-submit lay-filter="editRegisterUser">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/register/editRegisterUser.js"></script>
		<script type="text/javascript" src="/layadmin/modul/register/register.js"></script>
@endsection
