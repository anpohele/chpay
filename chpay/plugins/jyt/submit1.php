<?php

if(!defined('IN_PLUGIN'))exit();
# 加载SDK需要的文件
include_once  dirname(__FILE__). "/AdapaySdk/init.php";
# 加载商户的配置文件
// include_once  dirname(__FILE__). "/config/config.php";
    $con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
    if($con->connect_error){
      die("连接数据库失败!".$con->connect_error);
    }
    $sql = "select * from pay_channel where plugin='jyt'";
    $result = $con->query($sql);
    foreach($result as $k => $v){
        $appid=$v['appid'];
        $api_key_live=$v['appmchid'];
        $rsa_public_key=$v['appkey'];
        $rsa_private_key=$v['appsecret'];
    }
    $config_object=[
        'api_key_live'=>$api_key_live,
        'api_key_test'=>"api_test_a2816fd0-6c1d-4869-981c-8c2ed62c8684",
        "rsa_public_key"=>$rsa_public_key,
        "rsa_private_key"=>$rsa_private_key
    ];
    // echo "<pre>";print_r($config_object);die;
    \AdaPay\AdaPay::init($config_object, "live", true);


# 初始化支付类
$payment = new \AdaPaySdk\Payment();
//公网ip获取
$_GET['sitename']=isset($_GET['sitename'])?$_GET['sitename']:'云任务';
# 支付设置
$payment_params = array(
    'app_id'=>  $channel['appid'],
    'order_no'=> $order['out_trade_no'],
    // 'order_no'=> "PY_". date("YmdHis").rand(100000, 999999),
    'pay_channel'=> 'alipay_wap',
    // 'time_expire'=> date("YmdHis", time()+86400),
    // 'pay_amt'=> (float)$order['realmoney'],
    'pay_amt'=> number_format($order['realmoney'],2),
    'goods_title'=>$_GET['name'],
    'goods_desc'=>$_GET['sitename'],
    'description'=> 'description',
    // 'currency'=>'cny',
    'notify_url'=>'https://pay.fairydeed.com/plugins/adapay1/notify.php',
);
// echo "<pre>";print_r($payment_params);die;
 //记录异步地址   
    $file  = '/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs/'.$payment_params['order_no'].'.txt';
    //要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    // echo $_GET['notify_url'];die;
    $content = $_GET['notify_url'];
    if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            // echo "写入成功。<br />";
    }
    // echo "<pre>";print_r($payment_params);die;
# 发起支付
$payment->create($payment_params);

# 对支付结果进行处理
if ($payment->isError()){
    //失败处理
    $data=$payment->result;
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
} else {
    //成功处理
    $data=$payment->result;
    $url=$data['expend']['pay_info'];
    header("Location: $url");die;
    echo json_encode($data);die;
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
}