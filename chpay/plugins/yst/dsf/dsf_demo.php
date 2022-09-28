<?php

include "../demo.php";

/**
 * 代收付
 */
class dsf_demo
{

    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 批量代付明细接口（银行卡）
     */
    function batch_df_query()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.df.batch.detail.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $biz_content = array(
            "out_batch_no" => "F20170920204652Y",
            "shopdate" => "20170920",
            "out_trade_no" => "20170920204652"
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
        $this->common->post_url('https://searchdf.ysepay.com/gateway.do', $myParams, 'ysepay_df_batch_detail_query_response');
    }

    /**
     * 批量代付接口（银行卡）
     *
     * @param $order 订单号
     * @return
     */
    function batch_df($order)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.df.batch.normal.accept';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "out_batch_no" => "F" . $this->common->datetime2string(date('Y-m-d H:i:s')) . "Y",
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "total_num" => "1",
            "total_amount" => "1.5",
            "business_code" => "01000009",
            "currency" => "CNY",
            "detail_data" => array([
                "out_trade_no" => "$order",
                "amount" => "1.5",
                "subject" => "订单说明",
                "bank_name" => "中国银行深圳民治支行",
                "bank_province" => "广东省",
                "bank_city" => "深圳市",
                "bank_account_no" => "1111111111111111",
                "bank_account_name" => "李四",
                "bank_account_type" => "personal",
                "bank_card_type" => "credit",
            ])
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
        $this->common->post_url('https://batchdf.ysepay.com/gateway.do', $myParams, 'ysepay_df_batch_normal_accept_response');
    }


    /**
     * 批量代收接口（银行卡）
     *
     * @param $order 订单号
     * @param no 付款方证件号码
     * @return
     */
    function batch_ds($order, $no)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.ds.batch.normal.accept';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "out_batch_no" => "S" . $this->common->datetime2string(date('Y-m-d H:i:s')) . "Y",
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "total_num" => "1",
            "total_amount" => "1.5",
            "business_code" => "1010015",
            "currency" => "CNY",
            "detail_data" => array([
                "out_trade_no" => "$order",
                "amount" => "1.5",
                "subject" => "订单说明",
                "bank_name" => "中国银行深圳民治支行",
                "bank_province" => "广东省",
                "bank_city" => "深圳市",
                "bank_account_no" => "1111111111111111",
                "bank_account_name" => "李四",
                "bank_account_type" => "personal",
                "bank_card_type" => "credit",
                "bank_telephone_no" => "18620222011",
                "cert_type" => '00',
                "cert_no" => $no
            ])
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
        $this->common->post_url('https://batchds.ysepay.com/gateway.do', $myParams, 'ysepay_ds_batch_normal_accept_response');
    }


    /**
     * 说明:单笔代付加急接口
     *
     * @param $order 订单号
     * @return
     */
    function df_expedited($order)
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.df.single.quick.accept';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['extra_common_param'] = '同步参数测试';
        $biz_content_arr = array(
            "out_trade_no" => "$order",
//            "business_code" => "21000009",
            "business_code" => "01000009",
            "currency" => "CNY",
            "total_amount" => "10",
            "subject" => "测试",
            "bank_name" => "工商银行深圳支行",
            "bank_city" => "深圳市",
            "bank_account_no" => "9000101782233747700",
            "bank_account_name" => "工行",
            "bank_account_type" => "personal",
            "bank_card_type" => "debit"
        );
        $myParams['biz_content'] = json_encode($biz_content_arr, JSON_UNESCAPED_UNICODE);//构造字符串
        var_dump($myParams);
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['df_url'], $myParams, 'ysepay_df_single_quick_accept_response');
    }
}
$dsf = new dsf_demo();