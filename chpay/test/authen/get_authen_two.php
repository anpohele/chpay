<?php

include 'authen_demo.php';

/**
 * 说明 二要素(返照)验证接口
 * 请填写身份证号
 */
$oder = $authen->common->datetime2string(date('Y-m-d H:i:s'));
$id = $authen->common->ECBEncrypt("431021199210286398", $authen->common->param['seller_id']);
$tt = $authen->get_authen_two($oder, $id);