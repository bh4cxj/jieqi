<?php 
/**
 * 显示用户友情链接
 *
 * 显示一个用户的友情链接列表
 * 
 * 调用模板：/templates/userlink.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userlink.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
if($_REQUEST['uid']=='self') $_REQUEST['uid']=intval($_SESSION['jieqiUserId']);
if(empty($_REQUEST['uid']) && empty($_REQUEST['uname'])){
	if(!empty($_SESSION['jieqiUserId'])) $_REQUEST['uid']=$_SESSION['jieqiUserId'];
	else jieqi_printfail(LANG_ERROR_PARAMETER);
}

jieqi_loadlang('userlink', JIEQI_MODULE_NAME);
jieqi_getconfigs('system', 'configs');
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1; //页码

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['uid'])) $owneruser=$users_handler->get($_REQUEST['uid']);
else $owneruser=$users_handler->getByname($_REQUEST['oname'], 2);
if(!$owneruser) jieqi_printfail($jieqiLang['system']['userlink_no_owner']);
$_REQUEST['uid']=$owneruser->getVar('uid','n');

include_once(JIEQI_ROOT_PATH.'/class/userlink.php');
$userlink_handler=& JieqiUserlinkHandler::getInstance('JieqiUserlinkHandler');

include_once(JIEQI_ROOT_PATH.'/header.php');
$owner = $owneruser->getVar('name');
if(strlen($owner) == 0) $owner = $owneruser->getVar('uname');
$jieqiTpl->assign('owner', $owner);
$jieqiTpl->assign('uid', $_REQUEST['uid']);
$criteria = new CriteriaCompo(new Criteria('userid', $_REQUEST['uid']));
$criteria->setSort('toptime');
$criteria->setOrder('DESC');
$userlink_handler->queryObjects($criteria);
$linkrows=array();
$k=0;
while ($userlink = $userlink_handler->getObject()) {
	$linkrows[$k]['ulid'] = $userlink->getVar('ulid');
	$linkrows[$k]['ultitle'] = $userlink->getVar('ultitle');
	$linkrows[$k]['ulurl'] = $userlink->getVar('ulurl');
	$linkrows[$k]['ulinfo'] = htmlspecialchars($userlink->getVar('ulinfo','n'));
	$linkrows[$k]['userid'] = $userlink->getVar('userid');
	$linkrows[$k]['username'] = $userlink->getVar('username');
	$linkrows[$k]['score'] = $userlink->getVar('score');
	$linkrows[$k]['weight'] = $userlink->getVar('weight');
	$linkrows[$k]['toptime'] = $userlink->getVar('toptime');
	$linkrows[$k]['addtime'] = $userlink->getVar('addtime');
	$linkrows[$k]['allvisit'] = $userlink->getVar('allvisit');
	$k++;
}
$jieqiTpl->assign_by_ref('linkrows', $linkrows);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userlink.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>