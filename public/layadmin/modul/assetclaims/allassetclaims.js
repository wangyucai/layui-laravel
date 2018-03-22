layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#myassetclaims'
        ,url: '/admin/allassetclaims' //数据接口
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
            // ,{field: 'ly_uid', title: '申领人ID'}
            ,{field: 'real_name', title: '申领人'}
            ,{field: 'zcmc', title: '资产名称'}
            ,{field: 'zcpp', title: '资产品牌'}
            ,{field: 'zcxh', title: '资产型号'}
            ,{field: 'ly_nums', title: '领用数量'}
            ,{field: 'ly_gsml', title: '归属门类'}
            ,{field: 'ly_zcyt', title: '资产用途'}
            ,{field: 'lyrq', title: '领用日期'}
            ,{field: 'if_check', title: '是否审核',width: 100, templet: '#active'}
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

    table.on('tool(myassetclaimtab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象
        if(layEvent == 'check') {
            layer.confirm('是否审核通过？', {
                btn: ['通过', '不通过']
            }, function(index, layero){

                var feedback_msg = '';
                layer.prompt({
                    formType: 2,
                    title: '请输入审核通过的反馈信息',
                    area: ['300px', '120px'] 
                }, function(value, index, elem){
                    data['feedback_msg'] = value; // 反馈信息
                    data['check_result'] = 1;//审核通过
                    his.ajax({
                        url: '/admin/allassetclaim/check'
                        ,type: 'post'
                        ,data: data
                        ,error: function (msg) {
                            dialog.error(msg);
                        }
                        ,success: function () {
                            dialog.msg('信息反馈成功');
                            window.location.reload();
                        }
                    });
                    layer.close(index);
                });
            }, function(index){
                var feedback_msg = '';
                layer.prompt({
                    formType: 2,
                    title: '请输入审核不通过的反馈信息',
                    area: ['300px', '120px'] 
                }, function(value, index, elem){
                    data['feedback_msg'] = value; // 反馈信息
                    data['check_result'] = 2;//审核不通过
                    his.ajax({
                        url: '/admin/allassetclaim/check'
                        ,type: 'post'
                        ,data: data
                        ,error: function (msg) {
                            dialog.error(msg);
                        }
                        ,success: function () {
                            dialog.msg('信息反馈成功');
                            window.location.reload();
                        }
                    });
                    layer.close(index);
                });
            });
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
        table.reload('myassetclaims', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(myassetclaimtab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });
});