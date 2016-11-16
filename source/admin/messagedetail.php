<?php 
/**
 * 后台显示短消息内容
 *
 * 显示具体一条短消息的内容
 * 
 * 调用模板：/templates/admin/messagedetail.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: messagedetail.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminmessage'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('message', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['system']['message_no_exists']);
include_once(JIEQI_ROOT_PATH.'/class/message.php');
$message_handler=JieqiMessageHandler::getInstance('JieqiMessageHandler');
$message=$message_handler->get($_REQUEST['id']);
if(!$message) jieqi_printfail($jieqiLang['system']['message_no_exists']);
if($message->getVar('fromid') != 0 && $message->getVar('toid') != 0) jieqi_printfail($jieqiLang['system']['message_no_exists']);

include_once(JIEQI_ROOT_PATH.'/admin/header.php');

$jieqiTpl->assign('messageid', $_REQUEST['id']);
$jieqiTpl->assign('title', $message->getVar('title'));
if($message->getVar('fromid')>0){
	$jieqiTpl->assign('fromid',$message->getVar('fromid'));
	$jieqiTpl->assign('fromname',$message->getVar('fromname'));
}else{
    $jieqiTpl->assign('fromid',0);
	$jieqiTpl->assign('fromname','');
}
if($message->getVar('toid')>0){
	$jieqiTpl->assign('toid',$message->getVar('toid'));
	$jieqiTpl->assign('toname',$message->getVar('toname'));
}else{
	$jieqiTpl->assign('toid',0);
	$jieqiTpl->assign('toname','');
}
$jieqiTpl->assign('postdate', date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $message->getVar('postdate')));
include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
$ts=TextConvert::getInstance('TextConvert');
$jieqiTpl->assign('content', $ts->makeClickable($message->getVar('content')));
if($message->getVar('toid') == 0 || $message->getVar('toid') == $_SESSION['jieqiUserId']){
	$box='inbox';
}else{
	$box='outbox';
}
$jieqiTpl->assign('box', $box);
$jieqiTpl->assign('url_reply', JIEQI_URL.'/newmessage.php?reid='.$_REQUEST['id']);
$jieqiTpl->assign('url_forward', JIEQI_URL.'/newmessage.php?fwid='.$_REQUEST['id']);
$jieqiTpl->assign('url_delete', JIEQI_URL.'/message.php?box='.$box.'&delid='.$_REQUEST['id']);

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/messagedetail.html';

//设置已读标志
if($message->getVar('isread') != 1 && ($message->getVar('toid') == 0 || $message->getVar('toid') == $_SESSION['jieqiUserId'])){
    $message->setVar('isread', '1');
    $message_handler->insert($message);	
}

include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>