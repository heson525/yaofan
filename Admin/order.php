<?php
/*
 * 订单列表
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

$title = '订单列表';
include 'head.php';
$pagesize = 30;
$numrows = Db('SELECT count(*) from yyhy_order')[0]['count(*)'];
$pages = intval($numrows / $pagesize);
if ($numrows % $pagesize) {
    $pages++;
}
if (isset($_GET['page'])) {
    $page = intval($_GET['page']);
} else {
    $page = 1;
}
$offset = $pagesize * ($page - 1);
if (isset($_GET['text']) && !empty($_GET['text'])) {
    $sql = " `trade_no` like '%{$_GET['text']}%' or `money` like '%{$_GET['text']}%' or `ip` like '%{$_GET['text']}%' or `city` like '%{$_GET['text']}%' or `qq` like '%{$_GET['text']}%' or `nick` like '%{$_GET['text']}%' or `msg` like '%{$_GET['text']}%' or `addtime` like '%{$_GET['text']}%' or `endtime` like '%{$_GET['text']}%'";
    $order = Db('select * from yyhy_order where' . $sql . ' order by trade_no desc limit ' . $offset . ',' . $pagesize);
    $link = '&text=' . $_GET['text'];
} else {
    $order = Db('select * from yyhy_order order by trade_no desc limit ' . $offset . ',' . $pagesize);
    $link = '';
}
?>
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 center-block" style="float: none;">
        <div class="panel panel-info">
            <div class="panel-heading">订单列表
            </div>
            <div class="panel-body">
                <form class="form-inline">
                    <div class="form-group">
                        <input type="text" class="form-control input-pill input-solid" name="text"
                               placeholder="请输入搜索内容" value="">
                    </div>
                    <div class="form-group">
                        <button class="btn btn-sm btn-success" type="submit">搜索</button>
                    </div>
                </form>
                <br/>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>订单号</th>
                            <th>金额/支付方式</th>
                            <th>QQ/昵称</th>
                            <th>IP/城市</th>
                            <th>留言</th>
                            <th>创建时间</th>
                            <th>完成时间</th>
                            <th>状态</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($order as $v) {
                            if ($v['endtime']) {
                                $endtime = $v['endtime'];
                            } else {
                                $endtime = '<font color="red">未完成</font>';
                            }
                            if ($v['status'] == 1) {
                                $status = '<font color="green">已支付</font>';
                            } else {
                                $status = '<font color="red">未支付</font>';
                            }
                            if ($v['type'] == 'alipay') {
                                $type = '支付宝';
                            }
                            if ($v['type'] == 'wxpay') {
                                $type = '微信';
                            }
                            if ($v['type'] == 'qqpay') {
                                $type = 'QQ';
                            }
                            echo <<<EOF
<tr>
    <td>{$v['trade_no']}</td>
    <td>{$v['money']}元<br/>{$type}</td>
    <td>{$v['qq']}<br/>{$v['nick']}</td>
    <td>{$v['ip']}<br/>{$v['city']}</td>
    <td><button class="btn btn-success btn-xs" onclick="msg('{$v['trade_no']}')">点击查看</button></td>
    <td>{$v['addtime']}</td>
    <td>{$endtime}</td>
    <td>{$status}</td>
</tr>
EOF;

                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <center>
                    <?php
                    echo '<ul class="pagination">';
                    $first = 1;
                    $prev = $page - 1;
                    $next = $page + 1;
                    $last = $pages;
                    if ($page > 1) {
                        echo '<li><a href="order.php?page=' . $first . $link . '">首页</a></li>';
                        echo '<li><a href="order.php?page=' . $prev . $link . '">&laquo;</a></li>';
                    } else {
                        echo '<li class="disabled"><a>首页</a></li>';
                        echo '<li class="disabled"><a>&laquo;</a></li>';
                    }
                    for ($i = 1; $i < $page; $i++)
                        echo '<li><a href="order.php?page=' . $i . $link . '">' . $i . '</a></li>';
                    echo '<li class="disabled"><a>' . $page . '</a></li>';
                    if ($pages >= 10) $s = 10;
                    else $s = $pages;
                    for ($i = $page + 1; $i <= $s; $i++)
                        echo '<li><a href="order.php?page=' . $i . $link . '">' . $i . '</a></li>';
                    echo '';
                    if ($page < $pages) {
                        echo '<li><a href="order.php?page=' . $next . $link . '">&raquo;</a></li>';
                        echo '<li><a href="order.php?page=' . $last . $link . '">尾页</a></li>';
                    } else {
                        echo '<li class="disabled"><a>&raquo;</a></li>';
                        echo '<li class="disabled"><a>尾页</a></li>';
                    }
                    echo '</ul>';
                    ?>
                </center>
            </div>
        </div>
    </div>
    <script>
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
<?php
include 'foot.php';
?>