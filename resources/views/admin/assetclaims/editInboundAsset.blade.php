@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $inventories['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">仓库名称</label>
			<div class="layui-input-block">
				<select name="kc_ckid" lay-filter="kc_ckid" lay-verify="required">
			        <option value="">请选择仓库名称</option>
			        @foreach($ck as $v)
						<option @if($v['id'] == $inventories['kc_ckid']) selected @endif value="{{ $v['id'] }}">{{ $v['ckmc'] }}</option>
					@endforeach
		      	</select>
				
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">库存数量</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="kc_nums" lay-verify="required|kc_nums" placeholder="库存数量" value="{{ $inventories['kc_nums'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产状况</label>
			<div class="layui-input-block">
				<select name="kc_zczk" lay-filter="kc_zczk" lay-verify="required">
			        <option value="">请选择资产状况</option>
			        @foreach($zczk_arr as $k=>$v)
						<option @if($k == $inventories['kc_zczk']) selected @endif value="{{ $k }}">{{ $v }}</option>
					@endforeach
		      	</select>
				
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">取入依据</label>
			<div class="layui-input-block">
				<select name="kc_qryj" lay-filter="kc_qryj" lay-verify="required">
			        <option value="">请选择取入依据</option>
			        @foreach($qryj_arr as $k=>$v)
						<option @if($k == $inventories['kc_qryj']) selected @endif value="{{ $k }}">{{ $v }}</option>
					@endforeach
		      	</select>		
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">入库日期</label>
			<div class="layui-input-inline">
				<input type="text" id="kc_rkrq" class="layui-input kc_rkrq" lay-verify="required|date" name="kc_rkrq" placeholder="入库日期" value="{{ date('Y-m-d',$inventories['kc_rkrq']) }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editinbound">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/equipmentassets/editinbound.js"></script>
@endsection