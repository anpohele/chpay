<?php
include '../demo.php';

$s = new demo();
/**
 * DES加密
 */
$no = $s->ECBEncrypt("321083201711110119", "zhang_chenfei");
var_dump($no);

/**
 * DES解密
 */
$on = $s->doECBDecrypt('MRpPrkN60PY=', "zhang_chenfei");
var_dump($on);