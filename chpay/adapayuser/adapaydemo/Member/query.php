<?php
/**
 * AdaPay 查询普通用户
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2019/09/17
 */

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/../../AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/../config.php";


# 初始化用户对象类
$member = new \AdaPaySdk\Member();

# 查询用户对象
$member->query(['app_id'=> 'app_8cf327b5-84d3-45d3-8499-41211760c5d9', 'member_id'=> 'hf_prod_member_20210628']);

# 对查询用户对象结果进行处理
if ($member->isError()){
    //失败处理
    var_dump($member->result);
} else {
    //成功处理
    var_dump($member->result);
}