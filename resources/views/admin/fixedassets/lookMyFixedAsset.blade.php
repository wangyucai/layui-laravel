@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<div class="layui-form-item">
			<label class="layui-form-label">资产编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_bh_all" value="{{ $gdzc_bh_all  }}" lay-verify="required|gdzc_bh_all" placeholder="资产编号" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_mc" lay-verify="required|gdzc_mc" placeholder="资产名称" value="{{ $myFixedAsset['gdzc_mc'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产品牌</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_pp" lay-verify="required|gdzc_pp" placeholder="资产品牌" value="{{ $myFixedAsset['gdzc_pp'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产型号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_xh" lay-verify="required|gdzc_xh" placeholder="资产型号" value="{{ $myFixedAsset['gdzc_xh'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">发放部门</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_xh" lay-verify="required|gdzc_xh" placeholder="资产型号" value="{{ $ffbm_arr[$myFixedAsset['gdzc_ffbm']] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">单价</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="price" lay-verify="required" placeholder="单价" value="{{ $myFixedAsset['price'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">持有的数量</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_nums" lay-verify="required" placeholder="持有的数量" value="{{ $myFixedAsset['gdzc_nums'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">领取日期</label>
			<div class="layui-input-inline">
				<input type="text" id="gdzc_lqrq" class="layui-input gdzc_lqrq" lay-verify="required|date" name="gdzc_lqrq" placeholder="领取日期" value="{{ date('Y-m-d',$myFixedAsset['gdzc_lqrq']) }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注</label>
			<div class="layui-input-block">
				<textarea disabled placeholder="请输入备注" class="layui-textarea" name="gdzc_bz">{{ $myFixedAsset['gdzc_bz'] }}</textarea>
			</div>
		</div>	
		<div class="layui-form-item">	
		<label class="layui-form-label">扫描件</label>
			<div class="layui-input-block">
				<div class="layui-upload-list">
				    <img class="layui-upload-img" id="demo1" src="{{ $myFixedAsset['gdzc_pic'] }}">
  				</div>
			</div>
		</div>
	</form>
@endsection