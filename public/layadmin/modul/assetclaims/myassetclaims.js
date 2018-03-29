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
        elem: '#myassetclaims'
        ,url: '/admin/myassetclaims' //数据接口
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
            ,{field: 'zcbh', title: '资产编号'}
            ,{field: 'zcmc', title: '资产名称'}
            ,{field: 'zcpp', title: '资产品牌'}
            ,{field: 'zcxh', title: '资产型号'}
            ,{field: 'ly_nums', title: '领用数量'}
            ,{field: 'ly_gsml', title: '归属门类'}
            ,{field: 'ly_zcyt', title: '资产用途'}
            ,{field: 'created_at', title: '领用日期'}
            ,{field: 'if_check', title: '是否审核',width: 100, templet: '#active'}
            ,{title: '操作', width: 260, toolbar: '#op'}
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

    table.on('tool(myassetclaimtab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象
        if (layEvent == 'look') {
            dialog.open('查看我申领的设备资产', '/admin/myassetdevice/'+data.id);
        }else if (layEvent == 'down') {
            dialog.confirm('确认下载资产申领表吗?', function () {
                var loadIndex = dialog.load('下载中，请稍候');
                if(data.word_path){
                    window.location.href = ('/'+data.word_path);
                    dialog.msg('下载成功');
                }else{
                    his.ajax({
                        url: '/admin/myassetclaims/download'
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

    function flushTable (lqrq_start, lqrq_end, zcbh, zcmc, sortObj) {
        var query = {
            where: {
                lqrq_start: lqrq_start,
                lqrq_end: lqrq_end,
                zcbh: zcbh,
                zcmc: zcmc,      
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
        table.reload('myassetclaims', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var lqrq_start = $('#lqrq_start').val();
        var lqrq_end = $('#lqrq_end').val();
        var zcbh = $('#zcbh').val();
        var zcmc = $('#zcmc').val();
        flushTable(lqrq_start, lqrq_end, zcbh, zcmc);
    });

    // 排序
    table.on('sort(myassetclaimtab)', function (obj) {
        var lqrq_start = $('#lqrq_start').val();
        var lqrq_end = $('#lqrq_end').val();
        var zcbh = $('#zcbh').val();
        var zcmc = $('#zcmc').val();
        flushTable(lqrq_start, lqrq_end, zcbh, zcmc, obj);
    });
});