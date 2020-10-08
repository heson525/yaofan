<?php
/*
 * 函数库
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

//禁止文件直接被访问
if (!defined('ROOT')) {
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

//检测安装
function check_install()
{
    if (!file_exists(ROOT . '/Core/Config.php')) {
        error('您还未安装烟雨要饭系统,<a href="/Install">点击安装</a>！');
    } else {
        if (!defined('SYS_RANDOM_KEY') || empty(SYS_RANDOM_KEY)) error('您的烟雨要饭系统识别码丢失，请重新安装！');
        $row = Db('select * from yyhy_config');
        if (!$row) error('您的烟雨要饭系统数据库已损坏，请重新安装！');
    }
}

//报错信息
function error($msg)
{
    $year = date('Y');
    $msg = <<<EOF
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>烟雨要饭系统 - 系统错误</title>
</head>
<body>
    <center>
        <h3>
            <font color="red">$msg</font>
        </h3><hr/>
        <small>© $year 烟雨寒云</small>
    </center>
</body>
</html>
EOF;
    die($msg);
}

//模拟Get请求
function httpGet($url)
{
    $ch = curl_init();
    $ip = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    $UserAgent = "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; .NET CLR 3.5.21022; .NET CLR 1.0.3705; .NET CLR 1.1.4322)";
    $headers = ['X-FORWARDED-FOR:' . $ip . '', 'CLIENT-IP:' . $ip . ''];
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

//获取真实IP
function real_ip()
{
    $ip = $_SERVER['REMOTE_ADDR'];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
        foreach ($matches[0] AS $xip) {
            if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                $ip = $xip;
                break;
            }
        }
    } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    return $ip;
}

//获取IP归属地
function get_ip_city($ip)
{
    $url = 'http://whois.pconline.com.cn/ipJson.jsp?json=true&ip=';
    $city = httpGet($url . $ip);
    $city = mb_convert_encoding($city, "UTF-8", "GB2312");
    $city = json_decode($city, true);
    if ($city['city']) {
        $location = $city['pro'] . $city['city'];
    } else {
        $location = $city['pro'];
    }
    if ($location) {
        return $location;
    } else {
        return '获取失败！';
    }
}

//获取登录随机验证Code
function getAuth()
{
    return md5(uniqid(mt_rand(), 1) . time());
}

//快捷抛出json信息
function yyhy_json($arr)
{
    exit(json_encode($arr));
}

//数据库操作
function Db($sql, $exec = false)
{
    global $db_config;
    try {
        $DB = new PDO("mysql:host={$db_config['hostname']};dbname={$db_config['database']};port={$db_config['hostport']}", $db_config['username'], $db_config['password']);
    } catch (Exception $e) {
        error('链接数据库失败:' . $e->getMessage());
    }
    $DB->query('SET NAMES utf8');
    if ($exec) {
        $row = $DB->exec($sql);
    } else {
        $row = $DB->query($sql);
        if (!$row) return false;
        $row = $row->fetchAll(PDO::FETCH_ASSOC);
    }
    return $row;
}

//快速构造数据库插入
function insert($table, $arr)
{
    $str1 = '(';
    $str2 = ' VALUES (';
    foreach ($arr as $k => $v) {
        $str1 .= '`' . $k . '`,';
        $str2 .= "'{$v}',";
    }
    $str1 = trim($str1, ',') . ')';
    $str2 = trim($str2, ',') . ')';
    return 'INSERT INTO `' . $table . '` ' . $str1 . $str2;
}

//安全防护拦截
function StopAttack($StrFiltKey, $StrFiltValue, $ArrFiltReq)
{
    if (is_array($StrFiltValue)) {
        @$StrFiltValue = implode($StrFiltValue);
    }
    if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
        $arr = [
            'code' => -1,
            'msg' => '垃圾你玩你妈呢！？'
        ];
        yyhy_json($arr);
    }
}

//防护拦截
function safe()
{
    $getfilter = "\\<.+javascript:window\\[.{1}\\\\x|<.*=(&#\\d+?;?)+?>|<.*(data|src)=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[a-z]+?\\b[^>]*?\\bon([a-z]{4,})\s*?=|^\\+\\/v(8|9)|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    $postfilter = "<.*=(&#\\d+?;?)+?>|<.*data=data:text\\/html.*>|\\b(alert\\(|confirm\\(|expression\\(|prompt\\(|benchmark\s*?\(.*\)|sleep\s*?\(.*\)|\\b(group_)?concat[\\s\\/\\*]*?\\([^\\)]+?\\)|\bcase[\s\/\*]*?when[\s\/\*]*?\([^\)]+?\)|load_file\s*?\\()|<[^>]*?\\b(onerror|onmousemove|onload|onclick|onmouseover)\\b|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    $cookiefilter = "benchmark\s*?\(.*\)|sleep\s*?\(.*\)|load_file\s*?\\(|\\b(and|or)\\b\\s*?([\\(\\)'\"\\d]+?=[\\(\\)'\"\\d]+?|[\\(\\)'\"a-zA-Z]+?=[\\(\\)'\"a-zA-Z]+?|>|<|\s+?[\\w]+?\\s+?\\bin\\b\\s*?\(|\\blike\\b\\s+?[\"'])|\\/\\*.*\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)|UPDATE\s*(\(.+\)\s*|@{1,2}.+?\s*|\s+?.+?|(`|'|\").*?(`|'|\")\s*)SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE)@{0,2}(\\(.+\\)|\\s+?.+?\\s+?|(`|'|\").*?(`|'|\"))FROM(\\(.+\\)|\\s+?.+?|(`|'|\").*?(`|'|\"))|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
    foreach ($_GET as $key => $value) {
        StopAttack($key, $value, $getfilter);
    }
    foreach ($_POST as $key => $value) {
        StopAttack($key, $value, $postfilter);
    }
    foreach ($_COOKIE as $key => $value) {
        StopAttack($key, $value, $cookiefilter);
    }
}

//网站信息加载
function config($key)
{
    $row = Db('select v from yyhy_config where k="' . $key . '"');
    if (!$row) return false;
    if (!isset($row[0]['v'])) return false;
    $row = $row[0]['v'];
    return $row;
}

//前台施舍记录
function ss_list($order)
{
    $str = '';
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
        $v['ip'] = explode('.', $v['ip']);
        $ip = $v['ip'][0] . '.' . $v['ip'][1] . '.***.' . $v['ip'][3];
        $str .= <<<EOF
<tr>
    <td>{$v['qq']}<br/>{$v['nick']}</td>
    <td>{$type}<br/>{$v['money']}元</td>
    <td>{$ip}<br/>{$v['city']}</td>
    <td><button class="btn btn-success btn-xs" onclick="msg('{$v['trade_no']}')">点击查看</button></td>
    <td>{$v['addtime']}<br/>{$endtime}</td>
    <td>{$status}</td>
</tr>
EOF;
    }
    return $str;
}

//清理一小时前未支付订单
function clear()
{
    $data = date('Y-m-d H:i:s', time() - 3600);
    Db('delete from yyhy_order where status=0 and addtime<="' . $data . '"');
}

//弹框
function alert($msg, $url = false)
{
    if (!$url) $url = 'javascript:history.back(-1);';
    $msg = <<<EOF
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
    <title>系统提示</title>
</head>
<body>
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="/Static/js/layer.js"></script>
    <script >
        layer.open({
            content: '{$msg}',
            btn: '确定',
            yes:function(){
                window.location.href='{$url}';
            }
        });
    </script>
</body>
</html>
EOF;
    exit($msg);
}

//加载官方广告
function load_ad()
{
    $url = 'http://api.yyhy.me/yaofan_ad.php?sys_auth_token=' . SYS_RANDOM_KEY;
    $res = httpGet($url);
    $res = json_decode($res, true);
    if ($res['code'] != 1) return false;
    return $res['data'];
}

//获取QQ昵称
function get_qq_nick($qq)
{
    $get_info = httpGet('https://api.unipay.qq.com/v1/r/1450000186/wechat_query?cmd=1&pf=mds_storeopen_qb-__mds_qqclub_tab_-html5&pfkey=pfkey&from_h5=1&from_https=1&openid=openid&openkey=openkey&session_id=hy_gameid&session_type=st_dummy&qq_appid=&offerId=1450000186&sandbox=&provide_uin=' . $qq);
    $name = json_decode($get_info, true);
    if (!isset($name['nick'])) return '获取失败！';
    $name = urldecode($name['nick']);
    return $name;
}

//检测更新
function check_version()
{
    $url = 'http://api.yyhy.me/yaofan_ver.php?ver=' . VERSION;
    $res = httpGet($url);
    $res = json_decode($res, true);
    if (!$res) yyhy_json(['code' => -1, 'msg' => '更新服务器开小差了，请稍后再试！']);
    if ($res['new'] != 1) yyhy_json(['code' => -1, 'msg' => '您已经是最新版本V' . VERSION . '！']);
    yyhy_json(['code' => 1, 'msg' => '有新版本，即将跳转烟雨博客，请自行下载升级！']);
}

//管理员登录操作
function do_login()
{
    $auth = getAuth();
    setcookie('yyhy_auth', $auth, time() + 3600 * 24, '/');
    $row = Db('update yyhy_config set `v`="' . $auth . '" where `k`="auth"', 1);
    if (!$row) return false;
    return true;
}

//验证管理员登录
function admin_login($ajax = false)
{
    if (isset($_COOKIE['yyhy_auth'])) {
        $auth = config('auth');
        if ($_COOKIE['yyhy_auth'] != $auth) {
            $_COOKIE['yyhy_auth'] = null;
            if (!$ajax) alert('您还未登录，请登录后操作！', '/Admin/login.php');
            return false;
        }
    } else {
        if (!$ajax) alert('您还未登录，请登录后操作！', '/Admin/login.php');
        return false;
    }
    return true;
}

//管理员注销登录
function logout()
{
    setcookie('yyhy_auth', null, 0, '/');
}