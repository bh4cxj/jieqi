<?php
echo '<ul class="ultop">
';
if (empty($this->_tpl_vars['quizarry'])) $this->_tpl_vars['quizarry'] = array();
elseif (!is_array($this->_tpl_vars['quizarry'])) $this->_tpl_vars['quizarry'] = (array)$this->_tpl_vars['quizarry'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['quizarry']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['quizarry']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['quizarry']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['quizarry']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['quizarry']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <li><a href="'.$this->_tpl_vars['linkurl'].'/type_list.php?type='.urlencode($this->_tpl_vars['quizarry'][$this->_tpl_vars['i']['key']]['typeid']).'"> ['.$this->_tpl_vars['quizarry'][$this->_tpl_vars['i']['key']]['typeid'].'] </a><a href="'.$this->_tpl_vars['linkurl'].'/problems.php?id='.$this->_tpl_vars['quizarry'][$this->_tpl_vars['i']['key']]['quizid'].'">'.$this->_tpl_vars['quizarry'][$this->_tpl_vars['i']['key']]['title'].'</a> '.date('Y-m-d H:i',$this->_tpl_vars['quizarry'][$this->_tpl_vars['i']['key']]['addtime']).'</li>
';
}
echo '
</ul>
<div class="more"><a href="'.$this->_tpl_vars['url_more'].'">¸ü¶à...</a></div> ';
?>