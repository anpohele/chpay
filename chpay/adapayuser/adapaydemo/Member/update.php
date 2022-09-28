<?php
/**
 * AdaPay 更新普通用户
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/09/17
 */

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/../../AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/../config.php";


# 初始化用户对象类
$member = new \AdaPaySdk\Member();

# 更新用户对象设置
//$member_params = array(
//    # app_id
//    'app_id'=> 'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
//    # 用户id
//    'member_id'=> 'hf_prod_member_20210628',
//    # 用户地址
//    'location'=> '上海市浦东新区张江',
//    # 用户邮箱
//    'email'=> '1311401468@qq.com',
//    # 性别
//    'gender'=> 'MALE',
//    # 用户手机号
//    'tel_no'=> '15736827812',
//    # 是否禁用该用户
//    'disabled'=> 'N',
//    # 用户昵称
//    'nickname'=> '小马',
//    # 用户姓名
//    'user_name'=>'马海乐',
//    # 证件类型
//    'cert_type'=> '00',
//    # 证件号
//    'cert_id'=>'410426199611050510'
//);

$member_params = array(
    # app_id
    'app_id'=> 'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
    # 用户id
    'member_id'=> 'aphl_202107271526',
    # 用户地址
//    'location'=> '上海市浦东新区张江',
    # 用户邮箱
//    'email'=> '1311401468@qq.com',
    # 性别
//    'gender'=> 'MALE',
    # 用户手机号
    'tel_no'=> '18101832756',
    # 用户昵称
    'nickname'=> '孙颢玮',
);
# 更新用户对象
$member->update($member_params);

# 对更新用户对象结果进行处理
if ($member->isError()){
    //失败处理
    var_dump($member->result);
} else {
    //成功处理
    var_dump($member->result);
}