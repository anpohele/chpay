<?php

include "../demo.php";

/**
 * 订单
 */
class order_demo
{

    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 说明 订单查询接口
     *
     * @param $order 订单号
     * @param $order_no 交易流水号
     */
    function order_query($order, $order_no)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.trade.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';

        $biz_content_arr = array(
            "out_trade_no" => $order,
            "trade_no" => $order_no
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
        $ch = curl_init($this->common->param['order_query_url']);
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
            $data = json_encode($response['ysepay_online_trade_query_response'], JSON_PRESERVE_ZERO_FRACTION);
            $data = $this->common->arrayToString($data);
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
     * 通用订单查询接口
     */
    function common_order_query()
    {
        $myParams = array();
        $myParams['charset'] = 'utf-8';
        $myParams['method'] = 'ysepay.online.trade.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $biz_content = array(
            "out_trade_no" => "20190124094240"
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
        $ch = curl_init($this->common->param['order_query_url']);
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
            $data = json_encode($response['ysepay_online_trade_query_response'], JSON_PRESERVE_ZERO_FRACTION);
            $data = $this->common->arrayToString($data);
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
     * 订单退款接口
     *
     * @param $out_trade_no 订单号
     * @param $trade_no 交易流水号
     * @param $refund_amount 退款金额
     * @param $refund_reason 退款缘由
     */
    function order_refund($out_trade_no, $trade_no, $refund_amount, $refund_reason)
    {

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.trade.refund';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';

        $biz_content_arr = array(
            "out_trade_no" => $out_trade_no,
            "trade_no" => $trade_no,
            "refund_amount" => $refund_amount,
            "refund_reason" => $refund_reason,
            "out_request_no" => 'RD' . $this->common->datetime2string(date('Y-m-d H:i:s'))
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
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_trade_refund_response');
    }


    /**
     * 退款查询接口
     *
     * @param $out_trade_no 订单号
     * @param $trade_no 交易流水号
     * @param $out_request_no 退款订单号
     */
    function order_refund_query($out_trade_no, $trade_no, $out_request_no)
    {

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.trade.refund.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';

        $biz_content_arr = array(
            "out_trade_no" => $out_trade_no,
            "trade_no" => $trade_no,
            "out_request_no" => $out_request_no
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
        $this->common->post_url($this->common->param['order_url'], $myParams, 'ysepay_online_trade_refund_query_response');
    }

    /**
     * 对账单下载
     */
    function bill_down()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.bill.downloadurl.get';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $biz_content = array(
            "account_date" => "2019-01-24"
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
            $data = json_encode($response['ysepay_online_bill_downloadurl_get_response'], JSON_UNESCAPED_SLASHES);
            var_dump($data);
            /* 验证签名 仅作基础验证*/
            if ($this->common->sign_check($sign, $data) == true) {
                echo "验证签名成功!";
            } else {
                echo '验证签名失败!';
            }
        }
    }
}
$order = new order_demo();