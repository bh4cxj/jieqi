<?php
echo '	<ul class="ulitem">
	';
if (empty($this->_tpl_vars['gcats'])) $this->_tpl_vars['gcats'] = array();
elseif (!is_array($this->_tpl_vars['gcats'])) $this->_tpl_vars['gcats'] = (array)$this->_tpl_vars['gcats'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['gcats']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['gcats']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['gcats']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['gcats']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['gcats']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	<li><a href="'.$this->_tpl_vars['jieqi_modules']['group']['url'].'/grouplist.php?catid='.$this->_tpl_vars['gcats'][$this->_tpl_vars['i']['key']]['catid'].'">'.$this->_tpl_vars['gcats'][$this->_tpl_vars['i']['key']]['catname'].'</a></li>
	';
}
echo '
	</ul>

';
?>