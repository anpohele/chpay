<?php

// 错误日志
ini_set("error_log", "./err.txt");

// 自动加载
function loadClass($className)
{
    require './class/' . $className . '.php';
}
spl_autoload_register('loadClass');

# 支付常量
define("MER_NO", "填商户号");

# 公私钥
define("RSA_PUBLIC", "-----BEGIN PUBLIC KEY-----

-----END PUBLIC KEY-----");
define("RSA_PRIVATE", "-----BEGIN PRIVATE KEY-----

-----END PRIVATE KEY-----");
define("RSA_PUBLIC_HC", "-----BEGIN PUBLIC KEY-----

-----END PUBLIC KEY-----");

$product = array(
    40 => array(
        'name'      => '周卡VIP',
        'card'      => '800', # 多少钱:元x10
        'card_card' => '1',   # 哪种vip. 0 普通用户、 1 周、2 月、 3 季、 4 年
    ),
    41 => array(
        'name'      => '月卡VIP',
        'card'      => '2400',
        'card_card' => '2',
    ),
    42 => array(
        'name'      => '季卡VIP',
        'card'      => '4500',
        'card_card' => '3',
    ),
    43 => array(
        'name'      => '年卡VIP',
        'card'      => '8880',
        'card_card' => '4',
    ),
);

function arr2xml($arr){
    $simxml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><AggregatePayRequest></AggregatePayRequest>");//创建simplexml对象
    //遍历数组，循环添加到root节点中
    foreach($arr as $k=>$v){
        $simxml->addChild($k,$v);
    }
    //返回xml数据
    // header("Content-type:text/xml;charset=utf-8");
    return $simxml->saveXML();
}

function xml2arr($path)
{
    //xml字符串转数组
    //$xmlfile = file_get_contents($path);//提取xml文档中的内容以字符串格式赋给变量
    $ob         = simplexml_load_string($path, 'SimpleXMLElement', LIBXML_NOCDATA); //将字符串转化为变量
    $json       = json_encode($ob); //将对象转化为JSON格式的字符串
    $configData = json_decode($json, true); //将JSON格式的字符串转化为数组
    return $configData;
}

# 多维数组转换xml
function arr2xmls($arr){
    $simxml = new SimpleXMLElement("<?xml version='1.0' encoding='utf-8'?><AggregatePayRequest></AggregatePayRequest>"); //创建simplexml对象
    foreach($arr as $k=>$v){
        if(is_array($v)){//如果是数组的话则继续递归调用，并以该键值创建父节点
            arr2xml($v, $simxml->addChild($k));
        }else if(is_numeric($k)){//如果键值是数字，不能使用纯数字作为XML的标签名，所以此处加了"item"字符，这个字符可以自定义
            $simxml->addChild("item" . $k, $v);
        }else{//添加节点
            $simxml->addChild($k, $v);
        }
    }
    //返回数据  
    //header("Content-type:text/xml;charset=utf-8");
    return $simxml->saveXML();
}

# 私钥签名
function get_private_sign($sign_str, $private_key, $signature_alg=OPENSSL_ALGO_SHA1){
    $private_key = openssl_pkey_get_private($private_key);
    openssl_sign($sign_str, $signature, $private_key, $signature_alg);
    $signature = base64_encode($signature);
    openssl_free_key($private_key);
    return $signature;
}

# 公钥验签
function public_verify($sign_str, $sign, $public_key, $signature_alg=OPENSSL_ALGO_SHA1){
    $public_key = openssl_get_publickey($public_key);
    $verify = openssl_verify($sign_str, base64_decode($sign), $public_key, $signature_alg);
    openssl_free_key($public_key);
    return $verify==1;
}

# 数组转换为utf-8编码
function array_iconv($arr, $in_charset = "gbk", $out_charset = "utf-8") {
    $ret = eval("return " . iconv($in_charset, $out_charset, var_export($arr, true)) . ";");
    return $ret;
}

# 生成随机数
function great_rand() {
    $str = '1234567890abcdefghijklmnopqrstuvwxyz';
    $t1  = "";
    for ($i = 0; $i < 15; $i++) {
        $j = rand(0, 35);
        $t1 .= $str[$j];
    }
    return $t1;
}