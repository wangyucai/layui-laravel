@extends("admin.layout.main")
@section("content")
	<div class="layui-card-header" style="height: 95px;">
		<h2>{{ $mynoticedetail['title'] }}</h2>
		<p> <span>{{ $mynoticedetail['created_at'] }}</span> </p> 
	</div> 
	<div class="layui-card-body layui-text"> 
		<div class="layui-card-header"> 
			<p><span style=""><b>通知内容：</b></span>{{ $mynoticedetail['content'] }}</p>
		</div> 
		@if($mynoticedetail['content2'])
		<div class="layui-card-header" style="padding-top: 30px;"> 
			<p><span style=""><b>培训内容：</b>{{ $mynoticedetail['content2'] }}</p>
		</div> 
		@endif
		@if($mynoticedetail['attachment'])
		<div class="layui-card-header" style="padding-top: 30px;"> 
			<p><b>附件下载：<b><a notice-id="{{$mynoticedetail['id']}}" user-id="{{$mynoticedetail['user_id']}}" attachment-url="{{$mynoticedetail['attachment']}}" href="#" class="layui-btn layui-btn-primary layui-btn-sm attachment-down">附件下载</a></p>
		</div> 
		@endif
		@if($mynoticedetail['px_id'])
		<div class="layui-card-header" style="padding-top: 30px;"> 
			<p><span style=""><b>点击报名：</b>
			@if($mynoticedetail['if_enter']==1)
			<button class="layui-btn layui-btn-radius">您已报名</button>	
			@else
			<button enter-dwdm="{{ $enter_dwdm }}" notice-id="{{$mynoticedetail['id']}}" user-id="{{$mynoticedetail['user_id']}}" class="layui-btn layui-btn-radius layui-btn-danger enter">我要报名</button>
			@endif
			</p>
		</div> 
		@endif 
  	</div>
@endsection
@section("js")
<script type="text/javascript">
	layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'], function () {
            var form = layui.form, dialog = layui.dialog, his = layui.his, $ = layui.$;
		    // 附件下载
            $('.attachment-down').click(function (event) {
            	target = $(event.target);
            	var user_id = target.attr('user-id');
            	var notice_id = target.attr('notice-id');
            	var attachment_url = target.attr('attachment-url');
            	dialog.confirm('确认下载附件么', function () {
                	var loadIndex = dialog.load('下载附件中，请稍候');
	                his.ajax({
	                    url: '/admin/downattachment'
	                    ,type: 'post'
	                    ,data: {notice_id: notice_id, user_id: user_id}
	                    ,complete: function () {
	                        dialog.close(loadIndex);
	                    }
	                    ,error: function (msg) {
	                        dialog.error(msg);
	                    }
	                    ,success: function () {
	                    	window.location.href = (attachment_url);
	                        dialog.msg('下载成功');
	                    }
	                });
	            })
           	});
           	// 我要报名
           	$('.enter').click(function (event) {
            	target = $(event.target);
            	var enter_dwdm = target.attr('enter-dwdm');
            	var user_id = target.attr('user-id');
            	var notice_id = target.attr('notice-id');
            	dialog.confirm('确认报名吗', function () {
                	var loadIndex = dialog.load('报名中，请稍候');
	                his.ajax({
	                    url: '/admin/trainnotice/enter'
	                    ,type: 'post'
	                    ,data: {notice_id: notice_id, user_id: user_id, enter_dwdm: enter_dwdm}
	                    ,complete: function () {
	                        dialog.close(loadIndex);
	                    }
	                    ,error: function (msg) {
	                        dialog.error(msg);
	                    }
	                    ,success: function () {
	                        dialog.msg('报名成功');
	                        location.reload();
	                    }
	                });
	            })
           	});
        });
    </script>
@endsection