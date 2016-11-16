<?php 
/**
 * 增加好友
 *
 * 增加好友，参数可以是好友的用户ID或者用户名/昵称
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: addfriends.php 322 2009-01-13 11:28:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
if(empty($_REQUEST['id']) && empty($_REQUEST['username'])) jieqi_printfail(LANG_NO_USER);

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['id'])){
	$userobj=$users_handler->get($_REQUEST['id']);
}else{
	$_REQUEST['username']=trim($_REQUEST['username']);
    $userobj=$users_handler->getByname($_REQUEST['username'],3);
}

if(is_object($userobj)){
	jieqi_loadlang('users', JIEQI_MODULE_NAME);
	include_once(JIEQI_ROOT_PATH.'/class/friends.php');
	$friends_handler =& JieqiFriendsHandler::getInstance('JieqiFriendsHandler');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	jieqi_getconfigs('system', 'honors');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
	$maxfriendsnum=intval($jieqiConfigs['system']['maxfriends']); //默认好友数
	$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
	if($honorid && isset($jieqiRight['system']['maxfriends']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxfriends']['honors'][$honorid])) $maxfriendsnum = intval($jieqiRight['system']['maxfriends']['honors'][$honorid]); //根据头衔设置的好友数
	if(is_numeric($maxfriendsnum)){
	    $criteria=new CriteriaCompo(new Criteria('myid', $_SESSION['jieqiUserId']));
	    $friendsnum=$friends_handler->getCount($criteria);
	    if($friendsnum>=$maxfriendsnum) jieqi_printfail(sprintf($jieqiLang['system']['too_manay_friends'], $maxfriendsnum));
	}
	unset($criteria);
	$criteria=new CriteriaCompo(new Criteria('myid', $_SESSION['jieqiUserId']));
	$criteria->add(new Criteria('yourid', $userobj->getVar('uid', 'n')));
	$isexist=$friends_handler->getCount($criteria);
	if($isexist>0) jieqi_printfail($jieqiLang['system']['has_been_friends']);
	
	$newFriends = $friends_handler->create();       
	$newFriends->setVar('adddate', JIEQI_NOW_TIME);
	$newFriends->setVar('myid', $_SESSION['jieqiUserId']);
	$newFriends->setVar('myname', $_SESSION['jieqiUserName']);
	$newFriends->setVar('yourid', $userobj->getVar('uid', 'n'));
	if(strlen($userobj->getVar('name', 'n')) > 0) $newFriends->setVar('yourname', $userobj->getVar('name', 'n'));
	else $newFriends->setVar('yourname', $userobj->getVar('uname', 'n'));
	$newFriends->setVar('teamid', 0);
	$newFriends->setVar('team', '');
	$newFriends->setVar('fset', '');
	$newFriends->setVar('state', 0);
	$newFriends->setVar('flag', 0);
	
	if (!$friends_handler->insert($newFriends)) jieqi_printfail($jieqiLang['system']['add_friends_failure']);
	else {
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['add_friends_success']);
	}
}else{
	jieqi_printfail(LANG_NO_USER);
}

?>