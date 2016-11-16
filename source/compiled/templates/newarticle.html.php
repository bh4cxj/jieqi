<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" />
<title>作家中心-潮流原创文学网</title>
<link href="/themes/chaoliu/css/authorcenter.css" rel="stylesheet"/>
<script type="text/javascript" src="include/js/common.js"></script>

<script type="text/javascript" src="/template/newchaoliu/js/jquery-1.7.1.js"></script>


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
	$(\'.head\').css(\'background\',\'url(/themes/chaoliu/images/g1-icon01.gif) no-repeat 15px 5px\')
	$(\'.head\').click(function(){
			$(\'.head\').css(\'background\',\'url(/themes/chaoliu/images/g1-icon01.gif) no-repeat 15px 5px\')
			$(this).css(\'background\',\'url(/themes/chaoliu/images/g1-icon02.gif) no-repeat 15px 5px\')
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
<script language="javascript" type="text/javascript">
<!--
function frmnewarticle_validate(){
  if(document.frmnewarticle.sortid.value == ""){
    alert("请输入类别");
    document.frmnewarticle.sortid.focus();
    return false;
  }
  if(document.frmnewarticle.articlename.value == ""){
    alert("请输入文章名称");
    document.frmnewarticle.articlename.focus();
    return false;
  }
}
//-->
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
    	<h2>创建作品</h2>
		<form name="frmnewarticle" id="frmnewarticle" action="'.$this->_tpl_vars['url_newarticle'].'" method="post" onsubmit="return frmnewarticle_validate();" enctype="multipart/form-data">
<table width="100%" class="grid" cellspacing="1" align="center">
<tr valign="middle" align="left">
  <td class="odd" width="25%">类别</td>
  <td class="even">
  <select class="select" size="1" onchange="showtypes(this)" name="sortid" id="sortid">
  <option value="0">请选择类别</option>
  ';
if (empty($this->_tpl_vars['sortrows'])) $this->_tpl_vars['sortrows'] = array();
elseif (!is_array($this->_tpl_vars['sortrows'])) $this->_tpl_vars['sortrows'] = (array)$this->_tpl_vars['sortrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sortrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sortrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sortrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sortrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sortrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <option value="'.$this->_tpl_vars['i']['key'].'">'.$this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['caption'].'</option>
  ';
}
echo '
  </select>
  <span id="typeselect" name="typeselect"></span>
  <script language="javascript">
  function showtypes(obj){
    var typeselect=document.getElementById(\'typeselect\');
    typeselect.innerHTML=\'\';
    ';
if (empty($this->_tpl_vars['sortrows'])) $this->_tpl_vars['sortrows'] = array();
elseif (!is_array($this->_tpl_vars['sortrows'])) $this->_tpl_vars['sortrows'] = (array)$this->_tpl_vars['sortrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['sortrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['sortrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['sortrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['sortrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['sortrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	  ';
if($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'] != ''){
echo '
	  if(obj.options[obj.selectedIndex].value == '.$this->_tpl_vars['i']['key'].') typeselect.innerHTML=\'<select class="select" size="1" name="typeid" id="typeid">';
if (empty($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'])) $this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'] = array();
elseif (!is_array($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'])) $this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'] = (array)$this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '<option value="'.$this->_tpl_vars['j']['key'].'">'.$this->_tpl_vars['sortrows'][$this->_tpl_vars['i']['key']]['types'][$this->_tpl_vars['j']['key']].'</option>';
}
echo '</select>\';
	  ';
}
echo '
    ';
}
echo '
  }
  </script>
  </td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">文章名称</td>
  <td class="even"><input type="text" class="text" name="articlename" id="articlename" size="30" maxlength="50" value="" /></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">关键字</td>
  <td class="even"><input type="text" class="text" name="keywords" id="keywords" size="30" maxlength="50" value="" /> <span class="hottext">主角姓名,特定名词等,以空格分隔</span></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">管理员</td>
  <td class="even"><input type="text" class="text" name="agent" id="agent" size="30" maxlength="30" value="" /> <span class="hottext">可以指定一个本站现有用户作为管理员</span></td>
</tr>
';
if($this->_tpl_vars['allowtrans'] > 0){
echo '
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">作者</td>
  <td class="even"><input type="text" class="text" name="author" id="author" size="30" maxlength="30" value="" /> <span class="hottext">发表自己作品请留空</span></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">管理授权</td>
  <td class="even">
  ';
if (empty($this->_tpl_vars['authorflag']['items'])) $this->_tpl_vars['authorflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['authorflag']['items'])) $this->_tpl_vars['authorflag']['items'] = (array)$this->_tpl_vars['authorflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['authorflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['authorflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['authorflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['authorflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['authorflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <input type="radio" class="radio" name="authorflag" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['authorflag']['default']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['authorflag']['items'][$this->_tpl_vars['i']['key']].' 
  ';
}
echo '
</td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">授权级别</td>
  <td class="even">
  ';
if (empty($this->_tpl_vars['permission']['items'])) $this->_tpl_vars['permission']['items'] = array();
elseif (!is_array($this->_tpl_vars['permission']['items'])) $this->_tpl_vars['permission']['items'] = (array)$this->_tpl_vars['permission']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['permission']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['permission']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['permission']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['permission']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['permission']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <input type="radio" class="radio" name="permission" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['permission']['default']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['permission']['items'][$this->_tpl_vars['i']['key']].' 
  ';
}
echo '
</td>
</tr>
';
}
echo '
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">首发状态</td>
  <td class="even">
  ';
if (empty($this->_tpl_vars['firstflag']['items'])) $this->_tpl_vars['firstflag']['items'] = array();
elseif (!is_array($this->_tpl_vars['firstflag']['items'])) $this->_tpl_vars['firstflag']['items'] = (array)$this->_tpl_vars['firstflag']['items'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['firstflag']['items']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['firstflag']['items']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['firstflag']['items']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['firstflag']['items']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['firstflag']['items']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <input type="radio" class="radio" name="firstflag" value="'.$this->_tpl_vars['i']['key'].'" ';
if($this->_tpl_vars['i']['key'] == $this->_tpl_vars['firstflag']['default']){
echo 'checked="checked" ';
}
echo '/>'.$this->_tpl_vars['firstflag']['items'][$this->_tpl_vars['i']['key']].' 
  ';
}
echo '
</td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">内容简介</td>
  <td class="even"><textarea class="textarea" name="intro" id="intro" rows="6" cols="60"></textarea></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">本书公告</td>
  <td class="even"><textarea class="textarea" name="notice" id="notice" rows="6" cols="60"></textarea></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">封面小图</td>
  <td class="even"><input type="file" class="text" size="30" name="articlespic" id="articlespic" /> <span class="hottext">图片格式：.jpg</span></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">封面大图</td>
  <td class="even"><input type="file" class="text" size="30" name="articlelpic" id="articlelpic" /> <span class="hottext">图片格式：.jpg</span></td>
</tr>
<tr valign="middle" align="left" style="height:30px;line-height:40px;">
  <td class="odd" width="25%">&nbsp;<input type="hidden" name="action" id="action" value="newarticle" /></td>
  <td class="even"><input type="submit" class="button" name="submit"  id="submit" value="提 交" /></td>
</tr>
</table>
</form>
    </div>
                
</div>

';
?>