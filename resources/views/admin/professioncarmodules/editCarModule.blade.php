@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $professioncarmodule['id'] }}">
				<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<select name="zsmc" lay-filter="zsmc" lay-verify="required">
			        <option value="">请选择证书名称</option>
			        @foreach($professioncarcodes as $professioncarcode)
			       		<option value="{{$professioncarcode->car_code}}" @if($professioncarcode->car_code==$professioncarmodule['zsmc']) selected="selected" @endif>{{$professioncarcode->car_name}}</option>
			       	@endforeach
		      	</select>			
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zsbh" lay-verify="required|zsbh" placeholder="证书编号" value="{{ $professioncarmodule['zsbh'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">业务类别</label>
			<div class="layui-input-block">
				@foreach($businesses as $business)
					<input type="checkbox" class="ywlb_group" name="ywlb[]" title="{{$business['jdywfw_name']}}" value="{{$business['jdywfw_code']}}" @if($business['checked']) checked @endif>
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证机关</label>
			<div class="layui-input-block">
		      	<select name="fzjg" lay-filter="fzjg" lay-verify="required">
			        <option value="">请选择发证单位</option>
			       	<option value="0" @if($professioncarmodule['fzjg']==0) selected="selected" @endif>最高人民检察院</option>
			       	<option value="1" @if($professioncarmodule['fzjg']==1) selected="selected" @endif>贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书有效年限</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="zsyxq" lay-verify="required" placeholder="证书有效年限" value="{{ $professioncarmodule['zsyxq'] }}">
			</div>
		</div>

		<div class="layui-form-item">
			<label class="layui-form-label">标识</label>
			<div class="layui-input-inline">
				<input type="radio" name="bz" value="1" title="有效" @if($professioncarmodule['bz']==1) checked @endif>
				<input type="radio" name="bz" value="0" title="无效" @if($professioncarmodule['bz']==0) checked @endif>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editcarmodule">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/professioncarmodules/editcarmodule.js"></script>
@endsection