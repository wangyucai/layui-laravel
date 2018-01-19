@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdry_name" lay-verify="required" placeholder="姓名" value="{{ $myidentify['jdry_name'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdry_zsbh" lay-verify="required" placeholder="证书编号" value="{{ $myidentify['jdry_zsbh'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定业务</label>
			<div class="layui-input-block">
		      	<select  lay-filter="jdjg_dwdm" disabled>
			        <option>{{ $jdywfw_name }}</option>
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证单位</label>
			<div class="layui-input-block">
		      	<select disabled>
			        <option value="">请选择发证单位</option>
			       	<option @if("最高人民检察院" == $myidentify['jdry_fzdw']) selected @endif  value="最高人民检察院">最高人民检察院</option>
			       	<option @if("贵州省人民检察院" == $myidentify['jdry_fzdw']) selected @endif value="贵州省人民检察院">贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">发证日期</label>
				<div class="layui-input-inline">
					<input type="text" id="jdry_fzrq" class="layui-input jdry_fzrq" l value="{{ date('Y-m-d',$myidentify['jdry_fzrq']) }}" disabled>
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资格审核有效期至</label>
				<div class="layui-input-inline">
					<input type="text" id="jdry_yxrq" class="layui-input jdry_yxrq" value="{{ date('Y-m-d',$myidentify['jdry_yxrq']) }}" disabled>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">所在鉴定机构</label>
			<div class="layui-input-block">
		      	<select  lay-filter="jdjg_dwdm" disabled>
			        <option>{{ $jdjg_name }}</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">	
		<label class="layui-form-label">证书</label>
			<div class="layui-input-block">
				<div class="layui-upload-list">
				    <img class="layui-upload-img" id="demo1" src="{{ $myidentify['jdry_zspath'] }}">
  				</div>
			</div>
		</div>
		<div id="upload_zs"></div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/editIdentifyInfo.js"></script>
@endsection