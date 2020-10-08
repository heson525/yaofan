<?php
/*
 * 首页
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

include './Core/Common.php';
$data = $_REQUEST;
if (isset($data['type']) && !empty($data['type'])) {
    $pay = [
        'wxpay',
        'qqpay',
        'alipay'
    ];
    if (!in_array($data['type'], $pay)) yyhy_json(['code' => -1, 'msg' => '支付方式不合法！']);
    $trade_no = date('YmdHis') . mt_rand(1111, 9999);
    if (!$data['qq']) yyhy_json(['code' => -1, 'msg' => 'QQ不可为空！']);
    if (!preg_match('/^[1-9][0-9]{4,9}$/', $data['qq'])) yyhy_json(['code' => -1, 'msg' => 'QQ号格式不正确！']);
    if (!$data['msg']) yyhy_json(['code' => -1, 'msg' => '留言不可为空！']);
    if (!$data['money']) yyhy_json(['code' => -1, 'msg' => '金额不可为空！']);
    if ($data['money'] < 0 || !is_numeric($data['money']) || $data['money'] > 5000) yyhy_json(['code' => -1, 'msg' => '金额不合法！']);
    $data['money'] = round($data['money'], 2);
    $arr = [
        'trade_no' => $trade_no,
        'qq' => strip_tags($data['qq']),
        'nick' => get_qq_nick($data['qq']),
        'city' => get_ip_city(real_ip()),
        'msg' => strip_tags($data['msg']),
        'ip' => real_ip(),
        'money' => strip_tags($data['money']),
        'type' => strip_tags($data['type']),
        'addtime' => date('Y-m-d H:i:s')
    ];
    $row = Db(insert('yyhy_order', $arr), 1);
    if (!$row) yyhy_json(['code' => -1, 'msg' => '订单发起失败！']);
    yyhy_json(['code' => 1, 'msg' => '订单发起成功！', 'trade_no' => $trade_no]);
}
if (isset($data['msg']) && !empty($data['msg'])) {
    $order = Db('select * from yyhy_order where trade_no="' . $data['msg'] . '"');
    if (!$order) yyhy_json(['code' => -1, 'msg' => '留言查看失败！']);
    $order = $order[0];
    yyhy_json(['code' => 1, 'msg' => $order['msg']]);
}
$order = Db('select * from yyhy_order order by trade_no desc limit 10');
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?php echo config('sitename'); ?> - <?php echo config('title'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link href="/Static/css/main.css" rel="stylesheet">
    <link href="//cdn.bootcss.com/simple-line-icons/2.4.1/css/simple-line-icons.min.css" rel="stylesheet">
<body background="/Static/img/bg.jpg">
<div class="container" style="padding-top:20px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background: linear-gradient(to right,#8ae68a,#5ccdde,#b221ff);">
                <center><font color="#000000"><b><?php echo config('panel'); ?></b></font></center>
            </div>
            <div class="panel-body">
                <center>
                    <div class="alert alert-success">
                        <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo config('qq'); ?>&site=qq&menu=yes">
                            <img class="img-circle"
                                 style="border: 2px solid #1281FF; margin-left:3px; margin-right:3px;"
                                 src="https://q4.qlogo.cn/headimg_dl?dst_uin=<?php echo config('qq'); ?>&spec=640"
                                 width="60px" height="60px" alt="<?php echo config('sitename'); ?>">
                        </a><br>
                        <?php echo config('gg'); ?>
                    </div>
                </center>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-ghost"></i> 您的QQ</span>
                    <input name="qq" class="form-control" placeholder="请输入您的QQ以便于联系">
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-envelope"></i> 您的留言</span>
                    <textarea class="form-control" name="msg" cols="30" rows="3" placeholder="请输入您的施舍留言"></textarea>
                </div>
                <br/>
                <div class="input-group">
                    <span class="input-group-addon"><i class="icon-cup"></i> 施舍金额</span>
                    <input name="money" class="form-control" placeholder="请输入您要施舍的金额">
                </div>
                <br/>
                <center>
                    <div class="alert alert-warning">选择一种方式进行施舍...</div>
                    <div class="btn-group btn-group-justified" role="group" aria-label="...">
                        <div class="btn-group" role="group">
                            <button onclick="pay('alipay')" class="btn btn-primary">支付宝</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button onclick="pay('qqpay')" class="btn btn-danger">QQ</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button onclick="pay('wxpay')" class="btn btn-info">微信</button>
                        </div>
                    </div>
                </center>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background: linear-gradient(to right,#b221ff,#14b7ff,#8ae68a);">
                <center><font color="#000000"><b><i class="icon-heart" style="color:red"></i> 大佬们的施舍记录</b></font>
                </center>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>QQ/昵称</th>
                        <th>施舍方式/金额</th>
                        <th>ip/城市</th>
                        <th>留言</th>
                        <th>施舍时间/完成时间</th>
                        <th>状态</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php echo ss_list($order); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <center>
            <p style="text-align:center"><br>&copy; Powered by <a href="/"><?php echo config('copy'); ?></a>!</p>
        </center>
    </div>
    <?php
    if (config('music_sw') == 'on') {
        echo <<<EOF
<audio autoplay="autoplay">
    <source src="/Static/music/yaofan.mp3" type="audio/mp3"/>
</audio>
EOF;
    }
    ?>

    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/Static/js/layer.js"></script>
    <script>
        function pay(type) {
            layer.open({
                type: 2,
                content: '订单发起中...',
                time: false
            });
            var cont = $("input,textarea").serialize();
            $.ajax({
                url: "/index.php?type=" + type,
                data: cont,
                type: 'post',
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.code == 1) {
                        layer.open({
                            content: data.msg,
                            btn: ['支付', '取消'],
                            skin: 'footer',
                            yes: function () {
                                window.location.href = "/Pay/Submit?trade_no=" + data.trade_no;
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
        }

        function msg(trade_no) {
            layer.open({
                type: 2,
                content: '查询中...',
                time: false
            });
            $.ajax({
                url: "/index.php?msg=" + trade_no,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.code == 1) {
                        layer.open({
                            content: data.msg,
                            btn: '关闭'
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
        }
    </script>
</body>
</html>
