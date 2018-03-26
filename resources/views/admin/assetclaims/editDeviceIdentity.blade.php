@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $deviceIdentity['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">设备ID</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="sbsf_xh" lay-verify="required" placeholder="请输入设备ID" disabled value="{{ $deviceIdentity['sbsf_xh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="sbsf_bz" lay-verify="required|sbsf_bz" placeholder="请输入备注" value="{{ $deviceIdentity['sbsf_bz'] }}">
			</div>
		</div>
		<div class="layui-form-item">	
		<label class="layui-form-label">扫描件上传</label>
			<div class="layui-input-block">
				<button type="button" class="layui-btn" id="sbsf_pic">
				  <i class="layui-icon">&#xe67c;</i>上传扫描件
				</button>
				@if($deviceIdentity['sbsf_pic'])
				<div class="layui-upload-list">
				    <img class="layui-upload-img" id="demo1" src="{{ $deviceIdentity['sbsf_pic'] }}">
  				</div>
  				@endif
			</div>
		</div>
		<div id="upload_zs"></div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editdeviceidentity">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/assetclaims/editDeviceIdentity.js"></script>
@endsection
