@extends("admin.layout.main")
@section("content")
	<div class="layui-card-header" style="height: 95px;">
		<h2>{{ $myemaildetail['email_theme'] }}</h2>
		<p> <span>{{ $myemaildetail['created_at'] }}</span> </p> 
	</div> 
	<div class="layui-card-body layui-text"> 
		<div class="layui-card-header"> 
			<p><span style=""><b>邮件内容：</b></span>{{ $myemaildetail['email_content'] }}</p>
		</div> 
		@if($myemaildetail['email_attachments'])
		<div class="layui-card-header" style="padding-top: 30px;"> 
			<p><b>附件下载：<b>
			@foreach(unserialize($myemaildetail['email_attachments']) as $v)
			<a href="{{ $v }}" class="layui-btn layui-btn-primary layui-btn-sm attachment-down">点击下载</a>
			@endforeach
			</p>
		</div> 
		@endif
		@if($myemaildetail['email_pics'])
		<div class="layui-card-header" style="padding-top: 30px;"> 
			<p><b>图片展示：<b>
			@foreach(unserialize($myemaildetail['email_pics']) as $v)
			<a href="{{ $v }}" target="_blank"><img src="{{ $v }}" style="width: 100px;height: 100px;"></a>
			@endforeach
			</p>
		</div> 
		@endif
  	</div>
@endsection