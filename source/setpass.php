<?php
/**
 * 重新设定密码
 *
 * 用户忘记密码后，先用取回密码功能，系统发送验证链接到用户email，用户收到后点击链接设定新密码
 * 
 * 调用模板：/templates/setpass.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: setpass.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_loadlang('users', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id']) || empty($_REQUEST['checkcode'])) jieqi_printfail($jieqiLang['system']['no_checkcode_id']);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$user=$users_handler->get($_REQUEST['id']);
if(!is_object($user)) jieqi_printfail(LANG_NO_USER);
if(md5($user->getVar('pass')) != $_REQUEST['checkcode']) jieqi_printfail($jieqiLang['system']['error_checkcode']);
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'setpass';

switch($_REQUEST['action']) {
	case 'newpass':
	$_REQUEST['pass'] = trim($_REQUEST['pass']);
	$_REQUEST['repass'] = trim($_REQUEST['repass']);
	$errtext='';
	//检查密码
	if (strlen($_REQUEST['pass'])==0 || strlen($_REQUEST['repass'])==0) $errtext .= $jieqiLang['system']['need_pass_repass'].'<br />';
	elseif ($_REQUEST['pass'] != $_REQUEST['repass']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';
	if(empty($errtext)) {
		$user->setVar('pass', $users_handler->encryptPass($_REQUEST['pass']));
		$users_handler->insert($user);
		jieqi_jumppage(JIEQI_USER_URL.'/login.php', LANG_DO_SUCCESS, $jieqiLang['system']['set_password_success']);
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'setpass':
	default:
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('url_setpass', JIEQI_USER_URL.'/setpass.php?do=submit');
	$jieqiTpl->assign('action', 'newpass');
	$jieqiTpl->assign('id', $_REQUEST['id']);
	$jieqiTpl->assign('checkcode', $_REQUEST['checkcode']);
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/setpass.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}
?>