<?php
namespace AdaPay;

class AdaTools
{
    public $rsaPrivateKeyFilePath = "";
    public $rsaPublicKeyFilePath = "";
    public $rsaPrivateKey = "000";
    public $rsaPublicKey = "";

    public function generateSignature($url, $params){
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
        $sign = $this->SHA1withRSA($data);
        return $sign;
    }

    public function SHA1withRSA($data){

        if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
            $priKey=$this->rsaPrivateKey;
            $priKey = "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKjDLumgH+6cTXhS/EQhRw8pkkPHxxzBBh9xHg7fKrdtg1Ux1gwlOD1a29o1IwBRzNNpY//Tgr09pFaGiE7VEAwvDs6PXi09Eq8BZzgV3FLb71vnXufRokhvgCAuL8w44FuYZ+5Y0gEBxJ5aGT73voAhp1ZmnMasWObpGryrpBmtAgMBAAECgYEAnHQeyL4G/HnxJCyi7DKBox/iFm5ePF0CZSHEQPtJqbWgPNov2yCiS9cw3NHIOiKbph8dcu1OVkyQTxr3wUWrUm7d36aQHFkHS4m1ypL2Ft+TeN23/51c84m4seIy1MkcL871Dtf1lqoc9HRkCATYHxqMdA99QD46VcRfxhKaQGECQQDSZqEr4fgkdadKLNk6V5nzZxEJ5/F6s9Ytn5bNBiy0jLia3K87XdBijvqPqj7QyV8VuWUyS6XMizdV+Hpp1KY1AkEAzVZjAgLMwJYZyqnc6Of7BhwYnkRSrD5+s4GGE5worwJ30XZ5jEJC72CXA5Im8174+1SCTzB4fOSnV8AnY+I0mQJAK7j+FzNvMIxuhkCJp9EagfVSGh/kE56ZjIOUf+ifk6mGl0/y7kBRlJmnwgEb6qFeLBKJ0AjeXII1rpzjO2jgXQJAEyJYqYhPQib6kksP3dg4KRKXLLBbL9fHAL0yHEOx+tT1C1zJ6MsH57yNdfS5knYoJ2txlSWEJMc93Mx4HzOmCQJAf6/ZMUnS2b+fkbSB2GAWUPG46Ggl0K/jdTf+HjYe8oTCtj/kv74gbhe589qxcR8mD5aHu4XwNVKoncvoNCL34w==";
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
        return base64_encode($signature);
    }
  
    public function verifySign($signature, $data){

        if($this->checkEmpty($this->rsaPublicKeyFilePath)){
            $pubKey=$this->rsaPublicKey;
            
            $key = "-----BEGIN PUBLIC KEY-----\n".wordwrap($pubKey, 64, "\n", true)."\n-----END PUBLIC KEY-----";
        }else {
            $pubKey = file_get_contents($this->rsaPublicKeyFilePath);
            $key = openssl_get_publickey($pubKey);
        }
//        echo '<pre>';
        // var_dump($data,$signature,$key);
        if (openssl_verify($data, base64_decode($signature), $key)){
            // echo 123;die();
            return true;
        }else{
            // echo 456;die();
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