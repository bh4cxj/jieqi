<?php 
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

define('JIEQI_MODULE_NAME', 'space');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['space']['manageallblog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('blogset',JIEQI_MODULE_NAME);
if(empty($_REQUEST['uid'])) jieqi_printfail($jieqiLang['space']['blog_not_exists']);
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/blog.php');
$blog_handler = JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');
$blog=$blog_handler->get($_REQUEST['id']);
if(!is_object($blog)) jieqi_printfail($jieqiLang['space']['blog_not_exists']);

switch($_REQUEST['act']){
	case 'comm':
		$blog->setVar('ar_commend',1);
		$blog_handler->insert($blog);
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['blog_comm_success']);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allblog.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['blog_comm_success'] );
		break;		
	case 'nocomm':
		$blog->setVar('ar_commend',0);
		$blog_handler->insert($blog);
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['blog_nocomm_success']);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allblog.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['blog_nocomm_success'] );
		break;					
}
?>