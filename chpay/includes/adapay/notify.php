<?php
        //获取返回信息
        $data=serialize($_POST);
        $contents=unserialize($data);
        //json字符串转数组获取订单号
        $contents=json_decode($contents['data'],true);
       
        //获取异步地址
        $filename = "/www/wwwroot/adapay/light/pay/Alipay/Log/".$contents['order_no'].".txt";
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        $notify_url = fread($handle, filesize ($filename));
        fclose($handle);
        //拼接,异步回调
        $url=$notify_url."?created_time=".$contents['created_time']."&end_time=".$contents['end_time']."&order_no=".$contents['order_no']."&out_trans_id=".$contents['out_trans_id']."&party_order_id=".$contents['party_order_id']."&status=".$contents['status'];
        $data = file_get_contents($url);die;
        
        
        
        
        $file  = "/www/wwwroot/adapay/adapay/AdapayDemo/Payment/Log/".$contents['order_no'].".txt";//要写入文件的文件名（可以是任意文件名），如果文件不存在，将会创建一个
        $content = $url;
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
                echo "写入成功。<br />";
        }
        die;
       