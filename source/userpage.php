<?php 
/**
 * 用户专栏页
 *
 * 用户专栏页
 * 
 * 调用模板：/templates/userpage.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userpage.php 333 2009-02-24 07:28:30Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
if(empty($_REQUEST['uid']) && empty($_REQUEST['username'])) jieqi_printfail(LANG_NO_USER);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['uid'])){
	$userobj=$users_handler->get($_REQUEST['uid']);
}else{
	$_REQUEST['username']=trim($_REQUEST['username']);
	$userobj=$users_handler->getByname($_REQUEST['username']);
}
if(is_object($userobj)){
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userpage.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	jieqi_printfail(LANG_NO_USER);
}
?>