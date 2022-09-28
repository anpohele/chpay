<?php
//获取返回信息
$data=serialize($_POST);

$contents=unserialize($data);
//json字符串转数组获取订单号
$contents=json_decode($contents['data'],true);

//日志记录支付
        // $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/all_order.txt";
        // // $content = 'ok';
        
        // if($f  = file_put_contents($file, $contents['order_no'],FILE_APPEND)){     // 这个函数支持版本(PHP 5)
        //     // echo "写入成功。<br />";   
        // }
        
    // $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs.txt";
    // $content = $data.'数据修改成功';
    // if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
    //     echo "写入成功。<br />";
    // }
//    die;
//获取异步地址
// $filename = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs/".$contents['order_no'].".txt";

// $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
// //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
// $notify_url = fread($handle, filesize ($filename));
// fclose($handle);

//支付状态变成已完成
$con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}
$sql = "UPDATE pay_order set status='1' WHERE out_trade_no='".$contents['order_no']."'";
$result = $con->query($sql);
if($result){
    $updsql = "UPDATE pay_order set api_trade_no='".$contents['out_trans_id']."',endtime='".date('Y-m-d H:i:s')."',buyer='".$contents['party_order_id']."',date='".date('Y-m-d H:i:s')."' WHERE out_trade_no='".$contents['order_no']."'";
    $result = $con->query($updsql);
}
$resultMysql = mysqli_query($con,"select * from pay_order where out_trade_no='".$contents['order_no']."'");
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
        'type' => 'adapay'
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
 $url=$notify_url."?money=".$realmoney."&name=".$resultMysql['name']."&out_trade_no=".$resultMysql['out_trade_no']."&pid=".$resultMysql['uid']."&trade_no=".$resultMysql['trade_no']."&trade_status=TRADE_SUCCESS"."&type=adapay&sign=".$sign."&sign_type=MD5";
 
 
 if($contents['status'] == 'succeeded'){
    $data = file_get_contents($url);
    if($data){
        //日志记录支付
        $file  = "/www/wwwroot/pay.anpo.cc/chpay/plugins/adapay/all_order.txt";
        $content = $url;
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            echo "success";  
        }
        
    }
}else{
     echo "fail";
}

