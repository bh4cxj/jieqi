<?php
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>作家中心-潮流原创文学网</title>
<link href="css/authorcenter.css" rel="stylesheet"/>
<script type="text/javascript" src="include/js/common.js"></script>

<script type="text/javascript" src="/template/newchaoliu/js/jquery-1.7.1.js"></script>


</head>
<body>

<div id="top">
	<p class="top_left"><a href="#">设为首页</a><a href="#">收藏本站</a></p>
    <p class="top_right">欢迎您，<font style="color:#FF0000" ;>go123</font>&nbsp;&nbsp;<a href="/" >返回网站</a>&nbsp;&nbsp;<a href="/login.php?action=logout" style="color:#c99500;">退出</a></p>
    <div class="clear"></div>
</div>
<div id="head">
	<a class="logo"><img src="images/logo_03.gif" /></a>
    <div class="top_nav"><a href="/">首页</a><a href="/adminm.php?action=favorites">我的收藏</a><a href="/adminm.php?action=payfinance">账务管理</a><a href="adminm.php">个人中心</a></div>
	<div class="clear"></div>
</div><style type="text/css">
body {
	background:url(images/bg_01.jpg) #ebe6d6 top center no-repeat;
}
#top {
	width:960px;
	margin:0 auto;
	height:30px;
	line-height:30px;
	color:#a29999;
}
#top a {
	color:#a29999;
}
#top a:hover {
	color:#c99500;
}
.top_left {
	float:left;
}
.top_left a{
	padding-right:10px;
}
.top_right {
	float:right;
	width:300px;
	text-align:right;
}
.top_right a{
	padding-left:10px;
}
#head {
	width:960px;
	margin:0 auto;
	padding-top:30px;
}
.logo {
	display:block;
	float:left;
	width:219px;
	height:64px;
}
.top_nav {
	width:500px;
	padding-top:40px;
	text-align:right;
	float:right;
}
.top_nav  a {
	padding-left:20px;
	color:#863a16;
	font-weight:bold;
	font-size:14px;
}
#main {
	width:960px;
	min-height:560px;
    _height:560px;
    overflow:auto;
	_overflow:visible;
	margin:0 auto;
	margin-top:20px;
	background-color:white;
	border: 4px solid #c99500;
	border-radius: 12px;
	box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.2);
}
.menu {
	float: left;
	width: 172px;
	height:auto;
	padding-top: 50px;
	border-radius: 12px 0px 0px 0px;
	padding-bottom:30px;
	border-bottom:2px solid #c99500;
	background: url(images/menu_bg_03.gif) repeat-y left top;
}
.content {
	float:right;
	width:740px;
	height:auto;
	background-color:#fff;
	border-radius: 0px 12px 12px 0px;
	padding:40px 20px;
}
.menu li a {
	font-weight:bold;
	font-size:14px;
	color:#505050;
}
.zzxx,.wzgg,.cjzp,.zpgl,.zpcj,.wdgf,.cgx,.znx{
	display:block;
	height: 50px;
	line-height: 50px;
	margin-left:15px;
	padding-left:65px;
	cursor:pointer;
}
.zzxx {
    background: url(images/zuozhexinxi_13.gif) 27px center no-repeat;
}
.wzgg {
    background: url(images/xttz_03.gif) 27px center no-repeat;
}
.cjzp {
    background: url(images/chuangjian_14.gif) 27px center no-repeat;
}
.zpgl {
    background: url(images/zpgl_17.gif) 27px center no-repeat;
}
.zpcj {
    background: url(images/zpcj_26.gif) 27px center no-repeat;
}
.wdgf {
    background: url(images/wdgf_31.gif) 27px center no-repeat;
}
.cgx {
    background: url(images/cgx_33.gif) 27px center no-repeat;
}
.znx {
    background: url(images/znx_35.gif) 27px center no-repeat;
}
.menu li a:hover{
	padding-left: 60px;
	margin-left: 15px;
	border:2px solid #c99500;
	border-right-color:white;
	background-color:white;
	background-position: 20px center;
}
.menu .wzgg {
	padding-left: 60px;
	margin-left: 15px;
	border:2px solid #c99500;
	border-right-color:white;
	background-color:white;
	background-position: 20px center;
}

.content h2 {
	color: rgb(102, 102, 102);
	width: 740px;
	font-size: 18px;
	height: 50px;
	line-height:25px;
	background:url(images/tanchu_03.gif) #fff 0px 40px repeat-x;	
}
#navigation {
	padding-top:10px;
}
.xinwen li {
	
}

.gray {
	padding-left:10px;
	color:#666666;
}
.xinwen_con {
	padding-top: 10px;
	font-size: 12px;
	color: #666666;
	font-weight: normal;
	text-align: justify;
	line-height: 20px;
}
#navigation li{ background:url(images/g1-bg01.gif) repeat-x left bottom; padding:10px 5px 10px 0;}
#navigation li p{ padding:10px 40px; line-height:24px;}
span.head{ display:block; padding-left:41px; background: url(images/g1-icon01.gif) no-repeat 15px 5px; padding-top:6px; cursor:pointer; padding-bottom:5px;}


#footer {
	padding:25px 0px;
	width:960px;
	margin:0 auto;
	text-align:center;
	color:#999999;
}
#footer p {
	padding-top:10px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
	$(\'.head\').css(\'background\',\'url(images/g1-icon01.gif) no-repeat 15px 5px\')
	$(\'.head\').click(function(){
			$(\'.head\').css(\'background\',\'url(images/g1-icon01.gif) no-repeat 15px 5px\')
			$(this).css(\'background\',\'url(images/g1-icon02.gif) no-repeat 15px 5px\')
			$(\'#navigation li\').css(\'background\',\'#fff\')
			$(this).parent().css(\'background\',\'#f6f6f6\')
			$(this).next(\'.xinwen_con\').css(\'color\',\'#888\')
			$(\'.xinwen_con\').slideUp(200)
			if($(this).next(\'.xinwen_con\').css(\'display\')==\'none\'){
				$(this).next(\'.xinwen_con\').slideDown(200)
			}else{
				$(this).next(\'.xinwen_con\').slideUp(200)
			}
			})
			
		
	
})
</script>

';
?>