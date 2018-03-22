layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        $ = layui.jquery,
        his = layui.his;
    

    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#lyrq'
        });
    });
    form.on("submit(addassetclaim)",function(data){
        // 总库存量
        // 领用的数量
        var ly_nums = data.field.ly_nums;
        var kc_nums = data.field.kc_nums;
        var sy_nums = kc_nums-ly_nums;
        if(kc_nums<=0){
            layer.msg('库存量不足，无法申领');return;
        }
        if(sy_nums<0){
            layer.msg('您申领的数量已超过库存量:'+kc_nums+',请减少数量！'+ly_nums);return;
        }
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/assetclaim'
            ,type: 'post'
            ,data: data.field
            ,contentType: 'form'
            ,beforeSend: function(){
            }
            ,complete: function(){
                dialog.close(loadIndex);
            }
            ,error: function (msg) {
                dialog.error(msg);
            }
            ,success: function (msg, data, meta) {
                dialog.msg("装备资产申领成功！");
                dialog.closeAll('iframe');
                parent.location.reload();
            }
        });
        return false;
    })
})