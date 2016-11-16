<?php
@set_time_limit(0);
header('Content-type:text/html;charset=gb2312');

if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>1.6版本里面取消了之前的作家会客室模式，改成每个用户都可以拥有会客室。<br>本程序用于转换作家会客室留言数据，转换后若没有问题，请手工删除 jieqi_article_notebook 和 jieqi_article_parlor 数据表。<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始转换作家会客室数据</a><br><br>';
	exit;
}

include_once '../../configs/define.php';
echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
$conn=mysql_connect(JIEQI_DB_HOST, JIEQI_DB_USER, JIEQI_DB_PASS);
$conn=mysql_connect(JIEQI_DB_HOST, JIEQI_DB_USER, JIEQI_DB_PASS);
if(!$conn){
	echo '<font color="red">数据库连接失败！<br>'.mysql_error().'</font><br>';
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

$sql="SHOW TABLE STATUS like 'jieqi_article_notebook'";
$res=mysql_query($sql);
$row=mysql_fetch_array($res);
$increment=intval($row['Auto_increment'])-1;

echo '开始转换数据，最大记录ID：'.$increment.'<br>';
ob_flush();
flush();

$querynum=1000;
$maxnum=0;
while($maxnum<=$increment){
	echo ($maxnum+1).'.. ';
	ob_flush();
	flush();
	$sql="SELECT * FROM jieqi_article_notebook WHERE noteid > ".$maxnum." AND noteid <= ".($maxnum+$querynum);
	$res=mysql_query($sql);
	while($row=mysql_fetch_array($res)){
		$title=str_replace(array("\r","\n"),'',jieqi_substr($row['notetitle'],0,60));
		if(empty($title)) $title=str_replace(array("\r","\n"),'',jieqi_substr($row['notetext'],0,60));
		if(empty($title)) $title = '----';
		$size=strlen($row['notetext']);
		if($row['replyid'] > 0){
			$views=1;
			$replies=1;
			$lastinfo=serialize(array('time'=>$row['replydate'], 'uid'=>$row['replyid'], 'uname'=>$row['replyname']));
		}else{
			$views=0;
			$replies=0;
			$lastinfo='';
		}
		$sql="INSERT INTO jieqi_system_ptopics (topicid, siteid, ownerid, title, posterid, poster, posttime, replierid, replier, replytime, views, replies, islock, istop, isgood, rate, attachment, needperm, needscore, needexp, needprice, sortid, iconid, typeid, lastinfo, linkurl, size) VALUES (".$row['noteid'].", '0', '".$row['userid']."', '".addslashes($title)."', '".$row['posterid']."', '".addslashes($row['postername'])."', '".$row['postdate']."', '".$row['replyid']."', '".addslashes($row['replyname'])."', '".$row['replydate']."', '".$views."', '".$replies."', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', 'a:3:{s:4:\"time\";i:1236337918;s:3:\"uid\";s:1:\"2\";s:5:\"uname\";s:5:\"admin\";}', '".addslashes($lastinfo)."', '".$size."')";
		
		$ret=mysql_query($sql);
		if(!$ret){
			if(mysql_errno() == 1062) echo '<br>当前转换的数据已经存在，如果你之前已经执行转换程序，请不要重复执行！<br><br>';
			else echo '<br>数据转换出错，如果你之前已经执行转换程序，请不要重复执行！<br><br><font color="red">'.mysql_error().'</font><font color="blue">'.$sql.'</font><br>';
			exit;
		}
		
		$sql="INSERT INTO jieqi_system_pposts (postid, siteid, topicid, istopic, replypid, ownerid, posterid, poster, posttime, posterip, editorid, editor, edittime, editorip, editnote, iconid, attachment, subject, posttext, size) VALUES (0, '0', '".$row['noteid']."', '1', '0', '".$row['userid']."', '".$row['posterid']."', '".addslashes($row['postername'])."', '".$row['postdate']."', '', '0', '', '".$row['postdate']."', '', '', '0', '', '".addslashes($title)."', '".addslashes($row['notetext'])."', '".$size."')";
		$ret=mysql_query($sql);
		if(!$ret) echo '<br><font color="red">'.mysql_error().'</font><font color="blue">'.$sql.'</font><br>';
		if($row['replyid'] > 0){
			$sql="INSERT INTO jieqi_system_pposts (postid, siteid, topicid, istopic, replypid, ownerid, posterid, poster, posttime, posterip, editorid, editor, edittime, editorip, editnote, iconid, attachment, subject, posttext, size) VALUES (0, '0', '".$row['noteid']."', '0', '0', '".$row['userid']."', '".$row['replyid']."', '".addslashes($row['replyname'])."', '".$row['replydate']."', '', '0', '', '".$row['replydate']."', '', '', '0', '', '', '".addslashes($row['replytext'])."', '".strlen($row['replytext'])."')";
			$ret=mysql_query($sql);
			if(!$ret) echo '<br><font color="red">'.mysql_error().'</font><font color="blue">'.$sql.'</font><br>';
		}
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