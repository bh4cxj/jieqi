<?php
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'article';
echo '
';
$GLOBALS['jieqiTset']['jieqi_blocks_config'] = 'guideblocks';
echo '
';
$this->_tpl_vars['jieqi_pagetitle'] = "积分-鲜花鸡蛋 - {$this->_tpl_vars['jieqi_sitename']}";
echo '
';
$this->_tpl_vars['meta_keywords'] = "{$this->_tpl_vars['articlename']} {$this->_tpl_vars['author']}";
echo '
<div id="content">

<form action="/modules/article/jifen.php" method="post">
<table width="580" class="grid" cellspacing="1" align="center">
<caption>积分兑换鲜花/鸡蛋</caption>

<tr valign="middle" align="left">
  <td class="odd" width="174">用户名</td>
  <td class="even" width="399">'.$this->_tpl_vars['username'].'</td>
</tr>
<tr valign="middle" align="left">

  <td class="odd" width="174">现有积分</td>
  <td class="even" width="399">'.$this->_tpl_vars['scorenums'].'</td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="174">现有鲜花数</td>
  <td class="even" width="399">'.$this->_tpl_vars['flowernums'].'</td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="174">现有鸡蛋</td>

  <td class="even" width="399">'.$this->_tpl_vars['eggnums'].'</td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="174">1点积分兑换1朵鲜花</td>
  <td class="even" width="399"><input type="text" class="text" name="dhflower" size="15" maxlength="11" value="" />*输入你需要兑换的鲜花数,不输为0</td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="174">1点积分兑换1个鸡蛋</td>
  <td class="even" width="399"><input type="text" class="text" name="dhegg" size="15" maxlength="11" value="" />*输入你需要兑换的鸡蛋数</td>
</tr>
<tr valign="middle" align="left">
  <td class="odd" width="174">&nbsp;<input type="hidden" name="action" id="action" value="update" /><a href="/userdetail.php">如何获得积分</a></td>
  <td class="even" width="399"><input type="submit" class="button" name="submit"  id="submit" value=" 开始兑换 " />    注：积分兑换后不可转回！ </td>
</tr>
</table>
</form>
</div>
';
?>