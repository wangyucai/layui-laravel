layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;

    form.on("submit(addwarehouse)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/warehouse'
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
                dialog.msg("仓库创建成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });
        return false;
    })
})