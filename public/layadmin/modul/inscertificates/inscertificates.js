layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#inscertificates'
        ,url: '/admin/inscertificates' //数据接口
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
            ,{field: 'jdjg_name', title: '司法鉴定机构全称'}
            ,{field: 'zsbh', title: '证书编号'}
            ,{field: 'ssdwqc', title: '所属单位全称'}
            ,{field: 'jdjg_fzr', title: '机构负责人'}
            ,{field: 'fzdw', title: '发证单位'}
            ,{field: 'fzrq', title: '发证日期'}
            ,{field: 'zgsh_yxqz', title: '资格审核有效期至'}
            ,{field: 'if_jh', title: '是否激活',width: 80, templet: '#active'}
            ,{title: '操作', width: 160, toolbar: '#op'}
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

    table.on('tool(inscertificatetab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑管理员', '/admin/inscertificate/'+data.id+'/edit');
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该鉴定机构证书么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/inscertificate'
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
        table.reload('inscertificates', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(inscertificatetab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });

    // 添加司法鉴定机构代码
    $('.add_btn').click(function () {
        dialog.open('添加司法鉴定机构代码', '/admin/inscertificate/create');
    });
});