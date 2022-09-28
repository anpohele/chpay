<?php

include 'dsf_demo.php';

/**
 * 说明，批量代付(银行卡)发起
 */
$oder = $dsf->common->datetime2string(date('Y-m-d H:i:s'));
$dsf->batch_df($oder);
