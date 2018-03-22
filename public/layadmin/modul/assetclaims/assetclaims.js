layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#assetclaims'
        ,url: '/admin/assetclaims' //数据接口
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
            ,{field: 'zcmc', title: '资产名称'}
            ,{field: 'zcpp', title: '资产品牌'}
            ,{field: 'zcxh', title: '资产型号'}
            ,{field: 'kc_nums', title: '库存数量'}
            ,{title: '操作', width: 320, toolbar: '#op'}
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

    table.on('tool(assetclaimtab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'look') {
            dialog.open('查看设备资产', '/admin/deviceidentity/'+data.id);
        }else if (layEvent == 'detail') {
            dialog.open('申领资产', '/admin/assetclaim/'+data.kc_zcid+'/create');
        }else if (layEvent == 'down') {
            dialog.confirm('确认下载资产入库表吗?', function () {
                var loadIndex = dialog.load('下载中，请稍候');
                if(data.kc_word_path){
                    window.location.href = ('/'+data.kc_word_path);
                    dialog.msg('下载成功');
                }else{
                    his.ajax({
                        url: '/admin/equipmentasset/download'
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
        table.reload('assetclaims', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(assetclaimtab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });
});