<?php 
/**
 * 用户注册
 *
 * 用户注册页面
 * 
 * 调用模板：/templates/register.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: register.php 326 2009-02-04 00:26:22Z juny $
 */


define('JIEQI_MODULE_NAME', 'system');
if(isset($_REQUEST['action']) && $_REQUEST['action']=='newuser') define('JIEQI_NEED_SESSION', 1);
require_once('global.php');
//<!--jieqi insert check code-->
//if(JIEQI_LOCAL_URL != JIEQI_USER_URL) header('Location: '.JIEQI_USER_URL.jieqi_addurlvars(array()));
jieqi_loadlang('users', JIEQI_MODULE_NAME);
//是否允许注册
if (!defined("JIEQI_ALLOW_REGISTER") || JIEQI_ALLOW_REGISTER != 1) {
	jieqi_printfail($jieqiLang['system']['user_stop_register']);
	exit();
}


if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'register';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
switch ($_REQUEST['action']) {
	case 'newuser':
		//同一个IP重复注册时间限制
		$jieqiConfigs['system']['regtimelimit']=intval($jieqiConfigs['system']['regtimelimit']);
		if($jieqiConfigs['system']['regtimelimit']>0){
			$ip=jieqi_userip();
			jieqi_includedb();
			$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			$sql="SELECT * FROM ".jieqi_dbprefix('system_registerip')." WHERE ip='".jieqi_dbslashes($ip)."' AND regtime>".(JIEQI_NOW_TIME - $jieqiConfigs['system']['regtimelimit'] * 3600)." LIMIT 0,1";
			$res=$query->execute($sql);
			if($query->getRow()){
				jieqi_printfail(sprintf($jieqiLang['system']['user_register_timelimit'], $jieqiConfigs['system']['regtimelimit']));
			}
		}

		//$_REQUEST['username'] = strtolower(trim($_REQUEST['username']));
		$_REQUEST['username'] = trim($_REQUEST['username']);
		$_REQUEST['email'] = trim($_REQUEST['email']);
		$_REQUEST['password'] = trim($_REQUEST['password']);
		$_REQUEST['repassword'] = trim($_REQUEST['repassword']);
		if(empty($_REQUEST['checkcode'])) $_REQUEST['checkcode']='';
		else $_REQUEST['checkcode'] = trim($_REQUEST['checkcode']);
		
		$errtext='';
		include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		//检查用户名格式
		if (strlen($_REQUEST['username'])==0) $errtext .= $jieqiLang['system']['need_username'].'<br />';
		//elseif (!jieqi_safestring($_REQUEST['username'])) $errtext .= $jieqiLang['system']['error_user_format'].'<br />';
		elseif(preg_match('/^\s*$|^c:\\con\\con$|[%,;\|\*\"\'\\\\\/\s\t\<\>\&]/is', $_REQUEST['username'])) $errtext .= $jieqiLang['system']['error_user_format'].'<br />';
		elseif (strpos($_REQUEST['username'], '　') !== false) $errtext .= $jieqiLang['system']['error_user_format'].'<br />';
		elseif($jieqiConfigs['system']['usernamelimit']==1 && !preg_match('/^[A-Za-z0-9]+$/',$_REQUEST['username'])) $errtext .= $jieqiLang['system']['username_need_engnum'].'<br />';

		//检查Email格式
		if (strlen($_REQUEST['email'])==0) $errtext .= $jieqiLang['system']['need_email'].'<br />';
		elseif (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$_REQUEST['email'])) $errtext .= $jieqiLang['system']['error_email_format'].'<br />';

		//检查密码
		if (strlen($_REQUEST['password'])==0 || strlen($_REQUEST['repassword'])==0) $errtext .= $jieqiLang['system']['need_pass_repass'].'<br />';
		elseif ($_REQUEST['password'] != $_REQUEST['repassword']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';

		//检查用户名是否已注册
		if($users_handler->getByname($_REQUEST['username'], 3) != false) $errtext .= $jieqiLang['system']['user_has_registered'].'<br />';
		//检查Email是否已注册
		if($users_handler->getCount(new Criteria('email', $_REQUEST['email'], '=')) > 0) $errtext .= $jieqiLang['system']['email_has_registered'].'<br />';
		//检查验证码
		if(!empty($jieqiConfigs['system']['checkcodelogin']) && $_REQUEST['checkcode'] != $_SESSION['jieqiCheckCode']) $errtext .= $jieqiLang['system']['error_checkcode'].'<br />';

		//记录注册信息
		if(empty($errtext)) {
			$newUser = $users_handler->create();
			$newUser->setVar('siteid', JIEQI_SITE_ID);
			$newUser->setVar('uname', $_REQUEST['username']);
			$newUser->setVar('name', $_REQUEST['username']);
			$newUser->setVar('pass', $users_handler->encryptPass($_REQUEST['password']));
			$newUser->setVar('groupid', JIEQI_GROUP_USER);
			$newUser->setVar('regdate', JIEQI_NOW_TIME);
			$newUser->setVar('initial', jieqi_getinitial($_REQUEST['username']));
			$newUser->setVar('sex', $_REQUEST['sex']);
			$newUser->setVar('email', $_REQUEST['email']);
			$newUser->setVar('url', $_REQUEST['url']);
			$newUser->setVar('avatar', 0);
			$newUser->setVar('workid', 0);
			$newUser->setVar('qq', $_REQUEST['qq']);
			$newUser->setVar('icq', '');
			$newUser->setVar('msn', $_REQUEST['msn']);
			$newUser->setVar('mobile', '');
			$newUser->setVar('sign', '');
			$newUser->setVar('intro', '');
			$newUser->setVar('setting', '');
			$newUser->setVar('badges', '');
			$newUser->setVar('lastlogin', JIEQI_NOW_TIME);
			$newUser->setVar('showsign', 0);
			$newUser->setVar('viewemail', $_REQUEST['viewemail']);
			$newUser->setVar('notifymode', 0);
			$newUser->setVar('adminemail', $_REQUEST['adminemail']);
			$newUser->setVar('monthscore', 0);
			$newUser->setVar('experience', $jieqiConfigs['system']['scoreregister']);
			$newUser->setVar('score', $jieqiConfigs['system']['scoreregister']);
			$newUser->setVar('egold', 0);
			$newUser->setVar('esilver', 0);
			$newUser->setVar('credit', 0);
			$newUser->setVar('goodnum', 0);
			$newUser->setVar('badnum', 0);
			$newUser->setVar('isvip', 0);
			$newUser->setVar('overtime', 0);
			$newUser->setVar('state', 0);
			if (!$users_handler->insert($newUser)) jieqi_printfail($jieqiLang['system']['register_failure']);
			else {
				//自动登录
				//记录注册时间IP
				if($jieqiConfigs['system']['regtimelimit']>0){
					$sql="DELETE FROM ".jieqi_dbprefix('system_registerip')." WHERE regtime<".(JIEQI_NOW_TIME - ($jieqiConfigs['system']['regtimelimit'] > 72 ? $jieqiConfigs['system']['regtimelimit'] : 72) * 3600);
					$query->execute($sql);
					$sql="INSERT INTO ".jieqi_dbprefix('system_registerip')." (ip, regtime, count) VALUES ('".jieqi_dbslashes($ip)."', '".JIEQI_NOW_TIME."', '0')";
					$query->execute($sql);
				}

				//更新在线用户表
				include_once(JIEQI_ROOT_PATH.'/class/online.php');
				$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
				include_once(JIEQI_ROOT_PATH.'/include/visitorinfo.php');
				$online = $online_handler->create();
				$online->setVar('uid', $newUser->getVar('uid', 'n'));
				$online->setVar('siteid', JIEQI_SITE_ID);
				$online->setVar('sid', session_id());
				$online->setVar('uname', $newUser->getVar('uname', 'n'));
				$tmpvar = strlen($newUser->getVar('name', 'n')) > 0 ? $newUser->getVar('name', 'n') : $newUser->getVar('uname', 'n');
				$online->setVar('name', $tmpvar);
				$online->setVar('pass', $newUser->getVar('pass', 'n'));
				$online->setVar('email', $newUser->getVar('email', 'n'));
				$online->setVar('groupid', $newUser->getVar('groupid', 'n'));
				$tmpvar=JIEQI_NOW_TIME;
				$online->setVar('logintime', $tmpvar);
				$online->setVar('updatetime', $tmpvar);
				$online->setVar('operate', '');
				$tmpvar=VisitorInfo::getIp();
				$online->setVar('ip', $tmpvar);
				$online->setVar('browser', VisitorInfo::getBrowser());
				$online->setVar('os', VisitorInfo::getOS());
				$location=VisitorInfo::getIpLocation($tmpvar);
				if(JIEQI_SYSTEM_CHARSET == 'big5'){
					include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
					$location=jieqi_gb2big5($location);
				}
				$online->setVar('location', $location);
				$online->setVar('state', '0');
				$online->setVar('flag', '0');
				$online_handler->insert($online);

				//设置SESSION
				jieqi_setusersession($newUser);

				//设置COOKIE
				$jieqi_user_info['jieqiUserId']=$_SESSION['jieqiUserId'];
				$jieqi_user_info['jieqiUserName']=$_SESSION['jieqiUserName'];
				$jieqi_user_info['jieqiUserGroup']=$_SESSION['jieqiUserGroup'];

				include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
				if(JIEQI_SYSTEM_CHARSET == 'gbk') $jieqi_user_info['jieqiUserName_un']=jieqi_gb2unicode($_SESSION['jieqiUserName']);
				else $jieqi_user_info['jieqiUserName_un']=jieqi_big52unicode($_SESSION['jieqiUserName']);
				$jieqi_user_info['jieqiUserLogin']=JIEQI_NOW_TIME;
				$cookietime=0;
				@setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
				$jieqi_visit_info['jieqiUserLogin']=$jieqi_user_info['jieqiUserLogin'];
				$jieqi_visit_info['jieqiUserId']=$jieqi_user_info['jieqiUserId'];
				@setcookie('jieqiVisitInfo', jieqi_sarytostr($jieqi_visit_info), JIEQI_NOW_TIME+99999999, '/',  JIEQI_COOKIE_DOMAIN, 0);

				//推广积分
				if(JIEQI_PROMOTION_REGISTER > 0 && !empty($_COOKIE['jieqiPromotion'])){
					$users_handler->changeCredit(intval($_COOKIE['jieqiPromotion']), intval(JIEQI_PROMOTION_REGISTER), true);
					setcookie('jieqiPromotion', '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
				}

				include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
				jieqi_registerdo(JIEQI_URL.'/');
			}
		} else {
			jieqi_printfail($errtext);
		}
		break;
	case 'register':
	default:
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('form_action', JIEQI_USER_URL.'/register.php');
		$jieqiTpl->assign('check_url', JIEQI_USER_URL.'/regcheck.php');

		if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
		else $jieqiTpl->assign('show_checkcode', 0);
		
		$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/register.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>