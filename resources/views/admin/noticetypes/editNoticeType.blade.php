@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $noticetype['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">通知类型代码</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input layui-btn-disabled" name="notice_type_code" lay-verify="required" placeholder="请输入通知类型代码" disabled value="{{ $noticetype['notice_type_code'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">通知类型名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="notice_type_name" lay-verify="required" placeholder="请输入通知类型名称" value="{{ $noticetype['notice_type_name'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editnoticetype">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/noticetypes/editNoticeType.js"></script>
@endsection
