layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;

    var zc_id = $('#zc_id').val();
    table.render({
        elem: '#myassetdevices'
        ,url: '/admin/myassetdevices' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,request: {
             pageName: 'page' //页码的参数名称，默认：page
            ,limitName: 'limit' //每页数据量的参数名，默认：limit
        }
        ,where: {
            zc_id: zc_id
        }  
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'sbsf_id', title: '设备ID'}
            ,{field: 'back_time', title: '归还时间'}
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

    table.on('tool(myassetdevicetab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象
        if (layEvent == 'back') {         
            var newStatus = (data.if_back == 1) ? 0 : 1;
            layer.confirm('是否归还？', {
                btn: ['归还', '不归还']
            }, function(index, layero){
                var feedback_msg = '';
                layer.prompt({
                    formType: 2,
                    title: '请输入归还原因',
                    area: ['300px', '120px'] 
                }, function(value, index, elem){
                    data['feedback_msg'] = value; // 反馈信息
                    data['if_back'] = newStatus;//归还
                    his.ajax({
                        url: '/admin/myassetdevice/back'
                        ,type: 'post'
                        ,data: data
                        ,error: function (msg) {
                            dialog.error(msg);
                        }
                        ,success: function () {
                            dialog.msg('归还成功');
                            obj.update({
                                if_back: 1
                            });
                        }
                    });
                    layer.close(index);
                }); 
            });     
        }else if (layEvent == 'down') {
            dialog.confirm('确认下载资产归还表吗?', function () {
                var loadIndex = dialog.load('下载中，请稍候');
                if(data.word_path){
                    window.location.href = ('/'+data.word_path);
                    dialog.msg('下载成功');
                }else{
                    his.ajax({
                        url: '/admin/myassetdevice/download'
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
        table.reload('myassetdevices', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var cond = $('.search_input').val();
        flushTable(cond);
    });

    // 排序
    table.on('sort(myassetdevicetab)', function (obj) {
        var cond = $('.search_input').val();
        flushTable(cond, obj);
    });
});