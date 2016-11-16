<?php
echo '<form name="frmsearch" method="post" action="'.$this->_tpl_vars['article_dynamic_url'].'/admin/reviews.php">
<table class="grid" width="100%" align="center">
    <tr>
      <td class="odd">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
         <tr>
           <td width="65%">关键字：
            <input name="keyword" type="text" id="keyword" class="text" size="0" maxlength="50"> <input name="keytype" type="radio" class="radio" value="0" checked>
            文章名称 
            <input type="radio" name="keytype" class="radio" value="1">
            发表人 &nbsp;&nbsp;
       <input type="submit" name="btnsearch" class="button" value="搜 索"></td>
           <td width="35%" align="right">[<a href="'.$this->_tpl_vars['article_dynamic_url'].'/reviewslist.php?type=all">全部书评</a>] &nbsp;&nbsp; [<a href="'.$this->_tpl_vars['article_dynamic_url'].'/reviewslist.php?type=good">精华书评</a>]&nbsp;</td>
         </tr>
       </table></td>
    </tr>
</table>
</form>
<br />
<form name="frmsearch" method="post" action="'.$this->_tpl_vars['article_dynamic_url'].'/admin/reviews.php">
<table class="grid" width="100%" align="center">
  <tr align="center">
  	<td width="5%" class="title"><input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }"></th>
    <td width="40%" class="title">主题</td>
    <td width="11%" class="title">书名</td>
    <td width="11%" class="title">点击/回复</td>
    <td width="11%" class="title">发表人</td>
    <td width="16%" class="title">发表时间</td>
	<td width="6%" class="title">操作</td>
  </tr>
  ';
if (empty($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = array();
elseif (!is_array($this->_tpl_vars['reviewrows'])) $this->_tpl_vars['reviewrows'] = (array)$this->_tpl_vars['reviewrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['reviewrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['reviewrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['reviewrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['reviewrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
  	<td class="even" align="center"><input type="checkbox" id="checkid[]" name="checkid[]" value="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'"></td>
    <td class="odd">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 1){
echo '<span class="hottext">[顶]</span>';
}
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 1){
echo '<span class="hottext">[精]</span>';
}
echo '<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviewshow.php?rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td class="even"><a href="'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></td>
    <td align="center" class="odd">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['views'].'/'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replies'].'</td>
    <td class="even"><a href="'.jieqi_geturl('system','user',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid']).'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['poster'].'</a></td>
    <td align="center" class="odd">'.date('Y-m-d H:i:s',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posttime']).'</td>
	<td align="center" class="even"><a href="javascript:if(confirm(\'确实要删除该书评么？\')) document.location=\''.$this->_tpl_vars['jieqi_modules']['article']['url'].'/admin/reviews.php?action=del&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'\';">删除</a></td>
  </tr>
  ';
}
echo '
</table>
<div style="width:15%;float:left;text-align:left;padding:3px;"><input type="submit" name="Submit" value="批量删除" class="button"><input name="batchdel" type="hidden" value="1"></div>
</form>
<div style="width:84%;float:right;text-align:right;padding:3px;">'.$this->_tpl_vars['url_jumppage'].'</div>';
?>