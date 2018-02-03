@extends("admin.layout.main")
@section('css')
	<link rel="stylesheet" href="/layadmin/extra/zTree/css/zTreeStyle.css" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop
@section("content")
	<form class="layui-form layui-form-pane" >
		<input type="hidden" name="id" value="{{ $email['id'] }}">
		<div class="layui-form-item">
			<label class="layui-form-label">邮件主题</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="email_theme" lay-verify="required" placeholder="请输入邮件主题" value="{{ $email['email_theme'] }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">接收用户</label>
			<div class="layui-input-block">
				<div class="zTreeDemoBackground left">
					<ul id="treeDemo" class="ztree"></ul>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">邮件内容</label>
			<div class="layui-input-block">
				<textarea name="email_content" id="editor" placeholder="请输入邮件内容" class="layui-textarea" lay-verify="required">{{ $email['email_content'] }}</textarea>
			</div>
		</div>	
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="editemail">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>
	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/common/jquery.min.js"></script>
	<script type="text/javascript" src="/layadmin/extra/zTree/js/jquery.ztree.core.min.js"></script>
	<script type="text/javascript" src="/layadmin/extra/zTree/js/jquery.ztree.excheck.min.js"></script>
	<script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>
    <script type="text/javascript">
    	// 编辑器
	    $(document).ready(function(){
	        var editor = new Simditor({
	            textarea: $('#editor'),
	        });
	    });

		function showIconForTree(treeId, treeNode) {
			return treeNode.level != 2;
		};
        var setting = {
            check:{enable: true},
            view: {showLine: true, dblClickExpand: true,showIcon: showIconForTree},
            data: {
                simpleData: {enable: true, pIdKey:'sjdm', idKey:'dwdm'},
                key:{name:'dwqc'}
            }
        };
        var zNodes ={!! $companies !!};

        function setCheck() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.setting.check.chkboxType = { "Y":"s", "N":"s"};
        }
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        setCheck();
        layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'], function () {
            var form = layui.form, dialog = layui.dialog, his = layui.his;
    
            form.on('submit(editemail)', function (data) {
                var loadIndex = dialog.load('数据提交中，请稍候');
                // 提交到方法 默认为本身
                var treeObj = $.fn.zTree.getZTreeObj("treeDemo"),
                        nodes=treeObj.getCheckedNodes(true),
                        v=[];
                for(var i=0;i<nodes.length;i++){
                    v[i]=nodes[i].dwdm;
                }
                var post = data.field;
                post.token = '{{ csrf_token() }}';
                post.email_receivers = v.join(','); 	//数组转字符串
                his.ajax({
                    url: '/admin/email'
                    ,type: 'put'
                    ,data: data.field
                    ,contentType: 'form'
                    ,complete: function(){
		                dialog.close(loadIndex);
		            }
		            ,error: function (msg) {
		                dialog.error(msg);
		            }
		            ,success: function (msg, data, meta) {
		                dialog.msg("邮件更新成功！");
		                dialog.closeAll('iframe');
		                parent.location.reload();
		            }
                });
                return false;
            })
        });    
    </script>
@endsection