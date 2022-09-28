<?php

include 'pay_demo.php';

echo "系统当前时间戳为：";
echo "";
echo time();
echo("<script type=\"text/javascript\">");
echo("function fresh_page()");
echo("{");
echo("window.location.reload();");
echo("}");
echo("</script>");

/**
 * 说明 仅作接口调用功能测试 PC收银台
 */
$oder = $pay->common->datetime2string(date('Y-m-d H:i:s'));
$orders = $pay->get_code($oder);
echo $orders;






