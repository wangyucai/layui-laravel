@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $mechanism['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="nsjgmc" lay-verify="required|nsjgmc" placeholder="请输入内设机构名称" value="{{ $mechanism['nsjgmc'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editmymechanism">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/mechanismcodes/editMyMechanism.js"></script>
@endsection
