<?php
/*
 * 支付发起
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 * Moddfy by 黑石
 */

include '../../Core/Common.php';

$trade_no = $_GET['trade_no'] ?? alert('订单号不可为空！', '/');
$order = Db('select * from yyhy_order where trade_no="' . $trade_no . '"');
if (!$order) alert('订单不存在！', '/');
$order = $order[0];
$pay = new Pay(config('pid'), config('key'),config('api'));
$url = $pay->submit($order['type'], $order['trade_no'], 'https://' . $_SERVER['HTTP_HOST'] . '/Pay/Notify/', 'https://' . $_SERVER['HTTP_HOST'] . '/Pay/Return/', config('sitename') . ' - 支付订单', $order['money'], config('sitename'), $order['nick']);
header("Location: {$url}");