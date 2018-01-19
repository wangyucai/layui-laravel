@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="code" lay-verify="required" placeholder="请输入内设机构代码">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="code_name" lay-verify="required|code_name" placeholder="请输入内设机构名称">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addmechanismcode">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/mechanismcodes/addMechanismCode.js"></script>
@endsection
