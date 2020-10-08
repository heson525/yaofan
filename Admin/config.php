<?php
/*
 * 网站配置
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

$title = '网站配置';
include 'head.php';
?>
    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 center-block" style="float: none;">
        <div class="panel panel-info">
            <div class="panel-heading">网站配置</div>
            <div class="panel-body">
                <form>
                    <div class="form-group">
                        <label for="exampleInputEmail1">网站名称</label>
                        <input type="text" class="form-control" name="sitename"
                               value="<?php echo config('sitename'); ?>" placeholder="请输入网站名称">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">网站副标题</label>
                        <input type="text" class="form-control" name="title" value="<?php echo config('title'); ?>"
                               placeholder="请输入网站副标题">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">网站SEO关键字</label>
                        <input type="text" class="form-control" name="keywords"
                               value="<?php echo config('keywords'); ?>" placeholder="请输入网站SEO关键字">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">网站SEO描述</label>
                        <input type="text" class="form-control" name="description"
                               value="<?php echo config('description'); ?>" placeholder="请输入网站SEO描述">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">站长QQ</label>
                        <input type="text" class="form-control" name="qq" value="<?php echo config('qq'); ?>"
                               placeholder="请输入站长QQ">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">首页panel标题</label>
                        <input type="text" class="form-control" name="panel" value="<?php echo config('panel'); ?>"
                               placeholder="请输入首页panel标题">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">首页公告</label>
                        <textarea class="form-control" name="gg" cols="30" rows="3" placeholder="请输入首页公告"><?php echo config('gg'); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">网站版权</label>
                        <input type="text" class="form-control" name="copy" value="<?php echo config('copy'); ?>"
                               placeholder="请输入网站版权">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">首页音乐</label>
                        <select class="form-control" name="music_sw">
                            <option value="on" <?php if(config('music_sw')=='on'){echo 'selected';}?>>开启</option>
                            <option value="off" <?php if(config('music_sw')=='off'){echo 'selected';}?>>关闭</option>
                        </select>
                    </div>
                    <button type="button" id="submit" class="btn btn-success">保存</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#submit').click(function () {
            layer.open({
                type: 2,
                content: '保存中...',
                time: false
            });
            var cont = $("input,textarea,select").serialize();
            $.ajax({
                type: "POST",
                url: "/Admin/ajax.php?act=config",
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