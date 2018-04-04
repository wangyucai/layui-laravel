layui.config({base: '/layadmin/modul/common/'}).use(['table','form','dialog', 'his'], function(){
    var table = layui.table
        ,form = layui.form
        ,dialog = layui.dialog
        ,his = layui.his
        ,$ = layui.$;
    data = new Object();
    data.real_name = $('.real_name').val();
    data.sex = $('.sex').val();
    data.birth = $('.birth').val();
    data.nation = $('.nation').val();
    data.native_place = $('.native_place').val();
    data.native_heath = $('.native_heath').val();
    data.political_outlook = $('.political_outlook').val();
    data.join_party_time = $('.join_party_time').val();
    data.join_work_time = $('.join_work_time').val();
    data.id_number = $('.id_number').val();
    data.join_procuratorate_time = $('.join_procuratorate_time').val();
    data.join_technical_department_time = $('.join_technical_department_time').val();
    data.education = $('.education').val();
    data.academic_degree = $('.academic_degree').val();
    data.major_school = $('.major_school').val();
    data.major_degree_school = $('.major_degree_school').val();
    data.get_education_time = $('.get_education_time').val();
    data.get_academic_degree_time = $('.get_academic_degree_time').val();
    data.procurator = $('.procurator').val();
    data.administrative_duties = $('.administrative_duties').val();
    data.administrative_level = $('.administrative_level').val();
    data.technician_title = $('.technician_title').val();
    data.resume = $('.resume').val();
    // 搜索
    $('#down_resume').click(function () {
        dialog.confirm('确认下载我的简历吗?', function () {
            var loadIndex = dialog.load('下载中，请稍候');
            if(data.word_path){
                window.location.href = ('/'+data.word_path);
                dialog.msg('下载成功');
            }else{
                his.ajax({
                    url: '/admin/completeinfouser/down'
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
    });
});