<?php 
/**
 * 显示我的短消息列表
 *
 * 包括收件箱和发件箱的短消息
 * 
 * 调用模板：/templates/inbox.html;/templates/outbox.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: message.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
jieqi_loadlang('message', JIEQI_MODULE_NAME);
jieqi_getconfigs('system', 'configs');
if(!isset($_REQUEST['box']) || $_REQUEST['box'] != 'outbox') $_REQUEST['box']='inbox';
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1; //页码

//获得允许消息数和现有消息数
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxmessages = isset($jieqiConfigs['system']['maxmessages']) ? intval($jieqiConfigs['system']['maxmessages']) : 0;
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['system']['maxmessages']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxmessages']['honors'][$honorid])) $maxmessages = intval($jieqiRight['system']['maxmessages']['honors'][$honorid]); //根据头衔设置的消息数
	

include_once(JIEQI_ROOT_PATH.'/class/message.php');
$message_handler=& JieqiMessageHandler::getInstance('JieqiMessageHandler');

if(empty($_REQUEST['checkaction'])) $_REQUEST['checkaction']=0;
//处理批量删除
if($_REQUEST['checkaction'] == 1 && is_array($_REQUEST['checkid']) && count($_REQUEST['checkid'])>0){

	$where='';
	foreach($_REQUEST['checkid'] as $v){
		if(is_numeric($v)){
			$v=intval($v);
			if(!empty($where)) $where.=' OR ';
			$where.=$message_handler->autoid.'='.$v;
		}
	}

	if(!empty($where)){
		switch($_REQUEST['box']){
			case 'inbox':
			$sql='UPDATE '.jieqi_dbprefix('system_message').' SET todel=1 WHERE toid='.$_SESSION['jieqiUserId'].' AND fromdel=0 AND ('.$where.')';
			$message_handler->db->query($sql);
			$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE toid='.$_SESSION['jieqiUserId'].' AND fromdel=1 AND ('.$where.')';
			$message_handler->db->query($sql);
			break;
			case 'outbox':
			$sql='UPDATE '.jieqi_dbprefix('system_message').' SET fromdel=1 WHERE fromid='.$_SESSION['jieqiUserId'].' AND todel=0 AND ('.$where.')';
			$message_handler->db->query($sql);
			$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE fromid='.$_SESSION['jieqiUserId'].' AND todel=1 AND ('.$where.')';
			$message_handler->db->query($sql);
			break;
		}
	}
	$_REQUEST['checkaction']=0;
}elseif($_REQUEST['checkaction'] == 2){
	//删除全部
	switch($_REQUEST['box']){
		case 'inbox':
		$sql='UPDATE '.jieqi_dbprefix('system_message').' SET todel=1 WHERE toid='.$_SESSION['jieqiUserId'].' AND fromdel=0';
		$message_handler->db->query($sql);
		$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE toid='.$_SESSION['jieqiUserId'].' AND fromdel=1';
		$message_handler->db->query($sql);
		break;
		case 'outbox':
		$sql='UPDATE '.jieqi_dbprefix('system_message').' SET fromdel=1 WHERE fromid='.$_SESSION['jieqiUserId'].' AND todel=0';
		$message_handler->db->query($sql);
		$sql='DELETE FROM '.jieqi_dbprefix('system_message').' WHERE fromid='.$_SESSION['jieqiUserId'].' AND todel=1';
		$message_handler->db->query($sql);
		break;
	}
	$_REQUEST['checkaction']=0;
}

if(isset($_GET['checkaction'])) unset($_GET['checkaction']);
if(isset($_POST['checkaction'])) unset($_POST['checkaction']);

include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('box', $_REQUEST['box']);
$jieqiTpl->assign('url_action', JIEQI_URL.'/message.php?box='.$_REQUEST['box']);
$jieqiTpl->assign('url_delete', JIEQI_URL.'/message.php?box='.$_REQUEST['box'].'&checkaction=2');

$sql="SELECT COUNT(*) AS msgnum FROM ".jieqi_dbprefix('system_message')." WHERE (fromid=".$_SESSION['jieqiUserId']." AND fromdel=0) OR (toid=".$_SESSION['jieqiUserId']." AND todel=0)";
$res=$message_handler->db->query($sql);
$row=$message_handler->getRow($res);
$msgnum=(int)$row['msgnum'];
$jieqiTpl->assign('maxmessage', $maxmessages);
$jieqiTpl->assign('nowmessage', $msgnum);

$messagerows=array();
switch($_REQUEST['box']){
	case 'outbox':
	$jieqiTpl->assign('boxname', $jieqiLang['system']['message_send_box']);
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['table_message_receiver']);
	$criteria=new CriteriaCompo(new Criteria('fromid', $_SESSION['jieqiUserId']));
	$criteria->add(new Criteria('fromdel', 0));
	$criteria->setSort('messageid');
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['system']['messagepnum']);
	$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['messagepnum']);

	$message_handler->queryObjects($criteria);
	$k=0;
	while($v = $message_handler->getObject()){
		//处理删除
		if(isset($_REQUEST['delid']) && $_REQUEST['delid']==$v->getVar('messageid')){
			if($v->getVar('todel')>0){
				$message_handler->delete($_REQUEST['delid']);
			}else{
				$v->setVar('fromdel', 1);
				$message_handler->insert($v);
			}
		}else{
			$messagerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('messageid').'">';
			if($v->getVar('toid')>0){
				$messagerows[$k]['userid']=$v->getVar('toid');
				$messagerows[$k]['username']=$v->getVar('toname');
			}else{
				$messagerows[$k]['userid']=0;
				$messagerows[$k]['username']=$jieqiLang['system']['message_site_admin'];
			}
			$messagerows[$k]['messageid']=$v->getVar('messageid');
			$messagerows[$k]['title']=$v->getVar('title');
			$messagerows[$k]['postdate']=$v->getVar('postdate');
			$messagerows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('postdate'));
		}
		$k++;
	}
	$jieqiTpl->assign('messagerows', $messagerows);
	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	$jumppage = new JieqiPage($message_handler->getCount($criteria),$jieqiConfigs['system']['messagepnum'],$_REQUEST['page']);
	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/outbox.html';
	break;
	case 'inbox':
	$jieqiTpl->assign('boxname', $jieqiLang['system']['message_receive_box']);
	$jieqiTpl->assign('usertitle', $jieqiLang['system']['table_message_sender']);
	$criteria=new CriteriaCompo(new Criteria('toid', $_SESSION['jieqiUserId']));
	$criteria->add(new Criteria('todel', 0));
	$criteria->setSort('messageid');
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['system']['messagepnum']);
	$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['messagepnum']);
	$message_handler->queryObjects($criteria);
	$k=0;
	while($v = $message_handler->getObject()){
		//处理删除
		if(isset($_REQUEST['delid']) && $_REQUEST['delid']==$v->getVar('messageid')){
			if($v->getVar('fromdel')>0){
				$message_handler->delete($_REQUEST['delid']);
			}else{
				$v->setVar('todel', 1);
				$message_handler->insert($v);
			}
		}else{
			$messagerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('messageid').'">';

			if($v->getVar('fromid')>0){
				$messagerows[$k]['userid']=$v->getVar('fromid');
				$messagerows[$k]['username']=$v->getVar('fromname');
			}else{
				$messagerows[$k]['userid']=0;
				$messagerows[$k]['username']=$jieqiLang['system']['message_site_admin'];
			}
			$messagerows[$k]['messageid']=$v->getVar('messageid');
			$messagerows[$k]['title']=$v->getVar('title');
			$messagerows[$k]['postdate']=$v->getVar('postdate');
			$messagerows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('postdate'));
			if($v->getVar('isread')) $messagerows[$k]['isread']=1;
			else $messagerows[$k]['isread']=0;
		}
		$k++;
	}
	$jieqiTpl->assign('messagerows', $messagerows);
	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	$jumppage = new JieqiPage($message_handler->getCount($criteria),$jieqiConfigs['system']['messagepnum'],$_REQUEST['page']);
	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/inbox.html';
	//处理短消息提示
	if(isset($_SESSION['jieqiNewMessage']) && $_SESSION['jieqiNewMessage'] > 0){
		$_SESSION['jieqiNewMessage'] = 0;
		$jieqi_user_info=array();
		if(!empty($_COOKIE['jieqiUserInfo'])) $jieqi_user_info=jieqi_strtosary($_COOKIE['jieqiUserInfo']);
		else $jieqi_user_info=array();
		if(isset($jieqi_user_info['jieqiNewMessage']) && $jieqi_user_info['jieqiNewMessage']>0) $jieqi_user_info['jieqiNewMessage']=0;
		if(!empty($jieqi_user_info['jieqiUserPassword'])) $cookietime=JIEQI_NOW_TIME + 22118400;
		else $cookietime=0;
		@setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
	}
	default:
	break;
}
include_once(JIEQI_ROOT_PATH.'/footer.php');


?>