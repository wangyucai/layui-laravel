@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $equipmentassets['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">资产编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zcbh_all" value="{{ $zcbh_all  }}" lay-verify="required|zcbh_all" placeholder="资产编号" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zcmc" lay-verify="required|zcmc" placeholder="资产名称" value="{{ $equipmentassets['zcmc'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产品牌</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zcpp" lay-verify="required|zcpp" placeholder="资产品牌" value="{{ $equipmentassets['zcpp'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产型号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zcxh" lay-verify="required|zcxh" placeholder="资产型号" value="{{ $equipmentassets['zcxh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产单位</label>
			<div class="layui-input-block">
				<select name="zcdw" lay-filter="zcdw" lay-verify="required">
			        <option value="">请选择资产单位</option>
			        @foreach($assetunits as $assetunit)
						<option @if($assetunit['zcdw_code']==$equipmentassets['zcdw']) selected @endif value="{{ $assetunit['zcdw_code'] }}">{{ $assetunit['zcdw_name'] }}</option>
					@endforeach
		      	</select>
				
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产性质</label>
			<div class="layui-input-block">
				<select name="zcxz" lay-filter="zcxz" lay-verify="required">
			        <option value="">请选择资产性质</option>
			        @foreach($zcxz_arr as $k=>$v)
						<option @if($k==$equipmentassets['zcxz']) selected @endif value="{{ $k }}" >{{ $v }}</option>
					@endforeach
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">产地</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="cd" lay-verify="required" placeholder="产地" value="{{ $equipmentassets['cd'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editequipmentasset">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/equipmentassets/editequipmentasset.js"></script>
@endsection