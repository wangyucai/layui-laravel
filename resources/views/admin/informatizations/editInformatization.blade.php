@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $myinformatization['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_myname" lay-verify="required" placeholder="姓名" value="{{ $myinformatization['info_myname'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsbh" lay-verify="required" placeholder="证书编号" value="{{ $myinformatization['info_zsbh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsmc" lay-verify="required" placeholder="证书名称" value="{{ $myinformatization['info_zsmc'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">颁证机构</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_bzjg" lay-verify="required" placeholder="颁证机构" value="{{ $myinformatization['info_bzjg'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">发证日期</label>
				<div class="layui-input-inline">
					<input type="text" id="info_fzrq" class="layui-input info_fzrq" lay-verify="required|date" name="info_fzrq" placeholder="发证日期" value="{{ date('Y-m-d',$myinformatization['info_fzrq']) }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editinformatization">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/informatizations/editInformatization.js"></script>
@endsection