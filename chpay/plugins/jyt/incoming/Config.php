<?php
namespace JytPay\Client_ACCESS;

/**
 * @author liyabin
 * Class Config
 * @package JytPay\Client
 */
class Config{
    // 商户测试服务器地址
//    public $url='https://test.jytpay.com/JytAccessService/accessCenter/encReq.do';
    public $url='https://ma.jytpay.com:8080/JytAccessService/accessCenter/encReq.do';

	// 测试商户号（可替换成自己入网的商户号）
    public $merchant_id='290073990022';

    // 自签证书：pem格式密钥文件
//    public $cer_path='./certs/100047220002_jyt_pub.pem'; // 平台公钥文件
    public $cer_path= '/plugins/jyt/incoming/certs/JytPayPublicKey.pem'; // 平台公钥文件
    public $pfx_path= '/plugins/jyt/incoming/certs/rsa_private_key_2048.pem'; // 商户私钥文件

    // 使用三方证书的私钥（目前未用到）
    public $pfx_password = 'password';
}

