<?php
echo '<div class="block-head">'.$this->_tpl_vars['step_menu7'].'</div>
<div class="block-title">'.$this->_tpl_vars['step_title'].'</div>
<div class="block-summary">'.$this->_tpl_vars['step_summary'].'</div>
<form name="form1" method="post" action="'.$this->_tpl_vars['next_page'].'">
<div class="block-content">
	<table cellpadding="0" cellspacing="1">
		<tr class="font-bold">
			<td width="6%">选择</td>
			<td width="40%">模块名称</td>
			<td width="54%">安装状态</td>
		</tr>
		';
if (empty($this->_tpl_vars['mod_list'])) $this->_tpl_vars['mod_list'] = array();
elseif (!is_array($this->_tpl_vars['mod_list'])) $this->_tpl_vars['mod_list'] = (array)$this->_tpl_vars['mod_list'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['mod_list']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['mod_list']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['mod_list']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['mod_list']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['mod_list']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
		<tr>
			<td><input type="checkbox" name="mod_name[]" value="'.$this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['name'].'" onclick="doCheck();" ';
if($this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['status'] == 0){
echo ' disabled="true"';
}elseif($this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['status'] != 3){
echo ' checked="checked"';
}
echo ' /></td>
			<td>'.$this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['name'].'('.$this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['caption'].')</td>
			<td>';
if($this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['status'] == 3){
echo '<span class="span-blue">已经安装</span>
			    ';
}elseif($this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['status'] == 1){
echo '<span class="span-green">可以安装</span>
			    ';
}elseif($this->_tpl_vars['mod_list'][$this->_tpl_vars['i']['key']]['status'] == 2){
echo '<span class="span-red">必须安装</span>
				';
}else{
echo '<span class="span-red">不可安装，请配置SQL文件</span>
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
	<input type="submit" name="bt2" value="下一步" class="button">
</div>
</form>
<script language="javascript" src="js/checkbox.js"></script>';
?>