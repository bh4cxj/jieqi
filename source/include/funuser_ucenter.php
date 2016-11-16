<?php 
/**
 * ucenter1.5接口-用户注册、登录、退出处理
 *
 * 使用ucenter接口时候，把本文件改成 funuser.php
 * 配置好 /uc_client/config.inc.php 中的参数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funuser_ucenter.php 317 2009-01-06 09:03:33Z juny $
 */

include_once(JIEQI_ROOT_PATH.'/uc_client/config.inc.php');
include_once(JIEQI_ROOT_PATH.'/uc_client/client.php');

//用户注册后的附加处理
function jieqi_registerdo($gourl){
	global $jieqiLang;
	if(strpos($gourl, 'http') === false){
		if($_SERVER['HTTP_HOST'] != '') $gourl='http://'.$_SERVER['HTTP_HOST'].$gourl;
		else $gourl=JIEQI_URL.$gourl;
	}

	$uid = uc_user_register($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email']);
	/*
	if($uid <= 0) {
	if($uid == -1) {
	echo '用户名不合法';
	} elseif($uid == -2) {
	echo '包含要允许注册的词语';
	} elseif($uid == -3) {
	echo '用户名已经存在';
	} elseif($uid == -4) {
	echo 'Email 格式有误';
	} elseif($uid == -5) {
	echo 'Email 不允许注册';
	} elseif($uid == -6) {
	echo '该 Email 已经被注册';
	} else {
	echo '未定义';
	}
	}
	*/
	if($uid > 0) $ucsynlogin = uc_user_synlogin($uid);
	else $ucsynlogin = '';
	jieqi_jumppage($gourl, $jieqiLang['system']['registered_title'], $jieqiLang['system']['register_success'].$ucsynlogin);
}

//用户登陆后的附加处理
function jieqi_logindo($gourl){
	global $jieqiLang;
	if(strpos($gourl, 'http') === false){
		if($_SERVER['HTTP_HOST'] != '') $gourl='http://'.$_SERVER['HTTP_HOST'].$gourl;
		else $gourl=JIEQI_URL.$gourl;
	}
	if($_SESSION['jieqiUserGroup']==JIEQI_GROUP_ADMIN) $isadmin=1;
	else $isadmin=0;

	//通过接口判断登录帐号的正确性，返回值为数组
	list($uid, $username, $password, $email) = uc_user_login($_REQUEST['username'], $_REQUEST['password']);
	if($uid == -1){
		$uid = uc_user_register($_REQUEST['username'], $_REQUEST['password'], $_SESSION['jieqiUserEmail']);
	}
/*
	if($uid > 0) {
	//生成同步登录的代码
	$ucsynlogin = uc_user_synlogin($uid);
	echo '登录成功'.$ucsynlogin.'<br><a href="'.$_SERVER['PHP_SELF'].'">继续</a>';
	exit;
	} elseif($uid == -1) {
	echo '用户不存在,或者被删除';
	} elseif($uid == -2) {
	echo '密码错';
	} else {
	echo '未定义';
	}
*/
	if($uid > 0) $ucsynlogin = uc_user_synlogin($uid);
	else $ucsynlogin = '';
	jieqi_jumppage($gourl,$jieqiLang['system']['logon_title'], sprintf($jieqiLang['system']['login_success'], jieqi_htmlstr($_REQUEST['username'])).$ucsynlogin);
}

//用户退出后的附加处理
function jieqi_logoutdo($gourl){
	global $jieqiLang;
	if(strpos($gourl, 'http') === false){
		if($_SERVER['HTTP_HOST'] != '') $gourl='http://'.$_SERVER['HTTP_HOST'].$gourl;
		else $gourl=JIEQI_URL.$gourl;
	}
	//生成同步退出的代码
	$ucsynlogout = uc_user_synlogout();
	jieqi_jumppage($gourl, $jieqiLang['system']['logout_title'], $jieqiLang['system']['logout_success'].$ucsynlogout);
}
?>