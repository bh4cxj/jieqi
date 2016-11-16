<?php 
/**
 * 用户登录
 *
 * 用户登录，提交后检查密码和校验码等
 * 
 * 调用模板：/templates/login.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: login.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
if($_REQUEST['action']=='login') define('JIEQI_NEED_SESSION', 1);
require_once('global.php');
//<!--jieqi insert check code-->
if(isset($_REQUEST['action']) && $_REQUEST['action']=='login') @session_regenerate_id();
//载入语言
jieqi_loadlang('users', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(isset($_REQUEST['action']) && $_REQUEST['action']=='login' && !empty($_REQUEST['username']) && !empty($_REQUEST['password']))
{
	$_REQUEST['username']=trim($_REQUEST['username']);
	include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
	if(isset($_REQUEST['usecookie']) && is_numeric($_REQUEST['usecookie'])) $_REQUEST['usecookie']=intval($_REQUEST['usecookie']);
	else $_REQUEST['usecookie']=0;
	if(empty($_REQUEST['checkcode'])) $_REQUEST['checkcode']='';
	$islogin=jieqi_logincheck($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['checkcode'], $_REQUEST['usecookie']);
	if($islogin==0){
		if (empty($_REQUEST['jumpurl'])) {
			if(!empty($_REQUEST['jumpreferer']) && !empty($_SERVER['HTTP_REFERER']) && basename($_SERVER['HTTP_REFERER']) != 'login.php') $_REQUEST['jumpurl']=$_SERVER['HTTP_REFERER'];
			else $_REQUEST['jumpurl']=JIEQI_URL.'/';
		}
		include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		jieqi_logindo($_REQUEST['jumpurl']);
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
			case -9:
			jieqi_printfail($jieqiLang['system']['user_has_denied']);
			break;
			default:
			jieqi_printfail($jieqiLang['system']['login_failure']);
			break;
		}
	}
}else {
	include_once(JIEQI_ROOT_PATH.'/header.php');
	if (!empty($_REQUEST['jumpurl'])) {
		$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/login.php?do=submit&jumpurl='.urlencode($_REQUEST['jumpurl']));
	}elseif (!empty($_REQUEST['forward'])) {
		$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/login.php?do=submit&jumpurl='.urlencode($_REQUEST['forward']));
	}else{
		$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/login.php?do=submit');
	}
	$jieqiTpl->assign('url_register', JIEQI_USER_URL.'/register.php');
	$jieqiTpl->assign('url_getpass', JIEQI_USER_URL.'/getpass.php');
	if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
	else $jieqiTpl->assign('show_checkcode', 0);
	$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/login.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}
?>