<?php
/*
 * 核心文件
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

//屏蔽报错
error_reporting(0);

//程序根目录
define('ROOT', dirname(dirname(__FILE__)));

//程序版本号
define('VERSION', '2.0.4');

//设置时区
date_default_timezone_set('Asia/Shanghai');

//检测php版本
if (version_compare(PHP_VERSION, '7.0.0', '<')) error('您的php版本过低，请确保您的php版本在php7.0及以上再进行运行！');

//引入函数库
include ROOT . '/Core/Functions.php';

//数据库配置
$db_config = include ROOT . '/Core/Config.php';

//引入支付核心库
include ROOT . '/Core/Pay.Class.php';

//检测安装
check_install();

//安全防护模块
safe();

//清理订单
clear();
