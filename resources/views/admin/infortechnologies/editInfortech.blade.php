@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $infortechnology['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">信息化技术代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="xxhjs_code" lay-verify="required" placeholder="请输入信息化技术代码" disabled value="{{ $infortechnology['xxhjs_code'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">信息化技术名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="xxhjs_name" lay-verify="required" placeholder="请输入信息化技术名称" value="{{ $infortechnology['xxhjs_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editinfortech">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/infortechnologies/editInforTech.js"></script>
@endsection
