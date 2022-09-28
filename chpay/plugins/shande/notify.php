<?php
echo '123';die;
$data = $_POST;
$data = unserialize($data);
$file  = "/www/wwwroot/ys.dickmorley.cn/chpay/plugins/adapay/notify_log.txt";
$content = $data.'数据修改成功';
if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
    echo "写入成功。<br />";
}