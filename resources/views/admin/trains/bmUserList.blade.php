@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		@if($my_dwjb > $send_px_dwjb)
		<div class="layui-inline">
			<a class="layui-btn click-btn recommend" style="background-color:#5FB878" data-type="getCheckData">上报用户</a>
		</div>
		@endif
		@if($my_dwjb == $send_px_dwjb && $my_dwdm == $send_px_dwdm)
		<div class="layui-inline">
			<a class="layui-btn click-btn message_feedback;" style="background-color:#6d5fb8" data-type="getFeedbackData">信息反馈</a>
		</div>
		@endif
	</blockquote>
	<table id="bmusers" lay-filter="bmusertab">
		<input type="hidden" name="notice_id" value="{{ $notice_id  }}" id="notice_id">
		<input type="hidden" name="my_dwdm" value="{{ $my_dwdm  }}" id="my_dwdm">
		<input type="hidden" name="my_dwjb" value="{{ $my_dwjb  }}" id="my_dwjb">
	</table>

@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/trains/bmusers.js"></script>
@endsection
