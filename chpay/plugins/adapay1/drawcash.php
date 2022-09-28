<?php

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/AdapaySdk/init.php";

// $cash_amt = number_format($_POST['cash_amt'],2);
$payment = new \AdaPaySdk\Member();


# 取现设置
$payment_params = array(
    'app_id'=>  'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
    // 'order_no'=> $order['out_trade_no'],
    // // 'order_no'=> "PY_". date("YmdHis").rand(100000, 999999),
    // 'pay_channel'=> 'alipay_wap',
    // // 'time_expire'=> date("YmdHis", time()+86400),
    // // 'pay_amt'=> (float)$order['realmoney'],
    // 'pay_amt'=> number_format($order['realmoney'],2),
    // 'goods_title'=>$_GET['name'],
    // 'goods_desc'=>$_GET['sitename'],
    // // 'description'=> 'description',
    // // 'currency'=>'cny',
    // 'notify_url'=>'https://pay.fairydeed.com/plugins/adapay1/notify.php',
    // 'route_flag'=>'Y',
    // 'device_info'=> [
    //     'device_ip'=>$_GET['ip']
    //     // 'device_type'=>1
    // ]
);

print_r($payment->queryList($payment_params));