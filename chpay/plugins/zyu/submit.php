<?php
if(!defined('IN_PLUGIN'))exit();

$apiurl = $channel['appurl'];
$data = array(
    "pay_memberid" => $channel['appid'],
    "pay_orderid" => TRADE_NO,
	"pay_amount" => (float)$order['realmoney'],
    "pay_applydate" => date("Y-m-d H:i:s"),
	"pay_bankcode" => $channel['appmchid'],
    "pay_notifyurl" => $conf['localurl'].'pay/zyu/notify/'.TRADE_NO.'/',
    "pay_callbackurl" => $siteurl.'pay/zyu/return/'.TRADE_NO.'/',
);
ksort($data);
$md5str = "";
foreach ($data as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}

$sign = strtoupper(md5($md5str . "key=" . $channel['appkey']));

$data["pay_md5sign"] = $sign;
$data["pay_productname"] = $ordername;

echo '<form action="'.$apiurl.'" method="post" id="dopay">';
foreach($data as $k => $v) {
	echo "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
}
echo '<input type="submit" value="正在跳转"></form><script>document.getElementById("dopay").submit();</script>';
