<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Amaze UI Examples</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="/Public/All/login/css/amazeui.min.css">
    <link rel="stylesheet" href="/Public/All/login/css/app.css">
    <link rel="stylesheet" href="/Public/All/login/css/login.css">
</head>
<body>
<div class="am-g myapp-login">
    <div class="myapp-login-logo-block">
        <div class="myapp-login-logo">
            <i class="am-icon-jsfiddle"></i>
        </div>
        <div class="myapp-login-logo-text">
            <div class="myapp-login-logo-text">
                Js<span>Fiddle</span>
                <div class="info">Find the best places in town.</div>
            </div>
        </div>

        <div class="login-font">
            <span class="login_button_choose" style="color: red;"> 登录</span> or <span class="sign_button_choose"> 注册</span>
        </div>
        <div class="am-u-sm-10 login-am-center login_choose">
            <form method="post" action="#" class="am-form">
                <fieldset>
                    <div class="am-form-group">
                        <input type="email" class="user_name input_text_color" placeholder="请输入账号名">
                    </div>

                    <div class="am-form-group">
                        <input type="password" class="user_password input_text_color" placeholder="请输入密码">
                    </div>
                    <p><button type="submit" class="am-btn am-btn-default login_confirm">登录</button></p>
                </fieldset>
            </form>
        </div>

        <div class="am-u-sm-10 login-am-center sign_choose" style="display: none; float:initial">
            <form method="post" action="#" class="am-form">
                <fieldset>
                    <div class="am-form-group">
                        <input type="email" class="user_name input_text_color" placeholder="请注册新账户名">
                    </div>

                    <div class="am-form-group">
                        <input type="password" class="user_password input_text_color" placeholder="请输入密码">
                    </div>

                    <div class="am-form-group">
                        <input type="email" class="user_nickname input_text_color" placeholder="请输入昵称">
                    </div>
                    <p><button type="submit" class="am-btn am-btn-default sign_confirm">注册</button></p>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<!--[if (gte IE 9)|!(IE)]><!-->
<script src="/Public/All/login/js/jquery.min.js"></script>
<!--<![endif]-->
<!--[if lte IE 8 ]>
<script src="/Public/All/login/js/1.11.3/jquery.min.js"></script>
<!--<script src="/Public/All/login/js/modernizr.js"></script>-->
<!--<script src="assets/js/amazeui.ie8polyfill.min.js"></script>-->
<!--<![endif]&ndash;&gt;-->
<!--<script src="/Public/All/login/js/amazeui.min.js"></script>-->
<!--<script src="/Public/All/login/js/app.js"></script>-->
<script src="/Public/All/login/js/login.js"></script>
</body>
</html>