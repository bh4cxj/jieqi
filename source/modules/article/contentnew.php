<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php
$aid=$_GET['articleid'];
if(!isset($aid)){
$aid=16;}
else{
$aid=intval($aid);
}
include "../../configs/define.php";
$link=mysql_connect(JIEQI_DB_HOST,JIEQI_DB_USER,JIEQI_DB_PASS)
or die("无法连接到mysql数据库".mysql_error());
mysql_select_db(JIEQI_DB_NAME,$link);
mysql_query("SET NAMES 'gb2312'",$link);
$sql="SELECT chapterid FROM jieqi_article_chapter where articleid  = '".$aid."' and size > 0 order by chapterid desc limit 0,1";
$result=mysql_query($sql);
$row=mysql_fetch_row($result);
//var_dump($row);
$cid=intval($row[0]);
mysql_free_result($result);
mysql_close();
$ml=floor($aid/1000);
//$rootpath=$_SERVER['DOCUMENT_ROOT'];
//echo $rootpath;
$path="../../files/article/txt/".$ml."/".$aid."/".$cid.".txt";
$url = "/modules/article/reader.php?aid=$aid&cid=$cid";
$handle = @fopen($path, "r");

?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<style type="text/css">
<!--
body,td,th {
	font-size: 13px;
	color: #2c4c78;
}
a {
	font-size: 12px;
	color: #2c4c78;
}
a:hover {
	color: #FF0000;
	text-decoration: underline;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
-->
</style></head>
<body style="margin:0 auto; line-height:20px;">
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><div style="height:164px; overflow:hidden;">&nbsp;&nbsp;&nbsp;&nbsp;
<a href="<?php echo $url?>" target="_blank"><?php

	if ($handle) {    
	$buffer = fread($handle, 280);
print_r(str_replace("<br />","",nl2br($buffer)));
fclose($handle);
}
?>...</a></div>
</td>
  </tr>
</table>
</body>
</html>
