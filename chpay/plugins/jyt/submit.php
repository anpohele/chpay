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
    $data['head']['tranCode']= 'OP1001';
    $data['body']['payChannel']= '01';//00 微信   01支付宝
    $data['body']['payMode']= '05';
    $data['body']['totalAmt']= number_format($order['realmoney'],2);
    $data['body']['subject']= 'EN'.date("YmdHis").rand(10000000, 99999999);
    $data['body']['orderId']= $order['out_trade_no'];
    $data['body']['notifyUrl']= 'http://jianye.dickmorley.cn/plugins/jyt/notify.php';
    $data['body']['spbillCreatIp']= $externalIp;
    $data['body']['billFlag']= '01';
    // $data['body']['busType']= '02';//新模式
    // $data['body']['billMerchantId']= array_keys($a)[$shopId];
    
    //判断该ip今天是否支付超过10次，超过10次拒绝支付
    $con = mysqli_connect("127.0.0.1","chpay","MffKEhrNnjPCPRCH","chpay");
    if($con->connect_error){
        die("连接数据库失败!".$con->connect_error);
    }
    $sql = 'select * from pay_order where to_days(addtime) = to_days(now()) and ip='."'"."$externalIp"."'";
    // echo($sql);die;
    $resMysql = $con->query($sql);
    $resMysql = mysqli_fetch_all($resMysql);
    // print_r($resMysql);die;
    if(count($resMysql) >15){
        echo '你的账号当日交易金额已超限';die;
    }
    
    
    $sql = 'SELECT * FROM pay_order WHERE TIMESTAMPDIFF(SECOND, addtime, NOW()) <= 1*60*60 and ip='."'"."$externalIp"."'";
    // echo $sql;die;
    
    $resMysql = $con->query($sql);
    $resMysql = mysqli_fetch_all($resMysql);
    if(count($resMysql) >3){
        echo '你的账号交易频次超限，请1小时后再试';die;
    }
    
    // echo('<pre>'); print_r($data);die;
    // $data['body']['billMerchantId']= '2088310139256280';
    $res = $client->sendReq($data);
    if($res){
        // $url=urldecode(json_decode($res,true)['body']['urlScheme']);//新模式
        $url=json_decode($res,true)['body']['urlScheme'];//老模式
        header("Location: $url");
    }else{
        echo('支付失败');
    }
    

function getWXNumber(){
    
$a=array(
"231408217"=>"万载县清捕密百货店",
"231185668"=>"万载县媚粒维百货店",

);
    return array_rand($a,1);

}

function random_tranCode(){
    $client = new JytJsonClient;
    return "TD".$client->randomkeys(4); //自己设置，唯一即可
}

function random_order_id(){
    $client = new JytJsonClient;
    return "D".$client->randomkeys(15); //自己设置，唯一即可
}

function random_cust_id(){
    $client = new JytJsonClient;
    return $client->randomkeys(10); //自己设置，唯一即可
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
