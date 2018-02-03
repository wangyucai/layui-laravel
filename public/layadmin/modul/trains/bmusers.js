layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his', 'form'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$
        ,form = layui.form;

    var notice_id = $('#notice_id').val();
    var my_dwdm = $('#my_dwdm').val();
    var my_dwjb = $('#my_dwjb').val();
    table.render({
        elem: '#bmusers'
        ,url: '/admin/trainmodule/bmusers' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,where: {notice_id:notice_id, my_dwdm:my_dwdm, my_dwjb:my_dwjb}
        ,cols: [[ //表头
            {type:'checkbox', fixed: 'left'}         
            ,{field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'real_name', title: '姓名'}
            ,{field: 'sex', title: '性别'}
            ,{field: 'nation', title: '民族'}
            ,{field: 'tel', title: '手机号码'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
        ]]
        ,response: {
            statusName: 'code'
            ,statusCode: 0
            ,msgName: 'msg'
            ,countName: 'meta'
            ,dataName: 'data'
        }
//				,skin: 'row' // 'line', 'row', 'nob'
        ,even: false //开启隔行背景
//                ,size: 'lg' // 'sm', 'lg'

    });

    function flushTable (if_read,if_down, sortObj) {
        var query = {
            where: {
                if_read: if_read,
                if_down: if_down
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
        table.reload('bmusers', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        var if_read = $('#if_read').val();
        var if_down = $('#if_down').val();
        flushTable(if_read,if_down);
    });

    // 排序
    table.on('sort(bmusertab)', function (obj) {
        var if_read = $('#if_read').val();
        var if_down = $('#if_down').val();
        flushTable(if_read,if_down, obj);
    });
    //监听表格复选框选择
    table.on('checkbox(bmusertab)', function(obj){
        // console.log(obj)
    });
    var active = {
        getCheckData: function(){ //获取选中数据
            var checkStatus = table.checkStatus('bmusers')
            ,data = checkStatus.data;
            for(var i in data){
                data[i].my_dwjb = my_dwjb;
                data[i].my_dwdm = my_dwdm;
                data[i].notice_id = notice_id;
            }
            if(JSON.stringify(data)=='[]'){
                layer.msg('请选择上报用户');return;
            }
            dialog.confirm('确认上报已选用户么', function () {
                var loadIndex = dialog.load('上报中，请稍候');
                his.ajax({
                    url: '/admin/trainmodule/bmusers'
                    ,type: 'post'
                    ,data: data
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('上报成功');
                        obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                    }
                });
            })
        }
        ,getFeedbackData: function(){ //获取选中信息反馈人员
            var checkStatus = table.checkStatus('bmusers')
            ,data = checkStatus.data;
            if(JSON.stringify(data)=='[]'){
                layer.msg('请选择信息反馈的用户');return;
            }
            var feedback_msg = '';
            layer.prompt({
                formType: 2,
                title: '请输入反馈信息',
                area: ['300px', '120px'] 
            }, function(value, index, elem){
                for(var i in data){
                    data[i].feedback_msg = value; // 反馈信息
                    data[i].notice_id = notice_id;
                }
                his.ajax({
                    url: '/admin/trainmodule/feedback'
                    ,type: 'post'
                    ,data: data
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('信息反馈成功');
                    }
                });
                layer.close(index);
            });
      
        }
    }; 
    // 上报选中用户
    $('.click-btn').on('click', function(){
        var type = $(this).data('type');
        active[type] ? active[type].call(this) : '';
    });

});