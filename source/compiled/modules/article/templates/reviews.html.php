<?php
echo '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td><table width="100%"  border="0" cellspacing="2" cellpadding="3">
            <tr>
                <td width="40%" align="left"><a href="'.$this->_tpl_vars['url_articleinfo'].'">《'.$this->_tpl_vars['articlename'].'》</a> 书评列表</td>
                <td width="60%" align="right">';
if($this->_tpl_vars['type'] == "good"){
echo '&nbsp;&nbsp;&nbsp;&nbsp;[<a href="'.$this->_tpl_vars['article_dynamic_url'].'/reviews.php?aid='.$this->_tpl_vars['articleid'].'&type=all">全部书评</a>] &nbsp;&nbsp; [精华书评]';
}else{
echo '&nbsp;&nbsp;&nbsp;&nbsp;[全部书评] &nbsp;&nbsp; [<a href="'.$this->_tpl_vars['article_dynamic_url'].'/reviews.php?aid='.$this->_tpl_vars['articleid'].'&type=good">精华书评</a>]';
}
echo '</td>
            </tr>
        </table></td>
    </tr>
</table>
<table class="grid" width="100%" align="center">
  <tr align="center">
    <td width="54%" class="title">主题</td>
    <td width="12%" class="title">回复/查看</td>
    <td width="17%" class="title">发表人/回复人</td>
    <td width="15%" class="title">发表时间</td>
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
    <td class="odd">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 1){
echo '<span class="hottext">[顶]</span>';
}
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 1){
echo '<span class="hottext">[精]</span>';
}
echo '<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviewshow.php?rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td align="center" class="even">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replies'].'/'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['views'].'</td>
    <td class="odd">';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid'] > 0){
echo '<a href="'.jieqi_geturl('system','user',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posterid']).'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['poster'].'</a>';
}else{
echo '游客';
}
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replyflag'] > 0){
echo '/';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replierid'] > 0){
echo '<a href="'.jieqi_geturl('system','user',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replierid']).'" target="_blank">'.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['replier'].'</a>';
}else{
echo '游客';
}
}
echo '</td>
    <td align="center" class="even">'.date('Y-m-d H:i:s',$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['posttime']);
if($this->_tpl_vars['ismaster'] == 1){
echo '<br />';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['istop'] == 0){
echo '[<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviews.php?action=top&aid='.$this->_tpl_vars['articleid'].'&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'">置顶</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviews.php?action=untop&aid='.$this->_tpl_vars['articleid'].'&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'">置后</a>]';
}
echo ' ';
if($this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['isgood'] == 0){
echo '[<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviews.php?action=good&aid='.$this->_tpl_vars['articleid'].'&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'">加精</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviews.php?action=normal&aid='.$this->_tpl_vars['articleid'].'&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'">去精</a>]';
}
echo ' [<a href="javascript:if(confirm(\'确实要删除该书评么？\')) document.location=\''.$this->_tpl_vars['jieqi_modules']['article']['url'].'/reviews.php?action=del&aid='.$this->_tpl_vars['articleid'].'&rid='.$this->_tpl_vars['reviewrows'][$this->_tpl_vars['i']['key']]['topicid'].'\';">删除</a>]';
}
echo '</td>
  </tr>
  ';
}
echo '
</table>
<table width="100%"  border="0" cellspacing="2" cellpadding="3">
    <tr>
        <td align="right">'.$this->_tpl_vars['url_jumppage'].'</td>
    </tr>
</table>
';
if($this->_tpl_vars['enablepost'] == 1){
echo '
<form name="frmreview" method="post" action="'.$this->_tpl_vars['article_dynamic_url'].'/reviews.php?aid='.$this->_tpl_vars['articleid'].'">
<table class="grid" width="100%" align="center">
  <caption>发表书评：</caption>
  <tr>
    <td class="odd" width="25%">标题</td>
    <td class="even"><input type=\'text\' class=\'text\' name=\'ptitle\' id=\'ptitle\' size=\'60\' maxlength=\'60\' value=\'\' /></td>
  </tr>
  <tr>
    <td class="odd" width="25%">内容</td>
    <td class="even"><textarea class="textarea" name="pcontent" id="pcontent" cols="60" rows="12"></textarea>
    <script language="javascript">loadJs("'.$this->_tpl_vars['jieqi_url'].'/scripts/ubbeditor_'.$this->_tpl_vars['jieqi_charset'].'.js", function(){UBBEditor.Create("pcontent");});</script>
    </td>
  </tr>
';
if($this->_tpl_vars['postcheckcode'] > 0){
echo '
<tr>
  <td class="odd">验证码</td>
  <td class="even"><input type="text" class="text" name="checkcode" id="checkcode" size="8" maxlength="8" value="" /> <span class="hottext">&nbsp;<img src="'.$this->_tpl_vars['jieqi_url'].'/checkcode.php" style="cursor:pointer;" onclick="this.src=\''.$this->_tpl_vars['jieqi_url'].'/checkcode.php?rand=\'+Math.random();"></span></td>
</tr>
';
}
echo '
  <tr>
    <td class="odd" width="25%">&nbsp;<input type="hidden" name="action" id="action" value="newpost" /></td>
    <td class="even"><input type="submit" name="Submit" class="button" value=" 发表书评 "></td>
  </tr>
</table>
</form>
';
}

?>