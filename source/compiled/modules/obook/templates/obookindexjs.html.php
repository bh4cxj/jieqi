<?php
echo '	function checkbuy(form){
	  var ischeck=0;
	  for (var i=0;i<form.elements.length;i++){ 
	    if (form.elements[i].name == \'checkid[]\' && form.elements[i].disabled == false && form.elements[i].checked == true) ischeck++;
	  }
	  if (ischeck == 0){
	    alert(\'请先选择要订阅的章节！\');
		return false;
	  }
	  return true;
	}
	
	function selectall(form){
	  for (var i=0;i<form.elements.length;i++){ 
	    if (form.elements[i].name == \'checkid[]\' && form.elements[i].disabled == false) form.elements[i].checked = true;
	  }
	}
	
	function cancelall(form){
	  for (var i=0;i<form.elements.length;i++){ 
	    if (form.elements[i].name == \'checkid[]\' && form.elements[i].disabled == false) form.elements[i].checked = false;
	  }
	}

var obookid = \''.$this->_tpl_vars['obookid'].'\';
var obookname = \''.$this->_tpl_vars['obookname'].'\';
var articleid = \''.$this->_tpl_vars['articleid'].'\';
var ochapters = new Array();
';
if (empty($this->_tpl_vars['ochapterrows'])) $this->_tpl_vars['ochapterrows'] = array();
elseif (!is_array($this->_tpl_vars['ochapterrows'])) $this->_tpl_vars['ochapterrows'] = (array)$this->_tpl_vars['ochapterrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['ochapterrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['ochapterrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['ochapterrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['ochapterrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['ochapterrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
ochapters['.$this->_tpl_vars['i']['key'].'] = new Array(\''.$this->_tpl_vars['ochapterrows'][$this->_tpl_vars['i']['key']]['ochapterid'].'\',\''.$this->_tpl_vars['ochapterrows'][$this->_tpl_vars['i']['key']]['chaptername'].'\',\''.$this->_tpl_vars['ochapterrows'][$this->_tpl_vars['i']['key']]['saleprice'].'\',\''.$this->_tpl_vars['ochapterrows'][$this->_tpl_vars['i']['key']]['size'].'\',\''.$this->_tpl_vars['ochapterrows'][$this->_tpl_vars['i']['key']]['postdate'].'\');
';
}
echo '

if(ochapters.length > 0){
	var cols=3;
	document.write(\'<form action="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/buyobook.php" method="post" name="frmbuy" onsubmit="return checkbuy(this);"><table width="960" border="0" align="center" cellpadding="3" cellspacing="1" class="acss">\');
	document.write(\'<tr height=30><td colspan="\'+cols+\'" class="vcss">[<span class="hottext">VIP章节目录</span> | <a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/obookinfo.php?id=\'+obookid+\'" target="_blank">查看本书信息</a> | <a href="'.$this->_tpl_vars['jieqi_modules']['pay']['url'].'/buyegold.php" target="_blank">我的帐户充值</a>]&nbsp;<input type="button" name="btnbuy1" value="选择全部未订阅章节" class="button" onclick="selectall(this.form)" />	  &nbsp;&nbsp;<input type="button" name="btnbuy2" value="取消全部选择" class="button" onclick="cancelall(this.form)" />      &nbsp;&nbsp;<input type="submit" name="btnbuy3" value="订阅全部选中章节" class="button" />	  <input name="oid" type="hidden" value="\'+obookid+\'" /></td></tr>\');

	for(i=0; i<ochapters.length; i++){
		if(i % cols == 0) document.write(\'<tr>\');
		document.write(\'<td class="ccss" height="30"> <input type="checkbox" id="checkid[]" name="checkid[]" value="\'+ochapters[i][0]+\'"> <a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/reader.php?aid=\'+articleid+\'&cid=\'+ochapters[i][0]+\'" title="字数：\'+ochapters[i][3]+\' 价格：\'+ochapters[i][2]+\' 发表时间：\'+ochapters[i][4]+\'">\'+ochapters[i][1]+\'</a></td>\');
		if((i+1) % cols == 0) document.write(\'</tr>\');
	}
	var lastcol=i % cols;
	if(lastcol < cols){
		for(i=lastcol; i<cols; i++) document.write(\'<td class="ccss">&nbsp;</td>\');
		document.write(\'</tr>\');
	}
	document.write(\'</table></form>\');
}';
?>