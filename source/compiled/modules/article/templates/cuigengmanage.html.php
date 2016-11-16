<?php
echo '<style type="text/css">
<!--
ul.chaplist{
	list-style: none;
	clear: both;
	text-align: left;
	width: 100%;
}

li.chapter{
	float: left;
	width: 49%;
	line-height: 150%;
}

li.volume{
	width: 100%;
	clear: both;
	font-size: 14px;
	font-weight: bold;
	text-align: center;
	line-height: 150%;
	border-top: 1px solid #eaeaea;
	border-bottom: 1px solid #eaeaea;
}

ul.packflag{
	list-style: none;
	clear: both;
	text-align: left;
	width: 100%;
}

ul.packflag li{
	float: left;
	width: 49%;
	line-height: 150%;
}
-->
.geshi{width:32%;float:left;margin-top:5px;line-height:25px;height:25px;}
</style>

<table class="grid" cellspacing="1" width="100%" align="center">
 <caption>《'.$this->_tpl_vars['articlename'].'》[<a href="'.$this->_tpl_vars['url_articleinfo'].'" target="_blank">信息</a>] [<a href="'.$this->_tpl_vars['url_articleindex'].'" target="_blank">阅读</a>]</caption>
 <tr>
   <td align="center" class="head">
   [<a href="'.$this->_tpl_vars['article_static_url'].'/newvolume.php?aid='.$this->_tpl_vars['articleid'].'">新建分卷</a>] 
   [<a href="'.$this->_tpl_vars['article_static_url'].'/newchapter.php?aid='.$this->_tpl_vars['articleid'].'">增加章节</a>] 
   [<a href="'.$this->_tpl_vars['article_static_url'].'/articleedit.php?id='.$this->_tpl_vars['articleid'].'">编辑文章</a>] 
   [<a href="javascript:if(confirm(\'确实要删除该文章么？\')) document.location=\''.$this->_tpl_vars['article_static_url'].'/articledel.php?id='.$this->_tpl_vars['articleid'].'\';">删除文章</a>] 
   [<a href="javascript:if(confirm(\'确实要清空（删除所有章节）该文章么？\')) document.location=\''.$this->_tpl_vars['article_static_url'].'/articleclean.php?id='.$this->_tpl_vars['articleid'].'\';">清空文章</a>] 
   [<a href="'.$this->_tpl_vars['article_dynamic_url'].'/reviews.php?aid='.$this->_tpl_vars['articleid'].'">管理书评</a>] 
   ';
if($this->_tpl_vars['articlevote'] > 0){
echo '
   [<a href="'.$this->_tpl_vars['article_static_url'].'/votenew.php?aid='.$this->_tpl_vars['articleid'].'">新建投票</a>] 
   [<a href="'.$this->_tpl_vars['article_static_url'].'/votearticle.php?id='.$this->_tpl_vars['articleid'].'">管理投票</a>]
   ';
}
echo '
   [<a href="'.$this->_tpl_vars['article_static_url'].'/cuigengmanage.php?id='.$this->_tpl_vars['articleid'].'">查看催更</a>]
   </td>
 </tr>
 <tr>
 <td class="odd">

 ';
if($this->_tpl_vars['todaynums'] > 0){
echo '
    <div style="width:100%;text-align:center;border-bottom:1px #ccc solid;font-size:14px;height:30px;line-height:30px;">以下是读者今天投给您的催更票,合计'.$this->_tpl_vars['todaynums'].'潮流币，别辜负读者的期望哦！</div>
    <div style="width:100%;">
        <div class="geshi">用户</div><div class="geshi">所投潮流币</div><div class="geshi">催更时间</div>
    </div>
    <div style="clear:both;"></div>
 ';
if (empty($this->_tpl_vars['cuigengpiao'])) $this->_tpl_vars['cuigengpiao'] = array();
elseif (!is_array($this->_tpl_vars['cuigengpiao'])) $this->_tpl_vars['cuigengpiao'] = (array)$this->_tpl_vars['cuigengpiao'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['cuigengpiao']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['cuigengpiao']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['cuigengpiao']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['cuigengpiao']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['cuigengpiao']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
    <div style="width:100%;border-bottom:1px #ccc dashed;">
        <div class="geshi">'.$this->_tpl_vars['cuigengpiao'][$this->_tpl_vars['i']['key']]['uname'].'</div><div class="geshi">'.$this->_tpl_vars['cuigengpiao'][$this->_tpl_vars['i']['key']]['nums'].'</div><div class="geshi">'.date('H:i:s',$this->_tpl_vars['cuigengpiao'][$this->_tpl_vars['i']['key']]['dateline']).'</div>
    </div>
    <div style="clear:both;"></div>
 ';
}
echo '
 ';
}else{
echo '
    <div style="width:100%;text-align:center;border-bottom:1px #ccc solid;font-size:14px;height:30px;line-height:30px;">今天还没有催更票，请再接再厉</div>
 ';
}
echo '
 </td>
 </tr>
 <tr>
    <td style="height:30px;line-height:30px;">累计已获得催更潮流币总数为：<font style="color:red;font-weight:bold;">'.$this->_tpl_vars['zongshu'].'</font>
 </tr>
</table>';
?>