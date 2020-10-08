</div>
<script>
    function logout() {
        layer.open({
            type: 2,
            content: '注销中...',
            time: false
        });
        $.ajax({
            url: "/Admin/ajax.php?act=logout",
            type: 'get',
            dataType: 'json',
            success: function (data) {
                layer.closeAll();
                if (data.code == 1) {
                    layer.open({
                        content: data.msg,
                        skin: 'msg',
                        time: 2,
                        end: function () {
                            window.location.href = "/Admin/login.php";
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

    function check() {
        layer.open({
            type: 2,
            content: '检测更新中...',
            time: false
        });
        $.ajax({
            url: "/Admin/ajax.php?act=check_version",
            type: 'get',
            dataType: 'json',
            success: function (data) {
                layer.closeAll();
                if (data.code == 1) {
                    layer.open({
                        content: data.msg,
                        skin: 'msg',
                        time: 2,
                        end: function () {
                            window.location.href = "https://www.yyhy.me/84.html";
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
</script>
</body>
</html>