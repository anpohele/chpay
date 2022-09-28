<?php

namespace JytPay\Client;

// echo(123);die;
include_once  dirname(__FILE__). "/sdk/JytJsonClient.php";
header("Content-Type:text/html;   charset=utf-8");
ini_set("display_errors", "On");
error_reporting(E_ALL | E_STRICT);

$client = new JytJsonClient;
// echo'<pre>';
// print_r($_GET);
// print_r($order);
// die();
$client->init();

    $externalIp = getIp();
    $data['head']['version']='1.0.0';
    $data['head']['tranType']='01';
    $data['head']['merchantId']= $client->config->merchant_id;
    $data['head']['tranTime']=date('YmdHis',time());
    $data['head']['tranFlowid']= $client->config->merchant_id . date('YmdHis',time()) . substr(rand(),4);
    $data['head']['tranCode']= 'TM1001';
    // $data['body']['payChannel']= '01';//00 微信   01支付宝
    // $data['body']['payMode']= '05';
    // $data['body']['totalAmt']= number_format($_GET['money'],2);
    // $data['body']['subject']= 'EN'.date("YmdHis").rand(10000000, 99999999);
    // $data['body']['orderId']= $order['out_trade_no'];
    // $data['body']['notifyUrl']= 'http://pay.anpo.cc/plugins/jyt/notify.php';
    // $data['body']['spbillCreatIp']= $externalIp;
    // $data['body']['billMerchantId']= getNumber();
    $data['body']['picFile']= '/www/wwwroot/pay.anpo.cc/chpay/plugins/jyt/WechatIMG31.jpeg';
    
    // echo('<pre>'); print_r($data);die;
    // $data['body']['billMerchantId']= '2088310139256280';
    $res = $client->sendReq($data);
    var_dump($res);
    die();
    if($res){
        $url=json_decode($res,true)['body']['urlScheme'];
        header("Location: $url");
    }else{
        echo('支付失败');
    }
    



function getNumber(){
    
$a=array(
"2088310139256280"=>"如皋市喜芸小吃店",
"2088310140198104"=>"涟水县台涟北小站小吃店",
"2088310140683372"=>"柴桑区鸿兴烟酒行",
"2088310141647188"=>"都昌县刘肖电脑店",
"2088310140019490"=>"安福县兴新平价商店",
"2088310139137884"=>"高安市明华建材销售部",
"2088310140918665"=>"横峰县齐盛许大荣农家店",
"2088310139442740"=>"辛集市慧林烟酒店",
"2088310141826008"=>"万载县清捕密百货店",
"2088310179309959"=>"吉洪家具",
"2088310177980154"=>"发新家具",
"2088310175448281"=>"瑞凯家具",
"2088310174417592"=>"禄成耀家具",
"2088310175998320"=>"久辉寿五金",
"2088310177774126"=>"万同百货",
"2088310178976035"=>"顺巨通商贸",
"2088310177107997"=>"本进五金",
"2088310179093647"=>"华汇五金",
"2088310177019889"=>"裕欣隆五金",
"2088310178726135"=>"安巨五金",
"2088310179123005"=>"全元源百货",
"2088310178774587"=>"阳俊服装",
"2088310179394874"=>"霏珀丽百货"
);
    return array_rand($a,1);

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
