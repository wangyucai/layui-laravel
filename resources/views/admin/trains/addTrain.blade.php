@extends("admin.layout.main")
@section("css")
	<link rel="stylesheet" href="/layadmin/extra/zTree/css/zTreeStyle.css" media="all" />
@endsection
@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
		<div class="layui-form-item">
			<label class="layui-form-label">培训项目名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="px_title" lay-verify="required" placeholder="请输入培训项目名称">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">培训地点</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="px_place" lay-verify="required" placeholder="请输入培训地点">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">培训开始日期</label>
				<div class="layui-input-inline">
					<input type="text" id="px_start_time" class="layui-input px_start_time" lay-verify="required|date" name="px_start_time" placeholder="请选择培训开始日期">
				</div>
			</div>
			<div class="layui-inline">
				<label class="layui-form-label">培训结束日期</label>
				<div class="layui-input-inline">
					<input type="text" id="px_end_time" class="layui-input px_end_time" lay-verify="required|date" name="px_end_time" placeholder="请选择培训结束日期">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
		<label class="layui-form-label">培训方向</label>
		    <div class="layui-input-block">
		        <select name="px_fx" lay-filter="px_fx" lay-verify="required">
		          	<option value="">请选择培训方向</option>
		           	@foreach($fx_data as $fx)
	                @if($fx->pxfx_code == '03')
	                <optgroup  label="{{ $fx->pxfx_name }}">
	                @foreach($jd_data as $val)
                    	<option value="{{$fx->pxfx_code.','.$val->jdywfw_code}}">{{$val->jdywfw_name}}</option>
                    @endforeach
               		</optgroup>
	                @elseif($fx->pxfx_code == '04')
	                <optgroup  label="{{ $fx->pxfx_name }}">
	                @foreach($xinxi_data as $val2)
                        <option value="{{$fx->pxfx_code.','.$val2->xxhjs_code}}">{{$val2->xxhjs_name}}</option>
                    @endforeach
                    </optgroup>
	                @else
	                <option value="{{$fx->pxfx_code}}" >{{$fx->pxfx_name}}</option>
	                @endif
	                @endforeach
		        </select>
		    </div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">培训人数</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="px_renshu" lay-verify="required" placeholder="请输入培训人数">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">主办单位</label>
			<div class="layui-input-block">
				<input id="zbdw_id" type="text" class="layui-input" name="zbdw_id" lay-verify="required" placeholder="请输入主办单位">
				<select lay-filter="lishi">		        
					<option value="">主办单位历史记录</option>
			        @foreach($zhuban as $v)
                    <option value="{{ $v->name }}" >{{ $v->name }}</option>
                    @endforeach
		       </select>
			</div>
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
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addtrain">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>

	</form>
@endsection

@section("js")
	<script type="text/javascript" src="/layadmin/modul/common/jquery.min.js"></script>
	<script type="text/javascript" src="/layadmin/extra/zTree/js/jquery.ztree.core.min.js"></script>
	<script type="text/javascript" src="/layadmin/extra/zTree/js/jquery.ztree.excheck.min.js"></script>
	<script type="text/javascript">
		// function change(obj) {
	 //     	var value = $(obj).val();
	 //     	$('#zbdw_id').val(value);
	 //     	$(obj).val('');
	 //    }

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
                    elem: '#px_start_time'
                });
                laydate.render({
                    elem: '#px_end_time'
                });
            });
            // 选择框监听事件---选择主办单位时
		    form.on('select(lishi)', function(data){
		    	console.log(data.value);
		    	$('#zbdw_id').val(data.value);
		    }); 
            form.on('submit(addtrain)', function (data) {
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
                post.px_notice_dw = v.join(','); 	//数组转字符串
                his.ajax({
                    url: '/admin/trainmodule'
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
		                dialog.msg("培训信息添加成功！");
		                dialog.closeAll('iframe');
		                parent.location.reload();
		            }
                });
                return false;
            })
        });    
    </script>
@endsection