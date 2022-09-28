<?php
namespace JytPay\Client_ACCESS;
include 'JytJsonClient.php';

/**
 * Created by PhpStorm.
 * User: yabin
 * Date: 2021/9/15
 * Time: 9:05
 */


// 请求
//echo 2134;die();
test_tm1001();

/**
 * 测试图片上传
 */
function test_tm1001() {
    $client = new JytJsonClient;
    $client -> init();

    // 报文头信息
    $data['head']['version']='1.0.0';
    $data['head']['tranType']='01';
    $data['head']['merchantId']= $client->config->merchant_id;
    $data['head']['tranDate']=date('Ymd',time());
    $data['head']['tranTime']=date('His',time());
    $data['head']['tranFlowid']= $client->config->merchant_id . date('YmdHis',time()) . substr(rand(),4);
    $data['head']['tranCode']= 'TM1001';
    // 设置报文体为空
    $data['body']['']= '';
    $res = $client->sendReq($data, "/www/wwwroot/pay.anpo.cc/chpay/plugins/jyt/img/WechatIMG33.jpeg");
    echo $res;
}