@extends("admin.layout.main")

@section("content")
	<table id="lookappraiserstatistics" lay-filter="lookappraiserstatistictab">
		<input type="hidden" name="jdry_id" value="{{ $jdry_id  }}" id="jdry_id">
	</table>
@endsection

@section("js")
	<script type="text/html" id="op">
	<a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="detail">
		<i class="layui-icon">&#xe623;</i>
		查看
	</a>
	</script>
	<script type="text/javascript" src="/layadmin/modul/identifyinfos/lookappraiserstatistics.js"></script>
@endsection
