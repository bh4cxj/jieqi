<?php 
/**
 * frame模式的用户登录
 *
 * 通常用frame模式放在网站顶部，或者左右的区块里面，登录后现在用户状态
 * 
 * 调用模板：/templates/loginframe.html;/templates/statusframe.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: loginframe.php 320 2009-01-13 05:51:02Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
if($_REQUEST['action']=='login') define('JIEQI_NEED_SESSION', 1);
require_once('global.php');
jieqi_loadlang('users', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$errstr='';
if(isset($_REQUEST['action']) && $_REQUEST['action']=='login' && !empty($_REQUEST['username']) && !empty($_REQUEST['password']))
{
	$_REQUEST['username']=trim($_REQUEST['username']);
	include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
	if(isset($_REQUEST['usecookie']) && is_numeric($_REQUEST['usecookie'])) $_REQUEST['usecookie']=intval($_REQUEST['usecookie']);
	else $_REQUEST['usecookie']=0;
	
	if(empty($_REQUEST['checkcode'])) $_REQUEST['checkcode']='';
	$islogin=jieqi_logincheck($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['checkcode'], $_REQUEST['usecookie']);
	if($islogin==0){
		$islogin=true;
		include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		jieqi_logindo(JIEQI_URL.'/loginframe.php', false);
	}else{
		//返回 0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空
		//-4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 校验码错误 -8 帐号已经有人登陆
		switch($islogin){
			case -1:
				$errstr=$jieqiLang['system']['login_need_user'];
				break;
			case -2:
				$errstr=$jieqiLang['system']['login_need_password'];
				break;
			case -3:
				$errstr=$jieqiLang['system']['login_need_userpass'];
				break;
			case -4:
				$errstr=$jieqiLang['system']['login_no_user'];
				break;
			case -5:
				$errstr=$jieqiLang['system']['login_error_password'];
				break;
			case -6:
				$errstr=$jieqiLang['system']['login_error_userpass'];
				break;
			case -7:
				$errstr=$jieqiLang['system']['login_error_checkcode'];
				break;
			case -8:
				$errstr=$jieqiLang['system']['login_other_login'];
				break;
			default:
				$errstr=$jieqiLang['system']['login_failure'];
				break;
		}
		$islogin=false;
	}
}elseif($_REQUEST['action']=='logout'){
	include_once(JIEQI_ROOT_PATH.'/include/dologout.php');
	jieqi_dologout();
	$islogin=false;
	include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
	jieqi_logoutdo(JIEQI_URL.'/loginframe.php', false);
}else{
	if($jieqiUsersGroup==JIEQI_GROUP_GUEST){
		$islogin=false;
	}else{
		$islogin=true;
	}
}

include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
$jieqiTpl =& JieqiTpl::getInstance();
if($islogin){
	$jieqiTpl->assign('jieqi_userid', $_SESSION['jieqiUserId']);
	$jieqiTpl->assign('jieqi_username', jieqi_htmlstr($_SESSION['jieqiUserName']));
	$jieqiTpl->assign('jieqi_usergroup', $jieqiGroups[$_SESSION['jieqiUserGroup']]);
	$jieqiTpl->assign('jieqi_groupname', $jieqiGroups[$_SESSION['jieqiUserGroup']]);
	$jieqiTpl->assign('jieqi_honor', $_SESSION['jieqiUserHonor']);
	$jieqiTpl->assign('jieqi_score', $_SESSION['jieqiUserScore']);
	$jieqiTpl->assign('jieqi_experience', $_SESSION['jieqiUserExperience']);
	$jieqiTpl->assign('jieqi_vip', $_SESSION['jieqiUserVip']);
	$jieqiTpl->assign('jieqi_egold', $_SESSION['jieqiUserEgold']);
	if(isset($_SESSION['jieqiNewMessage']) && $_SESSION['jieqiNewMessage']>0){
		$jieqiTpl->assign('jieqi_newmessage', $_SESSION['jieqiNewMessage']);
	}else{
		$jieqiTpl->assign('jieqi_newmessage', 0);
	}

	$jieqiTpl->setCaching(0);
	$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/statusframe.html');
}else{
	if(empty($_REQUEST['username'])) $_REQUEST['username']='';
	$jieqiTpl->assign('username', jieqi_htmlstr($_REQUEST['username']));
	$jieqiTpl->assign('errstr', $errstr);
	if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
	else $jieqiTpl->assign('show_checkcode', 0);
	if(empty($jieqiConfigs['system']['usegd'])){
		$jieqiTpl->assign('usegd', 0);
	}else{
		$jieqiTpl->assign('usegd', 1);
	}
	$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
	$jieqiTpl->setCaching(0);
	$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/loginframe.html');
}
?>