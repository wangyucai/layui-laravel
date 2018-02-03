layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#mymessages'
        ,url: '/admin/mymessages' //数据接口
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
            ,{field: 'msg_content', title: '提示内容'}
            ,{field: 'msg_type', title: '消息类型'}
            ,{field: 'created_at', title: '提示时间'}
            ,{field: 'if_read', title: '标记已读', toolbar: '#active'}
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

    table.on('tool(mymessagetab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'biaoji') {
            dialog.confirm('确认标记为已读么', function () {
                var loadIndex = dialog.load('标记已读中，请稍候');
                his.ajax({
                    url: '/admin/readmymessage'
                    ,type: 'post'
                    ,data: {id: data.id}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('标记成功');
                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                    }
                });
            })
        }
    });
    function flushTable (notice_status,sortObj) {
        var query = {
            where: {
                notice_status: notice_status
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
        table.reload('mymessages', query);
    }
    // 全部通知
    $('.notice_all').click(function () {
        var notice_status = 2;
        flushTable(notice_status);
    });
    // 已读通知
    $('.notice_yes').click(function () {
        var notice_status = 1;
        flushTable(notice_status);
    });
    // 未读通知
    $('.notice_no').click(function () {
        var notice_status = 0;
        flushTable(notice_status);
    });
    // 排序
    table.on('sort(mymessagetab)', function (obj) {
        var notice_status = 2;
        flushTable(notice_status, obj);
    });    
});