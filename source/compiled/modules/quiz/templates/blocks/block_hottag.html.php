<?php
echo '<ul class="ultop">
';
if (empty($this->_tpl_vars['tagarry'])) $this->_tpl_vars['tagarry'] = array();
elseif (!is_array($this->_tpl_vars['tagarry'])) $this->_tpl_vars['tagarry'] = (array)$this->_tpl_vars['tagarry'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['tagarry']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['tagarry']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['tagarry']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['tagarry']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['tagarry']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<span><a href="'.$this->_tpl_vars['linkurl'].'/tag.php?tagname='.urlencode($this->_tpl_vars['tagarry'][$this->_tpl_vars['i']['key']]['tagname']).'">'.$this->_tpl_vars['tagarry'][$this->_tpl_vars['i']['key']]['tagname'].'</a></span>
';
}
echo '
</ul>
<div class="more"><a href="'.$this->_tpl_vars['linkurl'].'/tag_list.php">¸ü¶à...</a></div> ';
?>