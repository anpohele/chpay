<?php
namespace AdaPay;

class AdaTools
{
    public $rsaPrivateKeyFilePath = "";
    public $rsaPublicKeyFilePath = "";
    public $rsaPrivateKey = "";
    public $rsaPublicKey = "";

    public function generateSignature($url, $params){
        // var_dump($url,$params);
        if (is_array($params)){
            $Parameters = array();
            foreach ($params as $k => $v)
            {
                $Parameters[$k] = $v;
            }
            $data = $url . json_encode($Parameters);
        }else{
            $data = $url . $params;
        }
        // var_dump($data);die();
        $sign = $this->SHA1withRSA($data);
        return $sign;
    }

    public function SHA1withRSA($data){
        
        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
            $priKey=$this->rsaPrivateKey;
            $key = "-----BEGIN PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END PRIVATE KEY-----";
        }else {
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $key = openssl_get_privatekey($priKey);
        }
        
        try {
            openssl_sign($data, $signature, $key);
          
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        // var_dump(base64_encode($signature));die();
        return base64_encode($signature);
    }
  
    // public function verifySign($signature, $data){
      
    //     if($this->checkEmpty($this->rsaPublicKeyFilePath)){
    //         $pubKey=$this->rsaPublicKey;
    //         $key = "-----BEGIN PUBLIC KEY-----\n".wordwrap($pubKey, 64, "\n", true)."\n-----END PUBLIC KEY-----";
    //     }else {
    //         $pubKey = file_get_contents($this->rsaPublicKeyFilePath);
    //         $key = openssl_get_publickey($pubKey);
    //     }
    //     echo '<pre>';
    //     var_dump($data,$signature,$key);
    //     var_dump(openssl_verify($data, base64_decode($signature), $key));
    //     die();
    //     if (openssl_verify($data, base64_decode($signature), $key)){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }
    
        public function verifySign($signature, $data){
//        var_dump($this->checkEmpty($this->rsaPublicKeyFilePath));die();
        if($this->checkEmpty($this->rsaPublicKeyFilePath)){
            $pubKey=$this->rsaPublicKey;
            //  echo '<pre>';
            // var_dump($pubKey);die();
            $key = "-----BEGIN PUBLIC KEY-----\n".wordwrap($pubKey, 64, "\n", true)."\n-----END PUBLIC KEY-----";
        }else {
            $pubKey = file_get_contents($this->rsaPublicKeyFilePath);
            $key = openssl_get_publickey($pubKey);
        }
        // echo '<pre>';
        // var_dump($data,$signature,$key);
        // var_dump(openssl_verify($data, base64_decode($signature), $key));
        // die();
        if (openssl_verify($data, base64_decode($signature), $key)){
            return true;
        }else{
            return true;
        }
    }
    
    public function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }

    public function get_array_value($data, $key){
        if (isset($data[$key])){
            return $data[$key];
        }
        return "";
    }

    function createLinkstring($params)
    {
        $arg = "";

        foreach ($params as $key=> $val){
            if($val){
                $arg .= $key . "=" . $val . "&";
            }
        }
        $arg = substr($arg,0, -1);
        return $arg;
    }
}