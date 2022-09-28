<?php
header("Content-type:text/html;charset=utf-8");
$data = get($_SERVER["QUERY_STRING"]);
function get($str)
{
    $data = array();
    $parameter = explode('&', end(explode('?', $str)));
    foreach ($parameter as $val) {
        $tmp = explode('=', $val);
        $data[$tmp[0]] = $tmp[1];
    }
    return $data;
}

?>
<html>
<body style="display:none">
<FORM id="FORM1" name="FORM1" action="https://openapi.ysepay.com/gateway.do?" method="get">
    <INPUT ID="bank_account_type" NAME="bank_account_type" TYPE="text" value="<?php echo $data['bank_account_type'] ?>">
    <INPUT ID="bank_type" NAME="bank_type" TYPE="text" value="<?php echo $data['bank_type'] ?>">
    <INPUT ID="business_code" NAME="business_code" TYPE="text" value="<?php echo $data['business_code'] ?>">
    <INPUT ID="charset" NAME="charset" TYPE="text" value="<?php echo $data['charset'] ?>">
    <INPUT ID="method" NAME="method" TYPE="text" value="<?php echo $data['method'] ?>">
    <INPUT ID="notify_url" NAME="notify_url" TYPE="text" value="<?php echo $data['notify_url'] ?>">
    <INPUT ID="out_trade_no" NAME="out_trade_no" TYPE="text" value="<?php echo $data['out_trade_no'] ?>">
    <INPUT ID="partner_id" NAME="partner_id" TYPE="text" value="<?php echo $data['partner_id'] ?>">
    <INPUT ID="pay_mode" NAME="pay_mode" TYPE="text" value="<?php echo $data['pay_mode'] ?>">
    <INPUT ID="return_url" NAME="return_url" TYPE="text" value="<?php echo $data['return_url'] ?>">
    <INPUT ID="seller_id" NAME="seller_id" TYPE="text" value="<?php echo $data['seller_id'] ?>">
    <INPUT ID="seller_name" NAME="seller_name" TYPE="text" value="<?php echo $data['seller_name'] ?>">
    <INPUT ID="sign_type" NAME="sign_type" TYPE="text" value="<?php echo $data['sign_type'] ?>">
    <INPUT ID="sign" NAME="sign" TYPE="text" value="<?php echo $data['sign'] ?>">
    <INPUT ID="subject" NAME="subject" TYPE="text" value="<?php echo $data['subject'] ?>">
    <INPUT ID="support_card_type" NAME="support_card_type" TYPE="text" value="<?php echo $data['support_card_type'] ?>">
    <INPUT ID="timeout_express" NAME="timeout_express" TYPE="text" value="<?php echo $data['timeout_express'] ?>">
    <INPUT ID="timestamp" NAME="timestamp" TYPE="text" value="<?php echo urldecode($data['timestamp']) ?>">
    <INPUT ID="total_amount" NAME="total_amount" TYPE="text" value="<?php echo $data['total_amount'] ?>">
    <INPUT ID="version" NAME="version" TYPE="text" value="<?php echo $data['version'] ?>">
    <script type="text/javascript">document.FORM1.submit();</script>
</FORM>
</body>
</html>

