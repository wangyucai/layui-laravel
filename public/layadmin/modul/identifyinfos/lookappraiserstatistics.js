layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    var jdry_id = $('#jdry_id').val();
    table.render({
        elem: '#lookappraiserstatistics'
        ,url: '/admin/appraiserstatistics/looks' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,where: {jdry_id:jdry_id}
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }   
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'jdry_name', title: '姓名'}
            ,{field: 'jdry_zsbh', title: '证书编号'}
            ,{field: 'jdywfw_code', title: '鉴定业务范围'}
            ,{field: 'jdry_fzdw', title: '发证单位'}
            ,{field: 'jdry_fzrq', title: '发证日期'}
            ,{field: 'jdry_yxrq', title: '资格审核有效期至'}
            ,{field: 'jdry_zspath', title: '我的证书', width: 150,style:'height:100%;max-width:100%;',templet:'<div><img  src="{{ d.jdry_zspath}}"></div>'}
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

    table.on('tool(lookappraiserstatistictab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'detail') {
            dialog.open('查看该证书', '/admin/lookmyidentifyinfo/'+data.id);
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
        table.reload('lookappraiserstatistics', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(lookappraiserstatistictab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });

});