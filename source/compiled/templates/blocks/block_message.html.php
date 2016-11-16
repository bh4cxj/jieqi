<?php
echo '<ul class="ulitem">
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=inbox">收件箱</a></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/message.php?box=outbox">发件箱</a></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/newmessage.php">写新消息</a></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/newmessage.php?tosys=1">写给管理员</a></li>
</ul>';
?>