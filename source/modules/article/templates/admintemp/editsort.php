<link rel="stylesheet" type="text/css" media="all" href="/templates/admin/style.css" />
<style>
	.even textarea{font-size:12px;}
</style>
<div id="content">

<table class="grid" width="100%" align="center">
  <tr align="center">
    <td width="98%" class="title">编辑二级分类</td>
  </tr>
</table>
<form name="frmcollect" id="frmcollect" action="/modules/article/admin/type.php?action=edit" method="post">
<table class="grid" width="100%" align="center">
  <tr align="center">
    <td width="98%" class="title">
        上级分类：<select name="newbig">
                    <?php foreach($jieqiSort['article'] as $key=>$value){?>
                    <option value="<?php echo $key;?>"><?php echo $value[caption]?></option>
                    <?php } ?>
                </select>
        分类名：<input name="newname" type="text" value="<?php echo $editsort['0']['caption'];?>" />
        序号：<input name="newxuhao" type="text" value="<?php echo $editsort['0']['weight'];?>" />
        <input type="hidden" name="sortid" value="<?php echo $editsort['0']['sortid'];?>" /><input name="submit" type="submit" value="确定" />
    </td>
  </tr>
</table>
</form>
</div>