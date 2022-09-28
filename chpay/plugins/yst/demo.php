<?php

/**
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究银盛支付接口使用，只是提供一个参考。
 */
class demo
{

    /**
     * 构造函数
     *
     * @access  public
     * @param
     * @return void
     */
    function __construct()
    {
        $this->demo();
        date_default_timezone_set('PRC');
        define('BASE_PATH', str_replace('\\', '/', realpath(dirname(__FILE__) . '/')) . "/");
    }

    /**
     * 实例化固定参数值
     * 验证公钥 商户号 商户名 订单地址 订单查询地址 代付地址 代付查询地址 代收地址 代收查询地址 证书密码 异步地址 同步地址
     */
    function demo()
    {

        $this->param = array();
        $this->param['businessgatecerpath'] = "../certs/businessgate.cer";
//        $this->param['businessgatecerpath'] = 'http://' . $_SERVER['HTTP_HOST'] . "/pay/certs/businessgate.cer";

        $this->param['private_key'] = "../certs/证书名.pfx";

        $this->param['seller_id'] = '商户号';

        $this->param['seller_name'] = '商户名';

//        $this->param['order_url'] = 'https://mertest.ysepay.com/openapi_gateway/gateway.do';
        $this->param['order_url'] = 'https://openapi.ysepay.com/gateway.do';

//        $this->param['order_query_url'] = 'https://mertest.ysepay.com/openapi_gateway/gateway.do';
        $this->param['order_query_url'] = 'https://search.ysepay.com/gateway.do';

//        $this->param['df_url'] = 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do';
        $this->param['df_url'] = 'https://df.ysepay.com/gateway.do';

//        $this->param['df_query_url'] = 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do';
        $this->param['df_query_url'] = 'https://searchdf.ysepay.com/gateway.do';

//        $this->param['ds_url'] = 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do';
        $this->param['ds_url'] = 'https://ds.ysepay.com/gateway.do';

//        $this->param['ds_query_url'] = 'https://mertest.ysepay.com/openapidsf_gateway/gateway.do';
        $this->param['ds_query_url'] = 'https://searchds.ysepay.com/gateway.do';

        $this->param['notify_url'] = 'http://api.test.ysepay.net/atinterface/receive_return.htm';
        $this->param['return_url'] = 'http://api.test.ysepay.net/atinterface/receive_return.htm';
        $this->param['pfxpassword'] = '证书密码';
        $this->param['merchant_code'] = 'liuch';
        $this->param['ydt_url'] = '';
        $this->param['order_common'] = 'http://10.213.32.58:16000/openapi_dsf_gateway/gateway.do';
        $this->param['ydt_url_df'] = '';
        $this->param['register_url'] = 'http://10.213.32.58:10011/register_gateway/gateway.do';
        $this->param['upload_picture_url'] = 'http://10.213.32.58:13021/yspay-upload-service?method=upload';
        $this->param['division_refund_url'] = 'http://10.213.32.58:12005/openapi_gateway/gateway.do';
    }


    /**
     * 同步响应操作
     */
    function respond()
    {
        //返回的数据处理
        @$sign = trim($_POST['sign']);
        $result = $_POST;
        unset($result['sign']);
        ksort($result);
        $url = "";
        foreach ($result as $key => $val) {
            if ($val) $url .= $key . '=' . $val . '&';
        }
        $data = trim($url, '&');
        var_dump($data);
        /*写入日志*/
        $file = BASE_PATH . "log/respond.txt";
        file_put_contents($file, "\r\n", FILE_APPEND);
        file_put_contents($file, "return|data:" . $data . "|sign:" . $sign, FILE_APPEND);
        /* 验证签名 仅作基础验证*/
        var_dump('data:' . $data);
        var_dump('sign:' . $sign);
        if ($this->sign_check($sign, $data) == true) {
            echo "验证签名成功!";
        } else {
            echo '验证签名失败!';
        }
    }

    /**
     * 异步响应操作
     */
    function respond_notify()
    {
        //返回的数据处理
        @$sign = trim($_POST['sign']);
        $result = $_POST;
        unset($result['sign']);
        ksort($result);
        $url = "";
        foreach ($result as $key => $val) {
            if ($val) $url .= $key . '=' . $val . '&';
        }
        $data = trim($url, '&');
        /* 验证签名 仅作基础验证*/
        /*写入日志*/
        $file = BASE_PATH . "log/notify.txt";
        if ($this->sign_check($sign, $data) == true) {
            file_put_contents($file, "\r\n", FILE_APPEND);
            file_put_contents($file, "Verify success!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
        } else {
            file_put_contents($file, "\r\n", FILE_APPEND);
            file_put_contents($file, "Validation failure!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
        }
        /*
           开发须知:
           收到异步通知后,必须响应success给银盛,用于告诉银盛已成功接收到异步消息,
           多次不返回success的商户银盛将不会往商户异步地址发送异步消息(并拉黑商户异步地址)
         */
        echo 'success';
        exit;
    }

    /**
     * 异步响应操作
     */
    function ydt_respond_notify()
    {
        //返回的数据处理
        @$sign = trim($_POST['sign']);
        $result = $_POST;
        unset($result['sign']);
        ksort($result);
        $url = "";
        foreach ($result as $key => $val) {
            if ($val) $url .= $key . '=' . $val . '&';
        }
        $data = trim($url, '&');
        /* 验证签名 仅作基础验证*/
        /*写入日志*/
        $file = BASE_PATH . "log/notify.txt";
        if ($this->sign_check($sign, $data) == true) {
            file_put_contents($file, "\r\n", FILE_APPEND);
            file_put_contents($file, "Verify success!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
        } else {
            file_put_contents($file, "\r\n", FILE_APPEND);
            file_put_contents($file, "Validation failure!|notify|:" . $data . "|sign:" . $sign, FILE_APPEND);
        }
        /*
           开发须知:
           收到异步通知后,必须响应success给银盛,用于告诉银盛已成功接收到异步消息,
           多次不返回success的商户银盛将不会往商户异步地址发送异步消息(并拉黑商户异步地址,拉黑多个地址后拉黑商户号 无法解除)
         */
        echo 'success';
        exit;


    }

    /**
     * 日期转字符
     * 输入参数：yyyy-MM-dd HH:mm:ss
     * 输出参数：yyyyMMddHHmmss
     */
    function datetime2string($datetime)
    {

        return preg_replace('/\-*\:*\s*/', '', $datetime);
    }

    /**
     * 签名加密
     * @param input data
     * @return success
     * @return check
     * @return msg
     */
    function sign_encrypt($input)
    {

        $return = array('success' => 0, 'msg' => '', 'check' => '');
        $pkcs12 = file_get_contents($this->param['private_key']);
        if (openssl_pkcs12_read($pkcs12, $certs, $this->param['pfxpassword'])) {
            var_dump('证书,密码,正确读取');
            $privateKey = $certs['pkey'];
            $publicKey = $certs['cert'];
            $signedMsg = "";
            print_r("加密密钥" . $privateKey);
            if (openssl_sign($input['data'], $signedMsg, $privateKey, OPENSSL_ALGO_SHA1)) {
                var_dump('签名正确生成');
                $return['success'] = 1;
                $return['check'] = base64_encode($signedMsg);
                $return['msg'] = base64_encode($input['data']);
            }
        }

        return $return;
    }


    /**
     * 数组转字符串
     */
    function arrayToString($arr)
    {
        if (is_array($arr)) {
            return implode(',', array_map('arrayToString', $arr));
        }
        return $arr;
    }


    /**
     * DES加密方法
     * @param $data 传入需要加密的证件号码
     * @return string 返回加密后的字符串
     */
    function ECBEncrypt($data, $key)
    {
        $encrypted = openssl_encrypt($data, 'DES-ECB', $key, 1);
        return base64_encode($encrypted);
    }

    /**
     * DES解密方法
     * @param $data 传入需要解密的字符串
     * @return string 返回解密后的证件号码
     */
    function doECBDecrypt($data, $key)
    {
        $encrypted = base64_decode($data);
        $decrypted = openssl_decrypt($encrypted, 'DES-ECB', $key, 1);
        return $decrypted;
    }

    /**
     * 二要素带返照接口返回的加密字段
     *
     * @param $data
     */
    function base64toimages($data)
    {
        $img = base64_decode($data);
        Header("Content-type: image/jpeg");//直接输出显示jpg格式图片
        echo $img;
    }

    /**
     * post发送请求
     *
     * @param $url
     * @param $myParams
     * @param $response_name
     * @return false|string
     */
    function post_url($url, $myParams, $response_name)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($myParams));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
            if($response['sign'] != null){
                $sign = $response['sign'];
                echo $sign;
                $data = json_encode($response[$response_name], JSON_UNESCAPED_UNICODE);
                /* 验证签名 仅作基础验证*/
                if ($this->sign_check($sign, $data) == true) {
                    echo "验证签名成功!";
                    return $response[$response_name];
                } else {
                    echo '验证签名失败!';
                }
            }
        }
    }


    /**
     * 验签转明码
     * @param $sign 签名字符串
     * @param $data
     *
     * @return $success
     */
    function sign_check($sign, $data)
    {

        $publickeyFile = $this->param['businessgatecerpath']; //公钥
        $certificateCAcerContent = file_get_contents($publickeyFile);
        $certificateCApemContent = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
        print_r("验签密钥" . $certificateCApemContent);
        // 签名验证
        $success = openssl_verify($data, base64_decode($sign), openssl_get_publickey($certificateCApemContent), OPENSSL_ALGO_SHA1);
        var_dump($success);
        return $success;
    }

}
