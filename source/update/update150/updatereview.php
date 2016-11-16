<?php
@set_time_limit(0);
include_once '../../configs/define.php';
echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
$conn=mysql_connect(JIEQI_DB_HOST, JIEQI_DB_USER, JIEQI_DB_PASS);
$conn=mysql_connect(JIEQI_DB_HOST, JIEQI_DB_USER, JIEQI_DB_PASS);
if(!$conn){
	echo '<font color="red">连接失败！<br>'.mysql_error().'</font><br>';
	exit;
}
//这个最好是原来是什么就用什么
$mysql_charset='gbk';
if(defined('JIEQI_DB_CHARSET')){
	if(JIEQI_DB_CHARSET != 'default') $mysql_charset=JIEQI_DB_CHARSET;
	else $mysql_charset='';
}
$mysql_version = mysql_get_server_info();
if($mysql_version > '4.1' && !empty($mysql_charset)){
	@mysql_query("SET character_set_connection=".$mysql_charset.", character_set_results=".$mysql_charset.", character_set_client=binary");
}
if($mysql_version > '5.0') @mysql_query("SET sql_mode=''");

if(!mysql_select_db(JIEQI_DB_NAME)) {
	echo '<font color="red">数据库 '.JIEQI_DB_NAME.' 不存在或无访问权限，请检查数据库帐号！<br>'.mysql_error().'</font><br><br>';
	exit;
}
echo '<font color="blue">数据库连接成功！</font><br>';
ob_flush();
flush();

@ignore_user_abort(true); //忽略用户取消

$sql="SHOW TABLE STATUS like 'jieqi_article_review'";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
$increment=$row['Auto_increment'];

echo '开始转换数据，最大记录ID：'.$increment.'<br>';
ob_flush();
flush();

$querynum=1000;
$maxnum=0;
while($maxnum<=$increment){
	echo ($maxnum+1).'.. ';
	ob_flush();
	flush();
	$sql="SELECT * FROM jieqi_article_review WHERE reviewid > ".$maxnum." AND reviewid <= ".($maxnum+$querynum);
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res)){
		$title=str_replace(array("\r","\n"),'',jieqi_substr($row['reviewtext'],0,60));
		$size=strlen($row['reviewtext']);
		$sql="INSERT INTO `jieqi_article_reviews` ( `reviewid` , `siteid` , `articleid` , `title` , `posterid` , `poster` , `posttime` , `replytime` , `views` , `replies` , `islock` , `istop` , `isgood` , `topictype` , `lastinfo` , `size` ) VALUES (".$row['reviewid']." , '".$row['siteid']."', '".$row['articleid']."', '".addslashes($title)."', '".$row['userid']."', '".addslashes($row['username'])."', '".$row['postdate']."', '".$row['postdate']."', '0', '0', '0', '".$row['topflag']."', '".$row['goodflag']."', '0', '', '".$size."');";
		$ret=mysql_query($sql);
		if(!$ret){
			echo '<br>数据转换出错，如果你之前已经执行转换程序，请不要重复执行！<br><br><font color="red">'.mysql_error().'</font><font color="blue">'.$sql.'</font><br>';
			exit;
		}

		$sql="INSERT INTO `jieqi_article_replies` ( `replyid` , `siteid` , `reviewid` , `istopic` , `articleid` , `posterid` , `poster` , `posttime` , `posterip` , `subject` , `posttext` , `size` ) VALUES (NULL , '".$row['siteid']."', '".$row['reviewid']."', '1', '".$row['articleid']."', '".$row['userid']."', '".addslashes($row['username'])."', '".$row['postdate']."', '', '".addslashes($title)."', '".addslashes($row['reviewtext'])."', '".$size."');";
		$ret=mysql_query($sql);
		if(!$ret) echo '<br><font color="red">'.mysql_error().'</font><font color="blue">'.$sql.'</font><br>';
	}
	$maxnum+=$querynum;
}

echo '<br><font color="red">恭喜您，全部数据转换完成！</font>';

function jieqi_substr($str, $start, $length, $trimmarker = '...'){
	if (function_exists('mb_internal_encoding') && @mb_internal_encoding(JIEQI_CHAR_SET)) {
		return mb_strimwidth($str, $start, $length, $trimmarker, JIEQI_CHAR_SET);
	} else {
		$strlen = $start + $length - strlen($trimmarker);
		$len=strlen($str);
		if($strlen > $len) $strlen=$len;
		$tmpstr = "";
		for($i = 0;$i < $strlen;$i++) {
			if (ord($str[$i]) > 0x80) {
				if($i >= $start) $tmpstr .= $str[$i].$str[$i+1];
				$i++;
			} else if ($i >= $start) $tmpstr .= $str[$i];
		}
		if($strlen<$len) $tmpstr.= $trimmarker;
		return $tmpstr;
	}
}
?>