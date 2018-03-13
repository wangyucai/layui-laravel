<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <title>注册--后台</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/layadmin/layui/css/layui.css" media="all" />
    <link rel="stylesheet" href="/layadmin/modul/register/register.css" media="all" />
</head>
<body>
<img class="bgpic" src="/layadmin/modul/register/bg.jpg">
<div class="register">
    <h1>检察院技术管理系统</h1>
    <form class="layui-form">
        <div class="layui-form-item">
            <input class="layui-input" name="username" placeholder="用户名" lay-verify="required" type="text" autocomplete="off">
        </div>
        <div class="layui-form-item">
            <input class="layui-input" name="password" placeholder="密码" lay-verify="required|password" type="password" autocomplete="off" id="pwd">
        </div>
        <div class="layui-form-item">
            <input class="layui-input"  name="confirmPwd" placeholder="确认密码" lay-verify="required|confirmPwd" type="password" autocomplete="off" >
        </div>

        <div class="layui-form-item">
            <input class="layui-input" name="tel" placeholder="手机全号" lay-verify="required|tel" type="text" autocomplete="off">
        </div>
        
        <div class="layui-form-item">
            <input class="layui-input" name="tel_hm" placeholder="集团短号或座机号" lay-verify="required|tel_hm" type="text" autocomplete="off">
        </div>
        <div class="layui-form-item">
	      	<select name="company_dwdm" lay-filter="danwei" lay-verify="required|danwei">
		        <option value="">请选择单位</option>
		       	@foreach($danwei as $v)
		       		<option value="{{ $v->dwdm }}">{{ $v->html }}{{ $v->dwqc }}</option>
		       	@endforeach
	      	</select>
		 </div>
		 <div class="layui-form-item" id="bm">
	      	<select id="bumen" name="mechanism_id" lay-filter="bumen" lay-verify="required|bumen">
	      	</select>
		 </div>
        {{ csrf_field() }}
        <button type="button" class="layui-btn register_btn" lay-submit lay-filter="register">注册</button>
    </form>
</div>
<script type="text/javascript" src="/layadmin/layui/layui.js"></script>
<script type="text/javascript" src="/layadmin/modul/register/register.js"></script>
</body>
</html>