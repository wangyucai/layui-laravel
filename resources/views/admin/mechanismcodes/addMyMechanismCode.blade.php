@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="code_name" lay-verify="required|code_name" placeholder="请输入内设机构名称">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构行政级别</label>
			<div class="layui-input-block">
				<select name="fj_jdjg_code" lay-filter="fj_jdjg_code" lay-verify="required">
			        <option value="">请选择内设机构行政级别</option>
			        <option value="2">省级</option>
			       	<option value="3">市级</option>
			       	<option value="4">县级</option>
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">内设机构代码</label>
			<div class="layui-input-block">
				<select name="fj_jdjg_code" lay-filter="fj_jdjg_code" lay-verify="required">
			        <option value="">请选择内设机构代码</option>
			        @foreach($mechanismcodes as $mechanismcode)
			       		<option value="{{ $mechanismcode['code'] }}">{{ $mechanismcode['code']  }}</option>
			       	@endforeach
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addmymechanism">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/mechanismcodes/addMyMechanism.js"></script>
@endsection
