layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;
    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#lqrq_start'
        });
        laydate.render({
            elem: '#lqrq_end'
        });
    });
    table.render({
        elem: '#fixedassets'
        ,url: '/admin/fixedassets' //数据接口
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
            ,{field: 'real_name', title: '持有者'}
            ,{field: 'gdzc_bh', title: '资产编号'}
            ,{field: 'gdzc_mc', title: '资产名称'}
            ,{field: 'gdzc_pp', title: '资产品牌'}
            ,{field: 'gdzc_xh', title: '资产型号'}
            ,{field: 'gdzc_ffbm', title: '发放部门'}
            ,{field: 'price', title: '单价'}
            ,{field: 'gdzc_nums', title: '持有的数量'}
            ,{field: 'gdzc_lqrq', title: '领取日期'}
            ,{field: 'if_back', title: '是否归还',width: 120, templet: '#active'}
            ,{title: '操作', width: 210, toolbar: '#op'}
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

    table.on('tool(fixedassettab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑我的固定资产', '/admin/myfixedasset/'+data.id+'/edit');
        } else if (layEvent == 'detail') {
            dialog.open('查看我的固定资产', '/admin/myfixedasset/'+data.id+'/look');
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该固定资产么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/fixedasset'
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

    function flushTable (real_name,gdzc_bmdm,cond,lqrq_start, lqrq_end, sortObj) {
        var query = {
            where: {
                real_name: real_name,
                gdzc_bmdm: gdzc_bmdm,
                cond: cond,
                lqrq_start: lqrq_start,
                lqrq_end: lqrq_end,
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
        table.reload('fixedassets', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        var lqrq_start = $('#lqrq_start').val();
        var lqrq_end = $('#lqrq_end').val();
        var real_name = $('#real_name').val();
        var gdzc_bmdm = $('#gdzc_bmdm').val();
        flushTable(real_name,gdzc_bmdm,cond,lqrq_start, lqrq_end);
    });

    // 排序
    table.on('sort(myfixedassettab)', function (obj) {
        var cond = $('.search_input').val();
        var lqrq_start = $('#lqrq_start').val();
        var lqrq_end = $('#lqrq_end').val();
        var real_name = $('#real_name').val();
        var gdzc_bmdm = $('#gdzc_bmdm').val();
        flushTable(real_name,gdzc_bmdm,cond,lqrq_start, lqrq_end, obj);
    });
});