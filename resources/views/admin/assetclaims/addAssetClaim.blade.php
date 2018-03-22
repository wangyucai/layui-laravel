@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="ly_zcid" id='ly_zcid' value="{{ $zc_id }}">
		<input type="hidden" name="ly_ckid" value="{{ $ck_id }}">
		<input type="hidden" name="kc_nums" id='kc_nums' value="{{ $kc_nums }}">
		<div class="layui-form-item">
			<label class="layui-form-label">领用数量</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" id="ly_nums" name="ly_nums" lay-verify="required|ly_nums" placeholder="领用数量">
			</div>
		</div>
		
		<div class="layui-form-item">
			<label class="layui-form-label">归属门类</label>
			<div class="layui-input-block">
				<select name="ly_gsml" lay-filter="ly_gsml" lay-verify="required">
			        <option value="">请选择归属门类</option>
			        @foreach($gsml_arr as $k=>$v)
						<option value="{{ $k }}">{{ $v }}</option>
					@endforeach
		      	</select>
				
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产用途</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="ly_zcyt" lay-verify="required" placeholder="资产用途">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">领用日期</label>
			<div class="layui-input-inline">
				<input type="text" id="lyrq" class="layui-input lyrq" lay-verify="required|date" name="lyrq" placeholder="领用日期">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addassetclaim">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/assetclaims/addassetclaim.js"></script>
@endsection