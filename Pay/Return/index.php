<?php
/*
 * 支付回调
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 * Moddfy by 黑石
 */

include '../../Core/Common.php';

$data = $_REQUEST;
$pay = new Pay(config('pid'), config('key'),config('api'));
if ($data['pay_no']) {
    $order = Db('select * from yyhy_order where trade_no="' . $data['param'] . '"');
    if (!$order) exit('fail');
    $order = $order[0];
    if ($order['status'] != 1) {
        Db('update yyhy_order set `status`=1,`endtime`="' . date('Y-m-d H:i:s') . '" where trade_no="' . $data['param'] . '"');
    }
    alert('施舍成功，好人有好报！', '/');
} else {
    alert('支付验证失败,施舍失败了，又吃不到饭饭了！', '/');
}