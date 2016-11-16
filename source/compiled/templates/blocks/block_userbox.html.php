<?php
echo '<ul class="ulitem">
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/myfriends.php">我的好友</a></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?uid=self">我的会客室</a></li>
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/mylink.php">我的链接</a></li>
';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/bookcase.php">我的书架</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/obookcase.php">电子书架</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/buylog.php">我的电子书</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['pay']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/buyegold.php">购买'.$this->_tpl_vars['egoldname'].'</a></li>
';
}
echo '
';
if($this->_tpl_vars['jieqi_modules']['article']['publish'] > 0){
echo '
<li><a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/applywriter.php">申请作者</a></li>
';
}
echo '
<li><a href="'.$this->_tpl_vars['jieqi_url'].'/online.php">在线用户</a></li>
</ul>';
?>