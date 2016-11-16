<?php
echo '<html>
<head>
<meta http-equiv="content-type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<meta http-equiv="refresh" content=\'4; url='.$this->_tpl_vars['url'].'\'>
<title>'.$this->_tpl_vars['pagetitle'].'</title>
<link rel="stylesheet" type="text/css" media="all" href="'.$this->_tpl_vars['jieqi_themecss'].'" />
<script language="Javascript">
function Show(divid){
  if(document.all) divid.filters.revealTrans.apply(); 
  divid.style.visibility = "visible"; 
  if(document.all) divid.filters.revealTrans.play(); 
}
function Hide(divid){
  if(document.all) divid.filters.revealTrans.apply();
  divid.style.visibility = "hidden";
  if(document.all) divid.filters.revealTrans.play();
}
setTimeout("Hide(document.getElementById(\'msgboard\'))",3000);
</script>
<script language="javascript" type="text/javascript" src="'.$this->_tpl_vars['jieqi_url'].'/scripts/common.js"></script>
			</head>
<body onload="Show(document.getElementById(\'msgboard\'))">
<div style="width:100%; height:100%; text-align:center; padding-top:150px;">
<div id="msgboard" style="margin:auto; width:350px; height:100px; z-index:1; visibility: hidden; filter: revealTrans(transition=23,duration=0.5) blendTrans(duration=0.5);">
  <div class="block">
    <div class="blocktitle">'.$this->_tpl_vars['title'].'</div>
    <div class="blockcontent"><br />'.$this->_tpl_vars['content'].'<br /><br />如不能自动跳转，<a href="'.$this->_tpl_vars['url'].'">点击这里直接进入！</a><br /><br /></div>
  </div>
</div>
</div>
</body>
</html>';
?>