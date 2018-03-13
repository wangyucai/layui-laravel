layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#registerusers'
        ,url: '/admin/registerusers' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,where: {
                company_id: 0, 
            }
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'username', title: '用户名'}
            ,{field: 'tel', title: '手机号码'}
            ,{field: 'tel_hm', title: '电话号码'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
            ,{field: 'register_if_check', title: '是否审核', width: 80, templet: '#active'}
            ,{title: '操作', width: 260, toolbar: '#op'}
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

    table.on('tool(registerusertab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'active') {
            dialog.confirm('确认进行此操作', function () {
                var loadIndex = dialog.load('修改中，请稍候');
                var newStatus = (data.register_if_check == 1) ? 0 : 1;
                console.log(data);
                his.ajax({
                    url: '/admin/registeruser'
                    ,type: 'patch'
                    ,data: {id: data.id, register_if_check: newStatus}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function (msg, data, meta) {
                        dialog.msg('已更改成功');
                        obj.update({
                            register_if_check: newStatus
                        });
                    }
                });
            });
        }else if (layEvent == 'detail') {
            dialog.open('查看注册用户', '/admin/registeruser/'+data.id+'/detail');

        } else if (layEvent == 'edit') {
            dialog.open('编辑注册用户', '/admin/registeruser/'+data.id+'/edit');

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
                cond: cond,user_type: 1
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
        table.reload('registerusers', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(registerusertab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });

});