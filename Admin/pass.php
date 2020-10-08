<?php
/*
 * 修改密码
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

$title = '修改密码';
include 'head.php';
?>
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 center-block" style="float: none;">
        <div class="panel panel-info">
            <div class="panel-heading">修改密码</div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">原密码</label>
                        <input type="text" class="form-control" name="yuan" placeholder="请输入原密码">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">新密码</label>
                        <input type="text" class="form-control" name="new" placeholder="请输入新密码">
                    </div>
                    <button type="button" id="submit" class="btn btn-success">修改</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#submit').click(function () {
            layer.open({
                type: 2,
                content: '修改中...',
                time: false
            });
            var cont = $("input,textarea").serialize();
            $.ajax({
                type: "POST",
                url: "/Admin/ajax.php?act=pass",
                data: cont,
                dataType: 'json',
                success: function (data) {
                    layer.closeAll();
                    if (data.code == 1) {
                        layer.open({
                            content: data.msg,
                            skin: 'msg',
                            time: 2
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