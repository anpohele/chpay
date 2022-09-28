<?php
$mid='898310148160568';
$tid='88880001';
$md5key='fcAmtnx7MwismjWNhNKdHC44mNXtnEQeJkRrhKJwyrW2ysRR';
//业务内容
$time=time();
$msgSrcId='3194';
$content=[
'mid'=>$mid,
'tid'=>$tid,
'msgType'=>'trade.h5Pay',
'msgSrc'=>'WWW.TEST.COM',
'instMid'=>'H5DEFAULT',
'merOrderId'=>$msgSrcId.date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),//商户订单号
'totalAmount'=>1,//支付总金额
'requestTimestamp'=>date('Y-m-d H:i:s',$time),//报文请求时间
'notifyUrl'=>'',//支付通知地址
'returnUrl'=>'',//网页跳转地址
'signType'=>'SHA256'
];
$contents=json_encode($content);

//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
$options = '';
foreach ($content as $key =>  $channelalue) {
  $options.= $key . '=' . $channelalue .'&';
}

// echo json_encode($options);die;
$text=$options.$md5key;
//sha256加密并且所有字母大写

$content['sign']=strtoupper(hash("sha256",$text));
// echo "<pre>";print_r($content);die;
//拼接参数
$options = '';
foreach ($content as $key =>  $channelalue) {
  $options.= $key . '=' . $channelalue .'&';
}

$url="https://qr.chinaums.com/netpay-portal/webpay/pay.do?".urlencode($options);
// Header("Location: $url");exit();
echo "<pre>";print_r($url);die;
?>