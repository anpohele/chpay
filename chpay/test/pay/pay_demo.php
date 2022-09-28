<?php

include "../demo.php";

/**
 * 支付
 */
class pay_demo
{

    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 反扫码接口
     *
     * @return array
     */
    function barcode_pay()
    {

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.barcodepay';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content_arr = array(
            "out_trade_no" => $this->common->datetime2string(date('Y-m-d H:i:s')),
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "subject" => "测试反扫",
            "total_amount" => "0.02",
            "seller_id" => $this->common->param['seller_id'],
            "seller_name" => $this->common->param['seller_name'],
            "timeout_express" => "24h",
            "business_code" => "01000010",
//            "bank_type" => "1903000",  //支付宝
            "bank_type" => "1902000",  //微信
//            "bank_type" => "9001002",  //中国银联
//            "bank_type" => "1905000",  //苏宁
//            "scene" => "bar_code",   //支付场景，支付宝时必填
//            "scene" => "wave_code",   //支付宝时必填
            "auth_code" => "134707877954377268",
//            "device_info" => "cs002356",  //终端设备号，中国银联时必填
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams['sign']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_barcodepay_response');
    }


    /**
     * wap端h5唤起支付宝支付
     *
     * @param $order 订单号
     * @return
     */
    function h5_pay($order)
    {
        $myParams = array();
        $myParams['business_code'] = '01000010';
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.wap.directpay.createbyuser';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $myParams['out_trade_no'] = $order;
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['return_url'] = $this->common->param['return_url'];
        $myParams['seller_id'] = $this->common->param['seller_id'];
        $myParams['seller_name'] = $this->common->param['seller_name'];
        $myParams['sign_type'] = 'RSA';
        $myParams['subject'] = '支付测试';
        $myParams['timeout_express'] = '1d';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['total_amount'] = '0.20';
        $myParams['version'] = '3.0';
        $myParams['pay_mode'] = 'native';
        $myParams['bank_type'] = '1903000';  //支付宝
//        $myParams['bank_type'] = '1902000';  //微信
//        $myParams['bank_type'] = '1904000';  //QQ钱包
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $action = $this->common->param['order_url'];
        var_dump('提交地址：' . $action);
        $def_url = "<br /><form style='text-align:center;' method=post action='" . $action . "' target='_blank'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='Pay" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
        $def_url .= "<input type=submit value='点击提交' " . @$GLOBALS['_LANG']['pay_button'] . "'>";
        $def_url .= "</form>";
        return $def_url;
    }

    /**
     * 说明 余额查询接口
     */
    function balance_query()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.user.account.get';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
//        $myParams['notify_url'] =$this->param['notify_url'];
        $myParams['version'] = '3.0';

        $biz_content_arr = array(
            "user_code" => $this->common->param['seller_id'],
            "user_name" => $this->common->param['seller_name']
//              "merchant_usercode"=>"YS_test",
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_user_account_get_response');
    }

    /**
     * 微信SDK下单接口,测试环境仅作同步验签即可
     *
     * @param $order 订单号
     */
    function wxapp_pay($order)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.sdkpay';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content_arr = array(
            "out_trade_no" => "$order",
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "subject" => "微信APP下单接口",
            "total_amount" => "10",
            "currency" => "CNY",
            "seller_id" => $this->common->param['seller_id'],
            "seller_name" => $this->common->param['seller_name'],
            "timeout_express" => "24h",
            "business_code" => "01000010",
            "bank_type" => "1902000",
            "appid" => "wx123456789123"
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams['sign']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_sdkpay_response');
    }


    /**
     * 微信小程序下单接口
     *
     * @param $order 订单号
     */
    function wxminipg_pay($order)
    {

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.weixin.pay';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content_arr = array(
            "out_trade_no" => $order,
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "subject" => "微信小程序下单接口",
            "total_amount" => "10",
            "currency" => "CNY",
            "seller_id" => $this->common->param['seller_id'],
            "seller_name" => $this->common->param['seller_name'],
            "timeout_express" => "24h",
            "business_code" => "01000010",
            "sub_openid" => "wx123456789123",
            "is_minipg" => "1",
            "appid" => "wxbf58fd62cb5a4ae8"
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams['sign']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_weixin_pay_response');
    }

    /**
     * 微信公众号下单接口,测试环境仅作同步验签即可
     *
     * @param $order 订单号
     */
    function wxPublic_pay($order)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.weixin.pay';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content_arr = array(
            "out_trade_no" => $order,
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "subject" => "微信公众号下单接口",
            "total_amount" => "10",
            "currency" => "CNY",
            "seller_id" => $this->common->param['seller_id'],
            "seller_name" => $this->common->param['seller_name'],
            "timeout_express" => "24h",
            "business_code" => "01000010",
            "sub_openid" => "wx123456789123",
            "appid" => "wxbf58fd62cb5a4ae8"
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams['sign']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_weixin_pay_response');
    }

    /**
     * 支付宝二维码接口 测试环境无法模拟真实场景 仅作同步验签 商户自行修改商户号 商户名等参数
     *
     * @param $order 订单号
     * @return
     */
    function get_qrcode_pay($order)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.qrcodepay';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['return_url'] = $this->common->param['return_url'];
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content_arr = array(
            "out_trade_no" => "$order",
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "subject" => "测试扫码",
            "total_amount" => "0.02",
            "seller_id" => $this->common->param['seller_id'],
            "seller_name" => $this->common->param['seller_name'],
            "timeout_express" => "24h",
            "business_code" => "01000010",
//            "bank_type" => "1903000" //支付宝
//            "bank_type" => "9001002"  //银联
//            "bank_type" => "1904000"  //QQ
            "bank_type" => "1902000"  //微信
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams['sign']);
        var_dump($myParams);
        $ch = curl_init($this->common->param['order_url']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($myParams));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        var_dump($response);
        if (curl_errno($ch)) {
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
            $sign = $response['sign'];
            echo $sign;
            $data = json_encode($response['ysepay_online_qrcodepay_response'], 320);
            var_dump($data);
            /* 验证签名 仅作基础验证*/
            if ($this->common->sign_check($sign, $data) == true) {
                echo "验证签名成功!";
            } else {
                echo '验证签名失败!';
            }
        }
    }


    /**
     * PC收银台接口 测试环境仅需使用pc收银台->网银支付,作为商户测试环境校验.
     *
     * @param $order 订单号
     * @return
     */
    function get_code($order)
    {

        $myParams = array();
        $myParams['business_code'] = '01000010';
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.directpay.createbyuser';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $myParams['out_trade_no'] = $order;
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['return_url'] = $this->common->param['return_url'];
        $myParams['seller_id'] = $this->common->param['seller_id'];
        $myParams['seller_name'] = $this->common->param['seller_name'];
        $myParams['sign_type'] = 'RSA';
        $myParams['subject'] = '支付测试';
        $myParams['timeout_express'] = '1d';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['total_amount'] = '30';
        $myParams['version'] = '3.0';
        /* wap快捷直连需添加以下参数 */
//        $myParams['bank_account_no']           = '9558800200135073266';
//        $myParams['fast_pay_name']           = '银盛支付';
//        $myParams['pay_mode']           = 'fastpay';
//        $myParams['fast_pay_id_no']           = '530523198803220894';
        /* 信用卡必填*/
//        $myParams['bank_account_no']           = '6282886282888888';
//        $myParams['fast_pay_validity']           = '1001';
//        $myParams['fast_pay_cvv2']           = '123';
        /* 收银台快捷会自动根据卡bin判断，以下的值无需传入*/
//        $myParams['bank_type']           = '1041000';
//        $myParams['support_card_type']           = 'debit';
//        $myParams['bank_account_type']           = 'personal';

//        网银直连需添加以下参数
//        $myParams['pay_mode']           = 'internetbank';
//        $myParams['bank_type']           = '1021000';
//        $myParams['bank_account_type']           = 'personal';
//        $myParams['support_card_type']           = 'debit';

        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $action = $this->common->param['order_url'];
        $def_url = "<br /><form style='text-align:center;' id='Pay' method=post action='" . $action . "' target='_blank'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
        $def_url .= "<input type=submit id=Pay value='点击提交' " . @$GLOBALS['_LANG']['pay_button'] . "'>";
//        $def_url .= '<script>window.onload= function(){document.getElementById("Pay").submit();}</script>';
        $def_url .= "</form>";

        return $def_url;
    }
}

$pay = new pay_demo();