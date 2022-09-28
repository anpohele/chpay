<?php
//获取返回信息
$data=serialize($_POST);

$contents=unserialize($data);
//json字符串转数组获取订单号
$contents=json_decode($contents['data'],true);
    // $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs.txt";
    // $content = $data.'数据修改成功';
    // if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
    //     echo "写入成功。<br />";
    // }
//    die;
//获取异步地址
$filename = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs/".$contents['order_no'].".txt";

$handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
//通过filesize获得文件大小，将整个文件一下子读到一个字符串中
$notify_url = fread($handle, filesize ($filename));
fclose($handle);

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
// $con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
// if($con->connect_error){
//     die("连接数据库失败!".$con->connect_error);
// }
// $sql = "select * from pay_channel where plugin='adapay'";

//拼接,异步回调
$url=$notify_url."?created_time=".$contents['created_time']."&end_time=".$contents['end_time']."&order_no=".$contents['order_no']."&out_trans_id=".$contents['out_trans_id']."&party_order_id=".$contents['party_order_id']."&status=".$contents['status'];

$data = file_get_contents($url);
if($data){
    //日志记录支付
    $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay1/Logs/all_order.txt";
    $content = $url;
    if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
        echo "写入成功。<br />";
    }
}

die;


