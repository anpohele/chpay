<?php

include "../demo.php";

/**
 * 进件
 */
class register_demo
{
    public $common;

    function __construct()
    {
        $this->common = new demo();
    }

    /**
     * 获取注册token
     */
    function get_token()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.merchant.register.token.get';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = new class{};   //空对象
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $response = $this->common->post_url($this->common->param['register_url'], $myParams, 'ysepay_merchant_register_token_get_response');
        echo '<br/>';
        return $response['token'];
    }

    /**
     * 上传图片接口
     * 证件类型：00:公民身份证正面,30:公民身份证反面,33:手持身份证正扫面照,34:门头照,35:结算银行卡正面照,36:结算银行卡反面照,
     *          19:营业执照,31:客户协议,32:授权书,37开户许可证或印鉴卡 20.组织机构代码证
     *
     * 小微商户需上传：00,30,35,36,31
     * 企业商户需上传：00,30,19,31,37
     * 个体商户需上传：00,30,19,35,36,31
     *
     * @param $picType
     * @param $picFile
     * @param $token
     * @return mixed
     */
    function upload_picture($picType, $picFile, $token)
    {
        $ch = curl_init($this->common->param['upload_picture_url']);
        if (class_exists('\CURLFile')) {    //PHP版本>=5.5
            curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
            $myParams = array(
                'picType' => $picType,
                'token' => $token,
                'picFile' => new \CURLFile(realpath($picFile)),
                'superUsercode' => $this->common->param['seller_id']
            );
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {   //PHP版本<=5.5
                curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
            }
            $myParams = array(
                'picType' => $picType,
                'token' => $token,
                'picFile' => '@' . $picFile,
                'superUsercode' => $this->common->param['seller_id']
            );
        }
        ksort($myParams);
        echo '<br/>';
        var_dump($myParams);
        echo '<br/>';
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $myParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            var_dump($ch);
        } else {
            $response = json_decode($response, true);
            var_dump($response);
        }
    }


    /**
     * 注册查询接口
     */
    function register_query()
    {
        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.merchant.register.query';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "usercode" => 'dsf1',
//            "merchant_no" => 'dsf1',
        );
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $ch = curl_init($this->common->param['register_url']);
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
        }
    }


    /**
     * 商户注册接口
     *
     * 步骤：1.获取token
     *      2.用获取的token上传图片
     *      3.用获取的token上传注册文本信息
     *
     * @param $token
     */
    function register($token)
    {

        $myParams = array();
        $myParams['charset'] = 'UTF-8';
        $myParams['method'] = 'ysepay.merchant.register.accept';
        $myParams['partner_id'] = $this->common->param['seller_id'];
        $myParams['sign_type'] = 'RSA';
        $myParams['timestamp'] = date('Y-m-d H:i:s', time());
        $myParams['version'] = '3.0';
        $myParams['notify_url'] = $this->common->param['notify_url'];
        $biz_content = array(
            "merchant_no" => 'dsf3',
            "cust_type" => 'O',     //小微
//            "cust_type" => 'B',     //企业
//            "cust_type" => 'C',     //个体
            "token" => $token,
            "another_name" => '淘宝',
            "cust_name" => '杭州淘宝',
            "industry" => '20',
            "province" => '浙江省',
            "city" => '杭州市',
            "company_addr" => '详细地址',
            "legal_name" => '企业法人名字',    //企业法人名字,小微商户可空
            "legal_tel" => '13377778888',      //企业法人手机号
            "legal_cert_type" => '00',
            "legal_cert_no" => $this->common->ECBEncrypt("证件号", sprintf('%8.8s', $myParams['partner_id'])),   //证件号。DES加密
//            "bus_license" => '沪-A1283123132',    //营业执照,个体商户、企业户时为必填
//            "bus_license_expire" => '20191229',     //营业执照有效期，客户类型为个体商户、企业商户时为必填
            "settle_type" => '1',   //银行卡账户
//            "settle_type" => '0',   //平台内账户
            "bank_account_no" => '银行卡号',
            "bank_account_name" => '王二小',
            "bank_account_type" => 'personal',      //对私账户
//            "bank_account_type" => 'corporate',      //对公账户
            "bank_card_type" => 'debit',    //借记卡
//            "bank_card_type" => 'credit',    //贷记卡
//            "bank_card_type" => 'unit',    //单位结算卡
            "bank_name" => '中国工商银行股份有限公司北京市分行营业部',
            "bank_type" => '工商银行',
            "bank_province" => '广东省',
            "bank_city" => '深圳市',
            "cert_type" => '00',    //目前只支持00，00是身份证
            "cert_no" => $this->common->ECBEncrypt("证件号", sprintf('%8.8s', $myParams['partner_id'])),    //开户人证件号,DES加密
            "bank_telephone_no" => '13377778888',
        );

        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);//构造字符串
        ksort($myParams);
        var_dump($myParams);
        $signStr = "";
        foreach ($myParams as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = rtrim($signStr, '&');
        var_dump($signStr);
        $sign = $this->common->sign_encrypt(array('data' => $signStr));
        $myParams['sign'] = trim($sign['check']);
        var_dump($myParams);
        $ch = curl_init($this->common->param['register_url']);
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
        }
    }
}

$register = new register_demo();