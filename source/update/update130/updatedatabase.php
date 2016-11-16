<?php
@set_time_limit(3600);
header('Content-type:text/html;charset=gb2312');
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序用于数据库的更新，适合从1.10至1.22的任意版本数据库升级到1.30版。数据库升级之前请做好备份，以免意外情况无法恢复！<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始更新数据库</a><br><br>';
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

//清理参数表重复的记录
echo '正在检查参数表...';
ob_flush();
flush();
$sql='SELECT count(*) as cot, cid, modname, cname FROM `jieqi_system_configs` WHERE 1 group by modname, cname order by cot desc';
$res=mysql_query($sql);
$updateary=array();
while($row=mysql_fetch_array($res)){
	if($row['cot']>1){
		$updateary[]=$row;
	}else{
		break;
	}
}
foreach($updateary as $row){
	$sql="DELETE FROM `jieqi_system_configs` WHERE modname='".addslashes($row['modname'])."' AND cname='".addslashes($row['cname'])."' AND cid != '".addslashes($row['cid'])."'";
	$res=mysql_query($sql);
}
echo '<font color="blue">检查完成</font><br>';
ob_flush();
flush();

//清理权限表重复的记录
echo '正在检查权限表...';
ob_flush();
flush();
$sql='SELECT count(*) as cot, pid, modname, pname FROM `jieqi_system_power` WHERE 1 group by modname, pname order by cot desc';
$res=mysql_query($sql);
$updateary=array();
while($row=mysql_fetch_array($res)){
	if($row['cot']>1){
		$updateary[]=$row;
	}else{
		break;
	}
}
foreach($updateary as $row){
	$sql="DELETE FROM `jieqi_system_power` WHERE modname='".addslashes($row['modname'])."' AND pname='".addslashes($row['pname'])."' AND pid != '".addslashes($row['pid'])."'";
	$res=mysql_query($sql);
}
echo '<font color="blue">检查完成</font><br>';
ob_flush();
flush();

//创建新的数据表
echo '正在创建新的数据表...';
ob_flush();
flush();
$sql=file_get_contents('uptable.sql');
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
echo '<font color="blue">创建完成</font><br>';
ob_flush();
flush();

//更新数据结构
echo '正在更新数据结构...';
ob_flush();
flush();
$sql=file_get_contents('upstruct.sql');
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

//更新数据
echo '正在更新相关数据...';
ob_flush();
flush();
//更新几个文章的区块
include_once('../../configs/article/configs.php');

if(is_numeric($jieqiConfigs['article']['toptimenum'])) $blockrows=$jieqiConfigs['article']['toptimenum'];
else $blockrows=15;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_articlelist', classname='BlockArticleArticlelist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_articlelist.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置六个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排行方式（默认按总访问量），允许以下几种设置：1、“allvisit” - 按总访问量；2、“mouthvisit” - 按月访问量；3、“weekvisit” - 按周访问量；4、“dayvisit” - 按日访问量；5、“allvote” - 按总推荐次数；6、“mouthvote” - 按月推荐次数；7、“weekvote” - 按周推荐次数；8、“dayvote” - 按日推荐次数；9、“postdate” - 按最新入库；10、“toptime” - 按本站推荐；11、“goodnum” - 按收藏数量；12、“size” - 按文章字数；13、“lastupdate” - 按最近更新；<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示行数，使用整数（默认 15）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是文章类别（默认 0 表示所有类别），此处使用得是类别序号而不是名称，比如“玄幻小说”类别序号是 3 ，这里就设置成 3，如果要同时选择多个类别，可以用“|”分隔，比如 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是指是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “lastupdate,,0,1,0,0” 表示显示15条最近更新的原创作品，其中第二个参数留空，所以使用默认的15条。', vars='toptime,".$blockrows.",0,0,0,0', template='block_toptime.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticleToptime'";
mysql_query($sql);


if(is_numeric($jieqiConfigs['article']['lastupdatenum'])) $blockrows=$jieqiConfigs['article']['lastupdatenum'];
else $blockrows=15;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_articlelist', classname='BlockArticleArticlelist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_articlelist.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置六个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排行方式（默认按总访问量），允许以下几种设置：1、“allvisit” - 按总访问量；2、“mouthvisit” - 按月访问量；3、“weekvisit” - 按周访问量；4、“dayvisit” - 按日访问量；5、“allvote” - 按总推荐次数；6、“mouthvote” - 按月推荐次数；7、“weekvote” - 按周推荐次数；8、“dayvote” - 按日推荐次数；9、“postdate” - 按最新入库；10、“toptime” - 按本站推荐；11、“goodnum” - 按收藏数量；12、“size” - 按文章字数；13、“lastupdate” - 按最近更新；<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示行数，使用整数（默认 15）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是文章类别（默认 0 表示所有类别），此处使用得是类别序号而不是名称，比如“玄幻小说”类别序号是 3 ，这里就设置成 3，如果要同时选择多个类别，可以用“|”分隔，比如 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是指是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “lastupdate,,0,1,0,0” 表示显示15条最近更新的原创作品，其中第二个参数留空，所以使用默认的15条。', vars='lastupdate,".$blockrows.",0,0,0,0', template='block_lastupdate.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticleLastupdate'";
mysql_query($sql);

if(is_numeric($jieqiConfigs['article']['authorupdatenum'])) $blockrows=$jieqiConfigs['article']['authorupdatenum'];
else $blockrows=15;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_articlelist', classname='BlockArticleArticlelist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_articlelist.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置六个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排行方式（默认按总访问量），允许以下几种设置：1、“allvisit” - 按总访问量；2、“mouthvisit” - 按月访问量；3、“weekvisit” - 按周访问量；4、“dayvisit” - 按日访问量；5、“allvote” - 按总推荐次数；6、“mouthvote” - 按月推荐次数；7、“weekvote” - 按周推荐次数；8、“dayvote” - 按日推荐次数；9、“postdate” - 按最新入库；10、“toptime” - 按本站推荐；11、“goodnum” - 按收藏数量；12、“size” - 按文章字数；13、“lastupdate” - 按最近更新；<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示行数，使用整数（默认 15）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是文章类别（默认 0 表示所有类别），此处使用得是类别序号而不是名称，比如“玄幻小说”类别序号是 3 ，这里就设置成 3，如果要同时选择多个类别，可以用“|”分隔，比如 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是指是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “lastupdate,,0,1,0,0” 表示显示15条最近更新的原创作品，其中第二个参数留空，所以使用默认的15条。', vars='lastupdate,".$blockrows.",0,1,0,0', template='block_authorupdate.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticleAuthorupdate'";
mysql_query($sql);

if(is_numeric($jieqiConfigs['article']['masterupdatenum'])) $blockrows=$jieqiConfigs['article']['masterupdatenum'];
else $blockrows=15;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_articlelist', classname='BlockArticleArticlelist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_articlelist.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置六个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排行方式（默认按总访问量），允许以下几种设置：1、“allvisit” - 按总访问量；2、“mouthvisit” - 按月访问量；3、“weekvisit” - 按周访问量；4、“dayvisit” - 按日访问量；5、“allvote” - 按总推荐次数；6、“mouthvote” - 按月推荐次数；7、“weekvote” - 按周推荐次数；8、“dayvote” - 按日推荐次数；9、“postdate” - 按最新入库；10、“toptime” - 按本站推荐；11、“goodnum” - 按收藏数量；12、“size” - 按文章字数；13、“lastupdate” - 按最近更新；<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示行数，使用整数（默认 15）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是文章类别（默认 0 表示所有类别），此处使用得是类别序号而不是名称，比如“玄幻小说”类别序号是 3 ，这里就设置成 3，如果要同时选择多个类别，可以用“|”分隔，比如 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是指是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “lastupdate,,0,1,0,0” 表示显示15条最近更新的原创作品，其中第二个参数留空，所以使用默认的15条。', vars='lastupdate,".$blockrows.",0,2,0,0', template='block_masterupdate.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticleMasterupdate'";
mysql_query($sql);

if(is_numeric($jieqiConfigs['article']['postdatenum'])) $blockrows=$jieqiConfigs['article']['postdatenum'];
else $blockrows=15;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_articlelist', classname='BlockArticleArticlelist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_articlelist.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置六个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是排行方式（默认按总访问量），允许以下几种设置：1、“allvisit” - 按总访问量；2、“mouthvisit” - 按月访问量；3、“weekvisit” - 按周访问量；4、“dayvisit” - 按日访问量；5、“allvote” - 按总推荐次数；6、“mouthvote” - 按月推荐次数；7、“weekvote” - 按周推荐次数；8、“dayvote” - 按日推荐次数；9、“postdate” - 按最新入库；10、“toptime” - 按本站推荐；11、“goodnum” - 按收藏数量；12、“size” - 按文章字数；13、“lastupdate” - 按最近更新；<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是显示行数，使用整数（默认 15）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是文章类别（默认 0 表示所有类别），此处使用得是类别序号而不是名称，比如“玄幻小说”类别序号是 3 ，这里就设置成 3，如果要同时选择多个类别，可以用“|”分隔，比如 3|4|7<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指是否原创（默认 0 表示不判断），1 表示只显示原创作品，2 表示转载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数五是指是否全本（默认 0 表示不判断），1 表示只显示全本作品，2 表示连载作品<br>&nbsp;&nbsp;&nbsp;&nbsp;参数六是指显示顺序（默认 0 表示按从大到小排序），1 表示从小到大排序。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “lastupdate,,0,1,0,0” 表示显示15条最近更新的原创作品，其中第二个参数留空，所以使用默认的15条。', vars='postdate,".$blockrows.",0,0,0,0', template='block_postdate.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticlePostdate'";
mysql_query($sql);

if(is_numeric($jieqiConfigs['article']['newreviewnum'])) $blockrows=$jieqiConfigs['article']['newreviewnum'];
else $blockrows=10;
$sql="UPDATE `jieqi_system_blocks` SET filename='block_reviewlist', classname='BlockArticleReviewlist', description='&nbsp;&nbsp;&nbsp;&nbsp;本区块允许用户自定义模板和参数，并且不同的设置可以保存成不同的区块。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块默认模板文件为“block_newreview.html”，在/modules/article/templates/blocks目录下，如果您定义了另外模板文件，也必须在此目录。模板文件设置留空表示使用默认模板。<br>&nbsp;&nbsp;&nbsp;&nbsp;区块允许设置四个参数，不同参数之间用英文逗号分隔“,”。<br>&nbsp;&nbsp;&nbsp;&nbsp;参数一是显示行数，使用整数（默认 10）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数二是指是否置顶书评（默认 0 表示不判断），1 表示只显示置顶书评，2 表示非置顶书评<br>&nbsp;&nbsp;&nbsp;&nbsp;参数三是指是否精华书评（默认 0 表示不判断），1 表示只显示精华书评，2 表示非精华书评<br>&nbsp;&nbsp;&nbsp;&nbsp;参数四是指每条书评最大显示长度，必须是整数（默认 64 ，单位是字节，相当于 32 个汉字）<br>&nbsp;&nbsp;&nbsp;&nbsp;参数设置中一项或者多项留空均表示使用默认值。例子： “15,0,1,64” 表示显示15条最新精华书评。', vars='".$blockrows.",0,0,64', template='block_newreview.html', contenttype='1', hasvars='1' WHERE modname='article' AND classname='BlockArticleNewreview'";
mysql_query($sql);


$sql=file_get_contents('updata.sql');
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