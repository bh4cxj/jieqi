<?php
define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
jieqi_loadlang('article', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
$userset=unserialize($jieqiUsers->getVar('setting','n'));
$jieqiUsers->vars['egold']['value'];//用户虚拟币数量
$username = $jieqiUsers->vars['uname']['value'];//用户虚拟币数量
$articleid = abs((int)$_REQUEST['id']);//小说id
$authorid = $_REQUEST['authorid'];//作者id

include_once(JIEQI_ROOT_PATH.'/header.php');
$result = mysql_query("SELECT * FROM  `jieqi_article_article` WHERE  `articleid` = '$articleid' limit 1");
while($row = mysql_fetch_array($result,1)){
	$rows[] = $row;
}
$articlename = $rows[0][articlename];
if($_REQUEST['nums']){
	$nums = $_REQUEST['nums']?abs((int)$_REQUEST['nums']):'0';
	if(empty($nums)){
		jieqi_jumppage('/modules/article/articleinfo.php?id='.$articleid, '链接非法', '<font color="blue">正在跳转，请稍候...</font>');exit;
	}
	if($nums > $jieqiUsers->vars['egold']['value']){
		jieqi_jumppage('/modules/article/articleinfo.php?id='.$articleid, '您的雨币不足，请充值后再来', '<font color="blue">正在跳转，请稍候...</font>');exit;
	}
	$nowtime = time();
	$result = mysql_query("INSERT INTO `jieqi_go123_cuigeng` SET `uid` = '$_SESSION[jieqiUserId]',`uname` = '$username',`articleid` = '$articleid',`nums` = '$nums',`dateline`='$nowtime',`articlename` = '$articlename',`status`='0'");
	
	if($result){
		mysql_query("UPDATE `jieqi_system_users` SET `egold` = `egold` - '$nums' WHERE `uid` = '$_SESSION[jieqiUserId]'");
		jieqi_jumppage('/modules/article/articleinfo.php?id='.$articleid, '恭喜您催更成功，期待明日作者的更新吧！', '<font color="blue">正在跳转，请稍候...</font>');exit;
	}else{
		jieqi_jumppage('/modules/article/articleinfo.php?id='.$articleid, '数据插入错误，催更失败！', '<font color="blue">正在跳转，请稍候...</font>');exit;
	}
}

$jieqiTpl->assign('articlename', $rows['0']['articlename']);
$jieqiTpl->assign('eggold', $jieqiUsers->vars['egold']['value']);
$jieqiTpl->assign('articleid', $articleid);

$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/cuigeng.html';

include_once(JIEQI_ROOT_PATH.'/footer.php');