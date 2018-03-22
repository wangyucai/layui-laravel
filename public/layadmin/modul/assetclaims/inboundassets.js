layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    table.render({
        elem: '#inboundassets'
        ,url: '/admin/inboundassets' //数据接口
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
            ,{field: 'kc_name', title: '经手人'}
            ,{field: 'zcmc', title: '资产名称'}
            ,{field: 'zcpp', title: '资产品牌'}
            ,{field: 'zcxh', title: '资产型号'}
            ,{field: 'kc_rkrq', title: '入库日期'}
            ,{field: 'kc_nums', title: '库存数量'}
            ,{field: 'kc_qryj', title: '取入依据'}
            ,{field: 'if_check', title: '是否审核',width: 100, templet: '#active'}
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

    table.on('tool(inboundassettab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'look') {
            dialog.open('查看设备资产', '/admin/deviceidentity/'+data.id);
        }else if (layEvent == 'edit') {
            dialog.open('编辑入库资产', '/admin/inboundasset/'+data.id+'/edit');
        } else if (layEvent == 'del') {
            dialog.confirm('确认删除该入库资产么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/inboundasset'
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
        }else if(layEvent == 'check') {
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
                        url: '/admin/inboundasset/check'
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
                        url: '/admin/inboundasset/check'
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
        table.reload('inboundassets', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(inboundassettab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });
});