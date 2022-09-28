<?php 
if(!defined('IN_PLUGIN'))exit();

$data = array( // 返回字段
    "memberid" => $_REQUEST["memberid"], // 商户ID
    "orderid" =>  $_REQUEST["orderid"], // 订单号
    "amount" =>  $_REQUEST["amount"], // 交易金额
    "datetime" =>  $_REQUEST["datetime"], // 交易时间
    "transaction_id" =>  $_REQUEST["transaction_id"], // 流水号
    "returncode" => $_REQUEST["returncode"]
);

ksort($data);
reset($data);
$md5str = "";
foreach ($data as $key => $val) {
    $md5str .= $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $channel['appkey']));

if ($sign === $_REQUEST["sign"]) {

    if ($data["returncode"] == "00") {
		//付款完成后，支付宝系统发送该交易状态通知
		$out_trade_no = daddslashes($data['orderid']);
		$trade_no = daddslashes($data['transaction_id']);
		if($out_trade_no == TRADE_NO && round($data["amount"],2)==round($order['realmoney'],2) && $order['status']==0){
			if($DB->exec("update `pre_order` set `status` ='1' where `trade_no`='$out_trade_no'")){
				$DB->exec("update `pre_order` set `api_trade_no` ='$trade_no',`endtime` ='$date',`date` =NOW() where `trade_no`='$out_trade_no'");
				processOrder($order);
			}
		}
    }

	echo "OK";
}
else {
    //验证失败
    echo "FAIL";
}

?>