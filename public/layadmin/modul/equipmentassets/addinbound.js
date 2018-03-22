layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;
    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#kc_rkrq'
        });
        laydate.render({
            elem: '#kc_ckrq'
        });
    });
    form.on("submit(addinbound)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/equipmentasset/inbound'
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
                dialog.msg("装备资产入库成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });
        return false;
    })
})