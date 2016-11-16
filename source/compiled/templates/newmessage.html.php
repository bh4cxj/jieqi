<?php
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'system';
echo '
';
$GLOBALS['jieqiTset']['jieqi_blocks_config'] = 'userblocks';
echo '
<script type="text/javascript">
<!--
function frmnewmessage_validate(){
  if(typeof(document.frmnewmessage.receiver) != "undefined"){
    if(document.frmnewmessage.receiver.value == "" ){
      alert("请输入收件人");
	  document.frmnewmessage.receiver.focus();
	  return false;
    }
  }
  if(document.frmnewmessage.title.value == ""){
    alert("请输入标题");
	window.document.frmnewmessage.title.focus();
	return false;
  }
}
//-->
</script>
<form name="frmnewmessage" id="frmnewmessage" action="newmessage.php?do=submit" method="post" onsubmit="return frmnewmessage_validate();">
<table width="580" class="grid" cellspacing="1" align="center">
<caption>写新消息</caption>
<tr valign="middle" align="left">
  <td class="odd" width="25%">收件人</td>
  <td class="even">
  ';
if($this->_tpl_vars['tosys'] > 0){
echo '
  网站管理员<input type="hidden" name="tosys" id="tosys" value="'.$this->_tpl_vars['tosys'].'" />
  ';
}else{
echo '
  <input type="text" class="text" name="receiver" id="receiver" size="30" maxlength="30" value="'.$this->_tpl_vars['receiver'].'" />
  ';
}
echo '
  </td>
</tr>
  <tr valign="middle" align="left"><td class="odd" width="25%">标题</td>
  <td class="even"><input type="text" class="text" name="title" id="title" size="30" maxlength="100" value="'.$this->_tpl_vars['title'].'" /></td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="25%">内容</td>
  <td class="even"><textarea class="textarea" name="content" id="content" rows="12" cols="60">'.$this->_tpl_vars['content'].'</textarea></td>
</tr>
';
if($this->_tpl_vars['needscore'] == 1){
echo '
<tr valign="middle" align="left">
  <td class="odd" width="25%">提示</td>
  <td class="even"><span class="hottext">您已经超出每天发送 '.$this->_tpl_vars['maxdaymsg'].' 条消息的限制，如要继续发送，将消耗您的积分 '.$this->_tpl_vars['sendmsgscore'].' 点</span></td>
</tr>
';
}
echo '
<tr valign="middle" align="left">
  <td class="odd" width="25%">&nbsp;</td>
  <td class="even"><input type="submit" class="button" name="submit"  id="submit" value="发 送" /><input type="hidden" name="action" id="action" value="newmessage" /></td>
</tr>
</table>
</form>';
?>