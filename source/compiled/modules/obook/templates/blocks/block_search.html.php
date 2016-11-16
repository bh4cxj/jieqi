<?php
echo '<form name="obooksearch" method="post" action="'.$this->_tpl_vars['url_obooksearch'].'" target="_blank">
<ul class="ulrow">
<li>条&nbsp; 件：<select name="searchtype" id="searchtype" class="select">
    <option value="obookname" selected>&nbsp;&nbsp;书 名&nbsp;&nbsp;</option>
    <option value="author">&nbsp;&nbsp;作 者&nbsp;&nbsp;</option>
  </select></li>
<li>关键字：<input name="searchkey" type="text" class="text" id="searchkey" size="12" maxlength="50"></li>
<li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" class="button" value=" 搜 索 "></li>
</ul>
</form>';
?>