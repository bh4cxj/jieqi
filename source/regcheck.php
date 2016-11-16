<?php 
/**
 * ajax的注册信息校验
 *
 * 注册页面，输入用户名或者email等信息后，ajax提交到本页面检测是否允许
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: regcheck.php 286 2008-12-23 03:04:17Z juny $
 */
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
header('Content-Type:text/html;charset='.JIEQI_CHAR_SET);
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
include_once(JIEQI_ROOT_PATH.'/class/users.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_loadlang('users', JIEQI_MODULE_NAME);

$users_handler=&JieqiUsersHandler::getInstance('JieqiUsersHandler');
$imageright=sprintf($jieqiLang['system']['register_check_right'], JIEQI_URL);
$imageerror=sprintf($jieqiLang['system']['register_check_error'], JIEQI_URL);
switch($_GET['item']){
	case 'u': {
		//检查用户名
		if(strlen($_GET['username'])==0){//是否为空
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['need_username'];
		}elseif(!jieqi_safestring($_GET['username'])){//是否安全字符
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['error_user_format'];
		}elseif(strpos($_GET['username'], '　') !== false){//是否包含空格
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['error_user_format'];
		}elseif($jieqiConfigs[JIEQI_MODULE_NAME]['usernamelimit']==1 && !preg_match('/^[A-Za-z0-9]+$/',$_GET['username'])){//是否允许中文
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['username_need_engnum'];
		}elseif($users_handler->getCount(new Criteria('uname', $_GET['username'], '='))>0){//是否已注册
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['user_has_registered'];
		}else{
			$htmlstring=$imageright;//合法的用户名
		}
		echo $htmlstring;
		break;
	}
	case 'p': {
		//检查密码
		if(strlen($_GET['password'])==0){//是否为空
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['need_pass_repass'];
		}else{
			$htmlstring=$imageright;
		}
		echo $htmlstring;
		break;
	}
	case 'r': {
		//检查重复密码
		if(strlen($_GET['password'])==0 || strlen($_GET['repassword'])==0){//是否为空
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['need_pass_repass'];
		}elseif($_GET['password']!=$_GET['repassword']){//是否相等
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['password_not_equal'];
		}else{
			$htmlstring=$imageright;
		}
		echo $htmlstring;
		break;
	}
	case 'm': {
		//检查Email格式
		if(strlen($_GET['email'])==0){//是否为空
			$htmlstring=$imageerror.$jieqiLang['system']['need_email'];
		}elseif(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$_GET['email'])){
			$htmlstring=$imageerror.$jieqiLang['system']['error_email_format'];//是否合法格式
		}elseif($users_handler->getCount(new Criteria('email', $_GET['email'], '='))>0){//是否已注册
			$htmlstring=$imageerror.$jieqiLang[JIEQI_MODULE_NAME]['email_has_registered'];
		}else{
			$htmlstring=$imageright;//合法Email地址
		}
		echo $htmlstring;
		break;
	}
	default:
}
?>