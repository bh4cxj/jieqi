<?php
echo '<table class="grid" width="100%" align="center">
<form name="frmbatchclecn" method="post" action="'.$this->_tpl_vars['url_batchclean'].'" target="_blank">
<caption>文章批量清理</caption>
<tr>
  <td width="30%" align="right" class="odd">执行的操作：</td>
  <td width="70%" class="even">
  <input name="operate" type="radio" value="delarticle" checked> 删除文章，包括对应章节、书评及阅读文件<br />
  <input name="operate" type="radio" value="delchapter"> 删除符合条件文章的所有章节，包括阅读文件，保留文章信息和书评<br />
  <input name="operate" type="radio" value="delattach"> 删除符合条件文章中有附件的章节，通常是指图片附件<br />  </td>
</tr>
<tr>
  <td width="30%" align="right" class="odd">查询条件一：</td>
  <td width="70%" class="even">序号(ID)从 <input name="startid" type="text" id="startid" size="10" maxlength="11" class="text"> 到 <input name="stopid" type="text" id="stopid" size="10" maxlength="11" class="text"> 的文章</td>
</tr>
<tr>
  <td align="right" class="odd">查询条件二：</td>
  <td class="even">最近 <input name="upday" type="text" id="upday" size="10" maxlength="11" class="text"> 天内 <select name="upflag">
    <option value="0">未更新</option>
    <option value="1">更新过</option>
  </select> 
    的文章</td>
</tr>
<tr>
  <td align="right" class="odd">查询条件三：</td>
  <td class="even">
  <select name="visittype">
    <option value="allvisit">总点击</option>
    <option value="monthvisit">月点击</option>
	<option value="weekvisit">周点击</option>
	<option value="allvote">总推荐</option>
    <option value="monthvote">月推荐</option>
	<option value="weekvote">周推荐</option>
  </select>
  <select name="visitflag">
    <option value="0">小于</option>
    <option value="1">大于</option>
  </select>
  <input name="visitnum" type="text" id="visitnum" size="10" maxlength="11" class="text"> 的文章  </td>
</tr>
<tr>
  <td align="right" class="odd">查询条件四：</td>
  <td class="even">
  <input name="authorflag" type="radio" value="0" checked> 不限 
  <input name="authorflag" type="radio" value="1"> 原创文章
  <input name="authorflag" type="radio" value="2"> 转载文章  </td>
</tr>
<tr>
  <td align="right" class="odd">查询条件五：<br />查询文章序号(ID)或者文章名<br />多个ID用英文逗号分开，不要换行，如：<br />12,34,56,78<br /><br />多个文章名则是每个一行，如：<br />文章一<br />文章二<br />文章三</td>
  <td class="even"><input name="idname" type="radio" value="0" checked>按文章序号，逗号分隔 &nbsp;<input name="idname" type="radio" value="1">按文章名，每个一行 <br />
  <textarea class="textarea" name="articles" id="articles" rows="10" cols="70"></textarea></td>
</tr>
<tr>
  <td align="right" class="odd">特别说明：</td>
  <td class="odd"><span class="hottext">以上限制条件可选择多个，如果全部留空不填，则针对所有文章进行处理。<br />数据清理不可恢复，请谨慎使用！</span></td> </tr> <tr> <td align="right" class="odd">&nbsp;</td>
  <td class="even"><input type="submit" name="btnclecn" value="开始清理" class="button"><input type="hidden" name="action" value="clean"></td>
</tr>
<tr>
  <td colspan="2" align="right" class="foot">&nbsp;</td>
</tr> 
</form> 
</table>
';
?>