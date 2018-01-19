layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#fzrq_start'
        });
        laydate.render({
            elem: '#fzrq_end'
        });
    });

    table.render({
        elem: '#myidentifyinfos'
        ,url: '/admin/myinscertificates' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }    
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'jdry_name', title: '姓名'}
            ,{field: 'jdry_zsbh', title: '证书编号'}
            ,{field: 'jdywfw_code', title: '鉴定业务范围'}
            ,{field: 'jdry_fzdw', title: '发证单位'}
            ,{field: 'jdry_fzrq', title: '发证日期'}
            ,{field: 'jdry_yxrq', title: '资格审核有效期至'}
            ,{field: 'jdry_zspath', title: '我的证书', width: 150,style:'height:100%;max-width:100%;',templet:'#zspath'}
            ,{title: '操作', width: 220, toolbar: '#op'}
        ]]
        ,response: {
            statusName: 'code'
            ,statusCode: 0
            ,msgName: 'msg'
            ,countName: 'meta' //数据总数的字段名称，默认：count
            ,dataName: 'data'  //数据列表的字段名称，默认：data
        }
        ,even: false //开启隔行背景
    });

    table.on('tool(myidentifyinfotab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑本鉴定机构证书', '/admin/completeidentifyinfo/'+data.id+'/edit');
        }else if (layEvent == 'detail') {
            dialog.open('查看该证书', '/admin/lookmyidentifyinfo/'+data.id);
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该鉴定机构证书', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/completeidentifyinfo'
                    ,type: 'delete'
                    ,data: {id: data.id}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('删除成功');
                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                    }
                });
            })
        }
    });

    function flushTable (fzrq_start, fzrq_end, jdjg_dwdm, jdywfw_code, my_dwjb, sortObj) {
        var query = {
            where: {
                fzrq_start: fzrq_start,
                fzrq_end: fzrq_end,
                jdjg_dwdm: jdjg_dwdm,
                jdywfw_code: jdywfw_code,
                my_dwjb: my_dwjb,
            }
            ,page: {
                curr: 1
            }
        };
        if (sortObj != null) {
            query.initSort = sortObj;
            query.where.sortField = sortObj.field;   // 排序字段
            query.where.order = sortObj.type;        //排序方式
        }
        table.reload('myidentifyinfos', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var fzrq_start = $('#fzrq_start').val();
        var fzrq_end = $('#fzrq_end').val();
        var jdjg_dwdm = $('#jdjg_dwdm').val();
        var jdywfw_code = $('#jdywfw_code').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        my_dwjb = my_dwjb ? my_dwjb : 0;
        if(my_dwjb==2 && !jdjg_dwdm){
            layer.msg('请选择司法鉴定机构后,再选择包含子机构查询！！');
            return false;
        } 
        if(my_dwjb==4 && !jdjg_dwdm){
            layer.msg('县级没有子机构,请勿勾选！！');
            return false;
        }     
        flushTable(fzrq_start,fzrq_end,jdjg_dwdm,jdywfw_code,my_dwjb);
    });

    // 排序
    table.on('sort(myidentifyinfotab)', function (obj) {
        var fzrq_start = $('#fzrq_start').val();
        var fzrq_end = $('#fzrq_end').val();
        var jdjg_dwdm = $('#jdjg_dwdm').val();
        var jdywfw_code = $('#jdywfw_code').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        flushTable(fzrq_start, fzrq_end, jdjg_dwdm, jdywfw_code, my_dwjb, obj);
    });
});