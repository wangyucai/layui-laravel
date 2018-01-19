layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog','his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;
    // 日期插件
    layui.use('laydate', function(){
  		var laydate = layui.laydate;
  		laydate.render({
		    elem: '#jdry_fzrq'
		});
        laydate.render({
            elem: '#jdry_yxrq'
        });
	});
	// 我的证书上传
	layui.use('upload', function(){
		 var upload = layui.upload;
		 var uploadInst = upload.render({
		  elem: '#jdry_zspath'
		  ,type : 'images'
		  ,exts: 'jpg|png|gif' //设置一些后缀
		  ,url: '/admin/completeidentifyinfo/upload'
		  ,done: function(res){
			  if(res.status == 1){
                $('#upload_zs').append('<input type="hidden" name="jdry_zspath" value="' + res.jdry_zspath + '" />');
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
	
    form.on("submit(completeidentifyinfo)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/completeidentifyinfo'
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
                dialog.msg("您的鉴定信息添加成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });

        return false;

    })

})