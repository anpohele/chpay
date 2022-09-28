<?php
namespace JytPay\Client_ACCESS;
include 'JytJsonClient.php';

/**
 * Created by PhpStorm.
 * User: yabin
 * Date: 2021/9/15
 * Time: 10:25
 */


// 请求
test_tm1002();

/**
 * 测试入网申请
 */
function test_tm1002() {
    $client = new JytJsonClient;
    $client -> init();

    // 报文头信息
    $data['head']['version']='1.0.0';
    $data['head']['tranType']='01';
    $data['head']['merchantId']= $client->config->merchant_id;
    $data['head']['tranDate']=date('Ymd',time());
    $data['head']['tranTime']=date('His',time());
    $data['head']['tranFlowid']= $client->config->merchant_id . date('YmdHis',time()) . substr(rand(),4);
    $data['head']['tranCode']= 'TM1002';
    // 报文体请参照文档填写
    $data['body']['merchantName']= '西安市莲湖区金寿堂保健按摩区';
    $data['body']['merchantShortName']= '西安金寿堂';
    $data['body']['companyType']= '2';
    $data['body']['merchantProvince']= '610';
    $data['body']['merchantCity']= '7910';
    $data['body']['merchantCounty']= '';
    $data['body']['contactCertType']= '01';
    $data['body']['contactName']= '徐土玲';
    $data['body']['contactIdCardNo']= '610122198003092021';
    $data['body']['contactCertValid']= '2006053120260531';
    $data['body']['contactPhone']= '18629642451';
    $data['body']['contactEmailOne']= 'jinshoutang@qq.com';
    $data['body']['operateAddress']= '西安市莲湖区桃园路88号';
    $data['body']['bankAccountNo']= '6230270100024005199';
    $data['body']['bankAccountName']= '徐土玲';
    $data['body']['bankCode']= '99003736';
    $data['body']['bankProvinceCode']= '610';
    $data['body']['bankCityCode']= '7910';
    $data['body']['businessCertType']= '31';
    $data['body']['businessLicenseNo']= '92610104MA6U140NXY';
    $data['body']['businessLicensePicName']= '92610104MA6U140NXY'; //营业执照照片名称
    $data['body']['businessLicenseStart']= '20180504';
    $data['body']['businessLicenseEnd']= '30000101';
    $data['body']['legalName']= '徐土玲';
    $data['body']['legalEntityType']= '01';
    $data['body']['legalEntityId']= '610122198003092021';
    $data['body']['legalEntityIdVaild']= '2006053120260531';
    $data['body']['legalEntityIdVaild']= '20260531';
    // ......省略
    $res = $client->sendReq($data, null);
    echo $res;
}