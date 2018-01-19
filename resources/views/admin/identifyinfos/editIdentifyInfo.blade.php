@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $myidentify['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdry_name" lay-verify="required" placeholder="姓名" value="{{ $myidentify['jdry_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdry_zsbh" lay-verify="required" placeholder="证书编号" value="{{ $myidentify['jdry_zsbh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定业务</label>
			<div class="layui-input-block">
				<select name="jdywfw_code" lay-filter="jdywfw_code" lay-verify="required">
			        <option value="">请选择司法鉴定业务</option>
			        @foreach($businesses as $business)
			       	<option @if($business->jdywfw_code == $myidentify['jdywfw_code']) selected @endif value="{{$business->jdywfw_code}}">{{$business->jdywfw_name}}</option>
			       	@endforeach
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证单位</label>
			<div class="layui-input-block">
		      	<select name="jdry_fzdw" lay-filter="jdry_fzdw" lay-verify="required">
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
					<input type="text" id="jdry_fzrq" class="layui-input jdry_fzrq" lay-verify="required|date" name="jdry_fzrq" value="{{ date('Y-m-d',$myidentify['jdry_fzrq']) }}">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资格审核有效期至</label>
				<div class="layui-input-inline">
					<input type="text" id="jdry_yxrq" class="layui-input jdry_yxrq" lay-verify="required|date" name="jdry_yxrq" value="{{ date('Y-m-d',$myidentify['jdry_yxrq']) }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">所在鉴定机构</label>
			<div class="layui-input-block">
		      	<select name="jdjg_dwdm" lay-filter="jdjg_dwdm" lay-verify="required">
			        <option value="">请选择所在司法鉴定机构</option>
			        @foreach($institutioncodes as $institutioncode)
			       	<option @if($institutioncode->jdjg_dwdm == $myidentify['jdjg_dwdm']) selected @endif  value="{{ $institutioncode->jdjg_dwdm }}">{{ $institutioncode->jdjg_name  }}</option>
			       	@endforeach
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">	
		<label class="layui-form-label">上传证书</label>
			<div class="layui-input-block">
				<button type="button" class="layui-btn" id="jdry_zspath">
				  <i class="layui-icon">&#xe67c;</i>上传证书
				</button>
				<div class="layui-upload-list">
				    <img class="layui-upload-img" id="demo1" src="{{ $myidentify['jdry_zspath'] }}">
  				</div>
			</div>
		</div>
		<div id="upload_zs"></div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editidentifyinfo">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>

	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/editIdentifyInfo.js"></script>
@endsection