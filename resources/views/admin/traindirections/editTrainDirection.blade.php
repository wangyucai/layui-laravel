@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $traindirection['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">培训方向代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="pxfx_code" lay-verify="required" placeholder="请输入培训方向代码" disabled value="{{ $traindirection['pxfx_code'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">培训方向名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="pxfx_name" lay-verify="required" placeholder="请输入培训方向名称" value="{{ $traindirection['pxfx_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="edittraindirection">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/traindirections/editTrainDirection.js"></script>
@endsection
