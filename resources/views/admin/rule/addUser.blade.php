@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width:60%;">
		<div class="layui-form-item">
			<label class="layui-form-label">用户组</label>
			<div class="layui-input-block">
				@foreach($roles as $role)
					<input type="checkbox" class="user_group" name="role_id[]" title="{{$role->name}}" value="{{$role->id}}">
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">用户名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="username" lay-verify="required" placeholder="请输入用户名">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">密码</label>
			<div class="layui-input-block">
				<input type="password" class="layui-input" name="password" lay-verify="required" placeholder="请输入密码">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">确认密码</label>
			<div class="layui-input-block">
				<input type="password" class="layui-input" name="password_confirmation" lay-verify="required" placeholder="请再次输入密码">
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">单位</label>
			<div class="layui-input-block">
		      	<select name="company_dwdm" lay-filter="danwei" lay-verify="required|danwei">
			        <option value="">请选择单位</option>
			       	@foreach($danwei as $v)
			       		<option value="{{ $v->dwdm }}">{{ $v->html }}{{ $v->dwqc }}</option>
			       	@endforeach
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">单位级别</label>
			<div class="layui-input-block">
		      	<select name="dwjb" lay-filter="dwjb" lay-verify="required">
			        <option value="">请选择单位级别</option>
			       	<option value="2">省级</option>
			       	<option value="3">市级</option>
			       	<option value="4">县级</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="adduser">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/rule/addUser.js"></script>
@endsection
