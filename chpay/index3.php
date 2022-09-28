<?php
//测试号相关信息
header('Content-Type:text/html; charset=utf-8');
$mdKey = 'BBcb8Mzr7hk4wjYMYTbS6T6DxrshkRmFihrT2Q4RxTpQMjkc';
$mid = '89831014816019A';
$tid = '20159023';
// $mid = '89831014816019A';
// $tid = '20159023';



$time = time();
$requestTimestamp = date('Y-m-d H:i:s',$time);
$merOrderId = '11UP'.date('YmdHis') . mt_rand(100000, 999999);
$msgType = 'trade.h5Pay';
$data = [
  'notifyUrl' => 'http://172.27.49.240:8080/h5pay/notifyUrl.do',
  'instMid' => 'H5DEFAULT',//业务类型
  'merOrderId' => $merOrderId,//订单号
  'mid' => $mid,//商户号
  'msgSrc' => 'WWW.SHKQWLKJ.COM',//消息来源
  'msgType' => $msgType,//消息类型
  'orderDesc' => '充值缴费',//订单描述
  'requestTimestamp' => $requestTimestamp,//报文请求时间
  'tid' => $tid,//终端号
  'signType'=> 'SHA256',
  'totalAmount' => '100',//支付金额
  'returnUrl' => 'http://172.27.49.240:8080/h5pay/returnUrl.do',
];
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
$url = 'https://qr.chinaums.com/netpay-portal/webpay/pay.do?'.$options;

header("Location: $url");

?>