layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#pxrq_start'
        });
        laydate.render({
            elem: '#pxrq_end'
        });
    });

    table.render({
        elem: '#trains'
        ,url: '/admin/trainmodules' //数据接口
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
            ,{field: 'px_title', title: '培训标题'}
            ,{field: 'px_place', title: '培训地点'}
            ,{field: 'px_start_time', title: '培训开始时间'}
            ,{field: 'px_end_time', title: '培训结束时间'}
            ,{field: 'if_expire', title: '是否过期',width: 80, templet: '#active'}
            ,{field: 'name', title: '主办单位'}
            ,{field: 'px_fx', title: '培训方向'}
            ,{field: 'px_renshu', title: '培训人数'}
            ,{title: '操作', width: 160, toolbar: '#op'}
            ,{field: 'px_dwdm',title: '操作2', width: 180, toolbar: '#op1'}
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

    table.on('tool(traintab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑培训信息', '/admin/trainmodule/'+data.id+'/edit');
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该培训信息么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/trainmodule'
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
                        // parent.location.reload();
                    }
                });
            })
        } else if (layEvent == 'sednotice') {
            if(data.if_notice==1){
                dialog.msg('该培训已发送过通知，请勿重复操作！');
                return;
            }
            dialog.open('发送培训通知', '/admin/notice/create?px_id='+data.id);
        } else if (layEvent == 'bmuser') {
            dialog.open('查看报名用户信息', '/admin/trainmodule/'+data.id+'/bmuser');
        }
    });

    function flushTable (pxrq_start, pxrq_end, pxfx, pxbt, pxdd, zbdw, sortObj) {
        var query = {
            where: {
                pxrq_start: pxrq_start,
                pxrq_end: pxrq_end,
                pxfx: pxfx,
                pxbt: pxbt,
                pxdd: pxdd,
                zbdw: zbdw,
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
        table.reload('trains', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var pxrq_start = $('#pxrq_start').val();
        var pxrq_end = $('#pxrq_end').val();
        var pxfx = $('#pxfx').val();
        var pxbt = $('#pxbt').val();
        var pxdd = $('#pxdd').val();
        var zbdw = $('#zbdw').val();
        flushTable(pxrq_start, pxrq_end, pxfx, pxbt, pxdd, zbdw);
    });

    // 排序
    table.on('sort(traintab)', function (obj) {
        var pxrq_start = $('#pxrq_start').val();
        var pxrq_end = $('#pxrq_end').val();
        var pxfx = $('#pxfx').val();
        var pxbt = $('#pxbt').val();
        var pxdd = $('#pxdd').val();
        var zbdw = $('#zbdw').val();
        flushTable(pxrq_start, pxrq_end, pxfx, pxbt, pxdd, zbdw, obj);
    });

    // 添加司法鉴定机构代码
    $('.add_btn').click(function () {
        dialog.open('添加培训信息', '/admin/trainmodule/create');
    });
});