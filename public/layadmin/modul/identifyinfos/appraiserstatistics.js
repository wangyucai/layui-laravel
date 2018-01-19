layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    // 日期插件
    // layui.use('laydate', function(){
    //     var laydate = layui.laydate;
    //     laydate.render({
    //         elem: '#fzrq_start'
    //     });
    //     laydate.render({
    //         elem: '#fzrq_end'
    //     });
    // });

    table.render({
        elem: '#appraiserstatistics'
        ,url: '/admin/appraiserstatistics' //数据接口
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
            ,{field: 'username', title: '用户名'}
            ,{field: 'real_name', title: '姓名'}
            ,{field: 'sex', title: '性别'}
            ,{field: 'tel', title: '手机号'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
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

    table.on('tool(appraiserstatistictab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'detail') {
            dialog.open('查看该证书', '/admin/appraiserstatistics/look/'+data.id);
        }
    });

    function flushTable (jdjg_dwdm, jdywfw_code, my_dwjb, province_level, sortObj) {
        var query = {
            where: {
                jdjg_dwdm: jdjg_dwdm,
                jdywfw_code: jdywfw_code,
                my_dwjb: my_dwjb,
                province_level: province_level,
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
        table.reload('appraiserstatistics', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var jdjg_dwdm = $('#jdjg_dwdm').val();
        var jdywfw_code = $('#jdywfw_code').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        var province_level = $('#province_level').val();
        my_dwjb = my_dwjb ? my_dwjb : 0;
        if(my_dwjb==2 && !jdjg_dwdm){
            layer.msg('请选择司法鉴定机构后,再选择包含子机构查询！！');
            return false;
        } 
        if(my_dwjb==4 && !jdjg_dwdm){
            layer.msg('县级没有子机构,请勿勾选！！');
            return false;
        }     
        flushTable(jdjg_dwdm,jdywfw_code,my_dwjb,province_level);
    });

    // 排序
    table.on('sort(appraiserstatistictab)', function (obj) {
        var jdjg_dwdm = $('#jdjg_dwdm').val();
        var jdywfw_code = $('#jdywfw_code').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        var province_level = $('#province_level').val();
        flushTable(jdjg_dwdm, jdywfw_code, my_dwjb, province_level, obj);
    });
});