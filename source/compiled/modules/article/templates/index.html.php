<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 

"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>'.$this->_tpl_vars['article_title'].'-'.$this->_tpl_vars['sortname'].'-'.$this->_tpl_vars['jieqi_sitename'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
<link href="/themes/chaoliu/css/reset.css" rel="stylesheet"/>
<link href="/themes/chaoliu/css/index2.css" rel="stylesheet"/>
<script type="text/javascript" src="/themes/chaoliu/js/content.js"></script>
<script type="text/javascript">
<!--
var preview_page = "'.$this->_tpl_vars['preview_page'].'";
var next_page = "'.$this->_tpl_vars['next_page'].'";
var index_page = "'.$this->_tpl_vars['index_page'].'";
var article_id = "'.$this->_tpl_vars['article_id'].'";
var chapter_id = "'.$this->_tpl_vars['chapter_id'].'";

function jumpPage() {
  if (event.keyCode==37) location=preview_page;
  if (event.keyCode==39) location=next_page;
  if (event.keyCode==13) location=index_page;
}
document.onkeydown=jumpPage;
-->
</script>
</head>
<body>
<div id="wrap"> 
	<div class="page_head">
		<p class="nav"><a href="'.$this->_tpl_vars['jieqi_main_url'].'/">'.$this->_tpl_vars['jieqi_sitename'].'</a> > <a href="'.$this->_tpl_vars['url_bookroom'].'">书库首页</a> >  <a href="/modules/article/articleinfo.php?id='.$this->_tpl_vars['articleid'].'">'.$this->_tpl_vars['article_title'].'</a>&nbsp;</p>
		
		<div class="clear"></div>
	</div>
	
	<div class="page_main">
		<h1 class="index_title">'.$this->_tpl_vars['article_title'].'</h1>
		<p class="index_info"><span>作者：'.$this->_tpl_vars['author'].'</span></p>
		';
if (empty($this->_tpl_vars['indexrows'])) $this->_tpl_vars['indexrows'] = array();
elseif (!is_array($this->_tpl_vars['indexrows'])) $this->_tpl_vars['indexrows'] = (array)$this->_tpl_vars['indexrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['indexrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['indexrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['indexrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['indexrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['indexrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  ';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['ctype'] == "volume"){
echo '
  </ul><p class="section_title">
	';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['vname'] == ""){
echo '
    &nbsp; 
    ';
}else{
echo '
    '.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['vname'].'
    ';
}
echo '
	</p><div class="clear"></div><ul class="section_list">

  ';
}else{
echo '

	';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname1'] == ""){
echo '
    &nbsp;   
    ';
}else{
echo '
	<li><a href="'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['curl1'].'">'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname1'].'</a></li>
    ';
}
echo '
	';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname2'] == ""){
echo '
    &nbsp;   
    ';
}else{
echo '
	<li><a href="'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['curl2'].'">'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname2'].'</a></li>
    ';
}
echo '
	';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname3'] == ""){
echo '
    &nbsp;   
    ';
}else{
echo '
	<li><a href="'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['curl3'].'">'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname3'].'</a></li>
    ';
}
echo '
	';
if($this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname4'] == ""){
echo '
    &nbsp;   
    ';
}else{
echo '
	<li><a href="'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['curl4'].'">'.$this->_tpl_vars['indexrows'][$this->_tpl_vars['i']['key']]['cname4'].'</a></li>
    ';
}
echo '
  ';
}
echo '
  
  ';
}
echo '
  <div class="clear"></div>
  </ul>
	<script type="text/javascript" src="'.$this->_tpl_vars['jieqi_local_url'].'/configs/article/indexbottom.js"></script>

	</div>
	
</div>
<link href="/themes/chaoliu/css/index.css" rel="stylesheet" />
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/footer.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
';
?>