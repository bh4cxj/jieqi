<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于数据库的更新，适合从JIEQI CMS 1.5x 升级到 1.60 版。数据库升级之前请做好备份，以免意外情况无法恢复！<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始更新数据库</a><br><br>';
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

//检查系统有哪些模块
$modary=array();
$dirname='../../modules';
if(is_dir($dirname)){
	$handle = @opendir($dirname);
	while ($file = @readdir($handle)) {
		if($file[0] != '.'){
			$modary[]=$file;
		}
	}
	@closedir($handle);
}

//默认模块名字
$modnames=array('system'=>'系统功能', 'article'=>'小说连载', 'obook'=>'在线电子书', 'forum'=>'论坛', 'cartoon'=>'在线漫画', 'info'=>'分类信息', 'news'=>'新闻发布', 'vote'=>'投票调查', 'product'=>'产品发布', 'pay'=>'在线支付', 'wapbook'=>'WAP小说', 'quiz'=>'问答', 'group'=>'圈子交友', 'blog'=>'博客', 'space'=>'个人空间');

//检查模块配置
$jieqiModules=array();
if(is_file('../../configs/modules.php')) include_once('../../configs/modules.php');
if(!isset($jieqiModules['system'])){
		$jieqiModules['system'] = array('caption'=>'系统功能', 'dir'=>'', 'path'=>'', 'url'=>'', 'theme'=>'', 'publish'=>'1');
}
foreach($modary as $v){
	if(!isset($jieqiModules[$v])){
		$mname = isset($modnames[$v]) ? $modnames[$v] : $v;
		$jieqiModules[$v] = array('caption'=>$mname, 'dir'=>'', 'path'=>'', 'url'=>'', 'theme'=>'', 'publish'=>'1');
	}
}

//升级每个模块
$modconfig='';
foreach($jieqiModules as $k => $c){
	$modconfig .= '$jieqiModules[\''.jieqi_setslashes($k,'"').'\'] = array(\'caption\'=>\''.jieqi_setslashes($jieqiModules[$k]['caption'],'"').'\', \'dir\'=>\''.jieqi_setslashes($jieqiModules[$k]['dir'],'"').'\', \'path\'=>\''.jieqi_setslashes($jieqiModules[$k]['path'],'"').'\', \'url\'=>\''.jieqi_setslashes($jieqiModules[$k]['url'],'"').'\', \'theme\'=>\''.jieqi_setslashes($jieqiModules[$k]['theme'],'"').'\', \'publish\'=>\''.jieqi_setslashes($jieqiModules[$k]['publish'],'"').'\');'."\r\n\r\n";

	$sqlfile='mod_'.$k.'.sql';
	if(is_file($sqlfile)){
		echo '正在更新“'.$jieqiModules[$k]['caption'].'”数据库...';
		ob_flush();
		flush();
		$sql=file_get_contents($sqlfile);
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
	}
}

//写模块配置文件
$modconfig='<?php'."\r\n".$modconfig.'?>';
jieqi_writefile('../../configs/modules.php', $modconfig);


//正在更新小说模块数据库
echo '正在其他信息...';
ob_flush();
flush();
$sql="
UPDATE `jieqi_system_modules` SET version=160 WHERE name='article';
UPDATE `jieqi_system_modules` SET version=160 WHERE name='forum';
UPDATE `jieqi_system_modules` SET version=140 WHERE name='obook';
UPDATE `jieqi_system_modules` SET version=130 WHERE name='cartoon';
UPDATE `jieqi_system_modules` SET version=140 WHERE name='pay';
UPDATE `jieqi_system_modules` SET version=110 WHERE name='badge';
";
$sqlary=array();
jieqi_splitsqlfile($sqlary, $sql);
foreach($sqlary as $v){
	$v=trim($v);
	if(!empty($v) and strlen($v)>5){
		$retflag=mysql_query($v);
	}
}
//更新小说伪静态配置
include_once '../../configs/article/configs.php';
if(is_numeric($jieqiConfigs['article']['fakeinfo'])){
	if(!empty($jieqiConfigs['article']['fakeinfo'])){
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakeinfo']='/'.$jieqiConfigs['article']['fakeprefix'].'info<{$id|subdirectory}>/<{$id}>'.$jieqiConfigs['article']['fakefile'];
		else $jieqiConfigs['article']['fakeinfo']='/files/article/info<{$id|subdirectory}>/<{$id}>'.$jieqiConfigs['article']['fakefile'];
	}else{
		$jieqiConfigs['article']['fakeinfo']='';
	}
	$sql='UPDATE `jieqi_system_configs` SET ctype = 1, options=\'\', cvalue=\''.addslashes($jieqiConfigs['article']['fakeinfo']).'\', ctitle=\'文章信息页面伪静态规则\', `cdescription`=\'\r\n伪静态规则是带有替换标记的路径，留空表示不使用伪静态。\r\n允许使用的替换标记有 <{$id}> 文章ID ,<{$id|subdirectory}> 根据文章ID生成的子目录。\r\n如：/files/article/info<{$id|subdirectory}>/<{$id}>.htm\' WHERE modname=\'article\' AND cname=\'fakeinfo\';';
	mysql_query($sql);
}

if(is_numeric($jieqiConfigs['article']['fakesort'])){
	if(!empty($jieqiConfigs['article']['fakesort'])){
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakesort']='/'.$jieqiConfigs['article']['fakeprefix'].'sort<{$class}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		else $jieqiConfigs['article']['fakesort']='/files/article/sort<{$class}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
	}else{
		$jieqiConfigs['article']['fakesort']='';
	}
	$sql='UPDATE `jieqi_system_configs` SET ctype = 1, options=\'\', cvalue=\''.addslashes($jieqiConfigs['article']['fakesort']).'\', ctitle=\'文章分类页面伪静态规则\', `cdescription`=\'\r\n伪静态规则是带有替换标记的路径，留空表示不使用伪静态。\r\n允许使用的替换标记有 <{$class}> 分类ID ,<{$page}> 页码，<{$page|subdirectory}> 根据页码生成的子目录。\r\n如：/files/article/sort<{$class}><{$page|subdirectory}>/<{$page}>.htm\' WHERE modname=\'article\' AND cname=\'fakesort\';';
	mysql_query($sql);
}

if(is_numeric($jieqiConfigs['article']['fakeinitial'])){
	if(!empty($jieqiConfigs['article']['fakeinitial'])){
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['fakeinitial']='/'.$jieqiConfigs['article']['fakeprefix'].'initial<{$initial}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		else $jieqiConfigs['article']['fakeinitial']='/files/article/initial<{$initial}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
	}else{
		$jieqiConfigs['article']['fakeinitial']='';
	}
	$sql='UPDATE `jieqi_system_configs` SET ctype = 1, options=\'\', cvalue=\''.addslashes($jieqiConfigs['article']['fakeinitial']).'\', ctitle=\'首字母分类页面伪静态规则\', `cdescription`=\'\r\n伪静态规则是带有替换标记的路径，留空表示不使用伪静态。\r\n允许使用的替换标记有 <{$initial}> 首字母 ,<{$page}> 页码，<{$page|subdirectory}> 根据页码生成的子目录。\r\n如：/files/article/initial<{$initial}><{$page|subdirectory}>/<{$page}>.htm\' WHERE modname=\'article\' AND cname=\'fakeinitial\';';
	mysql_query($sql);
}

if(is_numeric($jieqiConfigs['article']['faketoplist'])){
	if(!empty($jieqiConfigs['article']['faketoplist'])){
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $jieqiConfigs['article']['faketoplist']='/'.$jieqiConfigs['article']['fakeprefix'].'top<{$sort}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
		else $jieqiConfigs['article']['faketoplist']='/files/article/top<{$sort}><{$page|subdirectory}>/<{$page}>'.$jieqiConfigs['article']['fakefile'];
	}else{
		$jieqiConfigs['article']['faketoplist']='';
	}
	$sql='UPDATE `jieqi_system_configs` SET ctype = 1, options=\'\', cvalue=\''.addslashes($jieqiConfigs['article']['faketoplist']).'\', ctitle=\'排行榜页面伪静态规则\', `cdescription`=\'\r\n伪静态规则是带有替换标记的路径，留空表示不使用伪静态。\r\n允许使用的替换标记有 <{$sort}> 排行列表 ,<{$page}> 页码，<{$page|subdirectory}> 根据页码生成的子目录。\r\n如：/files/article/top<{$sort}><{$page|subdirectory}>/<{$page}>.htm\' WHERE modname=\'article\' AND cname=\'faketoplist\';';
	mysql_query($sql);
}

mysql_query("DELETE FROM `jieqi_system_configs`  WHERE modname='article' AND cname='fakefile';");
mysql_query("DELETE FROM `jieqi_system_configs`  WHERE modname='article' AND cname='fakeprefix';");

echo '<font color="blue">更新完成</font><br>';
ob_flush();
flush();

echo '<br><font color="blue">恭喜您，数据库升级完成！</font><br>';
ob_flush();
flush();

//把字符串变成有反斜线的 $str=字符串 $submit=是否用户提交的数据 $filter=不加反斜线的字符
function jieqi_setslashes($str, $filter=''){
	if($filter == '"') return str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
	elseif($filter == '\'') return str_replace(array('\\', '"'), array('\\\\', '\\"'), $str);
	else return addslashes($str);
}


//写文件
function jieqi_writefile($file_name, &$data, $method = "wb"){
	$filenum = @fopen($file_name, $method);
	if(!$filenum) return false;
	@flock($filenum, LOCK_EX);
	$ret = @fwrite($filenum, $data);
	@flock($filenum, LOCK_UN);
	@fclose($filenum);
	@chmod($file_name, 0777);
	return $ret;
}

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