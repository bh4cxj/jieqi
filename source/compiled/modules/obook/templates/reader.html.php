<?php
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>'.$this->_tpl_vars['jieqi_pagetitle'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="description" content="'.$this->_tpl_vars['meta_description'].'" />
<meta name="author" content="'.$this->_tpl_vars['meta_author'].'" />
<meta name="copyright" content="'.$this->_tpl_vars['meta_copyright'].'" />
<meta name="generator" content="jieqi.com" />
<link href="/themes/chaoliu/css/reset.css" rel="stylesheet"/>
<link href="/themes/chaoliu/css/content.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" media="all" href="'.$this->_tpl_vars['jieqi_url'].'/configs/obook/page.css" />
'.$this->_tpl_vars['jieqi_head'].'
<script type="text/javascript">
<!--
var preview_page = "'.$this->_tpl_vars['url_preview'].'";
var next_page = "'.$this->_tpl_vars['url_next'].'";
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
<body style="background-image:url(/themes/chaoliu/images/wood.jpg);background-attachment: fixed;" >

<div id="wrap" > 
    <div class="page_head">
        <p class="nav pngFix"><a href="'.$this->_tpl_vars['dynamic_url'].'">'.$this->_tpl_vars['jieqi_sitename'].'</a>-&gt;<a href="'.$this->_tpl_vars['url_obookroom'].'">电子书店</a>-&gt;<a href="'.$this->_tpl_vars['url_obookinfo'].'">'.$this->_tpl_vars['obookname'].'</a></p>
        <div class="clear"></div>
    </div>
    <div class="page_main">
        <h1 class="chapter_title">'.$this->_tpl_vars['chaptertitle'].'</h1>
        <div class="chapter_info">
			<div class="prev_arrow"><a href="'.$this->_tpl_vars['url_preview'].'" class=\'pngFix\'></a></div>  <p class="chapter_info_mid"></p> <div class="next_arrow"><a href="'.$this->_tpl_vars['url_next'].'" class=\'pngFix\'></a></div>
        </div>
        <div class="chapter_con">
        <p><div id="frontdiv" style="position:absolute;z-index:10;border:0px;"><img src="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/images/front.gif" id="frontimage"></div>
	';
if (empty($this->_tpl_vars['picrows'])) $this->_tpl_vars['picrows'] = array();
elseif (!is_array($this->_tpl_vars['picrows'])) $this->_tpl_vars['picrows'] = (array)$this->_tpl_vars['picrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['picrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['picrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['picrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['picrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['picrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	<img id="obookimage'.$this->_tpl_vars['picrows'][$this->_tpl_vars['i']['key']].'" src="'.$this->_tpl_vars['url_obookimage'].'&pic='.$this->_tpl_vars['picrows'][$this->_tpl_vars['i']['key']].'" border="0" ';
if($this->_tpl_vars['i']['index']==0){
echo 'onload="showfront()" ';
}
echo '>
	';
}
echo '</p>
        </div>
<div class="chapter_info">
			<div class="prev_arrow"><a href="'.$this->_tpl_vars['url_preview'].'" class=\'pngFix\'></a></div>  <p class="chapter_info_mid"><span>←可使用左右快捷键翻页→</span></p> <div class="next_arrow"><a href="'.$this->_tpl_vars['url_next'].'" class=\'pngFix\'></a></div>
        </div>

</div>


<noscript>
<iframe scr="*.htm"></iframe> 
</noscript>
</body>
</html>
<script language="javascript">
function getx(e){ 
  var l=e.offsetLeft; 
  
  while(e=e.offsetParent){ 
    l+=e.offsetLeft; 
  } 
  return(l+\'px\'); 
} 
function gety(e){ 
  var l=e.offsetTop; 
  while(e=e.offsetParent){ 
    l+=e.offsetTop; 
  } 
  return(l+\'px\'); 
} 
function showfront(){
  var frontdiv=document.getElementById(\'frontdiv\');
  var frontimage=document.getElementById(\'frontimage\');
  var obookimage=document.getElementById(\'obookimage1\');
  var loadinginfo=document.getElementById(\'loadinginfo\');
  loadinginfo.style.visibility=\'hidden\';
  frontdiv.style.left=getx(obookimage);
  frontdiv.style.top=gety(obookimage);
  frontimage.width=obookimage.width;
  frontimage.height=obookimage.height;
}
//window.onload=showfront;
</script>';
?>