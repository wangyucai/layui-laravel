@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $company['id'] }}">
		<div class="layui-form-item">
			<div class="layui-inline">		
				<label class="layui-form-label">单位代码</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input dwdm" lay-verify="required|dwdm" placeholder="请输入单位代码" name="dwdm" value="{{ $company['dwdm'] }}">
				</div>
			</div>
			<div class="layui-inline">		
				<label class="layui-form-label">单位简称</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input dwjc" lay-verify="required" placeholder="请输入单位简称" name="dwjc" value="{{ $company['dwjc'] }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">		
				<label class="layui-form-label">单位全称</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input dwqc" lay-verify="required" placeholder="请输入单位全称" name="dwqc" value="{{ $company['dwqc'] }}">
				</div>
			</div>
			<div class="layui-inline">		
				<label class="layui-form-label">单位缩写</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input dwsx" lay-verify="" placeholder="请输入单位缩写" name="dwsx" value="{{ $company['dwsx'] }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">		
				<label class="layui-form-label">单位级别</label>
				<div class="layui-input-inline">
			      	<select name="dwjb" lay-filter="dwjb" lay-verify="">
				        <option value="">请选择单位级别</option>
				       	<option @if($company['dwjb'] == 2) selected="selected" @endif value="2">省级</option>
				       	<option @if($company['dwjb'] == 3) selected="selected" @endif value="3">市级</option>
				       	<option @if($company['dwjb'] == 4) selected="selected" @endif value="4">县级</option>
			      	</select>
				</div>
			</div>
			<div class="layui-inline">		
				<label class="layui-form-label">上级单位代码</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input sjdm" lay-verify="required" placeholder="请输入上级单位代码" name="sjdm" value="{{ $company['sjdm'] }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">		
				<label class="layui-form-label">上级缩写</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input sjsx" lay-verify="required" placeholder="请输入上级缩写" name="sjsx" value="{{ $company['sjsx'] }}">
				</div>
			</div>
			<div class="layui-inline">		
				<label class="layui-form-label">单位地址</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input dwdz" lay-verify="" placeholder="请输入单位地址" name="dwdz" value="{{ $company['dwdz'] }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">		
				<label class="layui-form-label">邮政编码</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input yzbm" lay-verify="" placeholder="请输入邮政编码" name="yzbm" value="{{ $company['yzbm'] }}">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editcompany">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/companies/editCompany.js"></script>
@endsection
