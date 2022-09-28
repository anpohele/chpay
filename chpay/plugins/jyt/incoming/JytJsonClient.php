<?php
namespace JytPay\Client_ACCESS;

include 'Config.php';
include 'ENC.php';
include 'HttpUtil.php';


/**
 * @author liyabin
 * Class JytJsonClient
 * @package JytPay\Client
 */
class JytJsonClient{
    public $enc_utils;
    public $config;
    
    /**
    * 初始化
    */
    function init(){
        $this->config = new Config;
        $this->enc_utils = new ENC($this->config);
    }

    /**
     * 明文参数转为密文请求参数
     * @param string $param_array 请求报文信息
     * @param string $img_url 上传图片路径及名称
     * @return array
     */
    function getParam($param_array, $img_url){
        $des_key = $this->randomkeys(8);
        $msg = json_encode($param_array);
        // 签名
        $sign = $this->enc_utils->sign($msg);
//        var_dump($sign);die();
        // 3des加密
        $msg_enc = $this->enc_utils->desEncrypt($msg, $des_key);
        // 私钥加密
        $key_enc = $this->enc_utils->encrypt($des_key);
        // 判断是上传图片还是入网申请及其它
        if ($img_url == '' || $img_url == null) {
            $data_r = array("merchant_id"=>$this->config->merchant_id,"key_enc"=>$key_enc,"msg_enc"=>$msg_enc,"sign"=>$sign,"mer_order_id"=>$param_array['head']['tranFlowid']);
        } else {
            $data_r = array("merchant_id"=>$this->config->merchant_id,"key_enc"=>$key_enc,"msg_enc"=>$msg_enc,"sign"=>$sign,"mer_order_id"=>$param_array['head']['tranFlowid'],'picFile'=>curl_file_create($img_url));
        }
//        var_dump($data_r);die();
        return $data_r;
    }
    
    // 发送请求POST,并返回明文json转化后的array数组
    function sendReq($param_array, $img_url){
        $data_r = $this->getParam($param_array, $img_url);
        $http = new Http();
//        var_dump($data_r);die();
        $res_ = $http->post($this->config->url,$data_r);
//        var_dump($this->config->url);die();
        $res_list = explode('&', $res_);
        if (empty($res_) || empty($res_list)){
            return '出错，接收到数据为空！';
        }
        // 得到的数据转成数组
        $response = array();
        foreach($res_list as $en) {
            $temp = explode("=", $en);
            $response[$temp[0]] = $temp[1];
        }
//        echo 21345;
//        echo '<pre>';
//        print_r(json_decode($response));die();
        return $this->parserRes($response);
    }

    // 解密通知/响应密文 -> 返回明文json转化后的array数组
    function parserRes($response){

        $merchant_id = $response['merchant_id'];
        $msg_enc = $response['msg_enc'];
        $key_enc = $response['key_enc'];
        $sign = $response['sign'];
        
        // 解密出来的真正的DES算法的密钥
        $key_enc = $this->enc_utils->decrypt($key_enc);
        // 解密报文
        $re_msg = $this->enc_utils->desDecrypt($msg_enc,$key_enc);
        // 验证签名
        $sign_re = $this->enc_utils->verify($re_msg, $sign);

//        var_dump($re_msg);die();
        return $re_msg;
    }
    
    public function randomkeys($length){
        $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ,./<>?;#:@~[]{}-_=+)(*&^%___FCKpd___0pound;"!'; // 字符池
        $key = '';
        for ($i=0; $i<$length; $i++){
            $key.=$pattern{mt_rand(0,35)}; // 生成php随机数
        }
        return $key;
    }
}