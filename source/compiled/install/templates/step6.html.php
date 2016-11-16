<?php
echo '<div class="block-head">'.$this->_tpl_vars['step_menu6'].'</div>
<div class="block-title">'.$this->_tpl_vars['step_title'].'</div>
<div class="block-summary">'.$this->_tpl_vars['step_summary'].'</div>
<div class="block-content">
	';
if($this->_tpl_vars['link_status'] == true){
echo '
	<span class="span-green">'.$this->_tpl_vars['step_content'].'</span>
	';
}else{
echo '
	<span class="span-red">'.$this->_tpl_vars['step_content'].'</span>
	';
}
echo '
</div>
<div class="block-menu">
	<input type="button" name="bt0" value="返回安装首页" class="button" onclick="window.location=\'./index.php\';"><span class="span-space"></span>	
	<input type="button" name="bt1" value="重新配置" class="button" onclick="history.go(-1);">
	<input type="button" name="bt2" value="下一步" class="button" onclick="window.location=\''.$this->_tpl_vars['next_page'].'\';" ';
if($this->_tpl_vars['link_status'] == 0){
echo 'disabled';
}
echo '>
</div>';
?>