@extends("admin.layout.main")
@section("css")
<link rel="stylesheet" href="/layadmin/modul/index/index.css" media="all" />
@endsection
@section("content")
    <div class="panel_box row">
        <div class="panel col">
            <a href="javascript:;" data-url="">
                <div class="panel_icon">
                    <i class="layui-icon" data-icon="&#xe63a;">&#xe63a;</i>
                </div>
                <div class="panel_word newMessage">
                    <span>5</span>
                    <cite>未读通知</cite>
                </div>
            </a>
        </div>
        <div class="panel col">
            <a href="javascript:;" data-url="">
                <div class="panel_icon" style="background-color:#FF5722;">
                    <i class="layui-icon" data-icon="&#xe62e;">&#xe62e;</i>
                </div>
                <div class="panel_word newMessage">
                    <span>10</span>
                    <cite>未读邮件</cite>
                </div>
            </a>
        </div>
        <div class="panel col">
            <a href="javascript:;" data-url="">
                <div class="panel_icon" style="background-color:#009688;">
                    <i class="layui-icon" data-icon="&#xe650;">&#xe650;</i>
                </div>
                <div class="panel_word userAll">
                    <span>2</span>
                    <cite>审核消息</cite>
                </div>
            </a>
        </div>
        <div class="panel col">
            <a href="javascript:;" data-url="registeruser">
                <div class="panel_icon" style="background-color:#F7B824;">
                <i class="layui-icon" data-icon="&#xe612;">&#xe612;</i>
                </div>
                <div class="panel_word waitNews">
                    <span>13</span>
                    <cite>注册用户</cite>
                </div>
            </a>
        </div>
        <div class="panel col">
            <a href="javascript:;" data-url="completeinfouser">
                <div class="panel_icon">
                    <i class="layui-icon" data-icon="&#xe613;">&#xe613;</i>
                </div>
                <div class="panel_word newMessage">
                    <span>11</span>
                    <cite>完善信息用户</cite>
                </div>
            </a>
        </div>
        <div class="panel col">
            <a href="javascript:;" data-url="appraiserstatistic">
                <div class="panel_icon">
                    <i class="layui-icon" data-icon="&#xe756;">&#xe756;</i>
                </div>
                <div class="panel_word newMessage">
                    <span>2</span>
                    <cite>鉴定人员列表</cite>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div>
            <blockquote class="layui-elem-quote title">系统基本参数</blockquote>
            <table class="layui-table">
                <tbody>
                <tr>
                    <td>网站域名</td>
                    <td class="host">{{$system_info['url']}}</td>
                </tr>
                <tr>
                    <td>网站ip</td>
                    <td class="ip">{{$system_info['server_ip']}}</td>
                </tr>
                <tr>
                    <td>web环境</td>
                    <td class="server">{{$system_info['server_soft']}}</td>
                </tr>
                <tr>
                    <td>PHP版本</td>
                    <td class="server">{{$system_info['php_version']}}</td>
                </tr>
                <tr>
                    <td>mysql版本</td>
                    <td class="dataBase">{{$system_info['mysql_version']}}</td>
                </tr>
                <tr>
                    <td>redis版本</td>
                    <td class="dataBase">{{$system_info['redis_version']}}</td>
                </tr>
                <tr>
                    <td>最大上传限制</td>
                    <td class="maxUpload">{{$system_info['max_upload_size']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section("js")
<script type="text/javascript" src="/layadmin/modul/index/main.js"></script>
<script type="text/javascript">
    layui.use('jquery', function () {
        var $ = layui.$;

//        $(".panel a").on("click",function(){
//            top.addTab($(this));
//        })
    })


</script>
@endsection
