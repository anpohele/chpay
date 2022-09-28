<?php
//include __DIR__.'/../pay/demo.php';


//$appSecret = 'vK8I6hRI1X6P5Cb78ICV3V2f9h5KYuf79VDVG8781uu1VK724U';
$myParams = array();
$myParams['appSecret'] = 'vK8I6hRI1X6P5Cb78ICV3V2f9h5KYuf79VDVG8781uu1VK724U';
$myParams['appUserCode'] = 'APHL'.date('YmdHis');
$myParams['userType'] = '00';
$myParams['accessTerminal'] = '01';
$myParams['name'] = '孙颢玮';
$myParams['certifiType'] = '00';
$myParams['certifiNo'] = '410411200105285529';
$url = 'https://yzt.ysepay.com:8443/api/applyAccount';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $myParams);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
//curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
var_dump($response);
//if (curl_errno($ch)) {
//    var_dump($ch);
//} else {
//    $response = json_decode($response, true);
//    var_dump($response);
//    $sign = $response['sign'];
//    echo $sign;
//    $data = json_encode($response['ysepay_online_qrcodepay_response'], 320);
//    var_dump($data);
//    /* 验证签名 仅作基础验证*/
//    if ($this->common->sign_check($sign, $data) == true) {
//        echo "验证签名成功!";
//    } else {
//        echo '验证签名失败!';
//    }
//}