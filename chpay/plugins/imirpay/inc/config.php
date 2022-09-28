<?php
$pay_config = array (
	//银行接口代码（101：汇付天下，102：当面付，103：乐刷科技，104：易宝支付）
	'type_id' => $channel['appmchid'],

	//商户证书序列号
	'cert_id' => $channel['appid'],

	//商户公钥
	'publickey' => $channel['appkey'],

	//商户私钥
	'privatekey' => $channel['appsecret'],

	//支付网关地址
	'apiurl' => 'https://imir.changoe.net/gateway_index.do',
);
