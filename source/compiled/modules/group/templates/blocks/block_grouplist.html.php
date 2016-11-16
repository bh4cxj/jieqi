<?php
echo '<table width="100%" align="center" class="hide">
  <tr>
    <th width="20%">圈子名</th>
	<th width="25%">圈子分类</th>
    <th width="25%">创建者</th>
    <th width="20%">话题数/会员数</th>
  </tr>
  ';
if (empty($this->_tpl_vars['grouprows'])) $this->_tpl_vars['grouprows'] = array();
elseif (!is_array($this->_tpl_vars['grouprows'])) $this->_tpl_vars['grouprows'] = (array)$this->_tpl_vars['grouprows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['grouprows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['grouprows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['grouprows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['grouprows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['grouprows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
	<tr>
		<td class="odd"><a href="'.$this->_tpl_vars['jieqi_modules']['group']['url'].'/?g='.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['gid'].'">'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['gname'].'</a></td>
		<td class="odd">'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['catname'].' </td>
		<td class="even"><a href="'.$this->_tpl_vars['jieqi_url'].'/userinfo.php?id=2" target="_blank">'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['guname'].'</a> </td>
		<td class="odd">'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['topicnum'].'/'.$this->_tpl_vars['grouprows'][$this->_tpl_vars['i']['key']]['gmembers'].'</td>
	</tr>
  ';
}
echo '
</table>';
?>