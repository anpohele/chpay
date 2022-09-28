<?php
/**
 * AdaPay 创建结算账户
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/09/17
 */

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/../../AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/../config.php";



# 初始化结算账户对象类
$account = new \AdaPaySdk\SettleAccount();

$account_params = array(
    'member_id'=>'aphl_202107271534',
    'app_id'=>'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
    'channel'=>'bank_account',
    'account_info'=>[
    #银行卡号
    'card_id'=>'6214830021876239',
    #银行卡账户名称
    'card_name'=>'徐永强',
    #身份证id
    'cert_id'=>'320303199112204915',
    #身份证类型
    'cert_type'=>'00',
    #电话
    'tel_no'=>'13148182527',
    #开户行号
    'bank_code'=>'03080000',
    #银行账户类型1 对公 2对私
    'bank_acct_type'=>'2',
    #省份
//    'prov_code'=>'0033',
//    #地区
//    'area_code'=>'3308'
]
);

# 创建结算账户
$account->create($account_params);

# 对创建结算账户结果进行处理
if ($account->isError()){
    //失败处理
    var_dump($account->result);
} else {
    //成功处理
    var_dump($account->result);
}