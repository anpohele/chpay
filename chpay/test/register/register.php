<?php

include "register_demo.php";

//1.获取token
$token = $register->get_token();

//2.上传图片
$register->upload_picture('00', '../images/logo.png', $token);      //身份证正面
$register->upload_picture('30', '../images/logo.png', $token);      //身份证反面
$register->upload_picture('31', '../images/logo.png', $token);      //客户协议
$register->upload_picture('35', '../images/logo.png', $token);      //结算银行卡正面照
$register->upload_picture('36', '../images/logo.png', $token);      //结算银行卡反面照

//3.上传注册文本信息
$register->register($token);