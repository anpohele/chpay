<?php

include '../demo.php';

/**
 * 本地验签
 */
$s = new demo();
$data = "account_date=20180130&notify_time=2018-01-30 14:18:49&notify_type=directpay.status.sync&out_trade_no=20180130120569774168345013&sign_type=RSA&total_amount=0.01&trade_no=01O180130528406195&trade_status=TRADE_SUCCESS";
//$data = "account_date=20180104&notify_time=2018-01-04 10:44:35&notify_type=ysepay.df.single.notify&out_trade_no=2018010410412938614587&sign_type=RSA&total_amount=1.00&trade_no=102180104196629023&trade_status=TRADE_SUCCESS";
$sign = "Uf6CpvAYlhFp4lYLEVfNoD2OVYT0xt7StzajCy2ruJfjCwRDJ41EZXUHT0wxP+XfBstjn7Fhvg38ZrLTPhsjf8OBopHA/1qnBK96740C94PdC1OoHDZgHjVdANyBTERQXy9pWlUQVqIzX0lOwwSzlRlB7IaHJWUT1hHYHpxXC2U=";
var_dump('data:' . $data);
var_dump('sign:' . $sign);
if ($s->sign_check($sign, $data)) {
    echo "验签成功";
} else {
    echo "验证失败";
}