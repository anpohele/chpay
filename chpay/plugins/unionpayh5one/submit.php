<?php
//测试号相关信息
header('Content-Type:text/html; charset=utf-8');
if(!defined('IN_PLUGIN'))exit();
$mdKey = $channel['appkey'];
$mid = $channel['appmchid'];
$tid = $channel['appsecret'];
$msgSrcId=$channel['appid'];
$msgSrc=$channel['appurl'];
// $mid = '89831014816019A';
// $tid = '20159023';



$time = time();
$requestTimestamp = date('Y-m-d H:i:s',$time);
$merOrderId = $msgSrcId.date('YmdHis') . mt_rand(100000, 999999);
$msgType = 'trade.h5Pay';
$data = [
  'instMid' => 'H5DEFAULT',//业务类型
  'merOrderId' => $msgSrcId.$_GET['out_trade_no'],//订单号
  'mid' => $mid,//商户号
  'msgSrc' => $msgSrc,//消息来源
  'msgType' => $msgType,//消息类型
  'orderDesc' => '元器件订单'.substr($msgSrcId.$_GET['out_trade_no'],4,30),//订单描述
  'requestTimestamp' => $requestTimestamp,//报文请求时间
  'tid' => $tid,//终端号
  'signType'=> 'SHA256',
  'totalAmount' => $_GET['money']*100,//支付金额
  'notifyUrl' => 'https://pay.fairydeed.com/plugins/unionpayh5/notify.php',
  'returnUrl' => $_GET['return_url'],
];
$order=$data['merOrderId'];
$con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}

// $updsql = "UPDATE pay_order set out_trade_no='".$order."' WHERE out_trade_no='".$_GET['out_trade_no']."'";
//     $result = $con->query($updsql);
// echo "<pre>";print_r($data);die;
ksort($data);
reset($data);
$options = '';
foreach ($data as $key => $value) {
  $options .= $key . '=' . $value .'&';
}
$options = rtrim($options, '&');
//存在转义字符，那么去掉转义
if(get_magic_quotes_gpc()){
  $options = stripslashes($options);
}

$sign=strtoupper(hash("sha256",$options.$mdKey));
$options .= '&sign=' . urlencode($sign);
$file  = '/www/wwwroot/www.chpay.com/chpay/plugins/unionpayh5/Logs/'.$_GET['out_trade_no'].'.txt';
    //要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    // echo "<pre>";print_r($data);die;
$content = "下单时间：".date('Y-m-d H:i:s')."\r\n";
if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            // echo "写入成功。<br />";
}else{
        // echo $file;die;
}
//测试
// $url = 'https://qr-test2.chinaums.com/netpay-portal/webpay/pay.do?'.$options;
//线上
$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$options;
// echo($url);die;
header("Location: $url");

?>