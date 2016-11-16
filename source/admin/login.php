<?php
/**
 * 后台用户登录
 *
 * 后台用户登录
 * 
 * 调用模板：/templates/admin/login.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: login.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
define('JIEQI_ADMIN_LOGIN', 1);
if($_REQUEST['action']=='login') define('JIEQI_NEED_SESSION', 1);
require_once('../global.php');

if(!empty($_SESSION['jieqiUserId']) && !empty($_SESSION['jieqiAdminLogin'])){
	if (empty($_REQUEST['jumpurl'])) {
		$_REQUEST['jumpurl']=JIEQI_URL.'/admin/index.php';
	}
	header('Location: '.$_REQUEST['jumpurl']);
	exit;
}

//if(JIEQI_LOCAL_URL != JIEQI_USER_URL) header('Location: '.JIEQI_USER_URL.jieqi_addurlvars(array()));
if(isset($_REQUEST['action']) && $_REQUEST['action']=='login' && empty($_SESSION['jieqiUserId'])) @session_regenerate_id();
//载入语言
jieqi_loadlang('users', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(!isset($_REQUEST['action'])) $_REQUEST['action']='';
if($_REQUEST['action']=='login'){
	if(!empty($_SESSION['jieqiUserId']) && !empty($_REQUEST['password'])){
		//已经登录情况，进入管理面板确认密码
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$jieqiUsers=$users_handler->get($_SESSION['jieqiUserId']);
		if(is_object($jieqiUsers)){
			if($jieqiUsers->getVar('pass', 'n') != $users_handler->encryptPass($_REQUEST['password'])){
				jieqi_printfail($jieqiLang['system']['error_password']);
			}else{
				$_SESSION['jieqiAdminLogin']=1;
				$jieqi_online_info = empty($_COOKIE['jieqiOnlineInfo']) ? array() : jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
				$jieqi_online_info['jieqiAdminLogin']=1;
				@setcookie('jieqiOnlineInfo', jieqi_sarytostr($jieqi_online_info), 0, '/',  JIEQI_COOKIE_DOMAIN, 0);
				if (empty($_REQUEST['jumpurl'])) {
					$_REQUEST['jumpurl']=JIEQI_URL.'/admin/index.php';
				}
				jieqi_jumppage($_REQUEST['jumpurl'], LANG_DO_SUCCESS, sprintf($jieqiLang['system']['login_success'], $jieqiUsers->getVar('uname')));
			}
		}else{
			jieqi_printfail($jieqiLang['system']['no_this_user']);
		}
		exit;
	}elseif(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
		//未登录情况，输入帐号登录
		//$_REQUEST['username']=strtolower(trim($_REQUEST['username']));
		$_REQUEST['username']=trim($_REQUEST['username']);
		include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
		if(isset($_REQUEST['usecookie']) && is_numeric($_REQUEST['usecookie'])) $_REQUEST['usecookie']=intval($_REQUEST['usecookie']);
		else $_REQUEST['usecookie']=0;
		if(empty($_REQUEST['checkcode'])) $_REQUEST['checkcode']='';
		$islogin=jieqi_logincheck($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['checkcode'], $_REQUEST['usecookie']);
		if($islogin==0){
			$_SESSION['jieqiAdminLogin']=1;
			$jieqi_online_info = empty($_COOKIE['jieqiOnlineInfo']) ? array() : jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
			$jieqi_online_info['jieqiAdminLogin']=1;
			@setcookie('jieqiOnlineInfo', jieqi_sarytostr($jieqi_online_info), 0, '/',  JIEQI_COOKIE_DOMAIN, 0);

			if (empty($_REQUEST['jumpurl'])) {
				$_REQUEST['jumpurl']=JIEQI_URL.'/admin/index.php';
			}
			jieqi_jumppage($_REQUEST['jumpurl'], LANG_DO_SUCCESS, sprintf($jieqiLang['system']['login_success'], jieqi_htmlstr($_REQUEST['username'])));
		}else{
			//返回 0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空
			//-4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 校验码错误 -8 帐号已经有人登陆
			switch($islogin){
				case -1:
					jieqi_printfail($jieqiLang['system']['need_username']);
					break;
				case -2:
					jieqi_printfail($jieqiLang['system']['need_password']);
					break;
				case -3:
					jieqi_printfail($jieqiLang['system']['need_userpass']);
					break;
				case -4:
					jieqi_printfail($jieqiLang['system']['no_this_user']);
					break;
				case -5:
					jieqi_printfail($jieqiLang['system']['error_password']);
					break;
				case -6:
					jieqi_printfail($jieqiLang['system']['error_userpass']);
					break;
				case -7:
					jieqi_printfail($jieqiLang['system']['error_checkcode']);
					break;
				case -8:
					jieqi_printfail($jieqiLang['system']['other_has_login']);
					break;
				default:
					jieqi_printfail($jieqiLang['system']['login_failure']);
					break;
			}
		}
		exit;
	}
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');

$self_fname = $_SERVER['PHP_SELF'] ? basename($_SERVER['PHP_SELF']) : basename($_SERVER['SCRIPT_NAME']);

if (!empty($_REQUEST['jumpurl'])) {
	$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/admin/'.$self_fname.'?do=submit&jumpurl='.urlencode($_REQUEST['jumpurl']));
}else{
	$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/admin/'.$self_fname.'?do=submit');
}
if(empty($_SESSION['jieqiUserId'])){
	$jieqiTpl->assign('jieqi_userid', 0);
	$jieqiTpl->assign('jieqi_username', '');
}else{
	$jieqiTpl->assign('jieqi_userid', $_SESSION['jieqiUserId']);
	$jieqiTpl->assign('jieqi_username', jieqi_htmlstr($_SESSION['jieqiUserUname']));
}
if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
else $jieqiTpl->assign('show_checkcode', 0);

if(empty($jieqiConfigs['system']['usegd'])){
	$jieqiTpl->assign('usegd', 0);
}else{
	$jieqiTpl->assign('usegd', 1);
}
$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/login.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

//包含页头页尾


?>