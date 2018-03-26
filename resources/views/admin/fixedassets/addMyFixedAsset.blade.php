@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="gdzc_bh" value="{{ $gdzc_bh }}">
		<div class="layui-form-item">
			<label class="layui-form-label">资产编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_bh_all" value="{{ $gdzc_bh_all }}" lay-verify="required|gdzc_bh_all" placeholder="资产编号">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_mc" lay-verify="required|gdzc_mc" placeholder="资产名称">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产品牌</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_pp" lay-verify="required|gdzc_pp" placeholder="资产品牌">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">资产型号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_xh" lay-verify="required|gdzc_xh" placeholder="资产型号">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">发放部门</label>
			<div class="layui-input-block">
				<select name="gdzc_ffbm" lay-filter="gdzc_ffbm" lay-verify="required">
			        <option value="">请选择发放部门</option>
			        @foreach($ffbm_arr as $k=>$v)
						<option value="{{ $k }}">{{ $v }}</option>
					@endforeach
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">单价</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="price" lay-verify="required" placeholder="单价">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">持有的数量</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="gdzc_nums" lay-verify="required" placeholder="持有的数量">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">领取日期</label>
			<div class="layui-input-inline">
				<input type="text" id="gdzc_lqrq" class="layui-input gdzc_lqrq" lay-verify="required|date" name="gdzc_lqrq" placeholder="领取日期">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">备注</label>
			<div class="layui-input-block">
				<textarea placeholder="请输入备注" class="layui-textarea" name="gdzc_bz"></textarea>
			</div>
		</div>	
		<div class="layui-form-item">	
			<label class="layui-form-label">扫描件上传</label>
			<div class="layui-input-inline">
				<button type="button" class="layui-btn" id="gdzc_pic" name="gdzc_pic">
				  <i class="layui-icon">&#xe67c;</i>上传扫描件
				</button>
			</div>
		</div>
		<div id="upload_zs"></div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addmyfixedasset">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/fixedassets/addmyfixedasset.js"></script>
@endsection