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
            elem: '#start'
        });
        laydate.render({
            elem: '#end'
        });
    });
    table.render({
        elem: '#certificates'
        ,url: '/admin/managecertificates' //数据接口
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
            ,{field: 'my_name', title: '姓名'}
            ,{field: 'my_sfzh', title: '身份证号'}
            ,{field: 'zsmc', title: '证书名称'}
            ,{field: 'zsbh', title: '证书编号'}
            ,{field: 'fzjg', title: '发证机构'}
            ,{field: 'zsyxq', title: '证书年限'}
            ,{field: 'bz', title: '是否有效',width: 100, templet: '#active'}
            ,{field: 'if_check', title: '是否审核',width: 100, templet: '#active1'}
            ,{title: '操作', width: 180, toolbar: '#op'}
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

    table.on('tool(certificatetab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'edit') {
            dialog.open('编辑申办的职业资格证书', '/admin/managecertificate/'+data.id+'/edit');
        } else if (layEvent == 'reporting') {
            dialog.confirm('确认上报么', function () {
                var loadIndex = dialog.load('上报中，请稍候');
                his.ajax({
                    url: '/admin/managecertificate/reporting'
                    ,type: 'post'
                    ,data: {id: data.id}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('上报成功');
                        window.location.reload();
                        // obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                    }
                });
            })
        } else if (layEvent == 'check') {
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
                    data['check_result'] = 2;//审核通过
                    his.ajax({
                        url: '/admin/managecertificate/check'
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
                    data['check_result'] = 3;//审核不通过
                    his.ajax({
                        url: '/admin/managecertificate/check'
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

    function flushTable (start, end, zsmc,county_if_check,city_if_check,check_status, sortObj) {
        var query = {
            where: {
                start: start,
                end: end,
                zsmc: zsmc,
                county_if_check: county_if_check,
                city_if_check: city_if_check,
                check_status: check_status
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
        table.reload('certificates', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var start = $('#start').val();
        var end = $('#end').val();
        var zsmc = $('#zsmc').val();
        var county_if_check = $('#county_if_check').val();
        var city_if_check = $('#city_if_check').val();
        var check_status = $('#check_status').val();
        flushTable(start, end, zsmc,county_if_check,city_if_check,check_status);
    });

    // 排序
    table.on('sort(certificatetab)', function (obj) {
        var start = $('#start').val();
        var end = $('#end').val();
        var zsmc = $('#zsmc').val();
        var county_if_check = $('#county_if_check').val();
        var city_if_check = $('#city_if_check').val();
        var check_status = $('#check_status').val();
        flushTable(start, end, zsmc,county_if_check,city_if_check,check_status, obj);
    });
});