@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $warehouse['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">仓库编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="ckbh_all" value="{{ $ckbh_all }}" lay-verify="required|ckbh_all" placeholder="仓库编号" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">仓库名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="ckmc" lay-verify="required|ckmc" placeholder="请输入仓库名称" value="{{ $warehouse['ckmc'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">仓库位置</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="ckwz" lay-verify="required|ckwz" placeholder="请输入仓库位置" value="{{ $warehouse['ckwz'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editwarehouse">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/warehouses/editWarehouse.js"></script>
@endsection
