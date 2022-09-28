<?php
include "common.php";
// ini_set("display_errors", "on"); // 显示错误提示，仅用于测试时排查问题
// error_reporting(E_ALL); // 显示所有错误提示，仅用于测试时排查问题

if($order){
    $out_trade_no = $order['out_trade_no'];
    $realmoney = $order['realmoney'];
    $url =urlencode("http://ys.dickmorley.cn/plugins/hc/submit.php?money=$realmoney&out_trade_no=$out_trade_no");
    // echo $url;die;
    header("Location:alipays://platformapi/startApp?appId=20000067&url=$url");
}

# 下单逻辑
$waytype = isset($_GET['waytype']) ? $_GET['waytype'] : 0;
// $money   = isset($_GET['account']) ? $_GET['account'] : 0;
// $uid     = isset($_GET['uid']) ? $_GET['uid'] : 0;

$money = $_GET['money'];
$uid = 52247;
if (empty($money) || empty($uid)) {
    die('参数错误');
}

// $pay_type = isset($_REQUEST['pay_type']) ? $_REQUEST['pay_type'] : 0;
$pay_type = 1;

// # 数据库
// $mysql = new Mysql(Config::$mysql['gm']);

// # 查询代理
// $agent = $mysql->get_one("SELECT * FROM fa_admin WHERE agentid={$uid}");
// if (empty($agent)) {
//     die('用户不存在');
// }

// # 查询计费点
// if ($waytype == 1) {
//     switch ($money) {
//         case '300':
//             $card = 850;
//             break;
//         case '600':
//             $card = 1900;
//             break;
//         case '1000':
//             $card = 3600;
//             break;
//         case '2000':
//             $card = 7700;
//             break;
//         case '5000':
//             $card = 23000;
//             break;
//         default:
//             die('计费点错误');
//             break;
//     }
// } else {
//     // $inWhiteList = (in_array($uid, [402951, 987563, 613749, 781245, 764802, 845206, 604183, 451893, 374569, 672310, 235764, 896237, 964308, 612483, 174239]) && time() >= strtotime('2019-03-02')) ? true : false;
//     // if ($inWhiteList) {
//     //     $card = intval($money / 0.12);
//     // } else {
//     if ($agent['agentid'] == 130247) {
//         # 乌海独代
//         $card = intval($money / 0.08);
//     } elseif (isset($agent['uid']) && $agent['uid'] == 130247 && $agent['invitation'] != 130247) {
//         # 乌海团队合伙人、下级代理
//         if ($agent['type'] == 1) {
//             $card = intval($money / 0.12);
//         } else {
//             $card = intval($money / 0.3);
//         }
//     } elseif ($agent['invitation'] == 130247) {
//         # 乌海独代下级代理
//         $card = intval($money / 0.2);
//     } else {
//         $card = intval($money / 0.2);
//     }
//     // }
// }

# 插入订单
$out_trade_no = $_GET['out_trade_no'];
// $charge_id = $mysql->insert('fa_pay', [
//     'ordernumber' => $out_trade_no,
//     'agentid'     => $uid,
//     'usertype'    => $agent['type'],
//     'card'        => $card,
//     'money'       => $money,
//     'createtime'  => time(),
//     'type'        => 1,
//     'channel'     => 'hc_jh',
// ]);

$PayTypes = [
    0 => 'WxJsapi_OffLine',
    1 => 'AliJsapiPay_OffLine'
];

$Subject = 'yns' . "_" . $out_trade_no; # 商品描述
$Desc    = "FangKa"; # 备注

# RSA拼接明文得到签名
$AdviceUrl       = "http://ys.dickmorley.cn/plugins/hc/notify.php"; # 异步通知地址
$Amount          = $money; # 支付金额(元)（原价减去折扣）
$MerchantNo      = MER_NO; # 商户号
$MerchantOrderNo = $out_trade_no; # 商户平台订单号
$PayType         = $PayTypes[$pay_type]; # 支付方式 微信小程序(公众号): WxJsapi_OnLine (线上) WxJsapi_OffLine(线下) 支付宝小程序: AliJsapiPay_OffLine

$RandomStr       = great_rand();

$data            = "AdviceUrl=$AdviceUrl&Amount=$Amount&MerchantNo=$MerchantNo&MerchantOrderNo=$MerchantOrderNo&PayType=$PayType&RandomStr=$RandomStr";

$SignInfo        = get_private_sign($data, RSA_PRIVATE);
// var_dump(public_verify($data, $SignInfo, RSA_PUBLIC));die();
// echo '<pre>';
// echo $data;die;

// $file  = "/www/wwwroot/ys.dickmorley.cn/chpay/plugins/hc/all_order.txt";
//     $content = $data.'数据修改成功';
//     if($f  = file_put_contents($file, $content,FILE_APPEND)){     // 这个函数支持版本(PHP 5)
//         echo "写入成功。<br />";
//     }

# 第三方参数
$PayWays = [
    0 => 'WX',
    1 => 'ZFB'
];
$arr = array(
    "MerchantNo"      => $MerchantNo,
    "MerchantOrderNo" => $MerchantOrderNo,
    "PayType"         => $PayType,
    "Amount"          => $Amount,
    "Subject"         => $Subject,
    "Desc"            => $Desc,
    "CompanyNo"       => 1,
    "RandomStr"       => $RandomStr,
    "SignInfo"        => $SignInfo,
    "AdviceUrl"       => $AdviceUrl,
    "SubAppid"        => 1,
    "UserId"          => 1,
    "SubMerchantType" => 5812,
    "PayWay"          => $PayWays[$pay_type] // 支付方式： ZFB WX
);
// $arr = array_iconv($arr);
$str = base64_encode(trim(arr2xml($arr)));
?>

<!-- 自动提交 -->
<form id="create_pay" action="https://paygw.yemadai.com/scan/merchant/dopay" method="post">
<!--<form id="create_pay" action="https://gwapi.yemadai.com/pay/scanpay" method="post">-->
<!--<form id="create_pay" action="https://paygw.yemadai.com/scan/merchant/dopay" method="post">-->
    <input type="text" name="requestDomain" value="<?php echo $str; ?>">
</form>
<script type="application/javascript">
    window.onload=function(ev){
        if(document.readyState=="complete") {
            document.getElementById("create_pay").submit();
        }
    }
</script>