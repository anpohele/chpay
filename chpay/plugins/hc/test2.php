<?php
$data=serialize($_GET);
 $file  = "/www/wwwroot/ys.dickmorley.cn/chpay/plugins/hc/test.txt";
        $content = $data;
        
        if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
            echo "success";  
        }