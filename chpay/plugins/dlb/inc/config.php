<?php
if(!defined('IN_PLUGIN')) exit();
return [
	'customerNum' => trim($channel['appid']),
	'shopNum'     => trim($channel['appkey']),
	'accessKey'   => trim($channel['appmchid']),
	'secretKey'   => trim($channel['appsecret']),
];