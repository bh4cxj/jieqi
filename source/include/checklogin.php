<?php
/**
 * 检查用户登录
 *
 * 验证登录账号、密码、验证码等
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: checklogin.php 324 2009-01-20 04:47:10Z juny $
 */

/**
 * 验证登录账号、密码、验证码，同过的话进行登录处理
 * 
 * @param      string      $username 用户名
 * @param      string      $password 密码
 * @param      string      $checkcode 验证码
 * @param      int         $usecookie 是否记录到cookie，下次自动登录。0表示不记录，大于0表示cookie保存时间
 * @param      bool        $encode 密码是否已经加密，默认否
 * @param      bool        $needcheck 是否需要验证码，默认是
 * @access     public
 * @return     int         0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空 -4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 验证码错误 -8 帐号已经有人登陆 -9 用户属于游客组
 */
function jieqi_logincheck($username='', $password='', $checkcode='', $usecookie=0, $encode=false, $needcheck=true){
	$ret = jieqi_loginpass($username, $password, $checkcode, $usecookie, $encode, $needcheck);
	if(is_object($ret)){
		return jieqi_loginprocess($ret, $usecookie);
	}elseif($ret == -10){
		//临时用户，未设置密码，ucenter存在时候自动更新密码，否则返回密码错误
		include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
		if(function_exists('uc_user_login')){
			list($uid, $uname, $upass, $uemail) = uc_user_login($username, $password);
			if($uid > 0){
				include_once(JIEQI_ROOT_PATH.'/class/users.php');
				$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
				$userobj = $users_handler->getByname($username);
				if(is_object($userobj)){
					$userobj->setVar('pass', $users_handler->encryptPass($upass));
					$userobj->setVar('email', $uemail);
					$users_handler->insert($userobj);
					return jieqi_loginprocess($userobj, $usecookie);
				}
			}
		}
		return -5;
	}else{
		return $ret;
	}
}

/**
 * 仅验证登录账号、密码、验证码，返回是否验证通过信息
 * 
 * @param      string      $username 用户名
 * @param      string      $password 密码
 * @param      string      $checkcode 验证码
 * @param      int         $usecookie 是否记录到cookie，下次自动登录。0表示不记录，大于0表示cookie保存时间
 * @param      bool        $encode 密码是否已经加密，默认否
 * @param      bool        $needcheck 是否需要验证码，默认是
 * @access     public
 * @return     int         0 正常, -1 用户名为空 -2 密码为空 -3 用户名或者密码为空 -4 用户名不存在 -5 密码错误 -6 用户名或密码错误 -7 验证码错误 -8 帐号已经有人登陆 -9 用户属于游客组 -10 未设置密码
 */
function jieqi_loginpass($username='', $password='', $checkcode='', $usecookie=0, $encode=false, $needcheck=true){
	global $jieqiConfigs;
	global $jieqiHonors;
	global $jieqiGroups;
	if(empty($username) || empty($password)) return -3;
	
	if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
	//检查验证码
	if(!empty($jieqiConfigs['system']['checkcodelogin']) && $needcheck){
	    if(empty($checkcode) || $checkcode != $_SESSION['jieqiCheckCode'])	return -7;
	}
	//检查用户名和密码
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$criteria = new CriteriaCompo(new Criteria('uname', $username));
	$users_handler->queryObjects($criteria);
	$jieqiUsers=$users_handler->getObject();
	if (!$jieqiUsers){
		return -4;
	}
	$truepass = $jieqiUsers->getVar('pass', 'n');
	if($truepass == '') return -10;
	if($encode) $encpass=$password;
	else $encpass=$users_handler->encryptPass($password);
	if($truepass != $encpass){
		return -5;
	}
	
	if($jieqiUsers->getVar('groupid', 'n') == JIEQI_GROUP_GUEST){
		return -9;
	}
	
	return $jieqiUsers;
}

/**
 * 用户登录后处理
 * 
 * @param      object      $jieqiUsers 用户对象
 * @access     public
 * @return     bool
 */
function jieqi_loginprocess($jieqiUsers, $usecookie = 0){
	global $jieqiConfigs;
	global $jieqiHonors;
	global $jieqiGroups;
	
	if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	//更新在线用户表
	include_once(JIEQI_ROOT_PATH.'/class/online.php');
	$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
	$criteria = new CriteriaCompo(new Criteria('uid', $jieqiUsers->getVar('uid', 'n')));
	$criteria->setSort('updatetime');
	$criteria->setOrder('DESC');
	$online_handler->queryObjects($criteria);
	$online=$online_handler->getObject();
	
	//读cookie信息
	$jieqi_user_info=array();
	if(!empty($_COOKIE['jieqiUserInfo'])) $jieqi_user_info=jieqi_strtosary($_COOKIE['jieqiUserInfo']);
	else $jieqi_user_info=array();
	$jieqi_visit_info=array();
	if(!empty($_COOKIE['jieqiVisitInfo'])) $jieqi_visit_info=jieqi_strtosary($_COOKIE['jieqiVisitInfo']);
	else $jieqi_visit_info=array();
	
	
	if(is_object($online)){
		$ip=jieqi_userip();
		if (JIEQI_SESSION_EXPRIE > 0) $exprie_time=JIEQI_SESSION_EXPRIE;
		else $exprie_time=@ini_get('session.gc_maxlifetime');
		if(empty($exprie_time)) $exprie_time=1800;
		if(defined('JIEQI_DENY_RELOGIN') && JIEQI_DENY_RELOGIN==1 && JIEQI_NOW_TIME - $online->getVar('updatetime') < $exprie_time && $online->getVar('ip', 'n') != $ip && $jieqi_visit_info['jieqiUserId'] != $jieqiUsers->getVar('uid')){
			return -8;
		}
		$tmpvar = strlen($jieqiUsers->getVar('name', 'q')) > 0 ? $jieqiUsers->getVar('name', 'q') : $jieqiUsers->getVar('uname', 'q');
		$sql="UPDATE ".jieqi_dbprefix('system_online')." SET uid=".$jieqiUsers->getVar('uid', 'q').", sid='".jieqi_dbslashes(session_id())."', uname='".$jieqiUsers->getVar('uname', 'q')."', name='".$tmpvar."', pass='".$jieqiUsers->getVar('pass', 'q')."',email='".$jieqiUsers->getVar('email', 'q')."', groupid=".$jieqiUsers->getVar('groupid', 'q').", updatetime=".JIEQI_NOW_TIME.", ip='".jieqi_dbslashes($ip)."' WHERE uid=".$jieqiUsers->getVar('uid', 'q')." OR sid='".jieqi_dbslashes(session_id())."'";
		$online_handler->db->query($sql);
	}else{
		include_once(JIEQI_ROOT_PATH.'/include/visitorinfo.php');
        $online = $online_handler->create();
        $online->setVar('uid', $jieqiUsers->getVar('uid', 'n'));
        $online->setVar('siteid', JIEQI_SITE_ID);
        $online->setVar('sid', session_id());
		$online->setVar('uname', $jieqiUsers->getVar('uname', 'n'));
		$tmpvar = strlen($jieqiUsers->getVar('name', 'n')) > 0 ? $jieqiUsers->getVar('name', 'n') : $jieqiUsers->getVar('uname', 'n');
		$online->setVar('name', $tmpvar);
		$online->setVar('pass', $jieqiUsers->getVar('pass', 'n'));
		$online->setVar('email', $jieqiUsers->getVar('email', 'n'));
		$online->setVar('groupid', $jieqiUsers->getVar('groupid', 'n'));
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
	}
	//删除过期的在线用户
	unset($criteria);
	$criteria = new CriteriaCompo(new Criteria('updatetime', JIEQI_NOW_TIME-$jieqiConfigs['system']['onlinetime'], '<'));
	$online_handler->delete($criteria);
	
    //检查短消息
    include_once(JIEQI_ROOT_PATH.'/class/message.php');
    $message_handler=JieqiMessageHandler::getInstance('JieqiMessageHandler');
    $criteria=new CriteriaCompo(new Criteria('toid', $jieqiUsers->getVar('uid'), '='));
    $criteria->add(new Criteria('isread', 0, '='));
    $criteria->add(new Criteria('todel', 0, '='));
    $newmsgnum=$message_handler->getCount($criteria);
    unset($criteria);
    //有短消息
    
	//用户信息
	$previewlogin=intval($jieqiUsers->getVar('lastlogin'));
	$jieqiUsers->setVar('lastlogin', JIEQI_NOW_TIME);
	$userset=unserialize($jieqiUsers->getVar('setting','n'));
	if(!isset($userset['lastip']) || $userset['lastip'] != jieqi_userip()) $userset['lastip'] = jieqi_userip();
	if(!isset($userset['logindate']) || $userset['logindate'] != date('Y-m-d')){
		$userset['logindate']=date('Y-m-d');
	    //增加登陆积分
	    $jieqiUsers->setVar('experience', $jieqiUsers->getVar('experience')+$jieqiConfigs['system']['scorelogin']);
	    $jieqiUsers->setVar('score', $jieqiUsers->getVar('score')+$jieqiConfigs['system']['scorelogin']);
	}
	//如果换月了，清空月积分
	//if(date('Y-m', $previewlogin) != date('Y-m', JIEQI_NOW_TIME)) $jieqiUsers->setVar('monthscore', 0);
	$jieqiUsers->setVar('setting', serialize($userset));
	$users_handler->insert($jieqiUsers);
	
	header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	//设置SESSION
	jieqi_setusersession($jieqiUsers);
	
	if($newmsgnum > 0) $_SESSION['jieqiNewMessage'] = $newmsgnum;
	//后台登录状态
	$jieqi_online_info = empty($_COOKIE['jieqiOnlineInfo']) ? array() : jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
	if(isset($jieqi_online_info['jieqiAdminLogin']) && $jieqi_online_info['jieqiAdminLogin'] == 1) $_SESSION['jieqiAdminLogin'] = 1;

	$jieqi_user_info['jieqiUserId']=$_SESSION['jieqiUserId'];
	$jieqi_user_info['jieqiUserName']=$_SESSION['jieqiUserUname'];
	
	$jieqi_user_info['jieqiUserGroup']=$_SESSION['jieqiUserGroup'];
	$jieqi_user_info['jieqiUserVip']=$_SESSION['jieqiUserVip'];
	if($newmsgnum > 0) $jieqi_user_info['jieqiNewMessage']=$newmsgnum;
	if($usecookie) $jieqi_user_info['jieqiUserPassword']=$encpass;
	include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
	
	if(JIEQI_SYSTEM_CHARSET == 'gbk'){
		$jieqi_user_info['jieqiUserName_un']=jieqi_gb2unicode($_SESSION['jieqiUserUname']);
		$jieqi_user_info['jieqiUserHonor_un']=jieqi_gb2unicode($_SESSION['jieqiUserHonor']);
		$jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
	}else{
		$jieqi_user_info['jieqiUserName_un']=jieqi_big52unicode($_SESSION['jieqiUserUname']);
		$jieqi_user_info['jieqiUserHonor_un']=jieqi_big52unicode($_SESSION['jieqiUserHonor']);
		$jieqi_user_info['jieqiUserGroupName_un']=jieqi_gb2unicode($jieqiGroups[$_SESSION['jieqiUserGroup']]);
	}
	$jieqi_user_info['jieqiUserLogin']=JIEQI_NOW_TIME;
	if($usecookie < 0) $usecookie=0;
	elseif($usecookie == 1) $usecookie=315360000;
	if($usecookie) $cookietime=JIEQI_NOW_TIME + $usecookie;
	else $cookietime=0; 
	@setcookie('jieqiUserInfo', jieqi_sarytostr($jieqi_user_info), $cookietime, '/',  JIEQI_COOKIE_DOMAIN, 0);
	$jieqi_visit_info['jieqiUserLogin']=$jieqi_user_info['jieqiUserLogin'];
	$jieqi_visit_info['jieqiUserId']=$jieqi_user_info['jieqiUserId'];
	@setcookie('jieqiVisitInfo', jieqi_sarytostr($jieqi_visit_info), JIEQI_NOW_TIME+99999999, '/',  JIEQI_COOKIE_DOMAIN, 0);
	
	//更新在线用户
	include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
    $jieqiTpl =& JieqiTpl::getInstance();
    $jieqiTpl->clear_cache(JIEQI_ROOT_PATH.'/templates/online.html');
    
	return 0;
}
?>