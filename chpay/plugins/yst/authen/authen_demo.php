<?php

include "../demo.php";

/**
 * 要素验证
 */
class authen_demo
{
    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 银行卡 三 四要素实名认证
     * 请填写姓名、身份证、卡号
     *
     * @param $no 订单号
     * @return
     */
    function get_authen($no)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.authenticate.four.key.element';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $biz_content = array(
            "out_trade_no" => $no,
            "bank_account_name" => "姓名",
            "bank_account_no" => "卡号",
            "id_card" => "身份证"
        );
        $myParams['biz_content'] = json_encode($biz_content, 320);//构造字符串
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
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_authenticate_four_key_element_response');
    }


    /**
     * 二要素(返照)实名认证
     *
     * @param $no 订单号
     * @param $id 身份证号
     */
    function get_authen_two($no, $id)
    {
        $myParams = array();
        $myParams['method'] = 'ysepay.authenticate.id.card.img';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['charset'] = 'UTF-8';
        $myParams['sign_type'] = 'RSA';
        $myParams['version'] = '3.0';
        $biz_content = array(
            "out_trade_no" => $no,
            "name" => "姓名",
            "id_card" => $id
        );
        $myParams['biz_content'] = json_encode($biz_content, 320);//构造字符串
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
        if (curl_errno($ch)) {
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
            $sign = $response['sign'];
            echo $sign;
            $data = json_encode($response['ysepay_authenticate_id_card_img_response'], 320);
            $this->common->base64toimages($data['archive_img']);
            var_dump($data);
            $file = BASE_PATH . "log/ok.txt";
            if ($this->common->sign_check($sign, $data) == true) {
                file_put_contents($file, "\r\n", FILE_APPEND);
                file_put_contents($file, "Verify success!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
                echo '验证签名成功';
            } else {
                file_put_contents($file, "\r\n", FILE_APPEND);
                file_put_contents($file, "Validation failure!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
                echo '验证签名失败';
            }
        }
    }


    /**
     * 运营商 三要素实名认证
     *
     * @param $no 订单号
     */
    function get_authen_mobile($no)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.authenticate.mobile.operators.three.key.element';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $biz_content = array(
            "out_trade_no" => $no,
            "name" => '姓名',
            "phone" => "手机号",
            "id_card" => $this->common->ECBEncrypt("身份证号", $myParams['partner_id'])
        );
        $myParams['biz_content'] = json_encode($biz_content, 320);//构造字符串
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
        if (curl_errno($ch)) {
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
            $sign = $response['sign'];
            echo $sign;
            $data = json_encode($response['ysepay_authenticate_mobile_operators_three_key_element_response'], 320);
            $data = $this->common->arrayToString($data);
            var_dump($data);
            $file = BASE_PATH . "log/ok.txt";
            if ($this->common->sign_check($sign, $data) == true) {
                file_put_contents($file, "\r\n", FILE_APPEND);
                file_put_contents($file, "Verify success!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
                echo '验证签名成功';
            } else {
                file_put_contents($file, "\r\n", FILE_APPEND);
                file_put_contents($file, "Validation failure!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
            }
        }
    }
}
$authen = new authen_demo();