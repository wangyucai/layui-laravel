layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    var my_id = $('#my_id').val();
    table.render({
        elem: '#mytrains'
        ,url: '/admin/mytrainmodules' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }
        ,where: {
            my_id: my_id,
        }    
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'px_title', title: '培训标题'}
            ,{field: 'px_place', title: '培训地点'}
            ,{field: 'px_start_time', title: '培训开始时间'}
            ,{field: 'px_end_time', title: '培训结束时间'}
            ,{field: 'if_expire', title: '是否过期',width: 80, templet: '#active'}
            ,{field: 'name', title: '主办单位'}
            ,{field: 'px_fx', title: '培训方向'}
            ,{field: 'px_renshu', title: '培训人数'}
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

    function flushTable (sortObj) {
        var query = {
            where: {
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
        table.reload('mytrains', query);
    }

    // 排序
    table.on('sort(mytraintab)', function (obj) {
        flushTable(obj);
    });
});