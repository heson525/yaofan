<?php
/*
 * 后台Ajax入口文件
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 */

include '../Core/Common.php';

$act = $_GET['act'] ?? yyhy_json(['code' => -1, 'msg' => 'No Act!']);
$data = $_REQUEST;
if (!admin_login(true)) {
    if ($act == 'login') {
        if (!isset($data['username']) || empty($data['username']) || !isset($data['password']) || empty($data['password'])) yyhy_json(['code' => -1, 'msg' => '请确保每项不为空！']);
        if ($data['username'] != config('username') || !password_verify($data['password'], config('password'))) yyhy_json(['code' => -1, 'msg' => '用户名或密码错误!']);
        do_login();
        yyhy_json(['code' => 1, 'msg' => '登陆成功！']);
    }
} else {
    if ($act == 'logout') {
        logout();
        yyhy_json(['code' => 1, 'msg' => '注销登录成功！']);
    }
    if ($act == 'pass') {
        if (!isset($data['yuan']) || empty($data['yuan']) || !isset($data['new']) || empty($data['new'])) yyhy_json(['code' => -1, 'msg' => '请确保每项不为空！']);
        if (!password_verify($data['yuan'], config('password'))) yyhy_json(['code' => -1, 'msg' => '原密码错误！']);
        $password = password_hash($data['new'], PASSWORD_DEFAULT);
        $row = Db('update yyhy_config set `v`="' . $password . '" where `k`="password"', 1);
        if (!$row) yyhy_json(['code' => -1, 'msg' => '修改密码失败！']);
        yyhy_json(['code' => 1, 'msg' => '修改密码成功！']);
    }
    if ($act == 'config') {
        unset($data['act']);
        foreach ($data as $key => $value) {
            $row = Db('select * from yyhy_config where k="' . $key . '"');
            if ($row) {
                $new = false;
            } else {
                $new = true;
            }
            if ($new) {
                Db(insert('yyhy_config', ['k' => $key, 'v' => $value]), 1);
            } else {
                Db('update yyhy_config set `v`="' . $value . '" where `k`="' . $key . '"', 1);
            }
        }
        yyhy_json(['code' => 1, 'msg' => '保存成功！']);
    }
    if ($act == 'ad') {
        $ad = load_ad();
        if (!$ad) yyhy_json(['code' => -1, 'msg' => '广告获取失败！']);
        $arr = [
            'code' => 1,
            'msg' => '广告获取成功！',
            'data' => $ad
        ];
        yyhy_json($arr);
    }
    if ($act == 'check_version') {
        check_version();
    }
}