layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    var notice_id = $('#notice_id').val();
    table.render({
        elem: '#noticeusers'
        ,url: '/admin/notice/users' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,where: {notice_id:notice_id}
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'username', title: '用户名'}
            ,{field: 'real_name', title: '姓名'}
            ,{field: 'tel', title: '手机号码'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
            ,{field: 'if_read', title: '是否已读', templet: '#readactive'}
            ,{field: 'if_down', title: '是否下载', templet: '#downactive'}
        ]]
        ,response: {
            statusName: 'code'
            ,statusCode: 0
            ,msgName: 'msg'
            ,countName: 'meta'
            ,dataName: 'data'
        }
//				,skin: 'row' // 'line', 'row', 'nob'
        ,even: false //开启隔行背景
//                ,size: 'lg' // 'sm', 'lg'

    });

    function flushTable (if_read,if_down, sortObj) {
        var query = {
            where: {
                if_read: if_read,
                if_down: if_down
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
        table.reload('noticeusers', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var if_read = $('#if_read').val();
        var if_down = $('#if_down').val();
        flushTable(if_read,if_down);
    });

    // 排序
    table.on('sort(noticeusertab)', function (obj) {
        var if_read = $('#if_read').val();
        var if_down = $('#if_down').val();
        flushTable(if_read,if_down, obj);
    });

});