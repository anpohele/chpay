<?php
if (!defined('IN_PLUGIN')) exit();
require PAY_ROOT . 'inc/App.php';
$sub = App::config(include PAY_ROOT . 'inc/config.php')->submit($order['trade_no'] , $order['realmoney']);
if ($sub === false) {
	sysmsg('下单失败！');
} else if (strtolower($sub['result']) === 'success' && array_key_exists('data' , $sub)) {
	$code_url = $sub['data']['url'];
} else {
	sysmsg('支付下单失败！['.$sub['error']['errorCode'].']'.$sub['error']['errorMsg']);
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Language" content="zh-cn">
<meta name="renderer" content="webkit"><meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no,email=no"/>
    <meta id="WV.Meta.Share.Disabled" value="true" />
<meta name="data-bizType" content="pay"/>
<meta name="data-aspm" content="a283"/>

<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
<link rel="apple-touch-icon" href="https://i.alipayobjects.com/i/ecmng/png/201406/2qBuceUYiL.png" />
<link rel="apple-touch-icon-precomposed" href="https://i.alipayobjects.com/i/ecmng/png/201406/2qBuceUYiL.png" />
    <link rel="stylesheet" type="text/css" href="https://as.alipayobjects.com:443/g/snake/h5cashier/1.0.5/h5cashier.css" media="all" />
<title>支付宝支付</title>
<link href="alipay_pay.css" rel="stylesheet" media="screen">
			
<!-- v5_h5_pay_route -->
<script>window.Tracker && Tracker.click('v5_h5_pay_route')</script>


<header class="am-header">
    <h1>
        <span class="title-main" data-title="支付宝收银台">支付宝收银台</span>    </h1>
                        
    </header>


<noscript><h1 style="color:red">您的浏览器不支持JavaScript，请更换浏览器或开启JavaScript设置!</h1></noscript>
<!-- FD:mobileclientgw:mobileclientgw/home/v5/h5PayRoute.vm:START --><style>
    
.qr-image{text-align:center} 

	body {
        font-weight: 500;
        font-family: PingFangSC-Regular, "Helvetica Neue",Helvetica,Arial,"Lucida Grande",sans-serif;
    }
    .am-header {
        display: none;
    }
    .alipay-logo {
        display: none;
    }
    .result {
        margin-top:40px;
        width: 100%;
    }
    .result-logo {
        width: 70px;
        height: 98px;
        margin: auto;
        background-image: url(data:image/png;base64,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);
        background-repeat: no-repeat;
        background-size: contain;
    }
    .result-title {
        margin: 40px 0 14px;
        text-align: center;
        font-size: 21px;
        color: #00a0e8;
        line-height: 25px;
    }
    .result-tips {
        max-width: 370px;
        margin: 0 auto 10px;
        padding: 0 15px;
        font-size: 15px;
        color: #a5a5a5;
        line-height:18px;
        text-align:left;
        word-break:break-all;
        word-wrap:break-word;
    }
    .result-botton {
        margin: 0 15px 20px;
    }
    .result-botton a {
        display: block;
        margin: auto;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        max-width: 384px;
        height:44px;
        text-align: center;
    }
    .result-botton a.am-button-white {
        color: #00aaee;
        background:#ffffff;
        border:1px solid #00aaee;
    }
    .result-botton .am-button[disabled=disabled] {
        color: #e6e6e6;
        background: #f8f8f8;
        border: 1px solid #dedede;
    }
    .loading {
        display: none;
    }
</style>
</head>
<body>
<div class="loading"></div>
<div class="am-content">
    <input type="hidden" name="params" value="{&quot;server_param&quot;:&quot;emlkPTU5O25kcHQ9NDk2MztjYz15&quot;}" />
    <div class="result">
     	   <div class="result-logo"></div>
		  <div class="result-title">订单金额￥<?php echo $order['money'] ?></div>
			<div class="qr-image" class ="center" id="qrcode">
			</div>
  	      <div class="result-title">正在尝试打开支付宝客户端</div>
          <div class="result-tips">1.如果无法打开支付宝APP，请保存二维码「打开支付宝扫码付款」；</div>
          <div class="result-tips" style="margin-bottom: 40px;">2.如果你已完成付款，请点击「已完成付款」；</div>
          <div class="result-botton"><a class="J-startapp am-button am-button-blue"  id="J_downloadBtn" href="javascript:;" onclick="openAli();"">打开支付宝APP付款</a></div>
                <div class="result-botton"><a class="J-complete am-button am-button-white" href="#">已完成付款</a></div>
    </div>
</div>
<script src="qrcode.min.js"></script>
<script src="qcloud_util.js"></script>
<script src="layer.js"></script>
<script>
	var code_url = '<?php echo $code_url ?>';
    var qrcode = new QRCode("qrcode", {
        text: code_url,
        width: 220,
        height: 220,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
    // 订单详情
    $('#orderDetail .arrow').click(function (event) {
        if ($('#orderDetail').hasClass('detail-open')) {
            $('#orderDetail .detail-ct').slideUp(500, function () {
                $('#orderDetail').removeClass('detail-open');
            });
        } else {
            $('#orderDetail .detail-ct').slideDown(500, function () {
                $('#orderDetail').addClass('detail-open');
            });
        }
    });
    // 检查是否支付完成
    function loadmsg() {
        $.ajax({
            type: "GET",
            dataType: "json",
            url: "../getshop.php",
            timeout: 10000, //ajax请求超时时间10s
            data: {type: "wxpay", trade_no: "<?php echo $order['trade_no'] ?>"}, //post数据
            success: function (data, textStatus) {
                //从服务器得到数据，显示数据并继续查询
                if (data.code == 1) {
					layer.msg('支付成功，正在跳转中...', {icon: 16,shade: 0.01,time: 15000});
					setTimeout(window.location.href=data.backurl, 1000);
                }else{
                    setTimeout("loadmsg()", 4000);
                }
            },
            //Ajax请求超时，继续查询
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                if (textStatus == "timeout") {
                    setTimeout("loadmsg()", 1000);
                } else { //异常
                    setTimeout("loadmsg()", 4000);
                }
            }
        });
    }
    window.onload = loadmsg();
</script>
<script>
	if (typeof AlipayWallet !== 'object') {
		AlipayWallet = {};
	}
	(function () {
		"use strict";
		function a(e, t) {
			for (var o = e.split("."), n = t.split("."), a = 0; a < o.length || a < n.length; a += 1) {
				var r = parseInt(o[a], 10) || 0,
					i = parseInt(n[a], 10) || 0;
				if (r < i) return -1;
				if (r > i) return 1
			}
			return 0
		}
		function r(e) {
			var x = window.document.createElement("iframe"); x.id = "callapp_iframe_" + Date.now(), x.frameborder = "0", x.style.cssText = "display:none;border:0;width:0;height:0;", window.document.body.appendChild(x), x.src = e
		}
		function i(e) {
			var t = x.createElement("a");
			t.setAttribute("href", e), t.style.display = "none", x.body.appendChild(t);
			var o = x.createEvent("HTMLEvents");
			o.initEvent("click", !1, !1), t.dispatchEvent(o)
		}
		function l(e) {
			return /^(http|https)\:\/\//.test(e)
		}
		AlipayWallet.open = function (n){
			var p = window.navigator.userAgent;
			var g = !1,
				m = !1,
				h = "",
				w = p.match(/Android[\s\/]([\d\.]+)/);
			w ? (g = !0, h = w[1]) : p.match(/(iPhone|iPad|iPod)/) && (m = !0, w = p.match(/OS ([\d_\.]+) like Mac OS X/), w && (h = w[1].split("_").join(".")));
			var v = !1,
				b = !1,
				y = !1;
			p.match(/(?:Chrome|CriOS)\/([\d\.]+)/) ? (v = !0, p.match(/Version\/[\d+\.]+\s*Chrome/) && (y = !0)) : p.match(/iPhone|iPad|iPod/) && (p.match(/Safari/) && p.match(/Version\/([\d\.]+)/) ? b = !0 : p.match(/OS ([\d_\.]+) like Mac OS X/) && (y = !0));
			var u = g && v && !y,
				d = g && !! p.match(/samsung/i) && a(h, "4.3") >= 0 && a(h, "4.5") < 0,
				s = m && a(h, "9.0") >= 0 && b;
			if(u){
				var f = n.substring(0, n.indexOf("://")),
					w = "#Intent;scheme=" + f + ";end";
					n = n.replace(/.*?\:\/\//, "intent://"), n += w;
			}
			if (s) {
				setTimeout(function() {
					i(n)
				}, 100)
			} else if (0 === n.indexOf("intent:")) setTimeout(function() {
				window.location.href = n
			}, 100);
			else {
				r(n)
			}
		}
	})();
	function openAli(){
		var scheme = 'alipays://platformapi/startapp?saId=10000007&qrcode=';
		scheme += encodeURIComponent(code_url);
		AlipayWallet.open(scheme);
	}
	window.onload = function(){
		openAli();
		setTimeout("loadmsg()", 2000);
	}
</script>
  <script charset="utf-8" id="seajsnode" src="https://a.alipayobjects.com:443/??seajs/seajs/2.2.0/sea.js,seajs/seajs-combo/1.0.0/seajs-combo.js"></script>

<script>
    seajs.config({
        alias: {
                                        '$': 'gallery/zepto/1.0.2/zepto',
                        'validator': 'arale/validator/0.9.7/validator',
            'widget': 'arale/widget/1.1.1/widget',
            'base': 'arale/base/1.1.1/base',
            'class': 'arale/class/1.1.0/class',
            'events': 'arale/events/1.1.0/events',
            'wapcashier': 'mobileclientgw/wapcashier/1.1.7/wapcashier'
        },
        vars: {
            locale: 'zh-cn'
        }
    });
</script>
 <script charset="utf-8" src="https://as.alipayobjects.com:443/g/snake/h5cashier/1.0.5/h5cashier.js"></script>
</body>
