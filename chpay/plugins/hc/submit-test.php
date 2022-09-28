<?php

$str='我是测试字符串';
$money = 1000;
?>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<style>
	* {
		margin: 0;
		padding: 0;
	}

	body {
		background-color: #eceaea;
	}

	.info {
		background-color: white;
		margin-top: 20%;
		height: 240px;
		text-align: center;

	}

	.info p:nth-child(1) {
		color: #E12C46;
		padding-top: 50px;
		font-size: 35px;
		font-weight: 600;
	}

	.info p:nth-child(2) {
		height: 40px;
		line-height: 40px;
	}

	.info p:nth-child(2) span {
		color: #19da7f;
	}

	.info p:nth-child(3) {
		height: 40px;
		line-height: 40px;
		color: #d5161c;
	}

	.clic {
		background-color: #2B62FB;
		border: none;
		width: 90%;
		height: 45px;
		margin: 0 auto;
		display: block;
		color: white;
		line-height: 45px;
		font-size: 16px;
		border-radius: 23px;
		margin-top: 125px;
	}
</style>

<!-- 自动提交 -->
<!--<form id="create_pay" action="https://paygw.yemadai.com/scan/merchant/dopay" method="post">-->
<form id="create_pay" action="https://www.baidu.com" method="post">
<!--<form id="create_pay" action="https://gwapi.yemadai.com/pay/scanpay" method="post">-->
<!--<form id="create_pay" action="https://paygw.yemadai.com/scan/merchant/dopay" method="post">-->
<div class="info">
	<p>￥<span><?php echo $money; ?></span></p>
	<p>一定要点击<span>允许获取位置信息</span>不然无法完成支付</p>
	<p>请点击下方按钮打开支付宝付款</p>
</div>
<input type="submit" class="clic" value="点击打开支付宝">
    <input type="text" style="display:none" name="requestDomain" value="<?php echo $str; ?>">
</form>
<script type="application/javascript">
    window.onload=function(ev){
        if(document.readyState=="complete") {
            let data = document.getElementById("create_pay");
            // data.submit();
        }
    }
</script>