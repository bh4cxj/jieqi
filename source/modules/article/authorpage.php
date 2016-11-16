<?php 
/**
 * 作者专栏
 *
 * 显示一个作者的所有文章。会客室留言等
 * 
 * 调用模板：/templates/userpage.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: authorpage.php 337 2009-03-07 00:51:05Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['id']) && empty($_REQUEST['name'])) jieqi_printfail(LANG_NO_USER);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['id'])){
	$userobj=$users_handler->get($_REQUEST['id']);
}else{
	$_REQUEST['name']=trim($_REQUEST['name']);
	$userobj=$users_handler->getByname($_REQUEST['name']);
}
if(is_object($userobj)){
	$_REQUEST['uid'] = $userobj->getVar('uid', 'n');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userpage.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	jieqi_printfail(LANG_NO_USER);
}
?>