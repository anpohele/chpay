<?php
namespace JytPay\Client;
/**
 * Created by 官迪.
 * User: Administrator
 * Date: 2016-11-07
 * Time: 10:26
 */

class Config{

    /**
     * @var string 服务器地址
     */
    // 外网测试地址
    // public $url='https://test.jytpay.com/onePayService/onePay.do';
    // public $url='https://netpay.jytpay.com/JytNetpay/payment.do';
    public $url='https://onepay.jytpay.com/onePayService/onePay.do';  //支付地址
    // public $url='https://ma.jytpay.com:8080/JytAccessService/accessCenter/encReq.do';     //接口入网正式地址
    
    
	// 测试环境 pfx 商户号(除信运付和代付业务外, 还要对接代收/实名支付等业务的,用此配置)
    public $merchant_id='290071070013';
    // 三方证书：cer/pfx格式密钥文件
    //public $cer_path='/pay/certs/jytpayserver.cer'; // .cer密钥文件的路径(公钥)
    //public $pfx_path='/pay/certs/merchantTest.pfx'; // .pfx密钥文件的路径(私钥)
    
	
	// 测试环境 pem 商户号(只对接信运付和代付的用此配置测试)
    // public $merchant_id='290082110001';
    // 自签证书：pem格式密钥文件
//    public $cer_path='D:/cert/290071040001/290071040001jytpaypublictest.pem'; // 平台公钥文件
//    public $pfx_path='D:/cert/290071040001/290071040001merprivatetest.pem'; // 商户私钥文件
    public $cer_path='/plugins/jyt/sdk/certs/JytPayPublicKey.pem'; // 平台公钥文件
    public $pfx_path='/plugins/jyt/sdk/certs/rsa_private_key_2048.pem'; // 商户私钥文件

    /**
     * @var string .pfx密钥文件的访问密码
     */
    public $pfx_password='password';
    
    /**
     * 异步回调
     * @var string
     */
    public $notify_url='';

}