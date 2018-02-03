@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_myname" lay-verify="required" placeholder="姓名" value="{{ $myinformatization['info_myname'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsbh" lay-verify="required" placeholder="证书编号" value="{{ $myinformatization['info_zsbh'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsmc" lay-verify="required" placeholder="证书名称" value="{{ $myinformatization['info_zsmc'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">颁证机构</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_bzjg" lay-verify="required" placeholder="颁证机构" value="{{ $myinformatization['info_bzjg'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">发证日期</label>
				<div class="layui-input-inline">
					<input type="text" id="info_fzrq" class="layui-input info_fzrq" lay-verify="required|date" name="info_fzrq" placeholder="发证日期" value="{{ date('Y-m-d',$myinformatization['info_fzrq']) }}" disabled>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书图片</label>
			<div class="layui-input-block">
				@foreach(unserialize($myinformatization['info_zspath']) as $v)
					<a href="{{ $v }}" target="_blank"><img style="width: 100px;height: 100px;" src="{{ $v }}"></a>
				@endforeach
			</div>
		</div>
	</form>
@endsection