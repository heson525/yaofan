<?php
/*
 * 后台公共头部文件
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

include '../Core/Common.php';
//验证登录
admin_login();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?> - <?php echo config('sitename'); ?></title>
    <link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//cdn.bootcss.com/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/2.1.3/jquery.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/Static/js/layer.js"></script>
    <style>
        .panel {
            box-shadow: 1px 1px 5px 5px rgba(169, 169, 169, 0.35);
            -moz-box-shadow: 1px 1px 5px 5px rgba(169, 169, 169, 0.35);
        }
    </style>
</head>
<body background="/Static/img/bg.jpg">
<nav class="navbar navbar-fixed-top navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar"
                    aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">导航按钮</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/Admin"><?php echo config('sitename'); ?></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/Admin"><span class="icon-home"></span> 后台首页</a></li>
                <li><a href="/Admin/order.php"><span class="icon-calculator"></span> 订单列表</a></li>
                <li><a href="/Admin/pay_config.php"><span class="icon-credit-card"></span> 支付配置</a>
                <li><a href="/Admin/config.php"><span class="icon-settings"></span> 网站配置</a></li>
                <li><a href="/Admin/pass.php"><span class="icon-compass"></span> 修改密码</a></li>
                <li><a onclick="check()"><span class="icon-direction"></span> 检测更新</a></li>
                <li><a onclick="logout()"><span class="icon-logout"></span> 退出登录</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container" style="padding-top:90px;">
