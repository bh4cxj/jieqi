<?php
define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('article', JIEQI_MODULE_NAME);

jieqi_checklogin();
jieqi_getconfigs('system', 'honors');
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);



include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['id']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqi_pagetitle=$article->getVar('articlename').'-'.$article->getVar('author').'-'.JIEQI_SITE_NAME;
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('useremoney', $jieqiUsers->getVar('egold'));

if(isset($_POST) && !empty($_POST)){
	$nums = intval($_REQUEST['payegold']);
	$id = intval($_REQUEST['id']);
	$uid = intval($jieqiUsers->getVar('uid'));
	$nowtime = time();
	$score = $nums*10;
	mysql_query("INSERT INTO `jieqi_article_tip` SET `uid`='$uid',`articleid`='$id',`nums`='$nums',`dateline`='$nowtime'");
	mysql_query("UPDATE `jieqi_system_users` SET `egold` = `egold`-'$nums', `score` = `score`+'$score' WHERE `uid`='$uid'");
	$authorid = intval($article->getVar('authorid'));
	if(isset($authorid) && $authorid != 0){
		$newgold = intval($nums*0.8);
		mysql_query("UPDATE `jieqi_system_users` SET `egold` = `egold`+'$newgold' WHERE `uid`='$authorid'");
	}
	jieqi_jumppage('/modules/article/articleinfo.php?id='.$id, '感谢您的打赏', '<font color="blue">正在跳转，请稍候...</font>');exit;
}
$jieqiTpl->assign('articleid', $_REQUEST['id']);
$jieqiTpl->assign('articlename', $article->getVar('articlename'));
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/tip.html';

include_once(JIEQI_ROOT_PATH.'/footer.php');