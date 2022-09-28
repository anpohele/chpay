<?php
header("content-type:text/html;charset=utf-8");
//获取返回信息
$data=serialize($_POST);
// $data = 'a:13:{s:14:"buyUserAccount";s:16:"2088142208840939";s:11:"FundChannel";s:79:"%5B%7B%22amount%22%3A%221.00%22%2C%22fundChannel%22%3A%22ALIPAYACCOUNT%22%7D%5D";s:6:"Amount";s:1:"1";s:8:"SignInfo";s:172:"HwkJjThaEOAk6PwmNyFLfg37TpTX7HPlXNFfA2WrXYsSOD5L/nUk7e1C4Cn0sD/hObnhY10rrQ1yoM5PeCPoLrV3Qx3SGDVrFpM27TnPYIV4J0fIa3lWpmMXLDSk6gpYX2/OYMkBWcFOwdDyeShiXTMAYmAvL0wwgc7KtrdXvpk=";s:7:"OrderNo";s:10:"2605637221";s:7:"PayType";s:19:"AliJsapiPay_OffLine";s:7:"Succeed";s:2:"88";s:6:"Result";s:7:"SUCCESS";s:6:"BillNo";s:23:"yns_2022081816545066761";s:10:"FinishTime";s:14:"20220818165506";s:9:"ChannelNo";s:30:"252022081822001440931447211715";s:5:"MerNo";s:5:"52247";s:6:"BankNo";s:10:"2605637221";}';
// $data=serialize($_POST);
// echo 1111;die;
$contents=unserialize($data); 
// echo '<pre>';
// print_r($contents);die;

$con = mysqli_connect("127.0.0.1","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}

$sql = "UPDATE pay_order set status='1' WHERE out_trade_no='".$contents['BillNo']."'";
$result = $con->query($sql);
if($result){
    $updsql = "UPDATE pay_order set endtime='".date('Y-m-d H:i:s')."',date='".date('Y-m-d H:i:s')."' WHERE out_trade_no='".$contents['BillNo']."'";
    $result = $con->query($updsql);
}

$resultMysql = mysqli_query($con,"select * from pay_order where out_trade_no='".$contents['BillNo']."'");
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
        'type' => 'hc'
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
 $url=$notify_url."?money=".$realmoney."&name=".$resultMysql['name']."&out_trade_no=".$resultMysql['out_trade_no']."&pid=".$resultMysql['uid']."&trade_no=".$resultMysql['trade_no']."&trade_status=TRADE_SUCCESS"."&type=hc&sign=".$sign."&sign_type=MD5";
// print_r($url);die;
// $url = 'https://www.baidu.com';
if($contents['Succeed'] == 88){
    $data = @file_get_contents($url);
     if($data){
        //日志记录支付
        $file  = "/www/wwwroot/ys.dickmorley.cn/chpay/plugins/hc/all_order.txt";
        $content = $url;
        
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            echo "success";  
        }
    }
}else {
    echo "fail";
}
