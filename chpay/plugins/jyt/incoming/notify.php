<?php
//获取返回信息
$data=serialize($_POST);

// $contents=unserialize($data);
// //json字符串转数组获取订单号
// $contents=json_decode($contents['data'],true);

//日志记录支付
        // $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/adapay/all_order.txt";
        // // $content = 'ok';
        
        // if($f  = file_put_contents($file, $contents['order_no'],FILE_APPEND)){     // 这个函数支持版本(PHP 5)
        //     // echo "写入成功。<br />";   
        // }
        
    $file  = "/www/wwwroot/www.chpay.com/chpay/plugins/jyt/incoming/Logs.txt";
    $content = $data.'数据修改成功';
    if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
        echo "写入成功。<br />";
    }
//    die;