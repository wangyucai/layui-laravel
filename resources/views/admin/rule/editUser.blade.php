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
		<div class="layui-form-item">
		<label class="layui-form-label">单位级别</label>
			<div class="layui-input-block">
		      	<select name="dwjb" lay-filter="dwjb" lay-verify="required">
			        <option value="">请选择单位级别</option>
			       	<option value="2" @if($admin['dwjb']==2) selected="selected" @endif>省级</option>
			       	<option value="3" @if($admin['dwjb']==3) selected="selected" @endif>市级</option>
			       	<option value="4" @if($admin['dwjb']==4) selected="selected" @endif>县级</option>
		      	</select>
		    </div>
		</div>
		<input type="hidden" name="id" value="{{$admin['id']}}">
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button type="button" class="layui-btn" lay-submit lay-filter="editUser">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/rule/editUser.js"></script>
@endsection
