@extends("admin.layout.main")
@section('css')
	<link rel="stylesheet" href="/layadmin/extra/zTree/css/zTreeStyle.css" media="all" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop
@section("content")
	<form class="layui-form layui-form-pane" >
		<div class="layui-form-item">
			<label class="layui-form-label">通知标题</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="title" lay-verify="required" placeholder="请输入通知标题">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">通知类别</label>
			<div class="layui-input-block">
				<select name="type" lay-filter="type" lay-verify="required">
			        <option value="">请选择通知类别</option>
			        @foreach($noticetypes as $noticetype)
			       	<option value="{{ $noticetype['notice_type_code'] }}">{{ $noticetype['notice_type_name']  }}</option>
			       	@endforeach
		      	</select>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">通知单位</label>
			<div class="layui-input-block">
				<div class="zTreeDemoBackground left">
					<ul id="treeDemo" class="ztree"></ul>
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">通知内容</label>
			<div class="layui-input-block">
				<textarea name="content" placeholder="请输入通知内容" class="layui-textarea" lay-verify="required"></textarea>
			</div>
		</div>
		@if($px_info)
		<div class="layui-form-item">
			<label class="layui-form-label">培训内容</label>
			<div class="layui-input-block">
				<textarea name="content2" id="editor" placeholder="请输入培训内容" class="layui-textarea" lay-verify="required"></textarea>
			</div>
		</div>
		@endif
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">通知有效期</label>
				<div class="layui-input-block">
					<input type="text" id="notice_yxq" class="layui-input" lay-verify="required|date" name="notice_yxq" placeholder="请选择通知有效期至">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">通知发布人</label>
				<div class="layui-input-inline">
					<input type="text" class="layui-input" name="from_dw" lay-verify="required" placeholder="请输入通知发布人">
				</div>
			</div>
		</div>	
		<div class="layui-form-item">
			<label class="layui-form-label">上传附件</label>
			<div class="layui-input-block">
				<button type="button" class="layui-btn" id="attachment">
				  	<i class="layui-icon">&#xe67c;</i>上传附件
				</button>
			</div>
		</div>
		<div id="upload_attachment"></div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addnotice">立即提交</button>
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
        var zNodes = {!!$companies!!};
        function setCheck() {
            var zTree = $.fn.zTree.getZTreeObj("treeDemo");
            zTree.setting.check.chkboxType = { "Y":"s", "N":"s"};
        }
        $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        setCheck();
        layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'], function () {
            var form = layui.form, dialog = layui.dialog, his = layui.his;
            // 日期插件
		    layui.use('laydate', function(){
		  		var laydate = layui.laydate;
		  		laydate.render({
				    elem: '#notice_yxq'
				});
			});	
     		// 通知附件上传
			layui.use('upload', function(){
				 var upload = layui.upload;
				 var uploadInst = upload.render({
				  elem: '#attachment'
				  ,type : 'file'
				  ,exts: 'jpg|png|docx|doc|pdf|xlsx|xls' //设置一些后缀
				  ,url: '/admin/notice/upload'
				  ,done: function(res){
					  if(res.status == 1){
		                $('#upload_attachment').append('<input type="hidden" name="attachment" value="' + res.attachment + '" />');
					   	return layer.msg('上传成功');
					  }else{
					   	layer.msg(res.message);
					  }
				  }
				  ,error: function(){
					  return layer.msg('上传失败,请重新上传');
				  }
				 });
		 	});
            form.on('submit(addnotice)', function (data) {
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
                post.notice_dwdm = v.join(','); 	//数组转字符串
                his.ajax({
                    url: '/admin/notice'
                    ,type: 'post'
                    ,data: data.field
                    ,contentType: 'form'
                    ,complete: function(){
		                dialog.close(loadIndex);
		            }
		            ,error: function (msg) {
		                dialog.error(msg);
		            }
		            ,success: function (msg, data, meta) {
		                dialog.msg("通知添加成功！");
		                dialog.closeAll('iframe');
		                parent.location.reload();
		            }
                });
                return false;
            })
        });    
    </script>
    <script>
    // $(document).ready(function(){
    //     var editor = new Simditor({
    //         textarea: $('#editor'),
    //         upload: {
    {{-- //             url: '{{ route('topics.upload_image') }}', --}}
    {{-- //             params: { _token: '{{ csrf_token() }}' }, --}}
    //             fileKey: 'upload_file',
    //             connectionCount: 3,
    //             leaveConfirm: '文件上传中，关闭此页面将取消上传。'
    //         },
    //         pasteImage: true,
    //     });
    // });
    </script>

@endsection