@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="my_dwdm" value="{{ $my_info->company_dwdm }}">
		<input type="hidden" name="my_bm" value="{{ $my_info->mechanism_id }}">
		<input type="hidden" name="bz" value="{{ $thisModule['bz'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="my_name" lay-verify="required|my_name" placeholder="姓名" value="{{ $my_info->real_name }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">身份证号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="my_sfzh" lay-verify="required" placeholder="身份证号" value="{{ $my_info->id_number }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsmc" lay-verify="required" placeholder="证书名称" value="{{ $thisZsmc }}" disabled>	
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsbh" lay-verify="required|zsbh" placeholder="证书编号" value="{{ $thisZsbh }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">业务类别</label>
			<div class="layui-input-block">
				@foreach($ywlb as $k=>$v)
					<input type="checkbox" class="ywlb_group" name="ywlb[]" title="{{$v}}" value="{{$k}}">
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证机关</label>
			<div class="layui-input-block">
				<select name="fzjg" lay-filter="fzjg" lay-verify="required">
			        <option value="">请选择发证单位</option>
			       	<option value="0" @if($thisModule['fzjg']==0) selected="selected" @endif>最高人民检察院</option>
			       	<option value="1" @if($thisModule['fzjg']==1) selected="selected" @endif>贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">有效年限</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsyxq" lay-verify="required" placeholder="证书有效年限" value="{{ $thisModule['zsyxq'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addcarbid">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/certificatebids/addcarbid.js"></script>
@endsection