<?php
if(!defined('IN_PLUGIN'))exit();
// echo "<pre>";print_r();die;
$appId = $channel['appid'];
$appKey = $channel['appkey'];
$mid = $channel['appmchid'];
$tid = $channel['appsecret'];

//业务内容
$time = time();
$content = [
  'requestTimestamp' =>  date('Y-m-d H:i:s', $time),//报文请求时间
  'merOrderId' =>  $_GET['out_trade_no'],//商户订单号
  'mid' =>  $mid,//商户号
  'tid' =>  $tid,//终端号
  'instMid' =>  'H5DEFAULT',//业务类型
  'totalAmount' =>  $_GET['money']*100,//支付总金额
  'expireTime' =>  date('Y-m-d H:i:s', strtotime('+1 day', $time)),//过期时间
  'notifyUrl' =>  'https://pay.fairydeed.com/plugins/unionpayh5/notify.php',//支付通知地址
  'returnUrl' =>  $_GET['return_url']//网页跳转地址
];
// echo "<pre>";print_r($content);die;
$timestamp = date('YmdHis', $time);
//随机数
$str = md5(uniqid(mt_rand(), true));
$uuid = substr($str, 0, 8) . '-';
$uuid .= substr($str, 8, 4) . '-';
$uuid .= substr($str, 12, 4) . '-';
$uuid .= substr($str, 16, 4) . '-';
$uuid .= substr($str, 20, 12);
$nonce = $uuid;
//签名
$hash = bin2hex(hash('sha256', json_encode($content), true));
$hashStr = $appId . $timestamp . $nonce . $hash;
$signature = base64_encode((hash_hmac('sha256', $hashStr, $appKey, true))); //$appKey银联商户H5支付产品的AppKey
$data = [
  'timestamp' =>  $timestamp,//时间戳
  'authorization' =>  'OPEN-FORM-PARAM',//认证方式
  'appId' =>  $appId,//APPID
  'nonce' =>  $nonce,//随机数
  'content' =>  urlencode(json_encode($content)),//业务内容
  'signature' =>  urlencode($signature),//签名
];
//接口返回信息
//支付宝：http://58.247.0.18:29015/v1/netpay/trade/h5-pay
//银联在线无卡：http://58.247.0.18:29015/v1/netpay/qmf/h5-pay
//银联：http://58.247.0.18:29015/v1/netpay/uac/order
$options = '';
foreach ($data as $key =>  $channelalue) {
  $options.= $key . '=' . $channelalue .'&';
}
$options = rtrim($options, '&');
//存在转义字符，那么去掉转义
// if(get_magic_quotes_gpc()){
//   $options = stripslashes($options);
// }

 $file  = '/www/wwwroot/www.chpay.com/chpay/plugins/unionpayh5/Logs/'.$content['merOrderId'].'.txt';
    //要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    
$content = $_GET['notify_url'];
if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            // echo "写入成功。<br />";
}else{
        // echo $file;die;
}
    // die;
$url = 'http://58.247.0.18:29015/v1/netpay/trade/h5-pay?' . $options;
Header("Location: $url");exit();
?>