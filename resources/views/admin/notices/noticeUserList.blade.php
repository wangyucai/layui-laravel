@extends("admin.layout.main")

@section("content")
	<blockquote class="layui-elem-quote news_search">
		<form class="layui-form layui-form-pane">
		<div class="layui-inline">
		    <div class="layui-inline">
			<label>是否阅读：</label>
				<div class="layui-input-inline">
					<select name="if_read" lay-filter="if_read" id="if_read">
				        <option value="">请选择是否阅读</option>
				        <option value="1">已读用户</option>
				        <option value="0">未读用户</option>
		      		</select>
				</div>
			</div> 
			<div class="layui-inline">
			<label>是否下载：</label>
				<div class="layui-input-inline">
					<select name="if_down" lay-filter="if_down" id="if_down">
				        <option value="">请选择是否下载</option>
				        <option value="1">已下载用户</option>
				        <option value="0">未下载用户</option>
		      		</select>
				</div>
			</div> 
			<a class="layui-btn search_btn">查询</a>
		</div>
		</form>
	</blockquote>
	<table id="noticeusers" lay-filter="noticeusertab">
		<input type="hidden" name="notice_id" value="{{ $id  }}" id="notice_id">
	</table>

@endsection

@section("js")
	<script type="text/html" id="readactive">
		@{{# if(d.if_read == 1){ }}
		<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="readactive">已读</a>
		@{{#  } else { }}
		<a class="layui-btn layui-btn-warm layui-btn-danger layui-btn-xs" lay-event="readactive">未读</a>
		@{{# } }}
	</script>
	<script type="text/html" id="downactive">
		@{{# if(d.if_down == 1){ }}
		<a class="layui-btn layui-btn-warm layui-btn-xs" lay-event="downactive">已下载</a>
		@{{#  } else { }}
		<a class="layui-btn layui-btn-warm layui-btn-danger layui-btn-xs" lay-event="downactive">未下载</a>
		@{{# } }}
	</script>
	<script type="text/javascript" src="/layadmin/modul/notices/noticeusers.js"></script>
@endsection
