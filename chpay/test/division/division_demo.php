<?php

include "../demo.php";

/**
 * 分账
 */
class division_demo
{
    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 分账查询
     */
    function division_query()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.single.division.online.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "src_usercode" => $this->common->param['seller_id'],
            "out_batch_no" => "S" . date('YmdHis', time()),
            "out_trade_no" => "20190109141932B2O1",
            "sys_flag" => "DD"
        );
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_common'], $myParams, 'ysepay_single_division_online_query_response');
    }

    /**
     * 分账登记
     */
    function division_accept()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.single.division.online.accept';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];

        $biz_content = array(
            "out_batch_no" => "S" . date('YmdHis', time()),
            "out_trade_no" => "20190109141932B2O1",     //原订单号
            "payee_usercode" => $this->common->param['seller_id'],      //主商户号（原交易收款方）
            "total_amount" => "25.00",
            "is_divistion" => "01",     //原订单是否参与分账01：是，02否
            "is_again_division" => "N",     //是否重新分账Y：是，N：否
            "division_mode" => "02",    //分账模式01 ：比例，02：金额
            "div_list" => array(
                [
                    "division_mer_usercode" => "分账商户号",   //分账商户号
                    "div_amount" => "10.0",      //分账金额
                    "is_chargeFee" => "02"      //是否收取手续费（01：是，02否）
                ],
                [
                    "division_mer_usercode" => "分账商户号",
                    "div_amount" => "15.0",
                    "is_chargeFee" => "01"
                ]
            )
        );
        $myParams['biz_content'] = json_encode($biz_content, JSON_PRESERVE_ZERO_FRACTION);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $this->common->post_url($this->common->param['order_common'], $myParams, 'ysepay_single_division_online_accept_response');
    }

    /**
     * 分账退款
     */
    function division_refund(){

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.trade.refund.split';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "out_trade_no" => '20190116105755O0L1',
            "shopdate" => $this->common->datetime2string(date('Ymd')),
//            "trade_no" => '311160414497667096',
            "refund_amount" => '9.25',
            "refund_reason" => '分账退款交易',
            "out_request_no" => 'RD' . $this->common->datetime2string(date('Y-m-d H:i:s')),
            "is_division" => '01',      //原交易是否参与分账（01或空代表是，02代表否）
//            "is_division" => '02',
            "ori_division_mode" => '02',    //原交易分账模式（01：比例，02：金额）

            "refund_split_info" => array([
                "refund_mer_id" => "退款商户号",
                "refund_amount" => 9.25
            ]),

            "order_div_list" => array(
                [
                    "division_mer_id" => "原订单分账收款方商户号",     //原订单分账收款方商户号
//                "division_amount" => 12.00,
                    "division_ratio" => 0.45,       //原订单分账比例
                    "is_charge_fee" => "02"     //是否收取手续费（01：是，02否）
                ],
                [
                    "division_mer_id" => "原订单分账收款方商户号",
//                "division_amount" => 12.00,
                    "division_ratio" => 0.55,
                    "is_charge_fee" => "01"
                ]
            )
        );
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        echo json_last_error_msg();
        var_dump($myParams);
        $this->common->post_url($this->common->param['division_refund_url'], $myParams, 'ysepay_online_trade_refund_split_response');
    }

    /**
     * 分账退款登记
     */
    function division_refund_enrollment(){

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.online.trade.refund.split.register';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
//        $myParams['tran_type'] = '1';     //交易类型，说明：1或者空：即时到账，2：担保交易
        $biz_content = array(
            "out_trade_no" => '2018112203217141',
            "shopdate" => $this->common->datetime2string(date('Ymd')),
            "trade_no" => '01O1811220268090291',
            "refund_amount" => '28.00',
            "refund_reason" => '交易退款',
            "out_request_no" => 'RD' . $this->common->datetime2string(date('Y-m-d H:i:s'))
        );
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        echo json_last_error_msg();
        var_dump($myParams);
        $this->common->post_url($this->common->param['division_refund_url'], $myParams, 'ysepay_online_trade_refund_split_register_response');
    }
}

$division = new division_demo();