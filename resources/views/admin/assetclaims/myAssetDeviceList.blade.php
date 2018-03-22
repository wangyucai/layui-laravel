@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<div class="layui-inline">
		    <div class="layui-input-inline">
		    	<input type="text" value="" placeholder="资产名称" class="layui-input search_input">
		    </div>
		    <a class="layui-btn search_btn">查询</a>
		</div>
		<div class="layui-inline">
			<div class="layui-form-mid layui-word-aux"></div>
		</div>
	</blockquote>
	<table id="myassetdevices" lay-filter="myassetdevicetab">
		<input type="hidden" name="zc_id" value="{{ $id }}" id="zc_id">
	</table>

@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/assetclaims/myassetdevices.js"></script>
@endsection
