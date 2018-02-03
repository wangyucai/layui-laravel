@extends("admin.layout.main")

@section("content")
	{{-- <blockquote class="layui-elem-quote news_search">
	
	</blockquote> --}}
	<table id="mytrains" lay-filter="mytraintab">
		<input type="hidden" name="my_id" value="{{ $my_id }}" id="my_id">
	</table>
	
@endsection
	
@section("js")
	<script type="text/html" id="active">
		@{{# if(d.if_expire == 1){ }}
		<input type="checkbox" checked="" name="open" lay-skin="switch" lay-filter="switchTest" lay-text="是|否" disabled>
		@{{#  } else { }}
		<input type="checkbox" name="close" lay-skin="switch" lay-text="是|否" disabled>
		@{{# } }}
	</script>
	<script type="text/javascript" src="/layadmin/modul/trains/mytrains.js"></script>
@endsection
