<?php
echo '<form name="frmsearch" method="post" action="'.$this->_tpl_vars['url_obook'].'">
<table class="grid" width="100%" align="center">
    <tr>
        <td class="odd">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="15" maxlength="50"> 
            <input name="keytype" type="radio" class="radio" value="0" checked>书名
            <input type="radio" name="keytype" class="radio" value="1">作者 
			<input type="radio" name="keytype" class="radio" value="2">发表者
			&nbsp;&nbsp;
            <input type="submit" name="btnsearch" class="button" value="搜 索">
            &nbsp;&nbsp;&nbsp;&nbsp;<a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php">全部电子书</a> | <a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?display=hide">待审电子书</a> | <a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?display=show">已审电子书</a> | <a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?display=unsale">已下架电子书</a> | <a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?display=self">本站电子书</a>        
        </td>
    </tr>
</table>
</form>
<br />
<form action="'.$this->_tpl_vars['url_obook'].'" method="post" name="checkform" id="checkform">
<table class="grid" width="100%" align="center">
<caption>'.$this->_tpl_vars['obooktitle'].'</caption>
  <tr align="center">
    <th width="20%">电子书名称</th>
    <th width="28%">最新章节</th>
    <th width="8%">作者</th>
    <th width="16%">总销售额</th>
    <th width="8%">更新</th>
    <th width="20%">操作</th>
  </tr>
  ';
if (empty($this->_tpl_vars['obookrows'])) $this->_tpl_vars['obookrows'] = array();
elseif (!is_array($this->_tpl_vars['obookrows'])) $this->_tpl_vars['obookrows'] = (array)$this->_tpl_vars['obookrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['obookrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['obookrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['obookrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['obookrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['obookrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="even"><a href="'.$this->_tpl_vars['obook_dynamic_url'].'/obookinfo.php?id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'" target="_blank">'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookname'].'</a></td>
    <td class="odd"><a href="'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['url_lastchapter'].'" target="_blank">'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['lastvolume'].' '.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['lastchapter'].'</a></td>
    <td class="even">';
if($this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['authorid'] == 0){
echo $this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['author'];
}else{
echo '<a href="'.jieqi_geturl('system','user',$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['authorid']).'" target="_blank">'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['author'].'</a>';
}
echo '</td>
    <td class="odd" align="center">'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['sumemoney'];
if($this->_tpl_vars['jieqi_silverusage']==1){
echo '('.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['sumegold'].'/'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['sumesilver'].')';
}
echo '</td>
    <td class="even" align="center">'.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['lastupdate'].'</td>
    <td class="odd" align="center">';
if($this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['display'] == 0){
echo '<a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?action=hide&id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'">隐藏</a> <a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/setgood.php?id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'" target="_blank">推荐</a>';
}else{
echo '<a href="'.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?action=confirm&id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'">审核</a> 推荐';
}
echo ' <a href="'.$this->_tpl_vars['obook_static_url'].'/obookmanage.php?id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'" target="_blank">管理</a> <a href="javascript:if(confirm(\'电子书下架将不再对外显示，但不影响以前销售额统计，要继续么？\')) document.location=\''.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?action=unsale&id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'\'">下架</a> <a href="javascript:if(confirm(\'删除电子书将同时删除销售记录，并无法恢复，要继续么？\')) document.location=\''.$this->_tpl_vars['obook_dynamic_url'].'/admin/obooklist.php?action=del&id='.$this->_tpl_vars['obookrows'][$this->_tpl_vars['i']['key']]['obookid'].'\'">删除</a></td>
  </tr>
  ';
}
echo '
</table>
</form>
<table width="100%"  border="0" cellspacing="2" cellpadding="3">
  <tr>
    <td width="12%" align="right"><!--<input type="submit" name="Submit" value="批量删除" class="button">
                <input name="batchdel" type="hidden" value="1">--></td>
    <td width="88%" align="right">'.$this->_tpl_vars['url_jumppage'].'</td>
  </tr>
</table>

';
?>