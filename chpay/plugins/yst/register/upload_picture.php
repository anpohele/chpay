<?php

include "register_demo.php";

//1.获取token
$token = $register->get_token();

//2.测试上传图片
$register->upload_picture('00', '../images/logo.png', $token);