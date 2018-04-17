@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width:60%;">
		<div class="layui-form-item">
			<label class="layui-form-label">我的角色</label>
			<div class="layui-input-block">
				@foreach($roles as $role)
					<input type="checkbox" class="user_group" name="role_id[]" title="{{$role['name']}}" value="{{$role['id']}}" checked>
				@endforeach
			</div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/register/editRegisterUser.js"></script>
@endsection
