@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<div class="layui-form-item">
			<label class="layui-form-label">职业证书代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="car_code" lay-verify="required" placeholder="请输入职业资格证书代码">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">职业证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="car_name" lay-verify="required|car_name" placeholder="请输入职业资格证书">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addprofessioncarcode">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/professioncarcodes/addProfessionCarCode.js"></script>
@endsection
