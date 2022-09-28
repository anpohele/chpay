<?php

include 'dsf_demo.php';

/**
 * 单笔代付加急
 */
$oder = $dsf->common->datetime2string(date('Y-m-d H:i:s'));
$dsf->df_expedited($oder);

