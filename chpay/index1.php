<?php

header('Content-type:text/html; Charset=utf-8');
//FDPAY商户号
$pid=1000;
// echo(9999);die;
//支付方式	
// $type='adapay';
// $type='jyt';
// $type='yst';
$type='hc';

//获取拜佛传入的总金额
// $total_money = $_GET['total_money'];

//商户订单号
// $out_trade_no=date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
$out_trade_no=date("YmdHis").rand(10000000, 99999999);

//异步通知地址
$notify_url='http://www.xxx.com/alipay/notify.php';
//跳转通知地址	
$return_url='http://www.xxx.com/alipay/return.php';
//商品名称	
$name='HC'.$out_trade_no;
//商品金额
$money=number_format(rand(10,20),0);
// $money=587;
    // $money=0.01;
//网站名称	
$sitename='HC';
//获取公网ip
// $externalContent = file_get_contents('http://checkip.dyndns.com/');
// preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
// $externalIp = $m[1];
$externalIp = '106.15.50.252';

//拼接参数
$datas='notify_url='.$notify_url.'&pid='.$pid.'&type='.$type.'&out_trade_no='.$out_trade_no.'&return_url='.$return_url.'&name='.$name.'&money='.$money.'&sitename='.$sitename.'&ip='.$externalIp;
//进行签名
$queryArr=[
    'notify_url' => $notify_url,
    'pid' => $pid,
    'type' => $type,
    'out_trade_no' => $out_trade_no,
    'return_url' => $return_url,
    'name' => $name,
    'money' => $money,
    'sitename' => $sitename,
    'ip' =>$externalIp,
    'sign' => '4cc1faef7100eb357917b56623e21317',
    'sign_type' => 'MD5'
];

//paraFilter
$para_filter = array();
foreach ($queryArr as $key=>$val) {
	if($key == "sign" || $key == "sign_type" || $val == "")continue;
	else $para_filter[$key] = $queryArr[$key];
}

//argSort
ksort($para_filter);
reset($para_filter);
//createLinkstring
$arg  = "";
foreach ($para_filter as $key=>$val) {
	$arg.=$key."=".$val."&";
}
//去掉最后一个&字符
$arg = substr($arg,0,-1);
//FDPAY商户KEY
$key='Zl033ODLhosF66hdofO6D7jo05d3h762';
$arg = $arg . $key;
//md5加密参数生成sign
$sign = md5($arg);
$url='http://ys.dickmorley.cn/submit.php?'.$datas.'&sign='.$sign.'&sign_type=MD5';
// var_dump($url);die();
header("Location: $url");die;