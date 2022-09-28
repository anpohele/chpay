<?php
/**
 * AdaPay 结算账户取现
 * author: adapay.com https://docs.adapay.tech/api/04-trade.html
 * Date: 2020/01/17
 */

# 加载SDK需要的文件
include_once  dirname(__FILE__). "/../../AdapaySdk/init.php";
# 加载商户的配置文件
include_once  dirname(__FILE__). "/../config.php";


# 初始化取现对象
$drawcash = new \AdaPaySdk\Drawcash();


$drawcash_params = array(
    'order_no'=> "CS_". date("YmdHis").rand(100000, 999999),
    'app_id'=> 'app_8cf327b5-84d3-45d3-8499-41211760c5d9',
//    'cash_type'=> 'T1',
    'cash_type'=> 'T1',
    'cash_amt'=> '20.00',
    'member_id'=> 'hf_prod_member_20210628',
//    'member_id'=> '0',
//    'notify_url'=> ''
);

# 账户取现
$drawcash->create($drawcash_params);

# 对账户取现结果进行处理
if ($drawcash->isError()){
    //失败处理
    var_dump($drawcash->result);
} else {
    //成功处理
    var_dump($drawcash->result);
}