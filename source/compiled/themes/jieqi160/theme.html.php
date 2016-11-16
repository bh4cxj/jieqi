<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>'.$this->_tpl_vars['jieqi_pagetitle'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
<meta name="generator" content="jieqi.com" />
<link rel="stylesheet" rev="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'style.css" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'theme.js"></script>
'.$this->_tpl_vars['jieqi_head'].'
</head>
<body>
<div class="main m_top">
<div class="fl">&nbsp;';
if($this->_tpl_vars['jieqi_userid'] == 0){
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，您可以选择[<a href="javascript:;" onclick="openDialog(\''.$this->_tpl_vars['jieqi_user_url'].'/login.php?jumpurl='.urlencode($this->_tpl_vars['jieqi_thisurl']).'&ajax_gets=jieqi_contents\', false);">登录</a>]或者[<a href="javascript:;" onclick="openDialog(\''.$this->_tpl_vars['jieqi_user_url'].'/register.php?ajax_gets=jieqi_contents\', false);">注册新用户</a>]！';
}elseif($this->_tpl_vars['jieqi_newmessage'] == 0){
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，'.$this->_tpl_vars['jieqi_username'].' [<a href="'.$this->_tpl_vars['jieqi_user_url'].'/logout.php">退出登录</a>]';
}else{
echo $this->_tpl_vars['jieqi_sitename'].'欢迎您，'.$this->_tpl_vars['jieqi_username'].' [<a href="'.$this->_tpl_vars['jieqi_user_url'].'/logout.php">退出登录</a>] [<a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=inbox"><span class="hottext">您有新消息</span></a>]';
}
echo '</div>
<div class="fr"><a href="'.$this->_tpl_vars['url_gb2312'].'">简体版</a> · <a href="'.$this->_tpl_vars['url_big5'].'">繁体版</a>&nbsp;&nbsp;</div>
</div>

<div class="main m_head">
<div class="h_logo"><a href="'.$this->_tpl_vars['jieqi_url'].'/"><img src="'.$this->_tpl_vars['jieqi_themeurl'].'logo.gif" border="0" alt="'.$this->_tpl_vars['jieqi_sitename'].'" /></a></div>
<div class="h_banner">'.$this->_tpl_vars['jieqi_banner'].'</div>
<div class="h_link">
  <ul class="ulitem">
  <li><a onClick="this.style.behavior=\'url(#default#homepage)\';this.setHomePage(\''.$this->_tpl_vars['jieqi_url'].'\');" href="#">设为首页</a>&nbsp;</li>
  <li><a href="mailto:'.$this->_tpl_vars['jieqi_email'].'">联系我们</a>&nbsp;</li>
  <li><a href="javascript:window.external.addFavorite(\''.$this->_tpl_vars['jieqi_url'].'\',\''.$this->_tpl_vars['jieqi_sitename'].'\')">加入收藏</a>&nbsp;</li>
  </ul>
</div>
</div>

<div class="main m_menu">
<ul id="jieqi_menu">
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/">返回首页</a></li>
';
if($this->_tpl_vars['jieqi_modules']['news']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['news']['url'].'/">本站新闻</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/">在线书库</a>
  <ul>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/articlelist.php?class=0">分类阅读</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/toplist.php?sort=allvisit">排 行 榜</a></li>
  </ul>
</li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/">电子书店</a>
  <ul>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/obooklist.php?class=0">全部书籍</a></li>
  </ul>
</li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['cartoon']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['cartoon']['url'].'/">在线漫画</a>
  <ul>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['cartoon']['url'].'/cartoonlist.php?class=0">分类阅读</a></li>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['cartoon']['url'].'/toplist.php?sort=allvisit">漫画排行</a></li>
  </ul>
</li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['space']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['space']['url'].'/">个人空间</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['group']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['group']['url'].'/">圈子交友</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['info']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['info']['url'].'/">分类信息</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['quiz']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['quiz']['url'].'/">你问我答</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['pay']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/buyegold.php">帐户冲值</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['note']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['note']['url'].'/">访客留言</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['vote']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['vote']['url'].'/">投票调查</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['forum']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['forum']['url'].'/">交流论坛</a>
  <ul>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['forum']['url'].'/index.php">论坛首页</a></li>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['forum']['url'].'/search.php">论坛搜索</a></li>
  </ul>
</li>
';
}
echo '
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/userdetail.php">用户面板</a>
  <ul>
	<li><a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=inbox">收 件 箱</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?oid=self">会 客 室</a></li>
	';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/bookcase.php">小说书架</a></li>
	';
}
echo '
	';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/obookcase.php">电子书书架</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/buylog.php">我的电子书</a></li>
	';
}
echo '
	';
if($this->_tpl_vars['jieqi_modules']['pay']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/buyegold.php">帐户冲值</a></li>
	';
}
echo '
	';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/applywriter.php">申请作者</a></li>
	';
}
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_url'].'/useredit.php">修改资料</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_url'].'/userlist.php">会员列表</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_user_url'].'/logout.php">退出登录</a></li>
  </ul>
</li>
';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/myarticle.php">作家工具</a>
  <ul>
    <li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/newarticle.php">新建小说</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/masterpage.php">我的小说</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/newdraft.php">新建草稿</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/draft.php">草 稿 箱</a></li>
	';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/newobook.php">新建电子书</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/masterpage.php">我的电子书</a></li>
	';
}
echo '
	';
if($this->_tpl_vars['jieqi_modules']['cartoon']['publish'] > 0){
echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['cartoon']['url'].'/newcartoon.php">新建漫画</a></li>
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['cartoon']['url'].'/masterpage.php">我的漫画</a></li>
	';
}
echo '
  </ul>
</li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_group'] == 2){
echo '<li><a href="'.$this->_tpl_vars['jieqi_url'].'/admin/index.php">网站管理</a></li>';
}
echo '
</ul>
</div>

';
if($this->_tpl_vars['jieqi_top_bar'] != ""){
echo '<div class="main">'.$this->_tpl_vars['jieqi_top_bar'].'</div>';
}
echo '
';
if($this->_tpl_vars['jieqi_showtblock'] == 1){
echo '
';
if (empty($this->_tpl_vars['jieqi_tblocks'])) $this->_tpl_vars['jieqi_tblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_tblocks'])) $this->_tpl_vars['jieqi_tblocks'] = (array)$this->_tpl_vars['jieqi_tblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_tblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_tblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_tblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_tblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_tblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <div class="main">
  <div class="block">
  ';
if($this->_tpl_vars['jieqi_tblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_tblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
  <div class="blockcontent">'.$this->_tpl_vars['jieqi_tblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
  </div>
  </div>
';
}
echo '
';
}
echo '

';
if($this->_tpl_vars['jieqi_showcblock'] == 1){
echo '
<div class="main" id="topm">
<div id="cleft">
';
if (empty($this->_tpl_vars['jieqi_clblocks'])) $this->_tpl_vars['jieqi_clblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_clblocks'])) $this->_tpl_vars['jieqi_clblocks'] = (array)$this->_tpl_vars['jieqi_clblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_clblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_clblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_clblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_clblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_clblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <div class="block">
  ';
if($this->_tpl_vars['jieqi_clblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_clblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
  <div class="blockcontent">'.$this->_tpl_vars['jieqi_clblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
  </div>
';
}
echo '
</div>
<div id="cright">
';
if (empty($this->_tpl_vars['jieqi_crblocks'])) $this->_tpl_vars['jieqi_crblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_crblocks'])) $this->_tpl_vars['jieqi_crblocks'] = (array)$this->_tpl_vars['jieqi_crblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_crblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_crblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_crblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_crblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_crblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <div class="block">
  ';
if($this->_tpl_vars['jieqi_crblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_crblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
  <div class="blockcontent">'.$this->_tpl_vars['jieqi_crblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
  </div>
';
}
echo '
</div>
</div>
';
}
echo '

<div class="main">
';
if($this->_tpl_vars['jieqi_showlblock'] == 1){
echo '
<div id="left">
';
if (empty($this->_tpl_vars['jieqi_lblocks'])) $this->_tpl_vars['jieqi_lblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_lblocks'])) $this->_tpl_vars['jieqi_lblocks'] = (array)$this->_tpl_vars['jieqi_lblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_lblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_lblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_lblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_lblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_lblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<div class="block">
';
if($this->_tpl_vars['jieqi_lblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_lblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
<div class="blockcontent">'.$this->_tpl_vars['jieqi_lblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
</div>
';
}
echo '  
</div>
  ';
if($this->_tpl_vars['jieqi_showrblock'] == 1){
echo '<div id="centers">';
}else{
echo '<div id="centerm">';
}
echo '
';
}else{
echo '
  ';
if($this->_tpl_vars['jieqi_showrblock'] == 1){
echo '<div id="centerm">';
}else{
echo '<div id="centerl">';
}
echo '
';
}
echo '
';
if($this->_tpl_vars['jieqi_showcblock'] == 1){
echo '
';
if (empty($this->_tpl_vars['jieqi_ctblocks'])) $this->_tpl_vars['jieqi_ctblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_ctblocks'])) $this->_tpl_vars['jieqi_ctblocks'] = (array)$this->_tpl_vars['jieqi_ctblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_ctblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_ctblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_ctblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_ctblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_ctblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<div class="block">
';
if($this->_tpl_vars['jieqi_ctblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_ctblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
<div class="blockcontent">'.$this->_tpl_vars['jieqi_ctblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
</div>
';
}
echo '
';
if (empty($this->_tpl_vars['jieqi_cmblocks'])) $this->_tpl_vars['jieqi_cmblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_cmblocks'])) $this->_tpl_vars['jieqi_cmblocks'] = (array)$this->_tpl_vars['jieqi_cmblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_cmblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_cmblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_cmblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_cmblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_cmblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<div class="block">
';
if($this->_tpl_vars['jieqi_cmblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_cmblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
<div class="blockcontent">'.$this->_tpl_vars['jieqi_cmblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
</div>
';
}
echo '
';
}
echo '
';
if($this->_tpl_vars['jieqi_contents'] != ""){
echo '<div id="content">'.$this->_tpl_vars['jieqi_contents'].'</div>';
}
echo '
';
if($this->_tpl_vars['jieqi_showcblock'] == 1){
echo '
';
if (empty($this->_tpl_vars['jieqi_cbblocks'])) $this->_tpl_vars['jieqi_cbblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_cbblocks'])) $this->_tpl_vars['jieqi_cbblocks'] = (array)$this->_tpl_vars['jieqi_cbblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_cbblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_cbblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_cbblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_cbblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_cbblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<div class="block">
';
if($this->_tpl_vars['jieqi_cbblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_cbblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
<div class="blockcontent">'.$this->_tpl_vars['jieqi_cbblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
</div>
';
}
echo '
';
}
echo '
</div>
';
if($this->_tpl_vars['jieqi_showrblock'] == 1){
echo '
<div id="right">
';
if (empty($this->_tpl_vars['jieqi_rblocks'])) $this->_tpl_vars['jieqi_rblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_rblocks'])) $this->_tpl_vars['jieqi_rblocks'] = (array)$this->_tpl_vars['jieqi_rblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_rblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_rblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_rblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_rblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_rblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<div class="block">
';
if($this->_tpl_vars['jieqi_rblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle"><span>'.$this->_tpl_vars['jieqi_rblocks'][$this->_tpl_vars['i']['key']]['title'].'</span></div>';
}
echo '
<div class="blockcontent">'.$this->_tpl_vars['jieqi_rblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
</div>
';
}
echo '
</div>
';
}
echo '
</div>

';
if($this->_tpl_vars['jieqi_showbblock'] == 1){
echo '
';
if (empty($this->_tpl_vars['jieqi_bblocks'])) $this->_tpl_vars['jieqi_bblocks'] = array();
elseif (!is_array($this->_tpl_vars['jieqi_bblocks'])) $this->_tpl_vars['jieqi_bblocks'] = (array)$this->_tpl_vars['jieqi_bblocks'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqi_bblocks']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqi_bblocks']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqi_bblocks']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqi_bblocks']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqi_bblocks']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <div class="main">
  <div class="block">
  ';
if($this->_tpl_vars['jieqi_bblocks'][$this->_tpl_vars['i']['key']]['title'] != ""){
echo '<div class="blocktitle">'.$this->_tpl_vars['jieqi_bblocks'][$this->_tpl_vars['i']['key']]['title'].'</div>';
}
echo '
  <div class="blockcontent">'.$this->_tpl_vars['jieqi_bblocks'][$this->_tpl_vars['i']['key']]['content'].'</div>
  </div>
  </div>
';
}
echo '
';
}
echo '
';
if($this->_tpl_vars['jieqi_bottom_bar'] != ""){
echo '<div class="main">'.$this->_tpl_vars['jieqi_bottom_bar'].'</div>';
}
echo '

<div class="main m_foot">
技术支持：<a href="http://www.jieqi.com/" target="_blank">杰奇网络</a> | 执行时间：'.$this->_tpl_vars['jieqi_exetime'].'秒
</div>
</body>
</html>';
?>