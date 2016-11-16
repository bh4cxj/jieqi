<?php
echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>作家中心-潮流原创文学网</title>
<link href="/themes/chaoliu/css/authorcenter.css" rel="stylesheet"/>
<script type="text/javascript" src="/themes/chaoliu//js/common.js"></script>

<script type="text/javascript" src="/themes/chaoliu/js/jquery-1.9.1.min.js"></script>
<style>
.wzgg{border: 2px solid #c99500;border-right:none;
background-color: white;}
.menu .cjzp{border:none;background:url(/themes/chaoliu/images/chuangjian_14.gif) 27px center no-repeat;}
</style>
<style type="text/css">
body {
	background:url(/themes/chaoliu/images/bg_01.jpg) #ebe6d6 top center no-repeat;
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
	background: url(/themes/chaoliu/images/menu_bg_03.gif) repeat-y left top;
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
    background: url(/themes/chaoliu/images/zuozhexinxi_13.gif) 27px center no-repeat;
}
.wzgg {
    background: url(/themes/chaoliu/images/xttz_03.gif) 27px center no-repeat;
}
.cjzp {
    background: url(/themes/chaoliu/images/chuangjian_14.gif) 27px center no-repeat;
}
.zpgl {
    background: url(/themes/chaoliu/images/zpgl_17.gif) 27px center no-repeat;
}
.zpcj {
    background: url(/themes/chaoliu/images/zpcj_26.gif) 27px center no-repeat;
}
.wdgf {
    background: url(/themes/chaoliu/images/wdgf_31.gif) 27px center no-repeat;
}
.cgx {
    background: url(/themes/chaoliu/images/cgx_33.gif) 27px center no-repeat;
}
.znx {
    background: url(/themes/chaoliu/images/znx_35.gif) 27px center no-repeat;
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
	background:url(/themes/chaoliu/images/tanchu_03.gif) #fff 0px 40px repeat-x;	
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
#navigation li{ background:url(/themes/chaoliu/images/g1-bg01.gif) repeat-x left bottom; }
#navigation li p{ padding:10px 40px; line-height:24px;}
span.head{ display:block; padding-left:41px; height:30px;line-height:30px;}


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

</head>
<body>

<div id="top" >
<div class="top_inner">
<div class="left orangea"><div style="float:left; height:30px; line-height:30px;"><img src="/themes/chaoliu/images/time.gif"
    align="absmiddle" /> <script type="text/javascript"  src="/themes/chaoliu/js/time.js"></script>&nbsp;  </div>
    <div style="float:right;">
		<div class="m_top">
&nbsp;';
if($this->_tpl_vars['jieqi_userid'] == 0){
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，您可以选择[<a href="'.$this->_tpl_vars['jieqi_user_url'].'/register.php">登录</a>]或者[<a href="'.$this->_tpl_vars['jieqi_user_url'].'/register.php">注册新用户</a>]！';
}else{
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，'.$this->_tpl_vars['jieqi_username'].'&nbsp; <span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_user_url'].'/userdetail.php" style="margin:0 8px;">个人中心</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/bookcase.php" style="margin:0 8px;">我的书架</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/modules/article/myarticle.php" style="margin:0 8px;">作者专区</a><span style="color:#dbdbdb;">|</span><a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=inbox" style="margin:0 8px;">短消息</a><span style="color:#dbdbdb;">|</span><a target="_blank" href="'.$this->_tpl_vars['jieqi_user_url'].'/logout.php" style="margin:0 8px;">退出登录</a>';
}
echo '
</div>
    </div><div class="clear"></div></div>
</div>
</div>
<div id="head">
	<a class="logo"><img src="/themes/chaoliu/images/logo_03.gif" /></a>

	<div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	//$(\'.head\').css(\'background\',\'url(/themes/chaoliu/images/g1-icon01.gif) no-repeat 15px 5px\')
	$(\'.head\').click(function(){
			//$(\'.head\').css(\'background\',\'url(/themes/chaoliu/images/g1-icon01.gif) no-repeat 15px 5px\')
			$(this).css(\'background\',\'url(/themes/chaoliu/images/g1-icon02.gif) no-repeat 15px 5px\')
			$(\'#navigation li\').css(\'background\',\'#fff\')
			//$(this).parent().css(\'background\',\'#f6f6f6\')
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
<div id="main">
	<ul class="menu">
    	<li><a href="/modules/article/myarticle.php" class="wzgg">网站公告</a></li>
        <li><a href="/modules/article/newarticle.php" class="cjzp">创建作品</a></li>
        <li><a href="/modules/article/masterpage.php" class="zpgl">作品管理</a></li>
        <li><a href="/modules/article/newdraft.php" class="cgx">草稿箱</a></li>
        <li><a href="/modules/obook/newobook.php" class="wdgf">新建电子书</a></li>
    	<li><a href="/modules/obook/masterpage.php" class="zzxx">我的电子书</a></li>
        <li><a href="/message.php?box=inbox" class="znx">站内信</a></li>
</ul>    <div class="content">
    	<h2>作家中心公告</h2>
        <div style="width:100%; float:left;">
    	<ul id="navigation">
		        	<li>
               <span class="head">网站和论坛用户名互通说明<span class="gray">2013-11-20 11:47:52</span></span>
                <div class="xinwen_con" style="display:none"><blockquote>
	<div style="margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px">&nbsp; 亲爱的文友们：</span></strong></span></div>
	<div style="margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px"><br />
		</span></strong></span></div>
	<div style="margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px">&nbsp;&nbsp;&nbsp;网站正在进行网站用户名和论坛用户名整合的工作，待整合结束后主站用户名可在论坛使用无需重新注册。在整合其间，主站和论坛的老用户可能出现如下问题：</span></strong></span></div>
	<div style="margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px"><br />
		1、主站用户名在论坛首次登陆，需重新验证一次用户名和密码。<br />
		2、论坛中显示的用户资料个别会出现已登记邮箱显示异常的问题，各位自行修改为正确邮箱地址即可，不影响功能使用。<br />
		3、如主站注册时所填写的用户名、邮箱信息与论坛注册时填写的用户名、邮箱信息相同，而密码不同，则请在论坛资料中修改论坛密码与主站密码一致，否则单独登陆论坛（不同时登陆网站）需使用原论坛登陆密码。<br />
		4、如上未特殊提及的老用户其论坛登陆名和密码均与主站一致。之前论坛中单独注册的用户信息仍将同时有效，但不能在主站中使用。<br />
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 所有新注册用户都可直接使用，不需关注以上信息。<br />
		<br />
		</span></strong></span></div>
	<div style="text-align: right; margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px"><br />
		</span></strong></span><span style="font-family: 宋体"><strong><span style="font-size: 16px">潮流sky运营团队</span></strong></span><span style="font-family: 宋体"><strong><span style="font-size: 16px">&nbsp;</span></strong></span></div>
	<div style="margin-left: 40px">
		&nbsp;</div>
	<div style="text-align: right; margin-left: 40px">
		<span style="font-family: 宋体"><strong><span style="font-size: 16px">2013-11-15</span></strong></span></div>
</blockquote>
</div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">10月稿费发放公告<span class="gray">2013-11-20 11:43:51</span></span>
                <div class="xinwen_con" style="display:none"><p class="MsoNormal">
	<strong><span style="font-size: 16px"><span style="font-family: 宋体">温馨提示：</span></span></strong></p>
<p class="MsoNormal">
	<strong><span style="font-size: 16px"><span style="font-family: 宋体">&nbsp;&nbsp;&nbsp; 凡是已申请十月份稿酬且符合发放条件的作者，稿费均已发放。如您在三个工作日内未查到到账记录请及时与我们联系，发放明细如下。</span></span></strong><br />
	<img alt="" height="784" src="/userfiles/image/20131120/20114249fd2d8200d51350.jpg" width="219" /></p>
<p class="MsoNormal">
	<strong><span style="font-size: 16px"><span style="font-family: 宋体">&nbsp;&nbsp; 因将近年底，现将<span lang="EN-US">5-15上线以来</span>至今所有已发稿费作者名单公布如下，请大家见证潮流发展。</span></span></strong></p>
<p class="MsoNormal">
	<span style="font-family: 宋体; mso-bidi-font-size: 10.5pt; mso-ascii-theme-font: minor-fareast; mso-fareast-font-family: 宋体; mso-fareast-theme-font: minor-fareast; mso-hansi-theme-font: minor-fareast"><img alt="" height="799" src="/userfiles/image/20131120/20114325de4335d3da7367.jpg" width="614" /><br />
	</span></p>
<p align="left" class="MsoNormal" style="text-align: left; text-indent: 21pt; mso-char-indent-count: 2.0; mso-pagination: widow-orphan">
	<strong><span style="font-size: 16px"><span style="font-family: 宋体">&nbsp;另：近日发现部分交稿作品出现内容重复问题，如《我的如花美眷》一书，章节<span lang="EN-US">11</span>：大美女与章节<span lang="EN-US">12</span>：总监<span lang="EN-US">&nbsp;</span>两章内容重复；章节<span lang="EN-US">13</span>：一丝担忧<span lang="EN-US">&nbsp;</span>与章节<span lang="EN-US">14</span>：你到底怎么了？<span lang="EN-US">&nbsp;</span>两章内容重复；章节<span lang="EN-US">59</span>：流氓啊一章内容重复等等，不再赘举。对于此种恶意重复行为潮流原创文学网将扣除该作者的所有未发稿费以示警惩，也请其他作者引以为鉴。</span></span></strong><strong><span style="font-size: 16px"><span style="font-family: 宋体"><span lang="EN-US"><br />
	&nbsp;&nbsp;&nbsp; </span>潮流文学网希望给所有爱好文学的作者们提供宽松的创作环境，也请广大签约作者们对得起网站的信任。近期有作者在写文时出现大量灌水及无意义内容，以及部分作品断更严重，照此发展，潮流网站将不得不出台相应措施予以约束或惩罚。请本月应有稿费却未收到稿费的作者联系编辑交流。如后续网站再发现恶意灌水，重复内容，抄袭违禁等情况，直接扣除稿费。<span lang="EN-US"><br />
	&nbsp;&nbsp; &nbsp;</span>我们是相亲相爱的一家人，信义行于君子，不好吗？</span></span></strong></p>
<p class="MsoNormal" style="text-align: right; text-indent: 21pt">
	&nbsp;</p>
<p class="MsoNormal" style="text-align: right; text-indent: 21pt">
	<strong><span style="font-size: 16px"><span style="font-family: 宋体"><span style="mso-bidi-font-family: \'times new roman\'; mso-ascii-theme-font: minor-fareast; mso-fareast-theme-font: minor-fareast; mso-hansi-theme-font: minor-fareast; mso-bidi-theme-font: minor-bidi; mso-ansi-language: en-us; mso-fareast-language: zh-cn; mso-bidi-language: ar-sa">潮流<span lang="EN-US">SKY</span>运营团队<span lang="EN-US"><br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2013.11.15</span></span></span></span></strong></p></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">9月稿费发放公告<span class="gray">2013-11-20 11:29:32</span></span>
                <div class="xinwen_con" style="display:none"><p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">温馨提示：<br />
	<br />
	凡是已申请九月份稿酬的作者，稿费均已发放。如您在三个工作日内未查到到账记录请及时与我们联系。<br />
	&nbsp;<br />
	附：以下为九月份月更新字数排行前十名的作者及稿费详情：<br />
	&nbsp;<br />
	1、笔名：**巴&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：3569.8<br />
	<br />
	2、笔名： *清*&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：3223&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
	&nbsp;<br />
	3、笔名：经**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2456.1<br />
	&nbsp;<br />
	4、笔名：*号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2032.48<br />
	<br />
	5、笔名：*刀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2969.4<br />
	&nbsp;<br />
	6、笔名：风**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2583.9<br />
	&nbsp;<br />
	7、笔名：*烟&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2159.4<br />
	&nbsp;<br />
	8、笔名：乐*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2002<br />
	<br />
	9. 笔名：夜*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2194.3<br />
	&nbsp;<br />
	10、笔名：火**莲&nbsp;&nbsp; 应发稿酬：2090.4<br />
	</span></strong></span></p>
<p>
	&nbsp;</p>
<p style="text-align: right">
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><br />
	<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 潮流SKY运营团队</strong></span></span></p>
<p style="text-align: right">
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; 2013.10.15&nbsp;<br />
	</strong></span></span></p></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">8月稿费发放公告<span class="gray">2013-11-20 11:27:09</span></span>
                <div class="xinwen_con" style="display:none"><span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312"><br />
	温馨提示：<br />
	<br />
	凡是已申请八月份稿酬的作者，稿费均已发放。如您在三个工作日内未查到到账记录请及时与我们联系。<br />
	&nbsp;<br />
	附：以下为八月份月更新字数排行前十名的作者及稿费详情：<br />
	&nbsp;<br />
	1、笔名：土*大*马**&nbsp; 应发稿酬：4080.2<br />
	<br />
	2、笔名： 柯*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2983.4&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
	&nbsp;<br />
	3、笔名：*小*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2607.8<br />
	&nbsp;<br />
	4、笔名：*蜓*水&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2255.9<br />
	<br />
	5、笔名：沫*&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：2732.2<br />
	&nbsp;<br />
	6、笔名：*心&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：1464.4<br />
	&nbsp;<br />
	7、笔名：*同&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：1600<br />
	&nbsp;<br />
	8、笔名：夜**&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：1488.9<br />
	<br />
	9. 笔名：*刀&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 应发稿酬：1676.1<br />
	&nbsp;<br />
	10、笔名：**客&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 应发稿酬：1401.2</span></strong></span><br />
	<br />
	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong><span style="font-size: 16px"><span style="font-family: 楷体_gb2312">潮流SKY运营团队<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2013.9.16<br />
	</span></span></strong></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">7月稿费发放及短篇评选公告<span class="gray">2013-11-20 11:19:43</span></span>
                <div class="xinwen_con" style="display:none"><p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">温馨提示：<br />
	<br />
	凡是已申请七月份稿酬的作者，稿费均已发放。如您在三个工作日内未查到到账记录请及时与我们联系。</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">附：以下为七月份月更新字数排行前十名的作者及稿费详情：</span></strong></span></p>
<p>
	<strong style="font-family: 楷体_gb2312"><span style="font-size: 16px">1、笔名：*心 &nbsp; &nbsp; &nbsp; &nbsp; 应发稿酬：1466.1</span></strong></p>
<p>
	<strong style="font-family: 楷体_gb2312"><span style="font-size: 16px">2、笔名：**然 &nbsp; &nbsp; &nbsp; &nbsp;应发稿酬：1264.0</span></strong></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">3、笔名：无**主 &nbsp; &nbsp; &nbsp;应发稿酬：2082</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">4、笔名：*尔**希 &nbsp; &nbsp; 应发稿酬：1443.8</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">5、笔名：**巴 &nbsp; &nbsp; &nbsp; &nbsp;应发稿酬：2236.3</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><span style="font-size: 16px"><strong>6、笔名：*尔*提 &nbsp; &nbsp; &nbsp;应发稿酬：1302.8</strong></span></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">7、笔名：*啡*灵 &nbsp; &nbsp; &nbsp;应发稿酬：1182.9</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><span style="font-size: 16px"><strong>8、笔名：*同 &nbsp; &nbsp; &nbsp; &nbsp; 应发稿酬：1282.9</strong></span></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">9、笔名：*月 &nbsp; &nbsp; &nbsp; &nbsp; 应发稿酬：1412.7</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">10、笔名：香**是* &nbsp; &nbsp;应发稿酬：1143.8<br />
	<br />
	<br />
	另：本月已评选出7月短篇前三名为：<br />
	<br />
	第一名：碧海潮生 作者：沈淮安 奖金：300<br />
	<br />
	第二名：曾经离你那么近 作者：那时如歌 奖金：200<br />
	<br />
	第三名：不散的筵席 作者：晏小婷 奖金：100<br />
	<br />
	请以上三位获奖短篇作者见到公告后联系潮流编辑QQ：295743468<br />
	<br />
	<br />
	<br />
	潮流SKY运营团队</span></strong></span></p>
<p>
	<span style="font-family: 楷体_gb2312"><strong><span style="font-size: 16px">2013.8.15</span></strong></span></p></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">网络文学受关注 潮流原创文学网乱世雄起<span class="gray">2013-11-20 11:16:49</span></span>
                <div class="xinwen_con" style="display:none"><p>
	&nbsp;</p>
<p>
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>各位亲爱的作者、读者朋友：</strong></span></span></p>
<p>
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>&nbsp;&nbsp;&nbsp; 感谢各位给予潮流原创文学网的关心和支持！在大家的一路相伴下，潮流原创文学网迎来了崭新的开端。网易TOM亦对潮流原创文学网给予了关注和报道：</strong></span></span></p>
<p>
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>&nbsp;&nbsp; &nbsp;<a href="http://post.yule.tom.com/2C001E1C508.html">http://post.yule.tom.com/2C001E1C508.html</a>&nbsp;</strong></span></span></p>
<p>
	<strong style="font-family: 楷体_gb2312; font-size: 16px">&nbsp;&nbsp;&nbsp; 网络文学受关注&nbsp;潮流原创文学网乱世雄起</strong></p>
<p>
	<strong style="font-family: 楷体_gb2312; font-size: 16px">&nbsp;&nbsp;&nbsp; 我们坚信潮流原创文学网将与大家携手走向一个崭新且辉煌的未来！</strong></p>
<p>
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong><br />
	</strong></span></span></p>
<p style="text-align: right">
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>&nbsp; &nbsp; &nbsp;潮流SKY运营团队</strong></span></span></p>
<p style="text-align: right">
	<span style="font-size: 16px"><span style="font-family: 楷体_gb2312"><strong>&nbsp; &nbsp; &nbsp; &nbsp;2013.7.26<br />
	<br />
	</strong></span></span></p></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">6月份稿酬发放及短篇评选公告<span class="gray">2013-11-20 11:02:25</span></span>
                <div class="xinwen_con" style="display:none"><p>
	&nbsp;</p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">温馨提示：</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">凡是已申请六月份稿酬的作者，稿费均已发放。如您在三个工作日内未查到到账记录请及时与我们联系。</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">附：以下为六月份月更新字数排行前十名的作者及稿费详情：</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">1、笔名：*心&nbsp; &nbsp; &nbsp; &nbsp;应发稿酬：2416&nbsp;</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">2、笔名：**然&nbsp; &nbsp; &nbsp;&nbsp;应发稿酬：1225.7</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">3、笔名：香**是* &nbsp;&nbsp;应发稿酬：2039.1</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">4、笔名：*言&nbsp; &nbsp; &nbsp; &nbsp;应发稿酬：2268.5</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">5、笔名：**雨&nbsp; &nbsp; &nbsp;&nbsp;应发稿酬：2526</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">6、笔名：**巴&nbsp; &nbsp; &nbsp;&nbsp;应发稿酬：1117.3</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">7、笔名：无**主&nbsp; &nbsp;&nbsp;应发稿酬：1112.1</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">8、笔名：玉*里**&nbsp;&nbsp; 应发稿酬：1789.6</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">9、笔名：*敏&nbsp; &nbsp; &nbsp; &nbsp;应发稿酬：1658</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">10、笔名：*岚&nbsp; &nbsp; &nbsp;&nbsp;应发稿酬：1325.7</span></strong></span></p>
<p>
	<o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312"><br />
	</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">另：本月已评选出5、6月短篇前三名为：</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">第一名：雨落成殇，暮然泪下&nbsp; &nbsp; &nbsp;作者：嫣然如梦&nbsp; &nbsp;&nbsp;奖金：300</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">第二名：你是谁的仙度瑞拉&nbsp;&nbsp; 作者：天已微凉&nbsp; &nbsp; &nbsp; &nbsp;奖金：200</span></strong></span><o:p></o:p></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">第三名：时光，花开&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 作者：果味儿&nbsp;&nbsp;&nbsp;&nbsp; 奖金：100</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">请以上三位获奖短篇作者见到公告后联系潮流编辑QQ：295743468</span></strong></span></p>
<p>
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312"><br />
	</span></strong></span></p>
<p style="text-align: right">
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">潮流SKY运营团队</span></strong></span><o:p></o:p></p>
<p style="text-align: right">
	<span style="font-size: 16px"><strong><span style="font-family: 楷体_gb2312">2013.7.16</span></strong></span><o:p></o:p></p>
<p class="MsoNormal">
	<span lang="EN-US" style="font-family: 宋体; font-size: 12pt; mso-bidi-font-family: 宋体; mso-font-kerning: 0pt"><o:p></o:p></span></p></div>

            </li><div style="clear:both;"></div>
                	<li>
               <span class="head">关于我们<span class="gray">2013-07-29 09:56:15</span></span>
                <div class="xinwen_con" style="display:none"><p>
	<strong><span style="font-family: 楷体_gb2312"><span style="font-size: 16px">&nbsp;&nbsp;&nbsp;宇宙之音(北京)科技有限公司是专业从事中国无线与互联网行业应用的公司，同时也是专业研究无线应用领域与互联网应用与开发服务为一体的权威单位。宇宙之音隶属于中国神华集团，我们依靠自身强大的渠道关系以及行业背景，在应用领域的开发和推广中硕果累累,为推动中国无线互联、基础互联网服务市场的蓬勃发展作出不可磨灭的贡献。<br />
	　　宇宙之音一直采取开放式的市场策略以及宏观的战略方针，倡导和谐、支持和引导创新，本着公平、公正、公开的原则在市场竞争中提升核心竞争力，为各个行业提供有效的科技帮助，成为最值得您信赖的合作伙伴。<br />
	　　宇宙之音是中国移动、中国联通、中国电信最紧密的战略合作伙伴，其行业深入研究与市场实施在国内一直处于领先地位。宇宙之音整合打造的&ldquo;行业应用先锋&rdquo;、&ldquo;无线应用支付科技&rdquo;、&ldquo;互联网应用支付科技&rdquo;三大策略战线，为更多的行业所接受和使用。宇宙之音一直使用自己坚实的基础激发各个行业领域的思维，研究可持续性发展，让更多行业从容的依靠我们尖端的科技实现多重市场价值！<br />
	　　潮流原创文学网（<a href="http://www.chaoliuyc.com">www.chaoliuyc.com</a>）创立于2011年，于2013年4月由宇宙之音（北京）科技有限公司收购并全面接管，目前已是国内著名的原创小说网站之一。网站集原创、阅读、无线增值服务、实体出版为一体，是综合性的网络文学平台。<br />
	&nbsp;&nbsp;&nbsp;&nbsp;潮流原创文学网一直以推动中国文学原创事业为宗旨，于挖掘原创文学和培养作者为己任。致力发展各种平台给作者提供全方位的优质服务，以及通过多种渠道向读者提供最为优秀的作品。<br />
	&nbsp;&nbsp;&nbsp;&nbsp;作为大型网络文学数字运营商，潮流原创文学网拥有一支由专业管理人才、运营人才、策划人才、资深编辑等所组成的庞大优秀团队。力争打造一个集所有特色为一体的一流优秀网络文学网站。<br />
	&nbsp;&nbsp;&nbsp;&nbsp;潮流原创文学网与多家出版社，无线运营商存在着良好的合作关系。在数字文化发展、数字出版、实体出版等领域存在自身的优越渠道。网站自身以长篇作品为主，囊括了玄幻、仙侠、奇幻、武侠、都市言情、青春校园、惊悚悬疑、侦探推理、灵异怪谈、历时军事、网游竞技等各种不同类型的高品质创作与阅读服务。同时还打造的有短片、诗歌、杂文、散文、日记、剧本等特色专区，为作者与读者提供全方位的优质服务享受。<br />
	&nbsp;&nbsp;&nbsp;&nbsp;网站旗下人才济济，汇聚的有大批优秀的管理者和作者。我们主张：快乐写作，欢乐阅读；努力创作一个完美的网络文学平台，带给大家不一样的全新享受。<br />
	</span></span></strong></p>
<p>
	<strong><span style="font-family: 楷体_gb2312"><span style="font-size: 16px"><br />
	联系电话：010-82349385<br />
	地址：北京北京海淀农大南路1号硅谷亮城</span></span></strong></p></div>

            </li><div style="clear:both;"></div>
        		<div style="float:right;height:30px;line-height:30px">
		
		</div>
      </ul>
    </div>
    </div>
</div>
</body>
</html>
';
?>