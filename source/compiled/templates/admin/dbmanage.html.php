<?php
if($this->_tpl_vars['option'] == 4){
echo $this->_tpl_vars['s'];
}
echo '
';
if($this->_tpl_vars['option'] == 3){
echo '
<br />
<br />
<table class="grid" align="center" width="50%">
<caption>数据库备份</caption>
  <tr class="odd"><td><br />'.$this->_tpl_vars['backup_info'].'<br /><br /></td></tr>
  <tr class="even"><td align="center"><a href="javascript:history.go(-1);">返回上页</a></td></tr>
</table>
';
}
echo '
<table class="grid" width="100%">
  ';
if($this->_tpl_vars['option'] == 1){
echo '  
  <caption>提示</caption>
  <tr class="odd"><td>
  <ul>
  <li>数据备份功能根据您的选择的数据表备份成文件，导出的数据文件可用“数据库恢复”功能或 phpMyAdmin 导入，默认备份文件保存在/files/systemdbbackup目录。</li>
  <li>全部备份均不包含模板文件和附件文件。模板、附件的备份只需通过 FTP 等下载 ./templates, ./files 目录即可，本系统不提供单独备份。</li>
  <li>数据备份选项中的设置，仅供高级用户的特殊用途使用，当您尚未对数据库做全面细致的了解之前，请使用默认参数备份，否则将导致备份数据错误等严重问题。</li>
  <li>十六进制方式可以保证备份数据的完整性，但是备份文件会占用更多的空间。</li>
  ';
}else if($this->_tpl_vars['option'] == 2){
echo '
  <caption>提示</caption>
  <tr class="odd"><td>
  <ul>
  <li>本功能在恢复备份数据的同时，将全部覆盖原有数据，请确定恢复前已将网站关闭，恢复全部完成后可以将网站重新开放。</li>
  <li>数据恢复功能只能恢复由当前版本程序导出的数据文件，其他软件导出格式可能无法识别。</li>
  <li>您可以手动输入备份SQL的文件名进行数据恢复，多卷文件只需输入某一分卷文件名，所有分卷数据文件会由系统自动导入。</li>
  <li>系统可自动识别形似080811_OPSVstMx-1.sql 或 080811_OPSVstMx-1 或 080811_OPSVstMx的文件名。</li>
  ';
}
echo '
  </ul>
  </td></tr>
</table>
<br />
'.$this->_tpl_vars['dbmanage_form'].'
<br />
';
if($this->_tpl_vars['option'] == 2){
echo '
<form name="form1" method="post" action="">
<table class="grid" width="100%">
<caption>数据备份纪录</caption>
  <tr align="center">
    <td width="5%">选择</td>
    <td width="5%">序号</td>
    <td width="20%">文件名称</td>
	<td width="17%">备份时间</td>
    <td width="10%">版本</td>
	<td width="10%">大小</td>
	<td width="10%">类型</td>
	<td width="5%">方式</td>
	<td width="5%">卷号</td>
	<td width="13%">操作</td>
  </tr>
';
if (empty($this->_tpl_vars['log_list'])) $this->_tpl_vars['log_list'] = array();
elseif (!is_array($this->_tpl_vars['log_list'])) $this->_tpl_vars['log_list'] = (array)$this->_tpl_vars['log_list'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['log_list']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['log_list']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['log_list']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['log_list']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['log_list']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr align="center">
    <td class="odd">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['checkbox'].'</td>
	<td class="even">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['id'].'</td>
    <td class="odd"><a href="'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['downloadurl'].'">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['name'].'</a></td>
	<td class="even">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['time'].'</td>
    <td class="odd">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['version'].'</td>
    <td class="even">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['size'].'</td>
	<td class="odd">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['type'].'</td>
	<td class="even">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['mode'].'</td>
    <td class="odd">'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['volume'].'</td>
	<td class="even"><a href="'.$this->_tpl_vars['log_list'][$this->_tpl_vars['i']['key']]['importurl'].'" onclick="javascript:if(confirm(\'确实要恢复该批数据吗？\\n\\n该操作会导致当前全部或部分数据丢失！恢复前请先做好数据备份工作！\')){return true;}else{return false;}">恢复</a></td>
  </tr>
';
}
echo '
  
</table>
<br />
<input type="checkbox" name="allcheck" onclick="javascript:if(this.checked==true){for(var i=0;i<this.form.elements.length;i++){this.form.elements[i].checked=true;}}else{for(var i=0;i<this.form.elements.length;i++){this.form.elements[i].checked=false;}}" />全选&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="delcheck" value="删除选中记录" class="button" onclick="javascript:if(confirm(\'确实要删除选中记录么？\')){ this.form.checkaction.value=\'1\';this.form.submit();}else{return false;}" /><input name="checkaction" type="hidden" id="checkaction" value="0" />
</form>
';
}
echo '
<br />
<br />';
?>