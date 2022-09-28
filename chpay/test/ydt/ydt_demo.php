<?php

include "../demo.php";

/**
 * 银贷通
 */
class ydt_demo
{

    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 银贷通付款接口
     */
    function ydt_df()
    {
        $myParams = array();
        $myParams['interface_name'] = 'pay.remittransfer.single.accept';
        $myParams['merchant_code'] = $this->common->param['merchant_code'];
        $myParams['quest_time'] = date('Y-m-d H:i:s', time());
        $myParams['sign_type'] = 'RSA';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $myParams["quest_no"] = $this->common->datetime2string(date('Y-m-d H:i:s:ss:s'));
        $myParams["bind_card_id"] = "20180104094521130104193050771541";
        $myParams["userid"] = 978836843;
        $myParams["order_amount"] = "1.00";
        $myParams['subject'] = 'utf-8';
        $myParams['principal_interest'] = '10.00';
        $myParams['principal'] = 100.00;
        $myParams['Periods'] = 1;
        $myParams['agreement_no'] = $this->common->datetime2string(date('Y-m-d H:i:s:ss:s'));;
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
        $ch = curl_init($this->common->param['ydt_url_df']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($myParams));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        var_dump($response);
        $file = BASE_PATH . "log/ok.txt";
        file_put_contents($file, "\r\n", FILE_APPEND);
        file_put_contents($file, "|notify|:" . $response, FILE_APPEND);
        if (curl_errno($ch)) {
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
            $sign = $response['sign'];
            unset($response['sign']);
            $data = json_encode($response, 320);
            var_dump('data:' . $data);
            var_dump('sign:' . $sign);
            /* 验证签名 仅作基础验证*/
            if ($this->common->sign_check($sign, $data) == true) {
                echo "验证签名成功!";
            } else {
                echo '验证签名失败!';
            }
        }
    }


    /**
     * 银贷通绑卡接口
     */
    function get_ydt()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['interface_name'] = 'pay.binding.single.acept';
        $myParams['merchant_code'] = $this->common->param['merchant_code'];
        $myParams['sign_type'] = 'RSA';
        $myParams['quest_time'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $myParams["quest_no"] = $this->common->datetime2string(date('Y-m-d H:i:s:ss:s'));
        $myParams["userid"] = mt_rand();
        $myParams["user_name"] = "取个名字好难";
        $myParams["idcard_no"] = "370705197804099954";
        $myParams["bank_name"] = "工商银行深圳支行";
        $myParams["card_type"] = "debit";
        $myParams["card_no"] = "900000782233747701";
        $myParams["mobile"] = "15173143940";
        $myParams["subject"] = "personal";
        $myParams["bank_province"] = "广东省";
        $myParams["bank_city"] = "深圳市";
        $myParams["bank_type"] = "1021000";

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
        $ch = curl_init($this->common->param['ydt_url']);
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
            unset($response['sign']);
            $data = json_encode($response, 320);
            var_dump('data:' . $data);
            var_dump('sign:' . $sign);
            /* 验证签名 仅作基础验证*/
            if ($this->common->sign_check($sign, $data) == true) {
                echo "验证签名成功!";
            } else {
                echo '验证签名失败!';
            }
        }
    }
}

$ydt = new ydt_demo();