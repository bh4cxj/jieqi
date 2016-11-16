<?php
echo '<script language="javascript" type="text/javascript">
<!--
function frmtip_validate(){
  if(document.frmtip.payegold.value == ""){
    alert("请输入打赏'.$this->_tpl_vars['egoldname'].'");
    document.frmtip.payegold.focus();
    return false;
  }

  if(parseInt(document.frmtip.payegold.value) < 5){
    alert("至少打赏'.$this->_tpl_vars['egoldname'].'5以上");
    document.frmtip.payegold.focus();
    return false;
  }

  if(parseInt(document.frmtip.payegold.value) > '.$this->_tpl_vars['useremoney'].'){
    alert("您的'.$this->_tpl_vars['egoldname'].'不足");
    document.frmtip.payegold.focus();
    return false;
  }
}
//-->
</script>
<br />
<form name="frmtip" id="frmtip" action="'.$this->_tpl_vars['jieqi_modules']['article']['url'].'/tip.php?do=submit" method="post" onsubmit="return frmtip_validate();" enctype="multipart/form-data">
<table width="500" class="grid" cellspacing="1" align="center">
<caption>打赏作品</caption>
<tr valign="middle" align="left">
  <td width="20%">小说名</td>
  <td><a href="'.jieqi_geturl('article','article',$this->_tpl_vars['articleid'],'info').'">'.$this->_tpl_vars['articlename'].'</a></td>
</tr>
<tr valign="middle" align="left">
  <td width="20%">我的'.$this->_tpl_vars['egoldname'].'</td>
  <td>'.$this->_tpl_vars['useremoney'].' '.$this->_tpl_vars['egoldname'].'  </td>
</tr>
<tr valign="middle" align="left">
  <td width="20%">打赏'.$this->_tpl_vars['egoldname'].'</td>
  <td>
  <input type="text" class="text" name="payegold" id="payegold" size="10" maxlength="10" value="" /> <span class="hottext">至少 5 以上</span>  </td>
</tr>
<tr valign="middle" align="left">
  <td width="20%">说明</td>
  <td>本功能是指用'.$this->_tpl_vars['egoldname'].'打赏给您喜欢的作品的作者，感谢您做作者的鼓励和支持！</td>
</tr>
<tr valign="middle" align="left">
  <td width="20%">
  &nbsp;
  <input type="hidden" name="action" id="action" value="post" />
  <input type="hidden" name="id" id="id" value="'.$this->_tpl_vars['articleid'].'" />  </td>
  <td>
  ';
if($this->_tpl_vars['ajax_request'] > 0){
echo '
  <input type="button" name="Submit" class="button" value="提 交" style="cursor:pointer;" onclick="Ajax.Request(\'frmtip\',{onLoading:function(){Form.disable(\'frmtip\');},onComplete:function(){alert(this.response.replace(/<br[^<>]*>/g,\'\\n\'));Form.enable(\'frmtip\');closeDialog();
}});">
  ';
}else{
echo '
  <input type="submit" class="button" name="submit"  id="submit" value="提 交" />
  ';
}
echo '
  </td>
</tr>
</table>
</form>';
?>