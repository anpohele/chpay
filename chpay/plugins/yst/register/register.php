<?php

include "register_demo.php";

//1.��ȡtoken
$token = $register->get_token();

//2.�ϴ�ͼƬ
$register->upload_picture('00', '../images/logo.png', $token);      //���֤����
$register->upload_picture('30', '../images/logo.png', $token);      //���֤����
$register->upload_picture('31', '../images/logo.png', $token);      //�ͻ�Э��
$register->upload_picture('35', '../images/logo.png', $token);      //�������п�������
$register->upload_picture('36', '../images/logo.png', $token);      //�������п�������

//3.�ϴ�ע���ı���Ϣ
$register->register($token);