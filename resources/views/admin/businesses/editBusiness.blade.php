@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $business['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定业务范围代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="jdywfw_code" lay-verify="required" placeholder="司法鉴定业务范围代码" value="{{ $business['jdywfw_code'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定业务范围名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdywfw_name" lay-verify="required" placeholder="司法鉴定业务范围名称" value="{{ $business['jdywfw_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editbusiness">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/businesses/editBusiness.js"></script>
@endsection
