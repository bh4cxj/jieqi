<?php
echo '<html>
<head>
<title>'.$this->_tpl_vars['jieqi_page_title'].'</title>
<meta http-equiv="content-type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'">
<link href="templates/style.css" type="text/css" rel="stylesheet">
</head>
<body>
<div id="header"><span class="title1">'.$this->_tpl_vars['jieqi_page_head'].'</span></div>

<div id="main">
	<div id="left">
		<div ';
if($this->_tpl_vars['current_step'] == 0){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu0'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 1){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu1'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 2){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu2'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 3){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu3'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 4){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu4'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 5){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu5'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 6){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu6'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 7){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu7'].'</div>
		<div ';
if($this->_tpl_vars['current_step'] == 8){
echo ' class="step-on" ';
}else{
echo ' class="step-off"';
}
echo '>'.$this->_tpl_vars['step_menu8'].'</div>
	</div>

	<div id="right">'.$this->_tpl_vars['jieqi_content'].'</div>
	<div class="clear"></div>
</div>

<div id="footer">Powered by JIEQI CMS &copy;2004-2009 <br />技术支持：<a href="http://www.jieqi.com" target="_blank">杰奇网络</a></div>
</body>
</html>';
?>