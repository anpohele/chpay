<?php
// $data='a:31:{s:13:"buyerUsername";s:11:"176****0605";s:7:"msgType";s:12:"trade.notify";s:7:"payTime";s:19:"2021-05-11 22:30:51";s:15:"buyerCashPayAmt";s:2:"10";s:10:"connectSys";s:8:"UNIONPAY";s:4:"sign";s:64:"A181A8EE29A2DFC0694C406BA8F413B6D4EE68E997D26196BE8D16C606156D6E";s:7:"merName";s:36:"上海刻契网络科技有限公司";s:3:"mid";s:15:"89831014816019A";s:13:"invoiceAmount";s:2:"10";s:10:"settleDate";s:10:"2021-05-11";s:9:"billFunds";s:12:"银行卡:10";s:7:"buyerId";s:16:"2088522253266573";s:9:"mchntUuid";s:32:"2d9081bd785f3fba01786e41b2077560";s:3:"tid";s:8:"20159023";s:7:"instMid";s:9:"H5DEFAULT";s:13:"receiptAmount";s:2:"10";s:12:"couponAmount";s:1:"0";s:13:"targetOrderId";s:28:"2021051122001466571448430099";s:8:"signType";s:6:"SHA256";s:13:"billFundsDesc";s:25:"银行卡支付0.10元。";s:9:"orderDesc";s:12:"充值缴费";s:5:"seqId";s:12:"21364607624N";s:10:"merOrderId";s:17:"11UP2021051142338";s:9:"targetSys";s:10:"Alipay 2.0";s:11:"totalAmount";s:2:"10";s:10:"createTime";s:19:"2021-05-11 22:30:40";s:14:"buyerPayAmount";s:2:"10";s:2:"wO";s:4:"wptl";s:8:"notifyId";s:36:"e5e38027-a39a-4006-9083-4ee30bb19615";s:7:"subInst";s:6:"104200";s:6:"status";s:13:"TRADE_SUCCESS";}';

//日志路径
$data=serialize($_POST);
$contents=unserialize($data);
// echo "<pre>";print_r($contents);die;
// out_trade_no截取掉前4位
$contents['merOrderId']=substr($contents['merOrderId'],4,30);
   

$file  = '/www/wwwroot/www.chpay.com/chpay/plugins/unionpayh5/Logs/' .$contents['merOrderId'].'.txt';
// $content = $data.'参数';
// $f  = file_put_contents($file, $content,FILE_APPEND);die;
$con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay" );
if($con->connect_error){
    die("连接数据库失败!".$con->connect_error);
}
$sql = "select * from pay_order where out_trade_no='".$contents['merOrderId']."'";

$result = $con->query($sql);
foreach($result as $k => $v){
    $notify_url=$v['notify_url'];
    $money=$v['money'];
    $name=$v['name'];
    $trade_no=$v['trade_no'];
    $out_trade_no=$v['out_trade_no'];
    $pid=$v['uid'];
}
$content = "接受参数时间：".date('Y-m-d H:i:s')."\r\n";
$f  = file_put_contents($file, $content,FILE_APPEND);

//查询订单状态是否改变
$status=0;
$sql = "select * from pay_order where out_trade_no='".$contents['merOrderId']."'";
$result = $con->query($sql);
foreach($result as $k => $v){
    if($v['status']==1){
        //订单已更新
        $status=1;
    }
}

if($status!=1){
    //订单未更新
    $sql = "UPDATE pay_order set status='1' WHERE out_trade_no='".$contents['merOrderId']."'";
    $result = $con->query($sql);

    if($result){
        $updsql = "UPDATE pay_order set api_trade_no='".$contents['targetOrderId']."',endtime='".date('Y-m-d H:i:s')."',buyer='".$contents['buyerId']."',date='".$contents['createTime']."' WHERE out_trade_no='".$contents['merOrderId']."'";
        $updsql = $con->query($updsql);
        if($updsql){
            $content = "订单状态更新时间：".date('Y-m-d H:i:s')."\r\n";
            $f  = file_put_contents($file, $content,FILE_APPEND);
        }
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
        'out_trade_no' => $contents['merOrderId'],
        'trade_status' => $contents['status'],
        'pid' => $pid,
        'type' => 'unionpayh5'
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
    
    //打印报文
    $f  = file_put_contents($file, "报文：".$arg,FILE_APPEND);
    
//生成签名
    $sign = md5($arg);
//拼接,异步回调，返回用户
    // $url=$notify_url."?created_time=".$contents['createTime']."&end_time=".$contents['payTime']."&order_no=".$contents['targetOrderId']."&out_trans_id=".$contents['merOrderId']."&party_order_id=".$contents['buyerId']."&status=".$contents['status'];
    //拼接地址
    $url=$notify_url."?money=".$money."&name=".$name."&trade_no=".$trade_no."&out_trade_no=".$contents['merOrderId']."&trade_status=".$contents['status']."&pid=".$pid."&type=unionpayh5&sign=".$sign."&sign_type=MD5";
    
    //打印URL
    $f  = file_put_contents($file, "URL:".$url."\r\n",FILE_APPEND);
    
    $data = file_get_contents($url);
    if($http_response_header[0]=='HTTP/1.1 200 OK'){
        //记录回调时间，返回银联
        $content = "调用成功时间：".date('Y-m-d H:i:s')."\r\n";
        $f  = file_put_contents($file, $content,FILE_APPEND);
        echo "SUCCESS";
    }else{         
        echo "FAILED";
    }
}else{
    $content = "订单已更新\r\n";
    $f  = file_put_contents($file, $content,FILE_APPEND);
    echo "SUCCESS";
}
die;


