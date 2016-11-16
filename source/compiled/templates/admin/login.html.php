<?php
echo '<div class="tc">
<div id="login">
<form name="frmlogin" method="post" action="'.$this->_tpl_vars['url_login'].'">
<ul>
<li>用户名：';
if($this->_tpl_vars['jieqi_username'] == ''){
echo '<input type="text" class="text" size="20" maxlength="30" style="width:120px" name="username" onKeyPress="javascript: if (event.keyCode==32) return false;">';
}else{
echo $this->_tpl_vars['jieqi_username'];
}
echo '</li>
<li>密　码：<input type="password" class="text" size="20" maxlength="30" style="width:120px" name="password"></li>
';
if($this->_tpl_vars['show_checkcode'] == 1){
echo '
<li>验证码：<input type="text" class="text" size="8" maxlength="8" name="checkcode">
&nbsp;<img src="'.$this->_tpl_vars['url_checkcode'].'" style="cursor:pointer;" onclick="this.src=\''.$this->_tpl_vars['url_checkcode'].'\';"></li>
';
}
echo '
<li>　　　　<input type="submit" class="login_button" value="&nbsp;" name="submit"><input type="hidden" name="action" value="login"></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/">返回首页</a>&nbsp;&nbsp;<a href="javascript:history.back(1);">返回上页</a>&nbsp;&nbsp;<a href="'.$this->_tpl_vars['jieqi_url'].'/getpass.php">取回密码</a></li>
</ul>
</form>
</div>
</div>';
?>