layui.config({base: '/layadmin/modul/common/'}).use(['form', 'dialog', 'his'],function(){
    var form = layui.form,
        $ = layui.jquery,
        dialog = layui.dialog,
        his = layui.his;
    // 自定义验证规则 
    form.verify({
        password : function(value, item){
            if(value.length < 6){
                return "密码长度不能小于6位";
            }
        },
        confirmPwd : function(value, item){
            if(!new RegExp($("#pwd").val()).test(value)){
                return "两次输入密码不一致，请重新输入！";
            }
        }
        ,tel: [/^1[3|4|5|7|8]\d{9}$/, '手机必须11位，只能是数字！']  
        ,tel_hm: [/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/, '固定电话有误，请重填'] 
          
    })

    function flushForm () {
        $('[name="password"]').val('');
    }
    // 选择框监听事件---选择单位下的部门
    form.on('select(danwei)', function(data){
      var company_dwdm = data.value; //得到被选中的值 
      $.ajax({
            type: 'POST',
            url: '/admin/register',
            data: {company_dwdm: company_dwdm,act:'danwei'},
            dataType:  'json',
            success: function(data){
                if(data.error==0){
                    $("#bumen").html("");
                    var str =  "<select id='bumen' name='mechanism_id' lay-filter='bumen' lay-verify='required|bumen'><option>请选择部门</option>"
                    $.each(data.bumen,function (key, val) {
                        var option1 = $("<option value="+val.id+">").val(val.id).text(val.nsjgmc);
                        var option2 = "<input value="+val.mechanism_code+" type='hidden' name='mechanism_code'>";
                        $("#bumen").append(option1);
                        $("#bumen").append(option2);
                        form.render('select');
                    });
                    $("#bumen").get(0).selectedIndex=0;
                }else{
                    dialog.error(data.msg);                
                }       
            }
         });
    });     
    //注册按钮事件
    form.on("submit(register)",function(data){
        console.log(data.field);
        var loadIndex = dialog.load('数据提交中，请稍候');
        his.ajax({
            url: '/admin/register'
            ,type: 'post'
            ,data: data.field
            ,contentType: 'form'
            ,complete: function(){
                dialog.close(loadIndex);
            }
            ,error: function (msg) {
                dialog.error(msg);
            }
            ,success: function (msg, data, meta) {
                layer.msg('注册成功,您的消息已提交给本级管理员审核，请审核通过后登陆!',{btn:['确定'],yes:function(index,layero){
                       top.location.href='/admin/login'; 
                }});
                // dialog.msg('注册成功,正在为您跳转');
                // top.location.href='/admin/login';          
            }
        });
        return false;
    })
})