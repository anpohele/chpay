<?php
/**
 * AdaPay 创建普通用户
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/09/17
 */

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/../../AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/../config.php";

// echo(132);die;
# 初始化用户对象类
$member = new \AdaPaySdk\Member();

//创建用户
$member_params = array(
    # app_id
    'app_id'=> 'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
    # 用户id
    'member_id'=> 'aphl_202107271534',
    # 用户地址
//    'location'=> '上海市浦东新区张江',
    # 用户邮箱
//    'email'=> '1311401468@qq.com',
    # 性别
//    'gender'=> 'MALE',
    # 用户手机号
    'tel_no'=> '13148182527',
    # 用户昵称
    'nickname'=> '徐永强',
);


//创建结算账户
//$member_params = [
//    'member_id'=>'aphl_202107271527',
//    'app_id'=>'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
//    'channel'=>'bank_account',
//    'account_info'=>[
//        #银行卡号
//        'card_id'=>'6230910199127344816',
//        #银行卡账户名称
//        'card_name'=>'孙颢玮',
//        #身份证id
//        'cert_id'=>'410411200105285529',
//        #身份证类型
//        'cert_type'=>'00',
//        #电话
//        'tel_no'=>'18101832756',
//        #开户行号
////        'bank_code'=>'105503000043',
//        'bank_code'=>'88003911',
//        #银行账户类型1 对公 2对私
//        'bank_acct_type'=>'2',
//        #省份
////        'prov_code'=>'410000',
//        #地区
////        'area_code'=>'411002'
//    ]
//];
# 创建
$member->create($member_params);

# 对创建用户对象结果进行处理
if ($member->isError()){
    //失败处理
    var_dump($member->result);
} else {
    //成功处理
    var_dump($member->result);
}