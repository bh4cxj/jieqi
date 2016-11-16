<?php 
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

define('JIEQI_MODULE_NAME', 'space');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['space']['manageallspace'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('spaceset',JIEQI_MODULE_NAME);

if(empty($_REQUEST['uid'])) jieqi_printfail($jieqiLang['space']['space_not_exists']);
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/space.php');
$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
$space=$space_handler->get($_REQUEST['uid']);
if(!is_object($space)) jieqi_printfail($jieqiLang['space']['space_not_exists']);

$space_config_path = jieqi_uploadpath('spaceconfigs',JIEQI_MODULE_NAME);
jieqi_checkdir( $space_config_path.'/basic'.'/'.jieqi_getsubdir($_REQUEST['uid']),1);
jieqi_checkdir($space_config_path.'/blogcat'.'/'.jieqi_getsubdir($_REQUEST['uid']),1);
$space_basic_config_file = $space_config_path.'/basic'.jieqi_getsubdir($_REQUEST['uid']).'/'.$_REQUEST['uid'].'basic.php';


switch($_REQUEST['act']){
	case 'comm':
		$space->setVar('sp_commend',1);
		$space_handler->insert($space);
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['space_comm_success']);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allspace.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['space_comm_success'] );
		break;		
	case 'nocomm':
		$space->setVar('sp_commend',0);
		$space_handler->insert($space);
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['space_nocomm_success']);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allspace.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['space_nocomm_success'] );
		break;			
	case 'open':
		$space->setVar('sp_open',1);
		$space_handler->insert($space);
		@unlink($space_basic_config_file);
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['space_open_success']);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allspace.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['space_open_success'] );
		break;			
	case 'close':
		$space->setVar('sp_open',0);
		$space_handler->insert($space);
		@unlink($space_basic_config_file);
		jieqi_jumppage($jieqiModules['space']['url'].'/admin/allspace.php?page='.$_REQUEST['page'],LANG_DO_SUCCESS,$jieqiLang['space']['space_close_success'] );
		//jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['space']['space_close_success']);
		break;			
}
?>