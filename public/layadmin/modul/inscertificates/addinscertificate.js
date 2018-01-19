layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;
    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#fzrq'
        });
        laydate.render({
            elem: '#zgsh_yxqz'
        });
    })

    form.on("submit(addinscertificate)",function(data){
        if ($('.ywfw_group:checked').length == 0) dialog.msg('请选择司法鉴定业务范围');
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/inscertificate'
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
                dialog.msg("司法鉴定机构证书添加成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });
        return false;
    })
})