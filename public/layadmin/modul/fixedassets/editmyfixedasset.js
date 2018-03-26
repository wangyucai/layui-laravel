layui.config({base: '/layadmin/modul/common/'}).use(['form','dialog','his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        his = layui.his,
        $ = layui.$;
    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#gdzc_lqrq'
        });
    });
    form.on("submit(editmyfixedasset)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/myfixedasset'
            ,type: 'put'
            ,data: data.field
            ,contentType: 'form'
            ,complete: function () {
                dialog.close(loadIndex);
            }
            ,error: function (msg) {
                dialog.error(msg);
            }
            ,success: function (msg, data, meta) {
                dialog.msg('更新成功！');
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });

    })

    // 上传扫描件
    layui.use('upload', function(){
         var upload = layui.upload;
         var uploadInst = upload.render({
          elem: '#gdzc_pic'
          ,type : 'images'
          ,exts: 'jpg|png|gif' //设置一些后缀
          ,url: '/admin/myfixedasset/upload'
          ,done: function(res){
              if(res.status == 1){
                $('#upload_zs').append('<input type="hidden" name="gdzc_pic" value="' + res.gdzc_pic + '" />');
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

})