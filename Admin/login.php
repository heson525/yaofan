<?php
/*
 * 后台登录
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

include '../Core/Common.php';
?>
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>后台登录 - <?php echo config('sitename'); ?></title>
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
                <li><a href="/Admin/login.php"><span class="icon-user"></span> 后台登录</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container" style="padding-top:90px;">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block" style="float: none;">
        <div class="panel panel-info">
            <div class="panel-heading">后台登录</div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">用户名</label>
                        <input type="text" class="form-control" name="username" placeholder="请输入用户名">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密码</label>
                        <input type="password" class="form-control" name="password" placeholder="请输入密码">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox"> 记住密码？
                        </label>
                    </div>
                    <button type="button" id="login" class="btn btn-success">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).keyup(function (event) {
        if (event.keyCode == 13) {
            $("#login").click();
        }
    });

    $("#login").click(function () {
        layer.open({
            type: 2,
            content: '登录中...',
            time: false
        });
        var cont = $("input").serialize();
        $.ajax({
            url: "ajax.php?act=login",
            data: cont,
            type: 'post',
            dataType: 'json',
            success: function (data) {
                layer.closeAll();
                if (data.code == 1) {
                    layer.open({
                        content: data.msg,
                        skin: 'msg',
                        time: 2,
                        end: function () {
                            window.location.href="/Admin";
                        }
                    });
                } else {
                    layer.open({
                        content: data.msg,
                        skin: 'msg',
                        time: 2
                    });
                }
            },
            timeout: 10000,
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.closeAll();
                if (textStatus == "timeout") {
                    layer.open({
                        content: '请求超时！',
                        skin: 'msg',
                        time: 2
                    });
                } else {
                    layer.open({
                        content: '服务器错误！',
                        skin: 'msg',
                        time: 2
                    });
                }
            }
        });
    });
</script>
<?php
include 'foot.php';
?>