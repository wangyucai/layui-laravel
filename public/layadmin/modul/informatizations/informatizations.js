layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#informatizations'
        ,url: '/admin/informatizations' //数据接口
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
            ,{field: 'info_myname', title: '姓名'}
            ,{field: 'info_zsbh', title: '证书编号'}
            ,{field: 'info_zsmc', title: '证书名称'}
            ,{field: 'info_bzjg', title: '颁证机构'}
            ,{field: 'info_fzrq', title: '发证日期'}
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

    table.on('tool(informatizationtab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑我的信息化资格证书', '/admin/informatization/'+data.id+'/edit');
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该信息化资格证书吗', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/informatization'
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
        }else if (layEvent == 'detail') {
            dialog.open('查看我的信息化证书详情', '/admin/lookmyinformatization/'+data.id);
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
        table.reload('informatizations', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(informatizationtab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });

    // 添加我的信息化资格(每个人最多添加五张证书)
    $('.add_btn').click(function () {
        his.ajax({
            url: '/admin/myinforcarnum'
            ,type: 'post'
            ,error: function (msg) {
                if(msg==1){
                    dialog.open('添加我的信息化资格证书', '/admin/informatization/create');    
                }else{
                    dialog.error(msg);
                }              
            }
        });  
    });
});