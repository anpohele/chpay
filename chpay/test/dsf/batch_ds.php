<?php


include 'dsf_demo.php';

/**
 * 说明 批量代收请求接口
 */
$no = $dsf->common->ECBEncrypt("370705197804099954", sprintf('%8.8s', $dsf->common->param['seller_id']));
$oder = $dsf->common->datetime2string(date('Y-m-d H:i:s'));
$dsf->batch_ds($oder, $no);
