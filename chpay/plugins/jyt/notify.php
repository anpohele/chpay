<?php
namespace JytPay\Client;
include_once  dirname(__FILE__). "/sdk/JytJsonClient.php";
$data = $_POST;
// $data = unserialize($data);
// print_r($data);die;
$client = new JytJsonClient;
$client->init();
$data['msg_enc'] = $data['xml_enc'];
$contents =json_decode($client->parserRes($data),true);
$con = mysqli_connect("127.0.0.1","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}

$sql = "UPDATE pay_order set status='1' WHERE out_trade_no='".$contents['body']['merOrderId']."'";
$result = $con->query($sql);
if($result){
    $updsql = "UPDATE pay_order set api_trade_no='".$contents['out_trans_id']."',endtime='".date('Y-m-d H:i:s')."',buyer='".$contents['party_order_id']."',date='".date('Y-m-d H:i:s')."' WHERE out_trade_no='".$contents['order_no']."'";
    $result = $con->query($updsql);
}

$resultMysql = mysqli_query($con,"select * from pay_order where out_trade_no='".$contents['body']['merOrderId']."'");
$resultMysql = $resultMysql->fetch_array();
$notify_url = $resultMysql['notify_url'];
$realmoney = floatval($resultMysql['money']);
$queryArr=[
        'money' => $realmoney,
        'name' => $resultMysql['name'],
        'out_trade_no' => $resultMysql['out_trade_no'],
        'pid' => $resultMysql['uid'],
        'trade_no' => $resultMysql['trade_no'],
        'trade_status' => 'TRADE_SUCCESS',
        'type' => 'jyt'
    ];
    $pid = $resultMysql['uid'];        
//paraFilter
    $para_filter = array();
    foreach ($queryArr as $key=>$val) {
        $para_filter[$key] = $queryArr[$key];
    }
//argSort
    ksort($para_filter);
    reset($para_filter);
    
//createLinkstring
    $arg  = "";
    foreach ($para_filter as $key=>$val) {
        $arg.=$key."=".$val."&";
    }
    
        //查询key
    $sql = "select * from pay_user where uid='".$pid."'";
    $result = $con->query($sql);
    foreach($result as $k => $v){
        $md5key=$v['key'];
    }
    
//去掉最后一个&字符
    $arg = substr($arg,0,-1);
    $arg = $arg . $md5key;

//生成签名
    $sign = md5($arg);
    
    //
    // $money = floatval($resultMysql['money']);
    
//拼接,异步回调
 $url=$notify_url."?money=".$realmoney."&name=".$resultMysql['name']."&out_trade_no=".$resultMysql['out_trade_no']."&pid=".$resultMysql['uid']."&trade_no=".$resultMysql['trade_no']."&trade_status=TRADE_SUCCESS"."&type=jyt&sign=".$sign."&sign_type=MD5";
// print_r($url);die;
// $url = 'https://www.baidu.com';
if($contents['head']['respCode'] == 'S0000000'){
    $data = @file_get_contents($url);
     if($data){
        //日志记录支付
        $file  = "/www/wwwroot/pay.jianyekeji.cc/chpay/plugins/jyt/all_order.txt";
        $content = $url;
        
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            echo "success";  
        }
        
    }
}else {
    echo "fail";
}

    


