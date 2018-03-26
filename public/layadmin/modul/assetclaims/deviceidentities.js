layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;
    // 库存ID
    var kc_id = $('#kc_id').val();
    table.render({
        elem: '#deviceidentities'
        ,url: '/admin/deviceidentities' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }
        ,where: {
            kc_id: kc_id,
        }      
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'sbsf_xh', title: '设备ID'}
            ,{field: 'sbsf_zcbh', title: '资产编号'}
            ,{field: 'sbsf_bz', title: '备注'}
            ,{field: 'bf', title: '报废时间'}
            ,{field: 'if_ck', title: '是否在库',width: 120, templet: '#active'}
            ,{field: 'if_bf', title: '是否报废', width: 120, templet: '#active1'}
            ,{title: '操作', width: 240, toolbar: '#op'}
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
    table.on('tool(deviceidentitytab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑设备身份', '/admin/deviceidentity/'+data.id+'/edit');
        }else if (layEvent == 'bf') {
            dialog.confirm('确认报废么', function () {
                var loadIndex = dialog.load('报废中，请稍候');
                var newStatus = (data.if_bf == 1) ? 0 : 1;
                his.ajax({
                    url: '/admin/deviceidentity/bf'
                    ,type: 'post'
                    ,data: {sbsf_id: data.id, if_bf: newStatus}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function (msg, data, meta) {
                        dialog.msg('报废成功');
                        obj.update({
                            if_bf: newStatus
                        });
                    }
                });
            })
        }else if (layEvent == 'down') {
            dialog.confirm('确认下载资产报废表吗?', function () {
                var loadIndex = dialog.load('下载中，请稍候');
                if(data.sbsf_word_path){
                    window.location.href = ('/'+data.sbsf_word_path);
                    dialog.msg('下载成功');
                }else{
                    his.ajax({
                        url: '/admin/deviceidentity/down'
                        ,type: 'post'
                        ,data: data
                        ,complete: function () {
                            dialog.close(loadIndex);
                        }
                        ,error: function (msg) {
                            dialog.error(msg);
                        }
                        ,success: function (data) {
                            window.location.href = (data);
                            dialog.msg('下载成功');
                        }
                    }); 
                }
                
            })
        }
    });

    function flushTable (if_ck, sortObj) {
        var query = {
            where: {
                if_ck: if_ck
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
        table.reload('deviceidentities', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var if_ck = $('#if_ck').val();
        flushTable(if_ck);
    });

    // 排序
    table.on('sort(deviceidentitytab)', function (obj) {
        var if_ck = $('#if_ck').val();
        flushTable(if_ck, obj);
    });
});