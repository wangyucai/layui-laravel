@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $inscertificate['id'] }}">
		<div class="layui-form-item">
		<label class="layui-form-label">司法鉴定机构全称</label>
			<div class="layui-input-block">
		      	<select name="jdjg_dm" lay-filter="jdjg_dm" lay-verify="required">
			        <option value="">请选择司法鉴定机构全称</option>
			        @foreach($institutioncodes as $institutioncode)
			       	<option @if($institutioncode->jdjg_dwdm == $inscertificate['jdjg_dm']) selected="selected" @endif value="{{ $institutioncode->jdjg_dwdm }}">{{ $institutioncode->jdjg_name  }}</option>
			       	@endforeach
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zsbh" lay-verify="required" placeholder="证书编号" value="{{ $inscertificate['zsbh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">所属单位全称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="ssdwqc" lay-verify="required" placeholder="所属单位全称" value="{{ $inscertificate['ssdwqc'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">机构负责人</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdjg_fzr" lay-verify="required" placeholder="机构负责人" value="{{ $inscertificate['jdjg_fzr'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定业务范围</label>
			<div class="layui-input-block">
				@foreach($businesses as $business)
					<input @if($business['checked']) checked @endif type="checkbox" class="ywfw_group" name="jdjg_ywfw[]" title="{{$business['jdywfw_name']}}" value="{{$business['jdywfw_code']}}">
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证单位</label>
			<div class="layui-input-block">
		      	<select name="fzdw" lay-filter="fzdw" lay-verify="required">
			        <option value="">请选择发证单位</option>
			       	<option @if($inscertificate['fzdw'] == '最高人民检察院') selected @endif  value="最高人民检察院">最高人民检察院</option>
			       	<option @if($inscertificate['fzdw'] == '贵州省人民检察院') selected @endif value="贵州省人民检察院">贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">发证日期</label>
				<div class="layui-input-inline">
					<input type="text" id="fzrq" class="layui-input fzrq" lay-verify="required|date" name="fzrq" value="{{ date('Y-m-d',$inscertificate['fzrq']) }}">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">资格审核有效期至</label>
				<div class="layui-input-inline">
					<input type="text" id="zgsh_yxqz" class="layui-input zgsh_yxqz" lay-verify="required|date" name="zgsh_yxqz" value="{{ date('Y-m-d',$inscertificate['zgsh_yxqz']) }}">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">是否激活代码</label>
				<div class="layui-input-inline">
					<input type="radio" name="if_jh" value="1" title="是" @if($inscertificate['if_jh']==1) checked @endif>
					<input type="radio" name="if_jh" value="0" title="否" @if($inscertificate['if_jh']==0) checked @endif>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editinscertificate">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/inscertificates/editInscertificate.js"></script>
@endsection