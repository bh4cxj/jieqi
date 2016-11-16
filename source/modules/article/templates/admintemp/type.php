<link rel="stylesheet" type="text/css" media="all" href="/templates/admin/style.css" />
<style>
	.even textarea{font-size:12px;}
</style>
<div id="content">


<form name="frmcollect" id="frmcollect" action="/modules/article/admin/type.php" method="post">
<table class="grid" width="100%" align="center">
  <tr align="center">
    <td width="5%" class="title">类别ID</td>
    <td width="20%" class="title">类别名称</td>
    <td width="10%" class="title">类别短名</td>
  </tr>
  <?php foreach($jieqiSort['article'] as $key=>$value){?>
  <tr>
    <td class="even" align="center"><?php echo $key;?></td>
    <td class="odd"><a href="/modules/article/articlelist.php?class=<?php echo $key?>" target="_blank"><?php echo $value[caption]?><br />
</a></td>
    <td align="center" class="odd"><?php echo $value[shortname];?></td>
  </tr>
  <?php $sortlist = getsamllsort($key);?>
  <?php if($sortlist){?>
  <tr>
    <td></td>
    <td><?php foreach($sortlist as $keys=>$values){?>
        <div style="float:left;">类别号：<?php echo $values[sortid];?></div><div style="float:left;margin-left:10px;"><a href="/modules/article/articlelist.php?class=<?php echo $key?>&type=<?php echo $values[sortid];?>"><?php echo $values[caption];?></a></div><div style="float:left;margin-left:10px;"><a href="/modules/article/admin/type.php?action=edit&typeid=<?php echo $values[sortid];?>">[编辑]</a><a href="/modules/article/admin/type.php?action=delete&typeid=<?php echo $values[sortid];?>">[删除]</a></div>
        <div style="clear:both"></div>
        <?php } ?>
    </td>
    <td></td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<div style="margin-top:10px;">
    <div class="title">增加二级类别</div>
    <div style="">
        <form action="" method="post">
            <div style="">选择上级目录：
                <select name="bigsort">
                    <?php foreach($jieqiSort['article'] as $key=>$value){?>
                    <option value="<?php echo $key;?>"><?php echo $value[caption]?></option>
                    <?php } ?>
                </select>
            </div>
            <div style="margin-top:10px;">新建二级类别：<input type="text" name="sortname" value="" />*小于等于4个汉字</div>
            <div style="margin-top:10px;">新建类别排序：<input type="text" name="weight" value="" />*数字越大越前,不填为0</div>
            <input name="submit" type="submit" value="确定" />
        </form>
    </div>
</div>
</div>
