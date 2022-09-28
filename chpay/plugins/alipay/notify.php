<?php
// $data='a:25:{s:10:"gmt_create";s:19:"2021-05-25 13:48:24";s:7:"charset";s:5:"UTF-8";s:12:"seller_email";s:23:"yipinxuanpaimai@163.com";s:7:"subject";s:6:"充值";s:4:"sign";s:344:"Sg4JyPmtXcibFp3hlNf6hMH1bNkErYBLPAgO5aZITnAd9BFFVH1OZnVuSFkzBfj2C6OSGmuwWcIeJVISmzGwpYs3GZW/5NpNdXvaBFwX9FL+F09OdetQ+PBmA/AlUjqT8F/r8u07KmHrpnH4FF7+gp7ALD65RLrinKuJtT5XxGrrFacZLBWHfCzXiIUnC4PCi8uP8XlGCYIR4RDdXL0UzZ5/V4HFy0NaVWLmTelcD6CxTu1TQfibpmK3dPif022WDmhUiaO7rV1kpNZHEJ2dJ+icTifdCF/yaxmsJro8BuKMcKdHj2ADLuw0GPbHvIr5hB6YeFq6CApoLt94zzfsQA==";s:8:"buyer_id";s:16:"2088522253266573";s:14:"invoice_amount";s:4:"1.40";s:9:"notify_id";s:34:"2021052500222134824066571454091938";s:14:"fund_bill_list";s:49:"[{"amount":"1.40","fundChannel":"ALIPAYACCOUNT"}]";s:11:"notify_type";s:17:"trade_status_sync";s:12:"trade_status";s:13:"TRADE_SUCCESS";s:14:"receipt_amount";s:4:"1.40";s:16:"buyer_pay_amount";s:4:"1.40";s:6:"app_id";s:16:"2021002133634863";s:9:"sign_type";s:4:"RSA2";s:9:"seller_id";s:16:"2088141062720153";s:11:"gmt_payment";s:19:"2021-05-25 13:48:24";s:11:"notify_time";s:19:"2021-05-25 13:48:25";s:7:"version";s:3:"1.0";s:12:"out_trade_no";s:19:"2021052513481034487";s:12:"total_amount";s:4:"1.40";s:8:"trade_no";s:28:"2021052522001466571457345577";s:11:"auth_app_id";s:16:"2021002133634863";s:14:"buyer_logon_id";s:11:"176****0605";s:12:"point_amount";s:4:"0.00";}';
$data=serialize($_POST);
$contents=unserialize($data);
// echo "<pre>";print_r($contents);die;
$con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}
$sql = "select * from pay_order where trade_no='".$contents['out_trade_no']."'";
$result = $con->query($sql);
foreach($result as $k => $v){
    $notify_url=$v['notify_url'];
    $out_trade_no=$v['out_trade_no'];
    $status=$v['status'];
    $money=$v['money'];
    $name=$v['name'];
    $trade_no=$v['trade_no'];
    $pid=$v['uid'];
    
}

if($status!=1){
    $sql = "UPDATE pay_order set status='1' WHERE trade_no='".$contents['out_trade_no']."'";
    $result = $con->query($sql);
    if($result){
        $updsql = "UPDATE pay_order set api_trade_no='".$contents['trade_no']."',endtime='".date('Y-m-d H:i:s')."',buyer='".$contents['buyer_logon_id']."',date='".date('Y-m-d H:i:s')."' WHERE trade_no='".$contents['out_trade_no']."'";
        // echo $updsql;die;
        $result = $con->query($updsql);
    }    
    //查询key
    $sql = "select * from pay_user where uid='".$pid."'";
    $result = $con->query($sql);
    foreach($result as $k => $v){
        $md5key=$v['key'];
    }
    
    
    //异步返回参数
    $queryArr=[
        'money' => $money,
        'name' => $name,
        'trade_no' => $trade_no,
        'out_trade_no' => $out_trade_no,
        'trade_status' => $contents['trade_status'],
        'pid' => $pid,
        'type' => 'alipay'
    ];
        
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
    
//去掉最后一个&字符
    $arg = substr($arg,0,-1);
    $arg = $arg . $md5key;
    // echo "<pre>";print_r($arg);die;
    //打印报文
    // $f  = file_put_contents($file, "报文：".$arg,FILE_APPEND);
    
//生成签名
    $sign = md5($arg);
    $url=$notify_url."?money=".$money."&name=".$name."&trade_no=".$trade_no."&out_trade_no=".$out_trade_no."&trade_status=".$contents['trade_status']."&pid=".$pid."&type=alipay&sign=".$sign."&sign_type=MD5";
    $data = file_get_contents($url);
    // echo "<pre>";print_r($http_response_header[0]);die;
    if($http_response_header[0]=='HTTP/1.0 200 OK'){
        echo "success";
    }else{         
        echo "fail";
    }
}else{
    echo "success";
}

?>