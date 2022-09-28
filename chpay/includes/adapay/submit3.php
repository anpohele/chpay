<?php
/**
 * AdaPay 发起扫码或者app支付
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/08/03 13:05
 */
# 加载SDK需要的文件
include_once  dirname(__FILE__). "/AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/config/config.php";



# 初始化支付类
$payment = new \AdaPaySdk\Payment();
$externalContent = file_get_contents('http://checkip.dyndns.com/');
preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
$externalIp = $m[1];
# 支付设置
$payment_params = array(
    'app_id'=>  'app_e83a1add-5c1a-4870-bf0e-d387073ba656',
    'order_no'=> '12421321421111111213',
    'pay_channel'=> 'alipay_wap',
    // 'time_expire'=> date("YmdHis", time()+86400),
    'pay_amt'=> '1.20',
    'goods_title'=> '电子元器件',
    'goods_desc'=> '电子元器件',
    'currency'=>'cny',
    'notify_url'=>'www.baidu.com',
    'route_flag'=>'Y',
    'device_info'=> [
        'device_ip'=>$externalIp,
        'device_type'=>1,
    ],
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
    $url=$data['expend']['pay_info'];
    header("Location: $url");
    exit();
    echo "<pre>";print_r($data);die;
    var_dump($payment->result);
}