@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<input type="hidden" name="id" value="{{ $certificateBid['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="my_name" lay-verify="required|my_name" placeholder="姓名" value="{{ $certificateBid['my_name'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">身份证号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="my_sfzh" lay-verify="required" placeholder="身份证号" value="{{ $certificateBid['my_sfzh'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsmc" lay-verify="required" placeholder="证书名称" value="{{ $certificateBid['zsmc'] }}" disabled>	
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsbh" lay-verify="required|zsbh" placeholder="证书编号" value="{{ $certificateBid['zsbh'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">业务类别</label>
			<div class="layui-input-block">
				@foreach($ywlb as $k=>$v)
					<input type="checkbox" class="ywlb_group" name="ywlb[]" title="{{$v}}" value="{{$k}}" @if(in_array($k, $hasbusinesses)) checked @endif>
				@endforeach
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">发证机关</label>
			<div class="layui-input-block">
				<select name="fzjg" lay-filter="fzjg" lay-verify="required">
			        <option value="">请选择发证单位</option>
			       	<option value="0" @if($certificateBid['fzjg']==0) selected="selected" @endif>最高人民检察院</option>
			       	<option value="1" @if($certificateBid['fzjg']==1) selected="selected" @endif>贵州省人民检察院</option>
		      	</select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">有效年限</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="zsyxq" lay-verify="required" placeholder="证书有效年限" value="{{ $certificateBid['zsyxq'] }}" disabled>
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editcertificate">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/certificatebids/editcertificate.js"></script>
@endsection