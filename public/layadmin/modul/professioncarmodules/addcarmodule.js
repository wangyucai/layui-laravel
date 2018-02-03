layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;

    form.on("submit(addcarmodule)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/professioncarmodule'
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
                dialog.msg("职业资格证书模板添加成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });
        return false;
    })
})