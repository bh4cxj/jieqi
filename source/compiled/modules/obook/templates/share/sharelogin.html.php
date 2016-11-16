<?php
echo '<br /><br /><br /><br />
<form name="frmlogin" method="post" action="'.$this->_tpl_vars['url_login'].'">
 <table class="grid" width="250" align="center">
    <caption>代理共享平台登陆</caption>
    <tr align="center">
      <td class="odd"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
        <tr>
          <td align="right" valign="middle">出版商：</td>
          <td>
              <select name="publishid">
			  ';
if (empty($this->_tpl_vars['publishrows'])) $this->_tpl_vars['publishrows'] = array();
elseif (!is_array($this->_tpl_vars['publishrows'])) $this->_tpl_vars['publishrows'] = (array)$this->_tpl_vars['publishrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['publishrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['publishrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['publishrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['publishrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['publishrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
                <option value="'.$this->_tpl_vars['publishrows'][$this->_tpl_vars['i']['key']]['id'].'">'.$this->_tpl_vars['publishrows'][$this->_tpl_vars['i']['key']]['name'].'</option>
			  ';
}
echo '
              </select>
         </td>
        </tr>
        <tr>
          <td align="right" valign="middle">密　码：</td>
          <td><input type="password" class="text" size=15 maxlength=30 name="password"></td>
        </tr>
';
if($this->_tpl_vars['show_checkcode'] == 1){
}
echo '
  <tr align="center">
    <td colspan="2"><input type="hidden" name="action" value="login">
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="submit" class="button" value="&nbsp;登&nbsp;&nbsp;录&nbsp;" name="submit"></td>
  </tr>
      </table></td>
    </tr>
  </table>
</form><br /><br />';
?>