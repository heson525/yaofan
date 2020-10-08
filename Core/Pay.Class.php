<?php
/*
 * 支付核心类库
 * Author：烟雨寒云
 * Mail：admin@yyhy.me
 * Date:2019/10/13
 * Moddfy by 黑石
 */

if (!defined('ROOT')) {
    header('HTTP/1.1 404 Not Found', true, 404);
    die();
}

class Pay
{
    private $pid;
    private $key;
    private $api;

    public function __construct($pid = null, $key = null, $api = null)
    {
        $this->pid = $pid;
        $this->key = $key;
        $this->api = $api;
    }

    /**
     * @Note  支付发起
     * @param $type   支付方式
     * @param $out_trade_no     订单号
     * @param $notify_url     异步通知地址
     * @param $return_url     回调通知地址
     * @param $name     商品名称
     * @param $money     金额
     * @param $sitename     站点名称
     * @param $typeid     码支付type
     * @param $nick     大爷昵称
     * @return string
     */
    public function submit($type, $out_trade_no, $notify_url, $return_url, $name, $money, $sitename, $nick)
    {
        if ($type == 'alipay') {
            $typeid = 1;
        }
        if ($type == 'qqpay') {
            $typeid = 2;
        }
        if ($type == 'wxpay') {
            $typeid = 3;
        }


        $data = [
            'id' => $this->pid,
            'type' => $typeid,
            'out_trade_no' => $out_trade_no,
            'notify_url' => $notify_url,
            'return_url' => $return_url,
            'name' => $name,
            'price' => $money,
            'sitename' => $sitename,
            'act' => 0,
            'debug' => 0,
            'pay_type' => 1,
            'pay_id' => $nick,
            'param' => $out_trade_no


        ];
        $string = http_build_query($data);
        $sign = $this->getsign($data);
        return 'http://codepay.fateqq.com/create_order/?' . $string . '&token='. $this->key ;
    }

    /**
     * @Note   验证支付
     * @param $data  待验证参数
     * @return bool
     */
    public function verify($data)
    {
        if (!isset($data['sign']) || !$data['sign']) {
            return false;
        }
        $sign = $data['sign'];
        unset($data['sign']);
        unset($data['sign_type']);
        $sign2 = $this->getSign($data);
        if ($sign != $sign2) {
            //兼容傻逼彩虹易支付
            unset($data['_input_charset']);
            $sign2 = $this->getSign($data);
            if ($sign == $sign2) {
                if ($_REQUEST['trade_status'] == 'TRADE_SUCCESS') return true;
                return false;
            }
            return false;
        }
        if ($_REQUEST['trade_status'] == 'TRADE_SUCCESS') return true;
        return false;
    }

    /**
     * @Note  生成签名
     * @param $data   参与签名的参数
     * @return string
     */
    public function getSign($data)
    {
        $data = array_filter($data);
        ksort($data);
        $str1 = '';
        foreach ($data as $k => $v) {
            $str1 .= '&' . $k . "=" . $v;
        }
        $str = $str1 . $this->key;
        $str = trim($str, '&');
        $sign = md5($str);
        return $sign;
    }
}