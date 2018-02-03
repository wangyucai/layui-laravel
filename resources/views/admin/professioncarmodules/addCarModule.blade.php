@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<select name="zsmc" lay-filter="zsmc" lay-verify="required">
			        <option value="">请选择证书名称</option>
			        @foreach($professioncarcodes as $professioncarcode)
			       		<option value="{{$professioncarcode->car_code}}">{{$professioncarcode->car_name}}</option>
			       	@endforeach
		      	</select>			
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zsbh" lay-verify="required" placeholder="证书编号">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">业务类别</label>
			<div class="layui-input-block">
				@foreach($businesses as $business)
					<input type="checkbox" class="ywlb_group" name="ywlb[]" title="{{$business->jdywfw_name}}" value="{{$business->jdywfw_code}}">
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证机关</label>
			<div class="layui-input-block">
		      	<select name="fzjg" lay-filter="fzjg" lay-verify="required">
			        <option value="">请选择发证单位</option>
			       	<option value="0">最高人民检察院</option>
			       	<option value="1">贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书有效年限</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zsyxq" lay-verify="required" placeholder="证书有效年限">
			</div>
		</div>

		<div class="layui-form-item">
			<label class="layui-form-label">标识</label>
			<div class="layui-input-inline">
				<input type="radio" name="bz" value="1" title="有效" checked>
			<input type="radio" name="bz" value="0" title="无效">
			</div>
		</div>

		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addcarmodule">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/professioncarmodules/addcarmodule.js"></script>
@endsection