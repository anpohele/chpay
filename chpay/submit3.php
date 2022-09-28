<?php
/**
 * AdaPay 发起扫码或者app支付
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/08/03 13:05
 */
# 加载SDK需要的文件
include_once  dirname(__FILE__). "/includes/adapay/AdapaySdk/init.php";
# 加载商户的配置文件
// include_once  dirname(__FILE__). "/includes/adapay/config/config.php";
//链接数据库
$con =  new MySQLi("106.14.16.249","chpay","MffKEhrNnjPCPRCH","chpay");
if($con->connect_error){
   die("连接数据库失败!".$con->connect_error);
}
$sql = "select * from pay_channel where id='5'";
$result = $con->query($sql);
foreach($result as $k => $v){
    $appid=$v['appid'];
    $api_key_live=$v['appmchid'];
    $rsa_public_key=$v['appkey'];
    $rsa_private_key=$v['appsecret'];
    // echo "<pre>";print_r($v);die;
}
// appmchid:应用key,appid:应用appid,appkey:商户公钥,appsecret:商户密钥
$config_object=[
    'api_key_live'=>$api_key_live,
    'api_key_test'=>"api_test_a2816fd0-6c1d-4869-981c-8c2ed62c8684",
    "rsa_public_key"=>$rsa_public_key,
    "rsa_private_key"=>$rsa_private_key
];
// echo "<pre>";print_r($config_object);die;
\AdaPay\AdaPay::init($config_object, "live", true);
// echo $appid;die;
// die;
# 初始化支付类
$payment = new \AdaPaySdk\Payment();

# 支付设置
$payment_params = array(
    'app_id'=>  $appid,
    'order_no'=> '1242132124211332123',
    'pay_channel'=> 'alipay_wap',
    // 'time_expire'=> date("YmdHis", time()+86400),
    'pay_amt'=> '1.20',
    'goods_title'=> '电子元器件',
    'goods_desc'=> '电子元器件',
    'currency'=>'cny',
    'notify_url'=>'www.baidu.com',
    // 'description'=> 'description',
    // 'device_id'=> ['device_id'=>"1111"],
    // 'expend'=> [
    //     'buyer_id'=> '1111111',              // 支付宝卖家账号ID
    //     'buyer_logon_id'=> '22222222222',   // 支付宝卖家账号
    //     'promotion_detail'=>[              // 优惠信息
    //         'cont_price'=> '100.00',      // 订单原价格
    //         'receipt_id'=> '123',        // 商家小票ID
    //         'goodsDetail'=> [           // 商品信息集合
    //             ['goods_id'=> "111", "goods_name"=>"商品1", "quantity"=> 1, "price"=> "1.00"],
    //             ['goods_id'=> "112", "goods_name"=>"商品2", "quantity"=> 1, "price"=> "1.01"]
    //         ]
    //     ]
    // ]
);

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
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
}