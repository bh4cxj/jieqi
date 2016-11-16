<?php
/**
 * 用户退出登录处理
 *
 * 用户退出登录处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: dologout.php 320 2009-01-13 05:51:02Z juny $
 */

/**
 * 用户退出登录处理
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_dologout(){
	include_once(JIEQI_ROOT_PATH.'/class/online.php');
	$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
	$criteria = new CriteriaCompo(new Criteria('sid', session_id()));
	$criteria->add(new Criteria('uid', intval($_SESSION['jieqiUserId'])), 'OR');
	$online_handler->delete($criteria);

	header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
	if (!empty($_COOKIE['jieqiUserInfo'])){
		setcookie('jieqiUserInfo', '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
	}
	if (!empty($_COOKIE[session_name()])){
		setcookie(session_name(), '', 0, '/', JIEQI_COOKIE_DOMAIN, 0);
	}
	
	$_SESSION = array();
	@session_destroy();
}
?>