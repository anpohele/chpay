<?php
$nosession = true;
include("./includes/common.php");

if (function_exists("set_time_limit"))
{
	@set_time_limit(0);
}
if (function_exists("ignore_user_abort"))
{
	@ignore_user_abort(true);
}

$s = isset($_GET['s'])?$_GET['s']:exit('404 Not Found');
unset($_GET['s']);

$loadfile = \lib\Plugin::load($s);

$order = $DB->getRow("SELECT * FROM pre_order WHERE trade_no='".TRADE_NO."' limit 1");
if(!$order)sysmsg('该订单号不存在，请返回来源地重新发起请求！');

$channelinfo = $DB->getColumn("SELECT channelinfo FROM pre_user WHERE uid='{$order['uid']}' limit 1");
$channel = \lib\Channel::get($order['channel'], $channelinfo);
if(!$channel || $channel['plugin']!=PAY_PLUGIN)sysmsg('当前支付通道信息不存在');
$channel['apptype'] = explode(',',$channel['apptype']);

$ordername = !empty($conf['ordername'])?ordername_replace($conf['ordername'],$order['name'],$order['uid'],TRADE_NO):$order['name'];

include $loadfile;