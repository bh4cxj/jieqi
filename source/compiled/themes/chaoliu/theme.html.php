<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'">
<TITLE>'.$this->_tpl_vars['jieqi_pagetitle'].'</TITLE>
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="Description"  content="'.$this->_tpl_vars['meta_description'].'" />
<link href="/themes/chaoliu/css/index.css" rel="stylesheet" />
<link href="/themes/chaoliu/css/nav.css" rel="stylesheet" />
<link rel="stylesheet" rev="stylesheet" href="'.$this->_tpl_vars['jieqi_themeurl'].'style.css" type="text/css" media="all" />
<script type="text/javascript" src="/themes/chaoliu/js/jquery-1.9.1.min.js"></script>
<script src="/themes/chaoliu/js/jquery.nav.js" type="text/javascript"></script>
<script type="text/javascript" src="/themes/chaoliu/js/kxbdSuperMarquee.js"></script>
<script type="text/javascript" src="/themes/chaoliu/js/index.js"></script>
<script language="javascript" type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="'.$this->_tpl_vars['jieqi_themeurl'].'theme.js"></script>
<!--[if IE 6]><script type="text/javascript" src="/themes/chaoliu/js/DD_belatedPNG.js">
</script>
<script>
 DD_belatedPNG.fix(".pngFix,.pngFix:hover,.pngFix img");
</script>
<![endif]-->
</head>
<body>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '

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
<div class="clear"></div>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/footer.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);

?>