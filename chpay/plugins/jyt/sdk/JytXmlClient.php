<?php
namespace JytPay\Client;

include 'Config.php';
include 'ENC.php';
include 'XMLHelper.php';
include 'HttpUtil.php';

/**
* Created by 旭东.
* Date: 2017-04-07
* Time: 15:17
*/

/**
* 扫码收银工具类
* 使用前请初始化
* 提供:
*  请求密文组装
*  交易请求
*  返回密文解密
*/
class JytXmlClient{
    public $enc_utils;
    // public $rsa_helper;
    public $config;
    public $xml_helper;
    
    /**
    * 初始化
    */
    function init(){
        $this->config = new Config;
		print_r("商户号: ");
        print_r($this->config->merchant_id);
        echo "<br><br>";
		print_r("平台公钥: ");
        print_r($this->config->cer_path);
        echo "<br><br>";
		print_r("商户私钥: ");
        print_r($this->config->pfx_path);
        echo "<br><br>";
		print_r("请求地址: ");
        print_r($this->config->url);
        echo "<br><br>";
        $this->enc_utils = new ENC($this->config);
        // $this->rsa_helper = new RSAHelper($this->config);
        $this->xml_helper = new XMLHelper;
    }
    
    /**
    * 明文参数转为密文请求参数
    */
    function getParam($param_array){
        
        $des_key=$this->randomkeys(8);
        
        $xml=$this->xml_helper->arrayToXml($param_array);//print_r($xml);
		print_r("请求明文: <br>");
        print_r($param_array);
        echo "<br><br>";
		
        //签名
        $sign=$this->enc_utils->sign($xml);
        
        $xml_enc=$this->enc_utils->desEncrypt($xml,$des_key);
        $key_enc=$this->enc_utils->encrypt($des_key);
        $data_r = array("merchant_id"=>$this->config->merchant_id,"key_enc"=>$key_enc,"xml_enc"=>$xml_enc,"sign"=>$sign);
        return $data_r;
    }
    
    // 发送请求
    // 返回 明文xml转化后的array数组
    function sendReq($param_array){
        $data_r = $this->getParam($param_array);
        print_r("请求参数加密: <br>");
        print_r($data_r);
        echo "<br><br>";
        $http = new Http();
        $res_=$http->post($this->config->url,$data_r);
        print_r("平台响应: <br>");
        print_r($res_);
        echo "<br><br>";
        
        $reslist=explode("&",$res_);
        if(empty($res_)||empty($reslist)){
            return '出错，接收到数据为空！';
        }
        //得到的数据转成数组
        $response=array();
        foreach($reslist as $en){
            $temp=explode("=",$en);
            $response[$temp[0]]=$temp[1];
        }
        //echo "响应结果转数组: <br>";
        //print_r($response);
        //echo "<br><br>";
        return $this->parserRes($response);
    }
    
    // 解密通知/响应密文
    // 返回 明文xml转化后的array数组
    function parserRes($response){
        
        $merchant_id = $response['merchant_id'];
        $xml_enc = $response['xml_enc'];
        $key_enc = $response['key_enc'];
        $sign = $response['sign'];
        
        //解密出来的真正的DES算法的密钥
        $key_enc=$this->enc_utils->decrypt($response['key_enc']);
        echo "DES密钥(16进制): <br>";
        print_r(bin2hex($key_enc));
        echo "<br><br>";
		
        $re_xml=$this->enc_utils->desDecrypt($response['xml_enc'],$key_enc);
        echo "响应明文: <br>";
        print_r($re_xml);
        echo "<br><br>";
                
        //验证签名
        $sign_re=$this->enc_utils->verify($re_xml,$response['sign']);

        if($sign_re){
            echo "验签通过<br><br>";
        }else{
            echo "验签失败<br><br>";
            return "验签失败";
        }

        return $this->xml_helper->xmlToArray($re_xml);
        //print_r($sign_re);die();
        
    }
    
    public function randomkeys($length){
        $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ,./<>?;#:@~[]{}-_=+)(*&^%___FCKpd___0pound;"!'; //字符池
        $key='';
        for($i=0;$i<$length;$i++){
            $key.=$pattern{mt_rand(0,35)};//生成php随机数
        }
        return $key;
    }
}
?>