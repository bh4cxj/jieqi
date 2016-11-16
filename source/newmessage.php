<?php 
/**
 * 发送新短消息
 *
 * 发送新短消息
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
require_once('global.php');
jieqi_checklogin();
jieqi_loadlang('message', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');

//取得最大每天发消息数
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');

$maxdaymsg=intval($jieqiConfigs['system']['maxdaymsg']);
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['system']['maxdaymsg']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxdaymsg']['honors'][$honorid])) $maxdaymsg = intval($jieqiRight['system']['maxdaymsg']['honors'][$honorid]);

//最大每天发消息数不等于零才限制，否则不限制
if(!empty($maxdaymsg)){
	//获取用户当天已发短信数
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
	if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
	$userset=unserialize($jieqiUsers->getVar('setting','n'));
	$today=date('Y-m-d');
}


if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'message';
switch($_REQUEST['action']) {
	case 'newmessage':
		$_REQUEST['receiver']=trim($_REQUEST['receiver']);
		$_REQUEST['title']=trim($_REQUEST['title']);
		$errtext='';
		if(!isset($_REQUEST['tosys']) || empty($_REQUEST['tosys'])) $_REQUEST['tosys']=false;
		else $_REQUEST['tosys']=true;
		if(strlen($_REQUEST['receiver'])==0 && !$_REQUEST['tosys']) $errtext.=$jieqiLang['system']['message_need_receiver'].'<br />';
		if(strlen($_REQUEST['title'])==0) $errtext.=$jieqiLang['system']['message_need_title'].'<br />';

		if(!empty($maxdaymsg) && isset($userset['msgdate']) && $userset['msgdate']==$today && (int)$userset['msgnum']>=(int)$maxdaymsg && $jieqiConfigs['system']['sendmsgscore']>0){
			if($_SESSION['jieqiUserScore'] < $jieqiConfigs['system']['sendmsgscore']) $errtext.=$jieqiLang['system']['low_sendmsg_score'];
		}
		if(empty($errtext)) {
			if(!$_REQUEST['tosys']){
				if($_REQUEST['receiver'] == $_SESSION['jieqiUserUname'] || $_REQUEST['receiver'] == $_SESSION['jieqiUserName']) jieqi_printfail($jieqiLang['system']['message_nosend_self']);
				//检查该用户是否存在
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler=JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$touser=$users_handler->getByname($_REQUEST['receiver'],3);
				if(!$touser) jieqi_printfail($jieqiLang['system']['message_no_receiver']);
			}
			include_once(JIEQI_ROOT_PATH.'/class/message.php');
			$message_handler=JieqiMessageHandler::getInstance('JieqiMessageHandler');
			$newMessage=& $message_handler->create();
			$newMessage->setVar('siteid', JIEQI_SITE_ID);
			$newMessage->setVar('postdate', JIEQI_NOW_TIME);
			$newMessage->setVar('fromid', $_SESSION['jieqiUserId']);
			$newMessage->setVar('fromname', $_SESSION['jieqiUserName']);
			if(!$_REQUEST['tosys']){
				$newMessage->setVar('toid', $touser->getVar('uid', 'n'));
				if(strlen($touser->getVar('name', 'n')) > 0) $newMessage->setVar('toname', $touser->getVar('name', 'n'));
				else $newMessage->setVar('toname', $touser->getVar('uname', 'n'));
			}else{
				$newMessage->setVar('toid', 0);
				$newMessage->setVar('toname', '');
			}
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
			//<!--jieqi insert license check-->
			if(!$message_handler->insert($newMessage)) jieqi_printfail($jieqiLang['system']['message_send_failure']);
			else{
				if(!empty($maxdaymsg)){

					//记录本日发送短信量
					if(isset($userset['msgdate']) && $userset['msgdate']==$today){
						$userset['msgnum']=(int)$userset['msgnum']+1;
					}else{
						$userset['msgdate']=$today;
						$userset['msgnum']=1;
					}
					$jieqiUsers->setVar('setting', serialize($userset));
					$jieqiUsers->saveToSession();
					$users_handler->insert($jieqiUsers);
					//发送短信扣积分
					if(isset($userset['msgdate']) && $userset['msgdate']==$today && (int)$userset['msgnum']>=(int)$maxdaymsg && $jieqiConfigs['system']['sendmsgscore']>0){
						$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['system']['sendmsgscore'], false, false);
					}
				}
				jieqi_jumppage(JIEQI_URL.'/message.php?box=outbox', LANG_DO_SUCCESS, $jieqiLang['system']['message_send_seccess']);
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'message':
	default:
		if(!isset($_REQUEST['tosys']) || $_REQUEST['tosys'] != 1) jieqi_checkpower($jieqiPower['system']['sendmessage'], $jieqiUsersStatus, $jieqiUsersGroup, false);

		//如果已发短信大于做大限制，直接提示禁止发送或者提示扣分
		$sendneedscore=false;
		if(!empty($maxdaymsg) && isset($userset['msgdate']) && $userset['msgdate']==$today && (int)$userset['msgnum']>=(int)$maxdaymsg){
			if($jieqiConfigs['system']['sendmsgscore']>0){
				$sendneedscore=true;
			}else{
				jieqi_printfail(sprintf($jieqiLang['system']['day_message_limit'], $maxdaymsg));
			}
		}

		include_once(JIEQI_ROOT_PATH.'/header.php');

		//获得允许消息数和现有消息数
		jieqi_getconfigs('system', 'honors');
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
		$maxmessage=$jieqiConfigs['system']['messagelimit'];
		$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
		if($honorid && isset($jieqiRight['system']['maxmessages']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxmessages']['honors'][$honorid])) $maxmessage = intval($jieqiRight['system']['maxmessages']['honors'][$honorid]); //根据头衔设置的消息数

		include_once(JIEQI_ROOT_PATH.'/class/message.php');
		$message_handler=JieqiMessageHandler::getInstance('JieqiMessageHandler');
		$sql="SELECT COUNT(*) AS msgnum FROM ".jieqi_dbprefix('system_message')." WHERE (fromid=".$_SESSION['jieqiUserId']." AND fromdel=0) OR (toid=".$_SESSION['jieqiUserId']." AND todel=0)";
		$res=$message_handler->db->query($sql);
		$row=$message_handler->getRow($res);
		$nowmessage=(int)$row['msgnum'];

		if($nowmessage >= $maxmessage){
			$jieqiTpl->setCaching(0);
			$jieqiTpl->assign('jieqi_contents', jieqi_msgbox($jieqiLang['system']['message_is_full'],$jieqiLang['system']['message_box_full']));
		}else{
			$jieqiTpl->assign('maxdaymsg', $maxdaymsg);
			$jieqiTpl->assign('nowmessage', $nowmessage);
			$jieqiTpl->assign('maxmessage', $maxmessage);
			$jieqiTpl->assign('url_newmessage', JIEQI_URL.'/newmessage.php?do=submit');
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
			}elseif(!empty($_REQUEST['event']) && $_REQUEST['event']=='applywriter'){
				if(empty($_REQUEST['title'])) $_REQUEST['title']=$jieqiLang['system']['message_appay_writer'];
				if(empty($_REQUEST['content'])) $_REQUEST['content']=$jieqiLang['system']['message_apply_reason'];
			}

			if(!isset($_REQUEST['receiver'])) $_REQUEST['receiver']='';
			if(!isset($_REQUEST['title'])) $_REQUEST['title']='';
			if(!isset($_REQUEST['content'])) $_REQUEST['content']='';
			if(isset($_REQUEST['tosys']) && $_REQUEST['tosys']==1){
				$jieqiTpl->assign('tosys', 1);
				$jieqiTpl->assign('receiver', $jieqiLang['system']['message_site_admin']);
			}else{
				$jieqiTpl->assign('tosys', 0);
				$jieqiTpl->assign('receiver', $_REQUEST['receiver']);
			}
			$jieqiTpl->assign('title', $_REQUEST['title']);
			$jieqiTpl->assign('content', $_REQUEST['content']);

			if($sendneedscore){
				$jieqiTpl->assign('needscore', 1);
				$jieqiTpl->assign('sendmsgscore', $jieqiConfigs['system']['sendmsgscore']);
			}else{
				$jieqiTpl->assign('needscore', 0);
			}
			$jieqiTpl->setCaching(0);
			$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/newmessage.html';
		}
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}
?>