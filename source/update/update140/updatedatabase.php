<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于数据库的更新，适合从JIEQI CMS 1.3X 升级到 1.40 版。数据库升级之前请做好备份，以免意外情况无法恢复！<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始更新数据库</a><br><br>';
	exit;
}
include_once '../../configs/define.php';
echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ';
echo '<font color="blue">数据库更新时间和数据大小有关，更新前请做好备份，更新时候请耐心等待，不要关闭浏览器！</font><br><br>';
echo '正在连接数据库...';
ob_flush();
flush();
$conn=mysql_connect(JIEQI_DB_HOST, JIEQI_DB_USER, JIEQI_DB_PASS);
if(!$conn){
	echo '<font color="red">连接失败！<br>'.mysql_error().'</font><br>';
	exit;
}
//这个最好是原来是什么就用什么
$mysql_charset='';
if(defined('JIEQI_DB_CHARSET') && JIEQI_DB_CHARSET != 'default') $mysql_charset=JIEQI_DB_CHARSET;
if(empty($mysql_charset)){
	$result = mysql_query("SHOW TABLE STATUS FROM ".JIEQI_DB_NAME." LIKE 'jieqi_system_users'");
	if($result){
		$myrow = mysql_fetch_array($result);
		if(isset($myrow['Collation'])){
			$tmpary = explode('_', $myrow['Collation']);
			$tmpcharset=strtolower($tmpary[0]);
			if(in_array($tmpcharset, array('gbk', 'gb2312', 'big5', 'utf8', 'latin1'))) $mysql_charset = $tmpcharset;
		}
	}
}
if(empty($mysql_charset)) $mysql_charset='gbk';

$mysql_version = mysql_get_server_info();
if($mysql_version > '4.1' && !empty($mysql_charset)){
	@mysql_query("SET character_set_connection=".$mysql_charset.", character_set_results=".$mysql_charset.", character_set_client=binary");
}
if($mysql_version > '5.0') @mysql_query("SET sql_mode=''");

if(!mysql_select_db(JIEQI_DB_NAME)) {
	echo '<font color="red">数据库 '.JIEQI_DB_NAME.' 不存在或无访问权限，请检查数据库帐号！<br>'.mysql_error().'</font><br><br>';
	exit;
}
echo '<font color="blue">连接成功！</font><br>';
ob_flush();
flush();

@ignore_user_abort(true); //忽略用户取消


//正在更新系统数据库
echo '正在更新系统数据库...';
ob_flush();
flush();
$sql=file_get_contents('mod_system.sql');
if(!empty($mysql_charset)){
	if($mysql_version > '5.0'){
		$sql=str_replace(array('TYPE=MyISAM', 'TYPE=HEAP'), array('ENGINE=MyISAM DEFAULT CHARSET='.$mysql_charset, 'ENGINE=MEMORY DEFAULT CHARSET='.$mysql_charset), $sql);
	}elseif($mysql_version > '4.1'){
		$sql=str_replace(array('TYPE=MyISAM', 'TYPE=HEAP'), array('ENGINE=MyISAM DEFAULT CHARSET='.$mysql_charset, 'ENGINE=HEAP DEFAULT CHARSET='.$mysql_charset), $sql);
	}
}
$sqlary=array();
jieqi_splitsqlfile($sqlary, $sql);
foreach($sqlary as $v){
	$v=trim($v);
	if(!empty($v) and strlen($v)>5){
		$retflag=mysql_query($v);
	}
}
echo '<font color="blue">更新完成</font><br>';
ob_flush();
flush();

//正在更新小说模块数据库
echo '正在更新小说模块数据库...';
ob_flush();
flush();
$sql=file_get_contents('mod_article.sql');
if(!empty($mysql_charset)){
	if($mysql_version > '5.0'){
		$sql=str_replace(array('TYPE=MyISAM', 'TYPE=HEAP'), array('ENGINE=MyISAM DEFAULT CHARSET='.$mysql_charset, 'ENGINE=MEMORY DEFAULT CHARSET='.$mysql_charset), $sql);
	}elseif($mysql_version > '4.1'){
		$sql=str_replace(array('TYPE=MyISAM', 'TYPE=HEAP'), array('ENGINE=MyISAM DEFAULT CHARSET='.$mysql_charset, 'ENGINE=HEAP DEFAULT CHARSET='.$mysql_charset), $sql);
	}
}
$sqlary=array();
jieqi_splitsqlfile($sqlary, $sql);
foreach($sqlary as $v){
	$v=trim($v);
	if(!empty($v) and strlen($v)>5){
		$retflag=mysql_query($v);
	}
}
echo '<font color="blue">更新完成</font><br>';
ob_flush();
flush();

//正在更新小说模块数据库
echo '正在其他信息...';
ob_flush();
flush();
$sql="UPDATE `jieqi_system_modules` SET version=140 WHERE name='article';
UPDATE `jieqi_system_modules` SET version=140 WHERE name='forum';
UPDATE `jieqi_system_modules` SET version=120 WHERE name='obook';
UPDATE `jieqi_system_modules` SET version=110 WHERE name='cartoon';";
$sqlary=array();
jieqi_splitsqlfile($sqlary, $sql);
foreach($sqlary as $v){
	$v=trim($v);
	if(!empty($v) and strlen($v)>5){
		$retflag=mysql_query($v);
	}
}
echo '<font color="blue">更新完成</font><br>';
ob_flush();
flush();

echo '<br><font color="blue">恭喜您，数据库升级完成！</font><br>';
ob_flush();
flush();

//数据表加前缀
function jieqi_dbprefix($tbname){
	return 'jieqi_'.$tbname;
}

//分解sql语句
function jieqi_splitsqlfile(&$ret, $sql, $release=32270){
	$sql          = trim($sql);
	$sql_len      = strlen($sql);
	$char         = '';
	$string_start = '';
	$in_string    = FALSE;
	$time0        = time();

	for ($i = 0; $i < $sql_len; ++$i) {
		$char = $sql[$i];

		// We are in a string, check for not escaped end of strings except for
		// backquotes that can't be escaped
		if ($in_string) {
			for (;;) {
				$i         = strpos($sql, $string_start, $i);
				// No end of string found -> add the current substring to the
				// returned array
				if (!$i) {
					$ret[] = $sql;
					return TRUE;
				}
				// Backquotes or no backslashes before quotes: it's indeed the
				// end of the string -> exit the loop
				else if ($string_start == '`' || $sql[$i-1] != '\\') {
					$string_start      = '';
					$in_string         = FALSE;
					break;
				}
				// one or more Backslashes before the presumed end of string...
				else {
					// ... first checks for escaped backslashes
					$j                     = 2;
					$escaped_backslash     = FALSE;
					while ($i-$j > 0 && $sql[$i-$j] == '\\') {
						$escaped_backslash = !$escaped_backslash;
						$j++;
					}
					// ... if escaped backslashes: it's really the end of the
					// string -> exit the loop
					if ($escaped_backslash) {
						$string_start  = '';
						$in_string     = FALSE;
						break;
					}
					// ... else loop
					else {
						$i++;
					}
				} // end if...elseif...else
			} // end for
		} // end if (in string)

		// We are not in a string, first check for delimiter...
		else if ($char == ';') {
			// if delimiter found, add the parsed part to the returned array
			$ret[]      = substr($sql, 0, $i);
			$sql        = ltrim(substr($sql, min($i + 1, $sql_len)));
			$sql_len    = strlen($sql);
			if ($sql_len) {
				$i      = -1;
			} else {
				// The submited statement(s) end(s) here
				return TRUE;
			}
		} // end else if (is delimiter)

		// ... then check for start of a string,...
		else if (($char == '"') || ($char == '\'') || ($char == '`')) {
			$in_string    = TRUE;
			$string_start = $char;
		} // end else if (is start of string)

		// ... for start of a comment (and remove this comment if found)...
		else if ($char == '#'
		|| ($char == ' ' && $i > 1 && $sql[$i-2] . $sql[$i-1] == '--')) {
			// starting position of the comment depends on the comment type
			$start_of_comment = (($sql[$i] == '#') ? $i : $i-2);
			// if no "\n" exits in the remaining string, checks for "\r"
			// (Mac eol style)
			$end_of_comment   = (strpos(' ' . $sql, "\012", $i+2))
			? strpos(' ' . $sql, "\012", $i+2)
			: strpos(' ' . $sql, "\015", $i+2);
			if (!$end_of_comment) {
				// no eol found after '#', add the parsed part to the returned
				// array if required and exit
				if ($start_of_comment > 0) {
					$ret[]    = trim(substr($sql, 0, $start_of_comment));
				}
				return TRUE;
			} else {
				$sql          = substr($sql, 0, $start_of_comment)
				. ltrim(substr($sql, $end_of_comment));
				$sql_len      = strlen($sql);
				$i--;
			} // end if...else
		} // end else if (is comment)

		// ... and finally disactivate the "/*!...*/" syntax if MySQL < 3.22.07
		else if ($release < 32270
		&& ($char == '!' && $i > 1  && $sql[$i-2] . $sql[$i-1] == '/*')) {
			$sql[$i] = ' ';
		} // end else if

		// loic1: send a fake header each 30 sec. to bypass browser timeout
		$time1     = time();
		if ($time1 >= $time0 + 30) {
			$time0 = $time1;
			header('X-pmaPing: Pong');
		} // end if
	} // end for

	// add any rest to the returned array
	if (!empty($sql) && ereg('[^[:space:]]+', $sql)) {
		$ret[] = $sql;
	}

	return TRUE;
}


?>