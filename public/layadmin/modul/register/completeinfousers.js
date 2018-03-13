layui.config({base: '/layadmin/modul/common/'}).use(['table', 'dialog', 'his'], function(){
    var table = layui.table
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;
    // 日期插件
    // 日期插件
    layui.use('laydate', function(){
        var laydate = layui.laydate;
        laydate.render({
            elem: '#join_work_time'
        });
        laydate.render({
            elem: '#join_procuratorate_time'
        });
        laydate.render({
            elem: '#start_time'
        });
        laydate.render({
            elem: '#end_time'
        });
    });
    table.render({
        elem: '#completeinfousers'
        ,url: '/admin/completeinfousers' //数据接口
        ,method: 'get'
        ,page: true //开启分页
        ,limit: 10
        ,limits: [10, 20]
        ,cols: [[ //表头
            {field: 'id', title: 'ID', width:80, sort: true, fixed: 'left', align: 'left'}
            ,{field: 'real_name', title: '姓名'}
            ,{field: 'tel', title: '手机号码'}
            ,{field: 'dwqc', title: '单位'}
            ,{field: 'nsjgmc', title: '部门'}
            ,{field: 'perinfor_if_check', title: '是否审核', width: 80, templet: '#completeinfouseractive'}
            ,{title: '操作', width: 220, toolbar: '#op'}
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

    table.on('tool(completeinfousertab)', function(obj){
        var data = obj.data;      //获得当前行数据
        var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
        var tr = obj.tr;          //获得当前行 tr 的DOM对象

        if (layEvent == 'completeinfouseractive') {
            dialog.confirm('确认进行此操作', function () {
                var loadIndex = dialog.load('审核中，请稍候');
                var newStatus = (data.perinfor_if_check == 1) ? 0 : 1;
                his.ajax({
                    url: '/admin/completeinfouser'
                    ,type: 'patch'
                    ,data: {id: data.id, perinfor_if_check: newStatus}
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function (msg, data, meta) {
                        dialog.msg('已更改成功');
                        obj.update({
                            perinfor_if_check: newStatus
                        });
                    }
                });
            });
        } else if (layEvent == 'detail') {
            dialog.open('查看完善人事信息用户', '/admin/completeinfouser/'+data.id+'/detail');

        }else if (layEvent == 'edit') {
            dialog.open('编辑完善人事信息用户', '/admin/completeinfouser/'+data.id+'/edit');

        } else if (layEvent == 'del') {
            dialog.confirm('确认删除改用户么', function () {
                var loadIndex = dialog.load('删除中，请稍候');
                his.ajax({
                    url: '/admin/user'
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

    function flushTable (sex,nation,political_outlook,join_work_time,join_procuratorate_time,
                   if_work,education,academic_degree,procurator,administrative_level,
                   technician_title,start_time,end_time,danwei,my_dwjb,like_search, sortObj) {
        var query = {
            where: {
                sex: sex
                ,nation: nation
                ,political_outlook: political_outlook
                ,join_work_time: join_work_time
                ,join_procuratorate_time: join_procuratorate_time
                ,if_work: if_work
                ,education: education
                ,academic_degree: academic_degree
                ,procurator: procurator
                ,administrative_level: administrative_level
                ,technician_title: technician_title
                ,start_time: start_time
                ,end_time: end_time
                ,danwei: danwei
                ,my_dwjb: my_dwjb
                ,like_search: like_search
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
        table.reload('completeinfousers', query);
    }

    // 搜索
    $('.search_btn').click(function () {
        // var real_name                    = $('#real_name').val();
        var sex                          = $('#sex').val();
        var nation                       = $('#nation').val();
        var political_outlook            = $('#political_outlook').val();
        var join_work_time               = $('#join_work_time').val();
        var join_procuratorate_time      = $('#join_procuratorate_time').val();
        var if_work                      = $('#if_work').val();
        var education                    = $('#education').val();
        var academic_degree              = $('#academic_degree').val();
        var procurator                   = $('#procurator').val();
        var administrative_level         = $('#administrative_level').val();
        var technician_title             = $('#technician_title').val();
        var start_time                   = $('#start_time').val();
        var end_time                     = $('#end_time').val();
        var danwei                       = $('#danwei').val();
        var like_search                  = $('#like_search').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        my_dwjb = my_dwjb ? my_dwjb : 0;
        if(my_dwjb==2 && !danwei){
            layer.msg('请选择单位后,再选择包含下辖单位查询！！');
            return false;
        } 
        if(my_dwjb==4 && !danwei){
            layer.msg('县级没有下辖单位,请勿勾选！！');
            return false;
        }  
        flushTable(sex,nation,political_outlook,join_work_time,join_procuratorate_time,
                   if_work,education,academic_degree,procurator,administrative_level,
                   technician_title,start_time,end_time,danwei,my_dwjb,like_search);
    });

    // 排序
    table.on('sort(completeinfousertab)', function (obj) {
        // var real_name                    = $('#real_name').val();
        var sex                          = $('#sex').val();
        var nation                       = $('#nation').val();
        var political_outlook            = $('#political_outlook').val();
        var join_work_time               = $('#join_work_time').val();
        var join_procuratorate_time      = $('#join_procuratorate_time').val();
        var if_work                      = $('#if_work').val();
        var education                    = $('#education').val();
        var academic_degree              = $('#academic_degree').val();
        var procurator                   = $('#procurator').val();
        var administrative_level         = $('#administrative_level').val();
        var technician_title             = $('#technician_title').val();
        var start_time                   = $('#start_time').val();
        var end_time                     = $('#end_time').val();
        var danwei                       = $('#danwei').val();
        var like_search                  = $('#like_search').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        my_dwjb = my_dwjb ? my_dwjb : 0;
        flushTable(sex,nation,political_outlook,join_work_time,join_procuratorate_time,
                   if_work,education,academic_degree,procurator,administrative_level,
                   technician_title,start_time,end_time,danwei,my_dwjb,like_search, obj);
    });
    // 导出数据
    $('.export_btn').click(function () {
        var sex                          = $('#sex').val();
        var nation                       = $('#nation').val();
        var political_outlook            = $('#political_outlook').val();
        var join_work_time               = $('#join_work_time').val();
        var join_procuratorate_time      = $('#join_procuratorate_time').val();
        var if_work                      = $('#if_work').val();
        var education                    = $('#education').val();
        var academic_degree              = $('#academic_degree').val();
        var procurator                   = $('#procurator').val();
        var administrative_level         = $('#administrative_level').val();
        var technician_title             = $('#technician_title').val();
        var start_time                   = $('#start_time').val();
        var end_time                     = $('#end_time').val();
        var danwei                       = $('#danwei').val();
        var like_search                  = $('#like_search').val();
        var my_dwjb = $('#my_dwjb:checked').val();
        my_dwjb = my_dwjb ? my_dwjb : 0;
        if(my_dwjb==2 && !danwei){
            layer.msg('请选择单位后,再选择包含下辖单位查询！！');
            return false;
        } 
        if(my_dwjb==4 && !danwei){
            layer.msg('县级没有下辖单位,请勿勾选！！');
            return false;
        } 
        var jsonstr = {
                 sex: sex
                ,nation: nation
                ,political_outlook: political_outlook
                ,join_work_time: join_work_time
                ,join_procuratorate_time: join_procuratorate_time
                ,if_work: if_work
                ,education: education
                ,academic_degree: academic_degree
                ,procurator: procurator
                ,administrative_level: administrative_level
                ,technician_title: technician_title
                ,start_time: start_time
                ,end_time: end_time
                ,danwei: danwei
                ,my_dwjb: my_dwjb
                ,like_search: like_search};
        dialog.confirm('确认导出用户么', function () {
                var loadIndex = dialog.load('导出中，请稍候');
                his.ajax({
                    url: '/admin/exportuser'
                    ,type: 'post'
                    ,data: jsonstr
                    ,complete: function () {
                        dialog.close(loadIndex);
                    }
                    ,error: function (msg) {
                        dialog.error(msg);
                    }
                    ,success: function () {
                        dialog.msg('导出成功');
                    }
                });
            }) 

    });

});