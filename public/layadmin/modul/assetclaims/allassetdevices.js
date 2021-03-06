layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    var zc_id = $('#zc_id').val();
    table.render({
        elem: '#allassetdevices'
        ,url: '/admin/allassetdevices' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }
        ,where: {
            zc_id: zc_id
        }  
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'sbsf_id', title: '设备ID'}
            ,{field: 'rk_time', title: '归还入库时间'}
            ,{field: 'if_back', title: '是否归还',width: 120, templet: '#active'}
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

    table.on('tool(allassetdevicetab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象
        if (layEvent == 'back_inbound') {
            dialog.confirm('确认归还入库么', function () {
                var loadIndex = dialog.load('归还入库中，请稍候');
                var newStatus = 0;
                his.ajax({
                    url: '/admin/assetdevices/inbound'
                    ,type: 'patch'
                    ,data: {id: data.sbsf_id, zc_id: zc_id ,if_ck: newStatus}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function (msg, data, meta) {
                        dialog.msg('已更改成功');
                        obj.update({
                            if_back_inbound: 1
                        });
                    }
                });
            })
        }
             
    });

    function flushTable (cond, sortObj) {
        var query = {
            where: {
                cond: cond
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
        table.reload('allassetdevices', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(allassetdevicetab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });
});