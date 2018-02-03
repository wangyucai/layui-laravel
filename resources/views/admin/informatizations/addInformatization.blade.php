@extends("admin.layout.main")

@section("content")
	<form class="layui-form layui-form-pane" style="width: 60%">
	<input type="hidden" name="user_myid" value="{{ $my_id }}">
	<input type="hidden" name="info_mydwdm" value="{{ $my_dwdm }}">
		<div class="layui-form-item">
			<label class="layui-form-label">姓名</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_myname" lay-verify="required" placeholder="姓名" value="{{ $my_name }}">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书编号</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsbh" lay-verify="required" placeholder="证书编号">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">证书名称</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_zsmc" lay-verify="required" placeholder="证书名称">
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">颁证机构</label>
			<div class="layui-input-block">
				<input type="text" class="layui-input" name="info_bzjg" lay-verify="required" placeholder="颁证机构">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-inline">
				<label class="layui-form-label">发证日期</label>
				<div class="layui-input-inline">
					<input type="text" id="info_fzrq" class="layui-input info_fzrq" lay-verify="required|date" name="info_fzrq" placeholder="发证日期">
				</div>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">上传证书</label>
			<div class="layui-input-block">
				<div class="layui-upload">
				    <button type="button" class="layui-btn layui-btn-normal" id="testList">选择单或多文件</button> 
				    <div class="layui-upload-list">
					    <table class="layui-table">
					      	<thead>
						        <tr>
							        <th>文件名</th>
							        <th>大小</th>
							        <th>状态</th>
							        <th>操作</th>
						      	</tr>
						      </thead>
					      	<tbody id="demoList"></tbody>
					    </table>
				  	</div>
				  	<button type="button" class="layui-btn" id="testListAction">开始上传</button>
				</div> 
			</div>
			<div id="upload_img_list"></div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" type="button" lay-submit lay-filter="addinformatization">立即提交</button>
				<button type="reset" class="layui-btn layui-btn-primary">重置</button>
		    </div>
		</div>

	</form>
@endsection

@section("js")
	<script type="text/javascript">
		layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog','his'],function(){
		    var form = layui.form,
		        dialog = layui.dialog,
		        $ = layui.jquery,
		        his = layui.his;
		    // 日期插件
		    layui.use('laydate', function(){
		  		var laydate = layui.laydate;
		  		laydate.render({
				    elem: '#info_fzrq'
				});
			});
			
			
		    form.on("submit(addinformatization)",function(data){
		        var loadIndex = dialog.load('数据提交中，请稍候');
		        his.ajax({
		            url: '/admin/informatization'
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
		                dialog.msg("您的信息化资格证书添加成功！");
		                dialog.closeAll('iframe');
		                parent.location.reload();
		            }
		        });
		        return false;
		    })

		    //Js 数据容量单位转换(kb,mb,gb,tb)
			function bytesToSize(bytes) {
			    if (bytes === 0) return '0 B';
			    var k = 1000, // or 1024
			        sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
			        i = Math.floor(Math.log(bytes) / Math.log(k));
			 
			   return (bytes / Math.pow(k, i)).toPrecision(3) + ' ' + sizes[i];
			}
	 		/*
	      	上传参数设定
	      	*/
			// var upurl = "{:url('/admin/email/upload',['author'=>'Ycl_24'])}";//上传图片地址
			var duotu = true;//是否为多图上传true false
			layui.use('upload', function(){
  				var $ = layui.jquery
  				,upload = layui.upload;
 				var load = '';
 				var indexNum = 0;
			 	 //多文件列表示例
			  	var demoListView = $('#demoList');
			  	uploadListIns = upload.render({
				    elem: '#testList'
				    ,url: '/admin/informatization/upload'
				    ,accept: 'images'  //只允许是图片
				    ,multiple: duotu
				    ,auto: false
				    ,bindAction: '#testListAction'
				    ,size: 512000
				    ,processData: false
				    ,contentType: false
			    	,before: function(obj) {
			    		// console.log(obj);
			      		load =  top.layer.load(2, {content:'正在上传...',shade: [0.001, '#393D49'],success: function(layero){
			                layero.find('.layui-layer-content').css({'padding-left':'40px','width':'100px','padding-top':'5px'});
			                //清空 input file 值，以免删除后出现同名文件不可选  
			                layero.css({'border-radius':'0','background':'white','box-shadow':'1px 1px 37px rgb0.value = "";'});
			                indexNum++;                 
			                tr = '<tr id="upload-'+indexNum+'"><td></td><td></td><td></td><td></td></tr>';   
			        		demoListView.append(tr);        		
			      		}});
			    	}
    				,done: function(res, index, upload){
      					if(res.code == 0){ //上传成功
        					console.log(index);
        					var tr = demoListView.find('tr#upload-'+ indexNum)
        					,tds = tr.children();
					        if(res.data.progress == '100'){
					          	top.layer.close(load);//关闭上传提示窗口
					          	tds.eq(0).html('<span>'+res.data.originalName+'</span>'); 
					          	tds.eq(1).html('<span>'+bytesToSize(res.data.size)+'</span>'); 
					          	tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>'); 
					          	tds.eq(3).html('<a href="'+res.data.tolink+'" target="_blank" class="layui-btn layui-btn-mini layui-btn-normal">查看</a>');
					          	if (duotu == true) {//调用多图上传方法,其中res.imgid为后台返回的一个随机数字
					            	$('#upload_img_list').append('<input type="hidden" name="file_info[]" value="' + res.data.tolink + '" />');     
					          	}else{//调用单图上传方法,其中res.imgid为后台返回的一个随机数字   
					            	$('#upload_img_list').html('<input type="hidden" name="file_info" value="' + res.data.tolink + '" />');         
					          	}
					        }else{
					        	tds.eq(0).html('<span>'+res.data.originalName+'</span>'); 
					          	tds.eq(1).html('<span>'+bytesToSize(res.data.size)+'</span>'); 
					          	tds.eq(2).html('<div class="layui-progress layui-progress-big" lay-showpercent="true"><div class="layui-progress-bar"  lay-percent="'+res.data.progress+'%" style=" width: '+res.data.progress+'%;"><span class="layui-progress-text">'+res.data.progress+'%</span></div></div>');
					          	tds.eq(3).html(''); //清空操作
					        }
        					// return delete this.files[index]; //删除文件队列已经上传成功的文件
        					return;
      					}
      					this.error(index, upload,res.data.info);
    				}
				    ,error: function(index, upload,info){
				      top.layer.close(load);//关闭上传提示窗口
				      var tr = demoListView.find('tr#upload-'+ indexNum)
				      ,tds = tr.children();
				      tds.eq(2).html('<span style="color: #FF5722;">上传失败.'+info+'</span>');
				      tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
				    }
  				}); 
			});

		})
	</script>
@endsection