<?php

namespace JytPay\Client;


include_once  dirname(__FILE__). "/sdk/JytJsonClient.php";
header("Content-Type:text/html;   charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

$client = new JytJsonClient;

$client->init();

    $externalIp = getIp();
    $data['head']['version']='1.0.1';
    $data['head']['tranType']='01';
    $data['head']['merchantId']= $client->config->merchant_id;
    $data['head']['tranTime']=date('YmdHis',time());
    $data['head']['tranFlowid']= $client->config->merchant_id . date('YmdHis',time()) . substr(rand(),4);
    $data['head']['tranCode']= 'OP1003';
    //原订单号
    $data['body']['oriMerOrderId']= 'ALH5ALI202108231542471143335516';  
    //商户退款流水号
    $data['body']['merRefundFlowId']= 'TKAP'.date("YmdHis").rand(10000000, 99999999);  
    //退款金额
    $data['body']['refundAmt']= number_format($order['realmoney'],2);  
    //退款渠道
    $data['body']['refundChannel']= $order['out_trade_no'];


    
    // echo('<pre>'); print_r($data);die;
    // $data['body']['billMerchantId']= '2088310139256280';
    $res = $client->sendReq($data);
    var_dump($res);die;
    if($res){
        $url=json_decode($res,true)['body']['urlScheme'];
        header("Location: $url");
    }else{
        echo('退款失败');
    }



function getIp()
{ //取IP函数
    static $realip;
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $realip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } else {
            $realip = getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR');
        }
    }
    return $realip;
}
