<?php
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'system';
echo '
<link href="/css/nt.css" rel="stylesheet" type="text/css" />
<!--正文部份-->
	<div class="homecon">
		<div class="homedh">
			<div class="hometit1">
				<ul>
					<li class="homesy"><a href="/userdetail.php" class="size14">用户中心</a></li>
					<li class="homesy2"><a target="_blank" href="/modules/article/applywriter.php"
						class="size14">申请作者</a></li>
				</ul>
			</div>
			<div class="cl">
			</div>
			<div>
			</div>
		</div>
		<div class="homedown">
			<!--会员左边-->
			<div class="homeDL">
	<div class="photo">
		<div class="photo_pic">
			<div>
				<a href="touxiang.aspx">
					<img style="width: 80px; height: 80px; border: 1px solid #ccc;" id="imagesrc" src="'.jieqi_geturl('system','avatar',$this->_tpl_vars['uid'],'l',$this->_tpl_vars['avatar']).'" /></a>
			</div>
			<div class="photo_name">'.$this->_tpl_vars['jieqi_username'].'
			</div>
		</div>
	</div>
	<div class="homeleft_dh">
		<ul>
			<li class="myaccount"><a href="/userdetail.php" title="账户">账户</a></li>
			<li class="myaccount" style="background-position: 0px -705px;"><a href="/setavatar.php"
				title="头像">头像</a></li>
			<li class="mybookcase"><a href="/modules/article/bookcase.php" title="书架">书架</a></li>
			<li class="mymsg"><a href="/message.php?box=inbox" title="消息">消息</a></li>
			<li class="myfootmark"><a href="/ptopics.php?uid=self" title="会客">会客</a></li>
			<li class="myhelp"><a target="_blank" href="/modules/forum/" title="交流">交流</a></li>
			<li class="zuxiao"><a href="/logout.php" title="注销">注销</a></li>
		</ul>
	</div>
</div>

			<!--会员左边结束-->
			<div class="homeDR">
                <div class="homezhdh">
					<ul>
						<li><a href="/ptopics.php?uid=self">会客室</a></li><li><a href="/myfriends.php">我的好友</a></li>
					</ul>
				</div>

				<div class="homeDRcon">
					<div class="myinformation">
						<table width="100%"  border="0" cellspacing="5" cellpadding="3">
  <tr>
    <td width="40%" align="left"><a href="'.jieqi_geturl('system','user',$this->_tpl_vars['ownerid']).'">'.$this->_tpl_vars['ownername'].'</a> 的会客室 </td>
    <td width="60%" align="right">';
if($this->_tpl_vars['type'] == "good"){
echo '[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?oid='.$this->_tpl_vars['ownerid'].'&type=all">全部主题</a>]&nbsp;&nbsp;[精华主题]';
}else{
echo '[全部主题]&nbsp;&nbsp;[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?oid='.$this->_tpl_vars['ownerid'].'&type=good">精华主题</a>]';
}
if($this->_tpl_vars['enablepost'] == 1){
echo '&nbsp;&nbsp;[<a href="#postnew">发表主题</a>]';
}
echo '</td>
  </tr>
</table>
<table class="grid" width="100%" align="center">
  <tr align="center">
    <th width="54%" class="head">主题</th>
    <th width="12%" class="head">回复/查看</th>
    <th width="17%" class="head">发表人/回复人</th>
    <th width="15%" class="head">发表时间</th>
  </tr>
  ';
if (empty($this->_tpl_vars['ptopicrows'])) $this->_tpl_vars['ptopicrows'] = array();
elseif (!is_array($this->_tpl_vars['ptopicrows'])) $this->_tpl_vars['ptopicrows'] = (array)$this->_tpl_vars['ptopicrows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['ptopicrows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['ptopicrows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['ptopicrows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['ptopicrows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['ptopicrows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <tr>
    <td class="odd">';
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['istop'] == 1){
echo '<span class="hottext">[顶]</span>';
}
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['isgood'] == 1){
echo '<span class="hottext">[精]</span>';
}
echo '<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopicshow.php?tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'">'.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['title'].'</a></td>
    <td align="center" class="even">'.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['replies'].'/'.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['views'].'</td>
    <td class="odd">';
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['posterid'] > 0){
echo '<a href="'.jieqi_geturl('system','user',$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['posterid'],'space').'" target="_blank">'.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['poster'].'</a>';
}else{
echo '游客';
}
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['replyflag'] > 0){
echo '/';
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['replierid'] > 0){
echo '<a href="'.jieqi_geturl('system','user',$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['replierid']).'" target="_blank">'.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['replier'].'</a>';
}else{
echo '游客';
}
}
echo '</td>
    <td align="center" class="even">'.date('Y-m-d H:i:s',$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['posttime']).'
	';
if($this->_tpl_vars['ismaster'] == 1){
echo '
	<br />';
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['istop'] == 0){
echo '[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?action=top&oid='.$this->_tpl_vars['ownerid'].'&tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'">置顶</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?action=untop&oid='.$this->_tpl_vars['ownerid'].'&tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'">置后</a>]';
}
echo ' 
	';
if($this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['isgood'] == 0){
echo '[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?action=good&oid='.$this->_tpl_vars['ownerid'].'&tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'">加精</a>]';
}else{
echo '[<a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?action=normal&oid='.$this->_tpl_vars['ownerid'].'&tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'">去精</a>]';
}
echo ' 
	[<a href="javascript:if(confirm(\'确实要删除该主题么？\')) document.location=\''.$this->_tpl_vars['jieqi_url'].'/ptopics.php?action=del&oid='.$this->_tpl_vars['ownerid'].'&tid='.$this->_tpl_vars['ptopicrows'][$this->_tpl_vars['i']['key']]['topicid'].'\';">删除</a>]
	';
}
echo '
	</td>
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
<a name="postnew"></a>
<script language="javascript" type="text/javascript">
<!--
function frmptopic_validate(){
  if(document.frmptopic.pcontent.value == ""){
    alert("请输入内容");
    document.frmptopic.pcontent.focus();
    return false;
  }
}
//-->
</script>
<form name="frmptopic" id="frmptopic" action="ptopics.php?oid='.$this->_tpl_vars['oid'].'&do=submit" method="post" onsubmit="return frmptopic_validate();" enctype="multipart/form-data">
<table width="100%" class="grid" cellspacing="1" align="center">
<caption>发表主题</caption>
<tr valign="middle" align="left">
  <td class="odd" width="25%">标题</td>
  <td class="even"><input type="text" class="text" name="ptitle" id="ptitle" size="60" maxlength="60" value="" /></td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="25%">内容</td>
  <td class="even"><textarea class="textarea" name="pcontent" id="pcontent" rows="12" cols="60"></textarea>
  <script language="javascript">loadJs("'.$this->_tpl_vars['jieqi_url'].'/scripts/ubbeditor_'.$this->_tpl_vars['jieqi_charset'].'.js", function(){UBBEditor.Create("pcontent");});</script>
  </td>
</tr>
';
if($this->_tpl_vars['postcheckcode'] > 0){
echo '
<tr valign="middle" align="left">
  <td class="odd">验证码</td>
  <td class="even"><input type="text" class="text" name="checkcode" id="checkcode" size="8" maxlength="8" value="" /> <span class="hottext">&nbsp;<img src="'.$this->_tpl_vars['jieqi_url'].'/checkcode.php" style="cursor:pointer;" onclick="this.src=\''.$this->_tpl_vars['jieqi_url'].'/checkcode.php?rand=\'+Math.random();"></span></td>
</tr>
';
}
echo '
<tr valign="middle" align="left">
  <td class="odd" width="25%">&nbsp;<input type="hidden" name="action" id="action" value="newpost" /></td>
  <td class="even"><input type="submit" class="button" name="btnpost"  id="btnpost" value="提 交" /></td>
</tr>
</table>
</form>
';
}
echo '
<br />
						<div class="cl">
						</div>
					</div>
				</div>
			</div>
			<div class="cl">
			</div>
		</div>
	</div>

';
?>