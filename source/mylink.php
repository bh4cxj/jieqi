<?php 
/**
 * 我的链接
 *
 * 我的友情链接列表，含友情链接增加、编辑、删除、置顶功能
 * 
 * 调用模板：/templates/mylink.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: mylink.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
jieqi_loadlang('userlink', JIEQI_MODULE_NAME);
jieqi_getconfigs('system', 'configs');

if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1; //页码

//获得允许链接数和现有链接数
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxuserlink = isset($jieqiConfigs['system']['maxuserlink']) ? intval($jieqiConfigs['system']['maxuserlink']) : 0;
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['system']['maxuserlink']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxuserlink']['honors'][$honorid])) $maxuserlink = intval($jieqiRight['system']['maxuserlink']['honors'][$honorid]); //根据头衔设置的消息数


include_once(JIEQI_ROOT_PATH.'/class/userlink.php');
$userlink_handler=& JieqiUserlinkHandler::getInstance('JieqiUserlinkHandler');

if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		case 'add':
			$errtext='';
			if(!empty($maxuserlink)){
				$nowlink = $userlink_handler->getCount(new Criteria('userid', $_SESSION['jieqiUserId']));
				if($nowlink >= $maxuserlink) $errtext.=sprintf($jieqiLang['system']['userlink_maxnum_limit'], $maxuserlink).'<br />';
			}

			if(strlen($_POST['ultitle'])==0) $errtext.=$jieqiLang['system']['userlink_need_ultitle'].'<br />';
			if(strlen($_POST['ulurl'])==0) $errtext.=$jieqiLang['system']['userlink_need_ulurl'].'<br />';
			if(empty($errtext)){
				$newUserlink=& $userlink_handler->create();
				$newUserlink->setVar('ultitle', $_POST['ultitle']);
				$newUserlink->setVar('ulurl', $_POST['ulurl']);
				$newUserlink->setVar('ulinfo', $_POST['ulinfo']);
				$newUserlink->setVar('userid', $_SESSION['jieqiUserId']);
				$newUserlink->setVar('username', $_SESSION['jieqiUserName']);
				$newUserlink->setVar('addtime', JIEQI_NOW_TIME);
				$userlink_handler->insert($newUserlink);
			}else{
				jieqi_printfail($errtext);
			}
			break;
		case 'edit':
			$errtext='';
			if(strlen($_POST['ultitle'])==0) $errtext.=$jieqiLang['system']['system_userlink_ultitle'].'<br />';
			if(strlen($_POST['ulurl'])==0) $errtext.=$jieqiLang['system']['system_userlink_ulurl'].'<br />';
			if(empty($errtext)){
				$newUserlink=& $userlink_handler->create();
				$newUserlink->setVar('ultitle', $_POST['ultitle']);
				$newUserlink->setVar('ulurl', $_POST['ulurl']);
				$newUserlink->setVar('ulinfo', $_POST['ulinfo']);
				$newUserlink->setVar('userid', $_SESSION['jieqiUserId']);
				$newUserlink->setVar('username', $_SESSION['jieqiUserName']);
				$newUserlink->setVar('addtime', JIEQI_NOW_TIME);
				$userlink_handler->insert($newUserlink);
			}else{
				jieqi_printfail($errtext);
			}
			break;
		case 'delete':
			if(!empty($_REQUEST['ulid'])){
				$sql="DELETE FROM ".jieqi_dbprefix('system_userlink')." WHERE userid=".intval($_SESSION['jieqiUserId'])." AND ulid=".intval($_REQUEST['ulid']);
				$userlink_handler->db->query($sql);
			}
			break;
		case 'top':
			if(!empty($_REQUEST['ulid'])){
				$sql="UPDATE ".jieqi_dbprefix('system_userlink')." SET toptime=".intval(JIEQI_NOW_TIME)." WHERE userid=".intval($_SESSION['jieqiUserId'])." AND ulid=".intval($_REQUEST['ulid']);
				$userlink_handler->db->query($sql);
			}
			break;
		case 'untop':
			if(!empty($_REQUEST['ulid'])){
				$sql="UPDATE ".jieqi_dbprefix('system_userlink')." SET toptime=0 WHERE userid=".intval($_SESSION['jieqiUserId'])." AND ulid=".intval($_REQUEST['ulid']);
				$userlink_handler->db->query($sql);
			}
			break;
	}
}

include_once(JIEQI_ROOT_PATH.'/header.php');
$criteria = new CriteriaCompo(new Criteria('userid', $_SESSION['jieqiUserId']));
$criteria->setSort('toptime');
$criteria->setOrder('DESC');
$userlink_handler->queryObjects($criteria);
$linkrows=array();
$k=0;
while ($userlink = $userlink_handler->getObject()) {
	$linkrows[$k]['ulid'] = $userlink->getVar('ulid');
	$linkrows[$k]['ultitle'] = $userlink->getVar('ultitle');
	$linkrows[$k]['e_ultitle'] = str_replace(array("\r", "\n"), '', addslashes($linkrows[$k]['ultitle']));
	$linkrows[$k]['ulurl'] = $userlink->getVar('ulurl');
	$linkrows[$k]['e_ulurl'] = str_replace(array("\r", "\n"), '', addslashes($linkrows[$k]['ulurl']));
	$linkrows[$k]['ulinfo'] = htmlspecialchars($userlink->getVar('ulinfo', 'n'));
	$linkrows[$k]['e_ulinfo'] = str_replace(array("\r", "\n"), '', addslashes($userlink->getVar('ulinfo')));
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
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/mylink.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>