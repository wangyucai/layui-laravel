layui.config({base: '/layadmin/modul/common/'}).use(['form','dialog','his'],function(){
    var form = layui.form,
        dialog = layui.dialog,
        his = layui.his,
        $ = layui.$;

    // 自定义验证规则 
    form.verify({
        jdjg_dwdm : function(value, item){
            if(value.length != 6){
                return "司法鉴定机构所属单位代码必须为6位";
            }
        },       
    })
    form.on("submit(editinstitutioncode)",function(data){
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/institutioncode'
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

})