<?php
echo '<div class="block-head">'.$this->_tpl_vars['step_menu4'].'</div>
<div class="block-title">'.$this->_tpl_vars['step_title'].'</div>
<div class="block-summary">'.$this->_tpl_vars['step_summary'].'</div>
<div class="block-content">
	<table cellpadding="0" cellspacing="1">
		<tr class="font-bold">
			<td width="80%">目录名称</td>
			<td width="20%">可写状态</td>
		</tr>
		';
if (empty($this->_tpl_vars['path_list'])) $this->_tpl_vars['path_list'] = array();
elseif (!is_array($this->_tpl_vars['path_list'])) $this->_tpl_vars['path_list'] = (array)$this->_tpl_vars['path_list'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['path_list']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['path_list']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['path_list']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['path_list']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['path_list']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
		<tr>
			<td>'.$this->_tpl_vars['path_list'][$this->_tpl_vars['i']['key']]['path'].'</td>
			<td>';
if($this->_tpl_vars['path_list'][$this->_tpl_vars['i']['key']]['status'] == 1){
echo '<span class="span-green">可写</span>
				';
}else{
echo '<span class="span-red">不可写</span>
				';
}
echo '
			</td>
		</tr>
		';
}
echo '
	</table>
</div>
<div class="block-menu">
	<input type="button" name="bt0" value="返回安装首页" class="button" onclick="window.location=\'./index.php\';"><span class="span-space"></span>
	<input type="button" name="bt1" value="重新测试" class="button" onclick="location.reload();">
	<input type="button" name="bt2" value="下一步" class="button" onclick="window.location=\''.$this->_tpl_vars['next_page'].'\';" ';
if($this->_tpl_vars['check_status'] == 0){
echo 'disabled';
}
echo '>
</div>';
?>