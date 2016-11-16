<?php 
/**
 * 后台发送新短消息
 *
 * 后台发送新短消息
 * 
 * 调用模板：/templates/newmessage.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newmessage.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminmessage'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('message', JIEQI_MODULE_NAME);
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'message';
switch ( $_REQUEST['action'] ) {
	case 'newmessage':
	$_REQUEST['receiver']=trim($_REQUEST['receiver']);
	$_REQUEST['title']=trim($_REQUEST['title']);
	$errtext='';
	if(strlen($_REQUEST['receiver'])==0) $errtext.=$jieqiLang['system']['message_need_receiver'].'<br />';
	if(strlen($_REQUEST['title'])==0) $errtext.=$jieqiLang['system']['message_need_title'].'<br />';
	if(empty($errtext)) {
		//检查该用户是否存在
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler=JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$touser=$users_handler->getByname($_REQUEST['receiver'],3);
		if(!$touser) jieqi_printfail($jieqiLang['system']['message_no_receiver']);
		include_once(JIEQI_ROOT_PATH.'/class/message.php');
		$message_handler =& JieqiMessageHandler::getInstance('JieqiMessageHandler');
		$newMessage= $message_handler->create();
		$newMessage->setVar('siteid', JIEQI_SITE_ID);
		$newMessage->setVar('postdate', JIEQI_NOW_TIME);
		$newMessage->setVar('fromid', 0);
		$newMessage->setVar('fromname', $_SESSION['jieqiUserName']);
		$newMessage->setVar('toid', $touser->getVar('uid', 'n'));
		if(strlen($touser->getVar('name', 'n')) > 0) $newMessage->setVar('toname', $touser->getVar('name', 'n'));
		else $newMessage->setVar('toname', $touser->getVar('uname', 'n'));
		$newMessage->setVar('title', $_REQUEST['title']);
		$newMessage->setVar('content', $_REQUEST['content']);
		$newMessage->setVar('messagetype', 0);
		$newMessage->setVar('isread', 0);
		$newMessage->setVar('fromdel', 0);
		$newMessage->setVar('todel', 0);
		$newMessage->setVar('enablebbcode', 1);
		$newMessage->setVar('enablehtml', 0);
		$newMessage->setVar('enablesmilies', 1);
		$newMessage->setVar('attachsig', 0);
		$newMessage->setVar('attachment', 0);
		if(!$message_handler->insert($newMessage)) jieqi_printfail($jieqiLang['system']['message_send_failure']);
		else{
			jieqi_jumppage(JIEQI_URL.'/admin/message.php?box=inbox', LANG_DO_SUCCESS, $jieqiLang['system']['message_send_seccess']);
		}
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'message':
	default:
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$message=false;
	if(!empty($_REQUEST['reid']) || !empty($_REQUEST['fwid'])){
		include_once(JIEQI_ROOT_PATH.'/class/message.php');
		$message_handler=JieqiMessageHandler::getInstance('JieqiMessageHandler');
		if(!empty($_REQUEST['reid'])){
			$message=$message_handler->get($_REQUEST['reid']);
		}elseif(!empty($_REQUEST['fwid'])){
			$message=$message_handler->get($_REQUEST['fwid']);
		}
	}
    if(is_object($message)) {
    	$_REQUEST['receiver']=$message->getVar('fromname', 'e');
    	$_REQUEST['title']=$message->getVar('title', 'e');
    	if(!empty($_REQUEST['reid'])){
    		$_REQUEST['title']='Re:'.$_REQUEST['title'];
    		$_REQUEST['content']='';
    	}elseif(!empty($_REQUEST['fwid'])){
    		$_REQUEST['title']='Fw:'.$_REQUEST['title'];
    		$_REQUEST['content']=$message->getVar('content', 'e');
    	}
    }
    
   	if(!isset($_REQUEST['receiver'])) $_REQUEST['receiver']='';
    if(!isset($_REQUEST['title'])) $_REQUEST['title']='';
    if(!isset($_REQUEST['content'])) $_REQUEST['content']='';
    $jieqiTpl->assign('url_newmessage', JIEQI_URL.'/admin/newmessage.php');
    $jieqiTpl->assign('receiver', $_REQUEST['receiver']);
    $jieqiTpl->assign('title', $_REQUEST['title']);
    $jieqiTpl->assign('content', $_REQUEST['content']);
    $jieqiTpl->assign('action', 'newmessage');

    $jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/newmessage.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
	break;
}

?>