@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $institutioncode['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定机构代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="jdjg_code" lay-verify="required" placeholder="司法鉴定机构代码" value="{{ $institutioncode['jdjg_code'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定机构所属单位代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdjg_dwdm" lay-verify="required|jdjg_dwdm" placeholder="司法鉴定机构所属单位代码" value="{{ $institutioncode['jdjg_dwdm'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">司法鉴定机构名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="jdjg_name" lay-verify="required" placeholder="司法鉴定机构名称" value="{{ $institutioncode['jdjg_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">所属上级鉴定机构</label>
			<div class="layui-input-block">
		      	<select name="fj_jdjg_code" lay-filter="fj_jdjg_code" lay-verify="required">
			        <option value="">请选择所属上级鉴定机构</option>
			        @foreach($sjdwdm as $v)
			       	<option @if($v->jdjg_dwdm == $institutioncode['fj_jdjg_code']) selected="selected" @endif value="{{ $v->jdjg_dwdm }}">{{ $v->jdjg_name  }}</option>
			       	@endforeach
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">单位级别</label>
			<div class="layui-input-block">
		      	<select name="jdjg_level" lay-filter="jdjg_level" lay-verify="required">
			        <option value="">请选择单位级别</option>
			       	<option @if($institutioncode['jdjg_level'] == 1) selected="selected" @endif value="1">省级</option>
			       	<option @if($institutioncode['jdjg_level'] == 2) selected="selected" @endif value="2">市级</option>
			       	<option @if($institutioncode['jdjg_level'] == 3) selected="selected" @endif value="3">县级</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editinstitutioncode">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/institutioncodes/editInstitutionCode.js"></script>
@endsection
