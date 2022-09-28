<?php
//获取返回信息
$data=serialize($_POST);
// echo 123;die;
// $data = 'a:18:{s:11:"notify_time";s:19:"2021-11-19 12:47:56";s:9:"payee_fee";s:4:"0.00";s:12:"account_date";s:8:"20211119";s:3:"fee";s:4:"0.00";s:15:"channel_send_sn";s:19:"1012111194063449311";s:4:"sign";s:172:"aXqRt6Tho+qA10TO4uPhCzmTXeIH4fxgk/45mFS5K1bWDDo+eKZoUriH3b3YomSIJvJjiPTc+sADDGA1YcN4qIIVsrSo3u5tWSwTPSbfJGAX4dR4rruZ3VJvlJqcLb4jCq26ckoyakDxVRWVTVMjaiIIkLwwcBV/G1HMa+OLeyA=";s:9:"card_type";s:0:"";s:11:"notify_type";s:21:"directpay.status.sync";s:11:"partner_fee";s:4:"0.00";s:12:"out_trade_no";s:25:"JY_2021111912473678220016";s:12:"total_amount";s:4:"0.10";s:12:"trade_status";s:13:"TRADE_SUCCESS";s:8:"trade_no";s:18:"01O211119406344931";s:17:"settlement_amount";s:4:"0.10";s:10:"paygate_no";s:9:"900000001";s:9:"payer_fee";s:4:"0.00";s:15:"channel_recv_sn";s:30:"612021111922001425221424015372";s:9:"sign_type";s:3:"RSA";}数据修改成功a:18:{s:11:"notify_time";s:19:"2021-11-19 12:48:07";s:9:"payee_fee";s:4:"0.00";s:12:"account_date";s:8:"20211119";s:3:"fee";s:4:"0.00";s:15:"channel_send_sn";s:19:"1012111194063449311";s:4:"sign";s:172:"Ug1GRIByrIQb5BjH2xFZVJ/nSeCJSNO43GzWo3q9GHXn2lWP8eyVqp4r2TvpepkeDza2GywqH4PO9YRUzOrYJSBfSlqX0OeuZPoAFMtpozcNz0c2O/2Jr2RbxDahDDkI7C4ucVg6OJNTIzN7IXEO10mGz630O3o+wjFOWDqicMU=";s:9:"card_type";s:0:"";s:11:"notify_type";s:21:"directpay.status.sync";s:11:"partner_fee";s:4:"0.00";s:12:"out_trade_no";s:25:"JY_2021111912473678220016";s:12:"total_amount";s:4:"0.10";s:12:"trade_status";s:13:"TRADE_SUCCESS";s:8:"trade_no";s:18:"01O211119406344931";s:17:"settlement_amount";s:4:"0.10";s:10:"paygate_no";s:9:"900000001";s:9:"payer_fee";s:4:"0.00";s:15:"channel_recv_sn";s:30:"612021111922001425221424015372";s:9:"sign_type";s:3:"RSA";}';


//日志记录支付
    //     $file  = "/www/wwwroot/pay.jianyekeji.cc/chpay/plugins/yst/Logs.txt";
    // $content = $data.'数据修改成功';
    // if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
    //     echo "写入成功。<br />";
    // }
    // die;
$contents=unserialize($data);
// echo '<pre>';
// print_r($contents);


    $con = mysqli_connect("127.0.0.1","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}

$sql = "UPDATE pay_order set status='1' WHERE out_trade_no='".$contents['out_trade_no']."'";
$result = $con->query($sql);
if($result){
    $updsql = "UPDATE pay_order set endtime='".date('Y-m-d H:i:s')."',date='".date('Y-m-d H:i:s')."' WHERE out_trade_no='".$contents['out_trade_no']."'";
    $result = $con->query($updsql);
}

$resultMysql = mysqli_query($con,"select * from pay_order where out_trade_no='".$contents['out_trade_no']."'");
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
        'type' => 'yst'
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
 $url=$notify_url."?money=".$realmoney."&name=".$resultMysql['name']."&out_trade_no=".$resultMysql['out_trade_no']."&pid=".$resultMysql['uid']."&trade_no=".$resultMysql['trade_no']."&trade_status=TRADE_SUCCESS"."&type=yst&sign=".$sign."&sign_type=MD5";
// print_r($url);die;
// $url = 'https://www.baidu.com';
if($contents['trade_status'] == 'TRADE_SUCCESS'){
    $data = @file_get_contents($url);
     if($data){
        //日志记录支付
        $file  = "/www/wwwroot/ys.dickmorley.cn/chpay/plugins/yst/all_order.txt";
        $content = $url;
        
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            echo "success";  
        }
    }
}else {
    echo "fail";
}