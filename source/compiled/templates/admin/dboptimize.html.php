<?php
echo '<table class="grid" width="100%" align="center">
  <caption>提示</caption>
  <tr class="odd"><td>
  <ul>
  <li>数据表优化可以去除数据库中的碎片，使记录排列紧密，提高读写速度。</li>
  <li>数据表修复可修复数据库在进行查询，删除，更新等操作时产生的错误。</li>
  </ul>
  </td></tr>
</table>
<br />
<form action="'.$this->_tpl_vars['url_action'].'" method="post" name="checkform" id="checkform">
<table class="grid" width="100%" align="center">
<caption>数据表优化/修复</caption>
  <tr>
    <th width="5%">'.$this->_tpl_vars['checkall'].'</th>
    <th width="30%">数据表</th>
    <th width="13%">类型</th>
    <th width="13%">记录数</th>
    <th width="13%">数据</th>
	<th width="13%">索引</th>
	<th width="13%">碎片</th>
  </tr>
';
if (empty($this->_tpl_vars['tablerows'])) $this->_tpl_vars['tablerows'] = array();
elseif (!is_array($this->_tpl_vars['tablerows'])) $this->_tpl_vars['tablerows'] = (array)$this->_tpl_vars['tablerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['tablerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['tablerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['tablerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['tablerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['tablerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['checkbox'].'</td>
    <td class="even">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Name'].'</td>
    <td class="odd">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Type'].'</td>
    <td class="even">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Rows'].'</td>
    <td class="odd">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Data_length'].'</td>
	<td class="even">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Index_length'].'</td>
    <td class="odd">'.$this->_tpl_vars['tablerows'][$this->_tpl_vars['i']['key']]['Data_free'].'</td>
  </tr>
';
}
echo '
  <tr>
    <th></th>
    <th>'.$this->_tpl_vars['totaltable'].'个表</th>
    <th></th>
    <th>'.$this->_tpl_vars['totalrows'].'条记录</th>
    <th>'.$this->_tpl_vars['totalsize'].'</th>
	<th>'.$this->_tpl_vars['totalindex'].'</th>
    <th>'.$this->_tpl_vars['totalfree'].'</th>
  </tr>
  <tr>
    <td colspan="7" class="foot"><input type="button" name="allcheck" value="全部选中" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if(this.form.elements[i].type == \'checkbox\') this.form.elements[i].checked = true; }">&nbsp;&nbsp;<input type="button" name="nocheck" value="全部取消" class="button" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if(this.form.elements[i].type == \'checkbox\') this.form.elements[i].checked = false; }">&nbsp;&nbsp;<input name="action" type="radio" value="optimize"';
if($this->_tpl_vars['option'] == "optimize"){
echo ' checked="checked"';
}
echo ' />
      优化表 <input name="action" type="radio" value="repair"';
if($this->_tpl_vars['option'] == "repair"){
echo ' checked="checked"';
}
echo ' />修复表 &nbsp;&nbsp;&nbsp;<input type="submit" name="Submit" class="button" value=" 提 交 " /></td>
  </tr>
</table>
</form>
<br /><br />';
?>