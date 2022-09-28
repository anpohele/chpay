<?php

/**
 * AdaPay 发起扫码或者app支付
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/08/03 13:05
 */
//  echo dirname(__FILE__). "/AdapaySdk/init.php";die;

if(!defined('IN_PLUGIN'))exit();
# 加载SDK需要的文件
include_once  dirname(__FILE__). "/AdapaySdk/init.php";
# 加载商户的配置文件
// include_once  dirname(__FILE__). "/config/config.php";
    $con =  new MySQLi("127.0.0.1","chpay","MffKEhrNnjPCPRCH","chpay");
    if($con->connect_error){
      die("连接数据库失败!".$con->connect_error);
    }
    $sql = "select * from pay_channel where plugin='adapay'";
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
$_GET['sitename']=isset($_GET['sitename'])?$_GET['sitename']:'学安';
$externalIp = getIp();
// print_r($_GET);die;
$realmoney = number_format($order['realmoney'],2);
# 支付设置
$arg_money = getDivideNumber($realmoney,3);
// print_r($channel['appid']);
$payment_params = array(
    'app_id'=>  $channel['appid'],
    'order_no'=> $order['out_trade_no'],
    // 'order_no'=> "PY_". date("YmdHis").rand(100000, 999999),
    'pay_channel'=> 'alipay_wap',
    // 'time_expire'=> date("YmdHis", time()+86400),
    'pay_amt'=> $realmoney,
    'goods_title'=>$_GET['name'],
    'goods_desc'=>$_GET['sitename'],
    'currency'=>'cny',
    'notify_url'=>'http://pay.anpo.cc/plugins/adapay/notify.php',
    'route_flag'=>'Y',
    'fee_mode'=>'I',
    'device_info'=> [
        'device_ip'=>$externalIp
        // 'device_type'=>1
    ],
    
    'div_members'=>json_encode([
        [
        'member_id'=>'aphl_202107271526',
        'amount'=>$arg_money[0],
        'fee_flag'=>'Y'
        ],
         [
        'member_id'=>'aphl_202107271535',
        'amount'=>$arg_money[1],
        'fee_flag'=>'N'
        ],
        [
        'member_id'=>'aphl_202107271532',
        'amount'=>$arg_money[2],
        'fee_flag'=>'N'
        ]
    ])
    
    // 'description'=> 'description',
    // 'device_id'=> ['device_id'=>"1111"]

);

// echo "<pre>";print_r($payment_params);die;
 //记录异步地址   
    // $file  = '/www/wwwroot/www.chpay.com/chpay/plugins/adapay/Logs/'.$payment_params['order_no'].'.txt';
    //要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
    // echo $_GET['notify_url'];die;
    // $content = $_GET['notify_url'];
    // if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            // echo "写入成功。<br />";
    // }
    // echo "<pre>";print_r($payment_params);die;
# 发起支付
$payment->create($payment_params);

//均分总金额
function getDivideNumber($number, $total, $index = 2) {
    // 除法取平均数
    $divide_number  = bcdiv($number, $total, $index);
    // 减法获取最后一个数
    $last_number = bcsub($number, $divide_number*($total-1), $index);
    // 拼装平分后的数据返回
    $number_str = str_repeat($divide_number.'+', $total-1).$last_number;
    return explode('+', $number_str);
}

# 对支付结果进行处理
if ($payment->isError()){
    //失败处理
    // echo(123);die;
    $data=$payment->result;
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
} else {
    //成功处理
    // echo(456);die;
    $data=$payment->result;
    $url=$data['expend']['pay_info'];
    header("Location: $url");die;
    echo json_encode($data);die;
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
}

function getIp()
{ //取IP函数
    static $realip;
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $realip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } else {
            $realip = getenv('HTTP_CLIENT_IP') ? getenv('HTTP_CLIENT_IP') : getenv('REMOTE_ADDR');
        }
    }
    return $realip;
}