layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#completeinfousers'
        ,url: '/admin/completeinfousers' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,where: {
                company_id: 0 
            }
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'real_name', title: '姓名'}
            ,{field: 'tel', title: '手机号码'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
            ,{field: 'perinfor_if_check', title: '是否审核', width: 80, templet: '#completeinfouseractive'}
            ,{title: '操作', width: 160, toolbar: '#op'}
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

    table.on('tool(completeinfousertab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'completeinfouseractive') {
            dialog.confirm('确认进行此操作', function () {
                var loadIndex = dialog.load('审核中，请稍候');
                var newStatus = (data.perinfor_if_check == 1) ? 0 : 1;
                his.ajax({
                    url: '/admin/completeinfouser'
                    ,type: 'patch'
                    ,data: {id: data.id, perinfor_if_check: newStatus}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function (msg, data, meta) {
                        dialog.msg('已更改成功');
                        obj.update({
                            perinfor_if_check: newStatus
                        });
                    }
                });
            });
        } else if (layEvent == 'edit') {
            dialog.open('编辑管理员', '/admin/completeinfouser/'+data.id+'/edit');

        } else if (layEvent == 'del') {
            dialog.confirm('确认删除改用户么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/user'
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

    function flushTable (cond, sortObj) {
        var query = {
            where: {
                cond: cond,user_type: 2
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
        table.reload('completeinfousers', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(completeinfousertab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });

});