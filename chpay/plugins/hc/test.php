<?php
$url ='http://119.252.254.44:56666/notice/aftpl2notice?money=3&name=测试订单&out_trade_no=X20842652035711749&pid=1014&trade_no=2022082816001256670&trade_status=TRADE_SUCCESS&type=hc&sign=7776b91e9a8770eec0946174d65fb3d8&sign_type=MD5';
$data = @file_get_contents($url);
echo '<pre>';
print_r($data);