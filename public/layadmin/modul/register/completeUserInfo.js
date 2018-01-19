layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog','his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;
    // 自定义验证规则 
    form.verify({
    	id_number: [/(^\d{15}$)|(^\d{17}(\d|X)$)/, '请输入正确的身份证号码']         
    })
    // 日期插件
    layui.use('laydate', function(){
  		var laydate = layui.laydate;
  		laydate.render({
		    elem: '#birth'
		});
        laydate.render({
            elem: '#join_party_time'
        });
        laydate.render({
            elem: '#join_work_time'
        });
        laydate.render({
            elem: '#join_procuratorate_time'
        });
        laydate.render({
            elem: '#join_technical_department_time'
        });
        laydate.render({
            elem: '#get_education_time'
        });
        laydate.render({
            elem: '#get_academic_degree_time'
        });
	});
	// 头像上传
	layui.use('upload', function(){
		 var upload = layui.upload;
		 var tag_token = $(".tag_token").val();
		 var uploadInst = upload.render({
		  elem: '#face'
		  ,type : 'images'
		  ,exts: 'jpg|png|gif' //设置一些后缀，用于演示前端验证和后端的验证
		  //,auto:false //选择图片后是否直接上传
		  //,accept:'images' //上传文件类型
		  ,url: '/admin/completeuserinfo/upload'
		  ,data:{'_token':tag_token}
		 //  ,before: function(obj){
			// //预读本地文件示例，不支持ie8
			// obj.preview(function(index, file, result){
			//    	$('.img-upload-view').attr('src', result); //图片链接（base64）
			// });
		 //  }
		  ,done: function(res){
			  //如果上传失败
			  if(res.status == 1){
			   	return layer.msg('上传成功');
			  }else{//上传成功
			   	layer.msg(res.message);
			  }
		  }
		  ,error: function(){
			  //演示失败状态，并实现重传
			  return layer.msg('上传失败,请重新上传');
		  }
		 });
 	});
	// 选择框监听事件---选择政治面貌时
    form.on('select(zzmm)', function(data){
    	if(data.value==12 || data.value==13){// 得到被选中的值
    		$(".join_party_time").addClass('layui-btn-disabled');
    		$(".join_party_time").attr('disabled','disabled');
    	}else{
    		$(".join_party_time").removeClass('layui-btn-disabled');
    		$(".join_party_time").removeAttr('disabled');
    	}
      
    });     
    form.on("submit(completeuser)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/completeuserinfo'
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
                dialog.msg("您的人事信息已完善！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });

        return false;

    })

})