<?php
echo '<script language="javascript" src="js/jumpmenu.js"></script>
<div class="block-head">'.$this->_tpl_vars['step_menu0'].'</div>
<div class="block-title">'.$this->_tpl_vars['step_title'].'</div>
<div class="block-summary">'.$this->_tpl_vars['step_summary'].'</div>
<div class="block-content">'.$this->_tpl_vars['step_content'].'</div>
<div class="block-menu">
<input type="button" name="bt1" value="全新安装" class="button" onclick="window.location=\''.$this->_tpl_vars['next_page'].'\';">
<input type="button" name="bt2" value="模块安装" class="button" onclick="window.location=\''.$this->_tpl_vars['direct_page'].'\';">
</div>';
?>