<?php
/*
 * 后台首页
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

$title = '后台管理';
include 'head.php';
//最新5条订单
$order = Db('select * from yyhy_order order by trade_no desc limit 5');
?>
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <center>后台首页</center>
                </div>
                <div class="panel-body">
                    <img src="/Static/img/bg.jpeg"
                         width="100%">
                    <p/>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <center><h3 style="margin-top: 2px;"><font color="success">欢迎您使用烟雨要饭系统，尊敬的管理员！</font></h3>
                            </center>
                        </li>
                        <li class="list-group-item">
                            <center><h5><font color="#87ceeb"><span id="nowTime"></span></font></h5></center>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <center>最新5条业务</center>
                </div>
                <div class="panel-body">
                    <ul class="list-group">
                        <?php
                        foreach ($order as $v) {
                            echo <<<EOF
<li class="list-group-item">
    <span class="badge">{$v['money']}元</span>
    {$v['nick']}
</li>
EOF;

                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <center>烟雨推荐</center>
                </div>
                <div class="panel-body">
                    <ul class="list-group" id="ad">
                        <li class="list-group-item">
                            广告加载中...
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        //获取系统时间
        var newDate = '';
        getLangDate();

        //值小于10时，在前面补0
        function dateFilter(date) {
            if (date < 10) {
                return "0" + date;
            }
            return date;
        }

        function getLangDate() {
            var dateObj = new Date(); //表示当前系统时间的Date对象
            var year = dateObj.getFullYear(); //当前系统时间的完整年份值
            var month = dateObj.getMonth() + 1; //当前系统时间的月份值
            var date = dateObj.getDate(); //当前系统时间的月份中的日
            var day = dateObj.getDay(); //当前系统时间中的星期值
            var weeks = ["星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
            var week = weeks[day]; //根据星期值，从数组中获取对应的星期字符串
            var hour = dateObj.getHours(); //当前系统时间的小时值
            var minute = dateObj.getMinutes(); //当前系统时间的分钟值
            var second = dateObj.getSeconds(); //当前系统时间的秒钟值
            var timeValue = "" + ((hour >= 12) ? (hour >= 18) ? "晚上" : "下午" : "上午"); //当前时间属于上午、晚上还是下午
            newDate = dateFilter(year) + "年" + dateFilter(month) + "月" + dateFilter(date) + "日 " + " " + dateFilter(hour) + ":" + dateFilter(minute) + ":" + dateFilter(second);
            document.getElementById("nowTime").innerHTML = timeValue + "好！当前时间为： " + newDate + "　" + week;
            setTimeout("getLangDate()", 1000);
        }

        layer.open({
            type: 2,
            content: '加载中...',
            time: false
        });
        $.ajax({
            url: "ajax.php?act=ad",
            type: 'get',
            dataType: 'json',
            success: function (data) {
                layer.closeAll();
                if (data.code == 1) {
                    $('#ad').html(' ');
                    for (var i = 0; i < data.data.length; i++) {
                        $('#ad').append(data.data[i]);
                    }
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
    </script>
<?php
include 'foot.php';
?>