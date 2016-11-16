<?php 
/**
 * 用户注册、登录、退出相关处理函数
 *
 * 用户注册、登录、退出相关处理函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funuser.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * 用户注册后的附加处理
 * 
 * @param      string      $gourl 处理后跳转的页面
 * @param      bool        $jump 是否显示跳转界面，默认是
 * @access     public
 * @return     void
 */
function jieqi_registerdo($gourl, $jump=true){
	global $jieqiLang;
	if($jump) jieqi_jumppage($gourl, $jieqiLang['system']['registered_title'], $jieqiLang['system']['register_success']);
	else header('Location: '.$gourl);
}

/**
 * 用户登录后的附加处理
 * 
 * @param      string      $gourl 处理后跳转的页面
 * @param      bool        $jump 是否显示跳转界面，默认是
 * @access     public
 * @return     void
 */
function jieqi_logindo($gourl, $jump=true){
	global $jieqiLang;
	if($jump) jieqi_jumppage($gourl, $jieqiLang['system']['logon_title'], sprintf($jieqiLang['system']['login_success'], jieqi_htmlstr($_REQUEST['username'])));
	else header('Location: '.$gourl);
}

/**
 * 用户退出后的附加处理
 * 
 * @param      string      $gourl 处理后跳转的页面
 * @param      bool        $jump 是否显示跳转界面，默认是
 * @access     public
 * @return     void
 */
function jieqi_logoutdo($gourl, $jump=true){
	global $jieqiLang;
	if($jump) jieqi_jumppage($gourl, $jieqiLang['system']['logout_title'], $jieqiLang['system']['logout_success']);
	else header('Location: '.$gourl);
}
?>