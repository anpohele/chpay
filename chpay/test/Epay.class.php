<?php


include 'demo.php';

class Epay
{
    protected $partner_id = '商户号'; //商户号
    protected $seller_id = '收款方银盛支付用户号（商户号）'; // 收款方银盛支付用户号（商户号）
    protected $seller_name = '注册时公司名称'; //收款方银盛支付客户名（注册时公司名称）
    protected $business_code = '01000010'; // 业务代码 固定值01000010
    protected $pfxpath = 'certs/证书名.pfx'; //商户私钥证书路径(发送交易签名使用)
    protected $pfxpassword = '密码'; //商户私钥证书密码
    protected $businessgatecerpath = "certs/businessgate.cer"; //银盛支付公钥证书路径(接收到银盛支付回执时验签使用)

//    protected $interface_url = 'https://mertest.ysepay.com/openapi_gateway/gateway.do'; //测试
    protected $interface_url = 'https://openapi.ysepay.com/gateway.do'; //正式
    protected $daifu_url = 'https://df.ysepay.com/gateway.do';
    protected $ds_url = 'https://ds.ysepay.com/gateway.do';

    public function getOrderNo($orderno)
    {
        $prefix = [
            'LC' => 10,
            'DK' => 11,
            'HK' => 12,
            'FL' => 13,
            'GZ' => 14,
            'RE' => 15,
            'RP' => 16,
            'DJ' => 17,
            'TX' => 18,
            'DF' => 19,
            'IG' => 20,
        ];
        $p = $prefix[substr($orderno, 0, 2)];
        if (!empty($p)) {
//            $orderno = substr($orderno, 2, strlen($orderno) - 2) . '_' . $p;
            $orderno = substr($orderno, 2, strlen($orderno) - 2);
        }
        return $orderno;
    }

    //收银台
    public function placeOrderPC(array $data)
    {
        $myParams = [
            'method' => 'ysepay.online.directpay.createbyuser', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'notify_url' => $data['notify_url'], //回调地址
            'return_url' => $data['return_url'], //页面跳转地址
            'version' => '3.0', //接口版本号
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'subject' => $data['desc'],
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'timeout_express' => '30m', //超时关闭  30分钟
            'business_code' => $this->business_code,
            'extra_common_param' => $data['attach'], //回传参数
        ];

        $myParams['sign'] = $this->sign($myParams);
        file_put_contents('./epay.txt', var_export($myParams, true), FILE_APPEND);
        $def_url = "<br /><form id='form' style='text-align:center;' method=post action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
//        $def_url .= "<input type=submit value='前往支付'>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;

    }

    //网关支付
    public function placeOrderGatewayPC(array $data)
    {
        $myParams = [
            'method' => 'ysepay.online.directpay.createbyuser', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'notify_url' => $data['notify_url'], //回调地址
            'return_url' => $data['return_url'], //页面跳转地址
            'version' => '3.0', //接口版本号
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'subject' => $data['desc'],
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'timeout_express' => '30m', //超时关闭  30分钟
            'pay_mode' => 'internetbank',
            'business_code' => $this->business_code,
            'extra_common_param' => $data['attach'], //回传参数
        ];

        $myParams['sign'] = $this->sign($myParams);
        file_put_contents('./epay.txt', var_export($myParams, true), FILE_APPEND);
        $def_url = "<br /><form id='form' style='text-align:center;' method=post action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
//        $def_url .= "<input type=submit value='前往支付'>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;

    }

    //扫码支付
    public function placeOrderQrcodePC(array $data)
    {
        $myParams = [
            'method' => 'ysepay.online.directpay.createbyuser', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'notify_url' => $data['notify_url'], //回调地址
            'return_url' => $data['return_url'], //页面跳转地址
            'version' => '3.0', //接口版本号
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'subject' => $data['desc'],
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'timeout_express' => '30m', //超时关闭  30分钟
            'business_code' => $this->business_code,
            'extra_common_param' => $data['attach'], //回传参数
        ];

        $myParams['sign'] = $this->sign($myParams);
        file_put_contents('./epay.txt', var_export($myParams, true), FILE_APPEND);
        $def_url = "<br /><form id='form' style='text-align:center;' method=post action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
//        $def_url .= "<input type=submit value='前往支付'>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;

    }

    //wap端下单
    public function placeOrder(array $data)
    {
        $myParams = [
            'method' => 'ysepay.online.wap.directpay.createbyuser', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'notify_url' => $data['notify_url'], //回调地址
            'return_url' => $data['return_url'], //页面跳转地址
            'version' => '3.0', //接口版本号
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'subject' => $data['desc'],
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'timeout_express' => '30m', //超时关闭  30分钟
            'business_code' => $this->business_code,
            'extra_common_param' => $data['attach'], //回传参数
//            'pay_mode' => 'native',
//            'bank_type' => '1903000'
        ];
        if (!is_weixin()) {
            $myParams['pay_mode'] = 'native';
            $myParams['bank_type'] = '1903000';
        } else {
            return $this->placeOrderWx($data);
        }

        $myParams['sign'] = $this->sign($myParams);
        file_put_contents('./epay.txt', var_export($myParams, true), FILE_APPEND);
        $def_url = "<br /><form id='form' style='text-align:center;' method='post' action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
//        $def_url .= "<input type=submit value='前往支付'>";
//        $def_url .= "</form>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;
    }

    public function placeOrderWx(array $data)
    {
        $myParams = [
            'method' => 'ysepay.online.jsapi.pay',
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'notify_url' => $data['notify_url'], //回调地址
//            'return_url' => $data['return_url'], //页面跳转地址
            'version' => '3.0', //接口版本号
        ];
        $biz_content = [
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'subject' => $data['desc'],
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'timeout_express' => '30m', //超时关闭  30分钟
            'business_code' => $this->business_code,
            'extra_common_param' => $data['attach'], //回传参数
            'sub_openid' => $_SESSION['openId'],
        ];
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        $myParams['sign'] = $this->sign($myParams);
        file_put_contents('./epay.txt', var_export($myParams, true), FILE_APPEND);
        $res = $this->curlPost($this->interface_url, $myParams);
        dump($res);
        die;
        $def_url = "<br /><form id='form' style='text-align:center;' method='post' action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
//        $def_url .= "<input type=submit value='前往支付'>";
//        $def_url .= "</form>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;
    }

    /**
     * 查询余额
     */
    public function getBalance()
    {
        $myParams = [
            'method' => 'ysepay.online.user.account.get', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
        ];
        $biz_content = [
            'user_code' => $this->seller_id,
            'user_name' => $this->seller_name,
        ];
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);

        $myParams['sign'] = $this->sign($myParams);
        $result = $this->curlPost($this->interface_url, $myParams);
        $data = json_decode($result, true);
        dump($data);
        die;
    }

    /**
     * 代付
     * @param array $data
     * @param string $type urgent 加急 实时到账  normal 普通代付一般是T+1,也有可能当天到.
     */
    public function pay(array $data, $type = 'urgent')
    {
        $myParams = [
//            'method' => 'ysepay.df.single.quick.accept', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
            'notify_url' => $data['notify_url'],
        ];
        $biz_content = [
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'business_code' => '2010005',
            'currency' => 'CNY',
            'total_amount' => $data['total_amount'], //金额 单位 元
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'subject' => $data['desc'],
            'bank_name' => $data['bank_name'], //银行名称
            'bank_province' => $data['bank_province'],
            'bank_city' => $data['bank_city'], //开户行所在市
            'bank_account_no' => $data['bank_account_no'], //银行帐号
            'bank_account_name' => $data['bank_account_name'], // 银行帐号用户名
            'bank_account_type' => $data['bank_account_type'], //银行账户类型  corporate :对公账户; persona :对私账户
            'bank_card_type' => $data['bank_card_type'] ? $data['bank_card_type'] : 'debit', //银行卡类型 此处必填: debit:借记卡
//            'extra_common_param' => $data['attach'], //回传参数
        ];
        if ($type == 'urgent') {
            $myParams['method'] = 'ysepay.df.single.quick.accept';
        } else {
            $myParams['method'] = 'ysepay.df.single.normal.accept';
        }
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $myParams['sign'] = $this->sign($myParams);
        ksort($myParams);
//        dump($myParams);echo $this->daifu_url;die;
//        return $myParams;
        $result = $this->curlPost($this->daifu_url, $myParams);
//        dump($result);die;
        file_put_contents('./epay.txt', var_export($myParams, true) . '---res---' . var_export($result, true), FILE_APPEND);
//        $data = json_decode($result, true);
        return $result;
    }

    /**
     * 代收
     * @param array $data
     * @param string $type urgent 加急 实时到账  normal 普通代付一般是T+1,也有可能当天到.
     */
    public function remitting(array $data, $type = 'urgent')
    {
//        require_once 'Des.class.php';
//        $Des = new Des();
//        $data['cert_no'] = $Des->encrypt($data['cert_no'], '     '.$this->partner_id, true);
        $data['cert_no'] = $this->curlPost('http://open.ysepay.com:4190/open_ysepay/encryption.do', ['content' => $data['cert_no'], 'merchants' => $this->partner_id]);

        //签约代收协议
        if (!$this->remitting_qy($data)) {
            return false;
        }

        $myParams = [
//            'method' => 'ysepay.df.single.quick.accept', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
            'notify_url' => $data['notify_url'],
        ];
        $biz_content = [
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'business_code' => '1010009',
            'currency' => 'CNY',
            'total_amount' => $data['total_amount'], //金额 单位 元
//            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
//            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'subject' => $data['desc'],
            'bank_name' => $data['bank_name'], //银行名称
            'bank_province' => $data['bank_province'],
            'bank_city' => $data['bank_city'], //开户行所在市
            'bank_account_no' => $data['bank_account_no'], //银行帐号
            'bank_account_name' => $data['bank_account_name'], // 银行帐号用户名
            'bank_account_type' => $data['bank_account_type'], //银行账户类型  corporate :对公账户; persona :对私账户
            'bank_telephone_no' => $data['bank_telephone_no'],
            'cert_type' => '00',
            'cert_no' => $data['cert_no'],
//            'cert_expire' => '20201007',
            'bank_card_type' => $data['bank_card_type'] ? $data['bank_card_type'] : 'debit', //银行卡类型 此处必填: debit:借记卡
//            'extra_common_param' => $data['attach'], //回传参数
        ];
        if ($type == 'urgent') {
            $myParams['method'] = 'ysepay.ds.single.quick.accept';
        } else {
            $myParams['method'] = 'ysepay.ds.single.normal.accept';
        }
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $myParams['sign'] = $this->sign($myParams);
        ksort($myParams);
//        dump($myParams);echo $this->daifu_url;die;
//        return $myParams;
        $result = $this->curlPost($this->ds_url, $myParams);
//        dump($result);die;
        file_put_contents('./epay.txt', var_export($myParams, true) . '---res---' . var_export($result, true), FILE_APPEND);
//        $data = json_decode($result, true);

//        $def_url = "<br /><form id='form' style='text-align:center;' method=post action='{$this->interface_url}'>";
//        while ($param = each($myParams)) {
//            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
//        }
////        $def_url .= "<input type=submit value='前往支付'>";
//        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";
//
//        echo $def_url;die;

        return $result;
    }

    /**
     * 代收签约
     * @param array $data
     * @param string $type urgent 加急 实时到账  normal 普通代付一般是T+1,也有可能当天到.
     */
    public function remitting_qy(array $data)
    {
//        $data['cert_no'] = $this->curlPost('http://open.ysepay.com:4190/open_ysepay/encryption.do', ['content'=>$data['cert_no'],'merchants'=>$this->partner_id]);
        $myParams = [
            'method' => 'ysepay.ds.protocol.single.accept', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
            'notify_url' => $data['notify_url'],
        ];
        $biz_content = [
            'protocol_no' => $this->getOrderNo($data['orderno']),
            'business_code' => '1010009',
            'effect_date' => date('Ymd'),
            'expire_date' => date('Ymd', strtotime('+1 day')),
            'bank_account_type' => $data['bank_account_type'], //银行账户类型  corporate :对公账户; persona :对私账户
            'bank_card_type' => $data['bank_card_type'] ? $data['bank_card_type'] : 'debit', //银行卡类型 此处必填: debit:借记卡
            'bank_name' => $data['bank_name'], //银行名称
            'bank_account_no' => $data['bank_account_no'], //银行帐号
            'bank_account_name' => $data['bank_account_name'], // 银行帐号用户名
            'bank_province' => $data['bank_province'],
            'bank_city' => $data['bank_city'], //开户行所在市
            'bank_telephone_no' => $data['bank_telephone_no'],
            'cert_type' => '00',
            'cert_no' => $data['cert_no'],
            'month_num_limit' => 50,
            'month_amount_limit' => 500000,
            'currency' => 'CNY',
        ];
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $myParams['sign'] = $this->sign($myParams);
        ksort($myParams);
//        dump($myParams);echo $this->daifu_url;die;
//        return $myParams;
        $result = $this->curlPost($this->ds_url, $myParams);
//        dump($result);die;
        file_put_contents('./epay.txt', '--代收协议其签约--' . var_export($myParams, true) . '---res---' . var_export($result, true), FILE_APPEND);
        $data = json_decode($result, true);
        foreach ($data as $k => $v) {
            if ($k != 'sign') {
                $data = $v;
                break;
            }
        }
        if ($data['protocol_status'] == 'PROTOCOL_ACCEPT_SUCCESS' || $data['sub_code'] == '9900') {
            return true;
        } else {
            return false;
        }
    }

    public function getBankType($name)
    {
        $arr = [
            '1041000' => '中国银行',
            '1031000' => '中国农业银行',
            '1021000' => '中国工商银行',
            '1051000' => '中国建设银行',
            '3012900' => '交通银行',

            '3085840' => '招商银行',
            '3051000' => '中国民生银行',
            '3093910' => '兴业银行',
            '3102900' => '浦发银行', // 上海浦东发展银行
//            '3065810' => '广东发展银行',

            '3021000' => '中信银行',
            '3031000' => '光大银行',
            '4031000' => '中国邮政储蓄银行',
            '3071000' => '平安银行',
//            '3131000' => '北京银行',

//            '3133010' => '南京银行',
//            '3133320' => '宁波银行',
//            '3222900' => '上海农村商业银行',
//            '5021000' => '东亚银行',
            '3041000' => '华夏银行',
        ];

        foreach ($arr as $k => $v) {
            if (strpos($v, $name) !== false || strpos($name, $v) !== false) {
                if ($v == '浦发银行') {
                    $v = '上海浦东发展银行';
                }
                return [$k, $v];
            }
        }
    }

    /**
     * DES加密方法
     * @param $data 传入需要加密的证件号码
     * @param $key key为商户号前八位.不足八位的需在商户号前补空格
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
     * @param $key key为商户号前八位.不足八位的需在商户号前补空格
     * @return string 返回解密后的证件号码
     */
    function doECBDecrypt($data, $key)
    {
        $encrypted = base64_decode($data);
        $decrypted = openssl_decrypt($encrypted, 'DES-ECB', $key, 1);
        return $decrypted;
    }

    /**
     * 无卡快捷支付
     * @param array $data
     */
    public function noCardPay(array $data, array $bank)
    {
        //  $bank['idcard'] = $this->ECBEncrypt($bank['idcard'], '     '.$this->partner_id);
        $bank['idcard'] = 'XvLS4lrBPNqBC4PToT6YyRT0I1iPCfzY';
        $myParams = [
            'method' => 'ysepay.online.fastpay', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
            'notify_url' => $data['notify_url'],
        ];
        $bank_info = $this->getBankType($bank['bank']);
//        dump($bank);die;
        $biz_content = [
            'out_trade_no' => $this->getOrderNo($data['orderno']),
            'business_code' => $this->business_code,
            'currency' => 'CNY',
            'total_amount' => $data['total_amount'], //金额 单位 元
            'timeout_express' => '30m', //超时关闭  30分钟
            'seller_id' => $this->seller_id, // 收款方银盛支付用户号（商户号）
            'seller_name' => $this->seller_name, //收款方银盛支付客户名（注册时公司名称）
            'subject' => $data['desc'],
            'bank_type' => $bank_info[0],
            'bank_name' => $bank_info[1], //银行名称
            'buyer_card_number' => $bank['card'], //银行帐号
            'buyer_name' => $bank['name'], // 银行帐号用户名
            'bank_account_type' => $bank['account_type'], //银行账户类型  corporate :对公账户; persona :对私账户
            'buyer_mobile' => $bank['tel'],
            'support_card_type' => $bank['card_type'] ? $bank['card_type'] : 'debit', //银行卡类型 此处必填: debit:借记卡
            'pyerIDTp' => '01',
            'pyerIDNo' => $bank['idcard'],
//            'cert_expire' => '20201007',
//            'extra_common_param' => $data['attach'], //回传参数
        ];
//        dump($myParams);dump($biz_content);die;
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $myParams['sign'] = $this->sign($myParams);
        ksort($myParams);
//        dump($myParams);
        $result = $this->curlPost($this->interface_url, $myParams);
        file_put_contents('BASE_PATH . "log/ok.txt"', '--无卡快捷支付--' . var_export($myParams, true) . '--res--' . var_export($result, true), FILE_APPEND);

        return $result;
    }

    /**
     * 订单查询
     * @param array $data
     * @param string $type urgent 加急 实时到账  normal 普通代付一般是T+1,也有可能当天到.
     */
    public function getOrderInfo(array $data)
    {
//        $bank['idcard'] = $this->curlPost('http://open.ysepay.com:4190/open_ysepay/encryption.do', ['content'=>$bank['idcard'],'merchants'=>$this->partner_id]);
        $myParams = [
            'method' => 'ysepay.online.trade.query', //接口名称
            'partner_id' => $this->partner_id, //商户号
            'timestamp' => date('Y-m-d H:i:s'), //交易开始时间
            'charset' => 'UTF-8',
            'sign_type' => 'RSA', //前面类型
            'version' => '3.0', //接口版本号
        ];
        $biz_content = [
            'out_trade_no' => $this->getOrderNo($data['orderno']),
//            'cert_expire' => '20201007',
//            'extra_common_param' => $data['attach'], //回传参数
        ];
//        dump($myParams);dump($biz_content);die;
        $myParams['biz_content'] = json_encode($biz_content, JSON_UNESCAPED_UNICODE);
        $myParams['sign'] = $this->sign($myParams);
        ksort($myParams);
//        $result = $this->curlPost($this->interface_url, $myParams);dump($result);die;
//        file_put_contents('./epay.txt', '--无卡快捷支付--'.var_export($myParams,true), FILE_APPEND);
        $def_url = "<br /><form id='form' style='text-align:center;' method='post' action='{$this->interface_url}'>";
        while ($param = each($myParams)) {
            $def_url .= "<input type = 'hidden' id='" . $param['key'] . "' name='" . $param['key'] . "' value='" . $param['value'] . "' />";
        }
        $def_url .= "<input type=submit value='前往支付'></form>";
        $def_url .= "</form><script src='/Public/Main/i/jquery.min.js'></script><script>\$(function(){\$('#form').submit();})</script>";

        return $def_url;
    }

    /**
     * 验签转明码
     * @param input check
     * @param input msg
     * @return data
     * @return success
     */

    public function sign_check($sign, $data)
    {

        $publickeyFile = $this->businessgatecerpath; //公钥
        $certificateCAcerContent = file_get_contents($publickeyFile);
        $certificateCApemContent = '-----BEGIN CERTIFICATE-----' . PHP_EOL . chunk_split(base64_encode($certificateCAcerContent), 64, PHP_EOL) . '-----END CERTIFICATE-----' . PHP_EOL;
        // 签名验证
        $success = openssl_verify($data, base64_decode($sign), openssl_get_publickey($certificateCApemContent), OPENSSL_ALGO_SHA1);

        return $success;
    }

    /**
     * 签名加密
     * @param input data
     * @return success
     * @return check
     * @return msg
     */
    public function sign_encrypt($input)
    {

        $return = [
            'success' => 0,
            'msg' => '',
            'check' => ''
        ];
        $pkcs12 = file_get_contents($this->pfxpath); //私钥
        if (openssl_pkcs12_read($pkcs12, $certs, $this->pfxpassword)) {
            $privateKey = $certs['pkey'];
            $publicKey = $certs['cert'];
            $signedMsg = "";
            if (openssl_sign($input['data'], $signedMsg, $privateKey, OPENSSL_ALGO_SHA1)) {
                $return['success'] = 1;
                $return['check'] = base64_encode($signedMsg);
                $return['msg'] = base64_encode($input['data']);

            }
        }

        return $return;

    }

    //签名
    public function sign($data)
    {
        ksort($data);
        $signStr = "";
        foreach ($data as $key => $val) {
            $signStr .= $key . '=' . $val . '&';
        }
        $signStr = trim($signStr, '&');
        $sign = $this->sign_encrypt(array('data' => $signStr));
        return trim($sign['check']);
    }

    //验签
    public function sign_verify($data)
    {
        //返回的数据处理
        $sign = trim($data['sign']);
        unset($data['sign']);
        ksort($data);
        $url = "";
        foreach ($data as $key => $val) {
            /* 验证签名 */
            if ($val) $url .= $key . '=' . $val . '&';
        }
        $str = trim($url, '&');
        if ($this->sign_check($sign, $str) != true) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * curl POST
     *
     * @param   string  url
     * @param   array   数据
     * @param   int     请求超时时间
     * @param   bool    HTTPS时是否进行严格认证
     * @return  string
     */
    function curlPost($url, $data = array(), $timeout = 30, $CA = true)
    {

        $cacert = ROOT_PATH . '/Epay/cacert.pem'; //CA根证书
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout - 2);
//        if ($SSL && $CA) {
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
//            curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
//        } else if ($SSL && !$CA) {
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
//        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:')); //避免data数据过长问题
        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); //data with URLEncode

        $ret = curl_exec($ch);
//        var_dump(curl_error($ch));  //查看报错信息

        curl_close($ch);
        return $ret;
    }

}

$ppp = new Epay();