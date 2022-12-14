<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="zh" class="ie8"> <![endif]-->
<!--[if !IE]><!-->
<html lang="zh">
<!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	<title><?php echo $conf['title']?></title>
  	<meta name="keywords" content="<?php echo $conf['keywords']?>">
	<meta name="description" content="<?php echo $conf['description']?>">	
	<!-- ================== BEGIN BASE CSS STYLE ================== -->
	<link href="//lib.baomitu.com/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" />
	<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
	<link href="//lib.baomitu.com/animate.css/3.7.2/animate.min.css" rel="stylesheet" />
	<link href="<?php echo STATIC_ROOT?>css/style.min.css" rel="stylesheet" />
	<link href="<?php echo STATIC_ROOT?>css/style-responsive.min.css" rel="stylesheet" />
	<link href="<?php echo STATIC_ROOT?>css/theme/blue.css" id="theme" rel="stylesheet" />
	<!-- ================== END BASE CSS STYLE ================== -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="//lib.baomitu.com/pace/1.0.2/pace.min.js"></script>
	<!-- ================== END BASE JS ================== -->
</head>
<body data-spy="scroll" data-target="#header-navbar" data-offset="51">
    <!-- begin #page-container -->
    <div id="page-container" class="fade">
        <!-- begin #header -->
        <div id="header" class="header navbar navbar-transparent navbar-fixed-top">
            <!-- begin container -->
            <div class="container">
                <!-- begin navbar-header -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#header-navbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="index.html" class="navbar-brand">
                        <span class="brand-logo"></span>
                        <span class="brand-text">
                            <span class="text-theme"><?php echo $conf['sitename']?></span>
                        </span>
                    </a>
                </div>
                <!-- end navbar-header -->
                <!-- begin navbar-collapse -->
                <div class="collapse navbar-collapse" id="header-navbar">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="#home" data-click="scroll-to-target">??????</a> </li>
                      	<li><a href="#service" data-click="scroll-to-target">??????</a></li>
                        <li><a href="#team" data-click="scroll-to-target">??????</a></li>
                        <?php if($conf['test_open']){?><li><a href="/user/test.php">????????????</a></li><?php }?>
                        <li><a href="/doc.html">????????????</a></li>
                      	<li><a href="/user/reg.php">????????????</a></li>
                        <li><a href="/user/">????????????</a></li>
                    </ul>
                </div>
                <!-- end navbar-collapse -->
            </div>
            <!-- end container -->
        </div>
        <!-- end #header -->
        
        <!-- begin #home -->
        <div id="home" class="content has-bg home">
            <!-- begin content-bg -->
            <div class="content-bg">
                <img src="<?php echo STATIC_ROOT?>img/home-bg.jpg" alt="Home" />
            </div>
            <!-- end content-bg -->
            <!-- begin container -->
            <div class="container home-content">
                <h1>???????????? <a href="JavaScript:;"><?php echo $conf['sitename']?></a></h1>
                <h3>????????????????????? ??????????????????<?php echo $conf['settle_rate']; ?>%???</h3>
                <h4>
                    ???????????????????????????????????????QQ????????????????????????????????????????????????????????????????????????????????????<br />
                    <a href="JavaScript:;">??????????????????????????????</a>
                </h4>
                <a href="./user/reg.php" class="btn btn-theme">????????????</a> <a href="./user/" class="btn btn-outline">????????????</a><br />
            </div>
            <!-- end container -->
        </div>
        <!-- end #home -->
    	
      	<!-- beign #service -->
        <div id="service" class="content" data-scrollview="true">
            <!-- begin container -->
            <div class="container">
                <h2 class="content-title">????????????????????????</h2>
                <p class="content-desc">
                    <?php echo $conf['sitename']?>??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
                </p>
                <!-- begin row -->
                <div class="row">
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-cog"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc">?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-paint-brush"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc">??????????????????<?php echo $conf['settle_rate']*100; ?>%????????????<?php echo $conf['settle_money']; ?>??????????????????????????????????????????????????????<?php echo $conf['settle_fee_min']; ?>????????????<?php echo $conf['settle_fee_max']; ?>??????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-file"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc"><?php echo $conf['sitename']?>????????????APP???QQ??????????????????????????????????????????????????????????????????????????????????????????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                </div>
                <!-- end row -->
                <!-- begin row -->
                <div class="row">
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-code"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc">???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-shopping-cart"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc">??????T+1??????????????????????????????<?php echo $conf['settle_money']; ?>?????????????????????????????????????????????????????????10??????????????????????????????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4">
                        <div class="service">
                            <div class="icon bg-theme" data-animation="true" data-animation-type="bounceIn"><i class="fa fa-heart"></i></div>
                            <div class="info">
                                <h4 class="title">????????????</h4>
                                <p class="desc">??????SDK???????????????????????????????????????????????????????????????discuz???WordPress?????????????????????????????????</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end #service -->
      
        <!-- begin #milestone -->
        <div id="milestone" class="content bg-black-darker has-bg" data-scrollview="true">
            <!-- begin content-bg -->
            <div class="content-bg">
                <img src="<?php echo STATIC_ROOT?>img/milestone-bg.jpg" alt="Milestone" />
            </div>
            <!-- end content-bg -->
            <!-- begin container -->
            <div class="container">
                <!-- begin row -->
                <div class="row">
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4 milestone-col">
                        <div class="milestone">
                            <div class="number" data-animation="true" data-animation-type="number" data-final-number="1292">1,292</div>
                            <div class="title">????????????</div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4 milestone-col">
                        <div class="milestone">
                            <div class="number" data-animation="true" data-animation-type="number" data-final-number="9039">9,039</div>
                            <div class="title">????????????</div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4 milestone-col">
                        <div class="milestone">
                            <div class="number" data-animation="true" data-animation-type="number" data-final-number="129">129</div>
                            <div class="title">????????????</div>
                        </div>
                    </div>
                    <!-- end col-3 -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end #milestone -->
        
        <!-- begin #team -->
        <div id="team" class="content" data-scrollview="true">
            <!-- begin container -->
            <div class="container">
                <h2 class="content-title">???????????????</h2>
                <p class="content-desc">
                    
                </p>
                <!-- begin row -->
                <div class="row">
                    <!-- begin col-3 -->
                    <div class="col-md-4 col-sm-4 col-md-offset-4 col-sm-offset-4">
                        <!-- begin team -->
                        <div class="team">             
                            <div class="image" data-animation="true" data-animation-type="flipInX">
                                <img src="//q1.qlogo.cn/g?b=qq&nk=<?php echo $conf['kfqq']?>&s=640" alt="Mia Donovan" />
                            </div>
                            <div class="info">
                                <h3 class="name">??????</h3>
                                <div class="title text-theme">??????</div>
                                <p>???????????????????????? </p>
                                <div class="social">
                                    <a href="https://wpa.qq.com/msgrd?v=3&uin=<?php echo $conf['kfqq']?>&site=pay&menu=yes" title="??????????????????QQ" target="_blank"><i class="fa fa-qq fa-lg fa-fw"></i></a>
                                    <a href="JavaScript:;"><i class="fa fa-weibo fa-lg fa-fw"></i></a>
                                    <a href="JavaScript:;"><i class="fa fa-home fa-lg fa-fw"></i></a>
                                </div>
                            </div>                     
                        </div>
                        <!-- end team -->
                    </div>
                    <!-- end col-3 -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end #team -->
      
        <!-- begin #footer -->
        <div id="footer" class="footer">
            <div class="container">
                <div class="footer-brand">
                    <div class="footer-brand-logo"></div>
                    <?php echo $conf['sitename']?>
                </div>
                <p>
                    Copyright&nbsp;&nbsp;&copy;&nbsp;2020&nbsp;All Rights Reserved.<br/><?php echo $conf['footer']?>
                </p>
            </div>
        </div>
        <!-- end #footer -->
        
        <!-- begin theme-panel -->
        <div class="theme-panel">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <ul class="theme-list clearfix">
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Purple">&nbsp;</a></li>
                    <li class="active"><a href="javascript:;" class="bg-blue" data-theme="blue" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Blue">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-green" data-theme="default" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Default">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Orange">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Red">&nbsp;</a></li>
                </ul>
            </div>
        </div>
        <!-- end theme-panel -->
    </div>
    <!-- end #page-container -->
	
	<!-- ================== BEGIN BASE JS ================== -->
	<script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
	<script src="//lib.baomitu.com/jquery-migrate/1.4.1/jquery-migrate.min.js"></script>
	<script src="//lib.baomitu.com/twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<!--[if lt IE 9]>
		<script src="//lib.baomitu.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="//lib.baomitu.com/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<script src="//lib.baomitu.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
	<script src="//lib.baomitu.com/scrollmonitor/1.2.0/scrollMonitor.js"></script>
	<script src="<?php echo STATIC_ROOT?>js/apps.min.js"></script>
	<!-- ================== END BASE JS ================== -->
	
	<script>
	var staticroot = '<?php echo STATIC_ROOT?>';
	    $(document).ready(function() {
	        App.init();
	    });
	</script>

</body>
</html>
