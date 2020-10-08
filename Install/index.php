<?php
/*
 * 安装文件
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

//设置程序根目录
define('ROOT', dirname(dirname(__FILE__)));
include '../Core/Functions.php';
$databaseFile = '../Core/Config.php';
if (file_exists($databaseFile)) {
    error('你已经成功安装，如需重新安装，请手动删除/Core/Config.php配置文件！');
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/Static/css/main.css" rel="stylesheet">
    <title>烟雨要饭系统V2.0 - 安装向导</title>
</head>
<body background="/Static/img/bg.jpg">
<div class="container" style="padding-top:55px;">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 center-block text-center" style="float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background: linear-gradient(to right,#8ae68a,#5ccdde,#b221ff);"><font color="#000000">烟雨要饭系统</font></div>
            <div class="panel-body">
                <div class="alert alert-danger" role="alert">2020/3/19 15:32 作者说：最近实在太忙了没空更新，今天抽空更新一下，同时在此声明：烟雨要饭系统是本人的原创作品，最初想法来自于<a href="http://blog.s-b.fun/" target="_blank">Dzoer</a>，后由本人编写代码，发布了1.0版本，后来又有了2.0版本，发布后的热度一度超乎我的想象，网上也出来很多二开版本，也有部分人移植了Python版，Java版，说这个并不是说我反对别人二开我的作品，因为本来就是个开源作品本人非常欢迎志同道合的人一起来开发要饭系统，只是有些别有用心的人拿来倒卖，加后门，比如前阵子就看到互站有人卖我的源码，淘宝上也比比兼是，拿我免费的东西来卖的这种行为真的令人不齿！<hr/>使用须知：您使用本源码以及二次开发本程序造成的一切后果均由您自行承担，作者对此不负任何责任！</div>
                <font color="#FF0000">不</font><font color="#FA0005">妥</font><font color="#F5000A">协</font><font color="#F0000F">，</font><font color="#EB0014">不</font><font color="#E60019">逐</font><font color="#E1001E">流</font><font color="#DC0023">；</font><font color="#D70028">随</font><font color="#D2002D">性</font><font color="#CD0032">而</font><font color="#C80037">不</font><font color="#C3003C">失</font><font color="#BE0041">个</font><font color="#B90046">性</font><font color="#B4004B">，</font><font color="#AF0050">有</font><font color="#AA0055">设</font><font color="#A5005A">计</font><font color="#A0005F">而</font><font color="#9B0064">不</font><font color="#960069">漏</font><font color="#91006E">痕</font><font color="#8C0073">迹</font><font color="#870078">；</font><font color="#82007D">烟</font><font color="#7D0082">雨</font><font color="#780087">要</font><font color="#73008C">饭</font><font color="#6E0091">系</font><font color="#690096">统</font><font color="#64009B">，</font><font color="#5F00A0">繁</font><font color="#5A00A5">华</font><font color="#5500AA">阅</font><font color="#5000AF">尽</font><font color="#4B00B4">处</font><font color="#4600B9">，</font><font color="#4100BE">简</font><font color="#3C00C3">约</font><font color="#3700C8">不</font><font color="#3200CD">简</font><font color="#2D00D2">单</font><font color="#2800D7">！</font>
            </div>
            <div class="panel-footer text-center">
                <a href="install.php" class="btn btn-success">开始享用</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>