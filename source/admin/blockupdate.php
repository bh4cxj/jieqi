<?php
/**
 * 刷新区块缓存
 *
 * 强制更新区块的缓存
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blockupdate.php 176 2008-11-24 08:04:58Z juny $
 */

if(empty($_GET['mod'])) $_GET['mod']='system';
define('JIEQI_MODULE_NAME', $_GET['mod']);
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars($_GET['mod']);
jieqi_checkpower($jieqiPower[$_GET['mod']]['adminblock'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
//载入语言
jieqi_loadlang('blocks', JIEQI_MODULE_NAME);
//取得设置
if(!empty($_REQUEST['id'])){
	include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
	$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
	$block=$blocks_handler->get($_REQUEST['id']);
	if(!is_object($block)) jieqi_printfail($jieqiLang['system']['block_not_exists']);
	$blockSet=array('bid'=>$block->getVar('bid'), 'blockname'=>$block->getVar('blockname'), 'module'=>$block->getVar('modname'), 'filename'=>$block->getVar('filename', 'n'), 'classname'=>$block->getVar('classname', 'n'), 'side'=>$block->getVar('side', 'n'), 'title'=>$block->getVar('title', 'n'), 'vars'=>$block->getVar('vars', 'n'), 'template'=>$block->getVar('template', 'n'), 'contenttype'=>$block->getVar('contenttype', 'n'), 'custom'=>$block->getVar('custom', 'n'), 'publish'=>$block->getVar('publish', 'n'), 'hasvars'=>$block->getVar('hasvars', 'n'));
}elseif(!empty($_REQUEST['configid'])){
	jieqi_includedb();
	$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$_REQUEST['configid']=intval($_REQUEST['configid']);
	$sql='select * from '.jieqi_dbprefix('system_blockconfigs').' where id = \''.jieqi_dbslashes($_REQUEST['configid']).'\'';
	$res=$query->execute($sql);
	$modconfig = $query->getObject($res);
	if(!is_object($modconfig)) jieqi_printfail($jieqiLang['system']['block_config_notexists']);
	unset($jieqiBlocks);
	jieqi_getconfigs($modconfig->getVar('modules', 'n'), $modconfig->getVar('file', 'n'),'jieqiBlocks');
	if(!isset($jieqiBlocks[$_REQUEST['key']])) jieqi_printfail($jieqiLang['system']['block_not_exists']);
	$blockSet=$jieqiBlocks[$_REQUEST['key']];
}else{
	jieqi_printfail(LANG_ERROR_PARAMETER);
}


$modname=$blockSet['module'];
if($modname=='system'){
	include(JIEQI_ROOT_PATH.'/blocks/'.$blockSet['filename'].'.php');
}else{
	include($jieqiModules[$modname]['path'].'/blocks/'.$blockSet['filename'].'.php');
}
$classname=$blockSet['classname'];
include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
$jieqiTpl =& JieqiTpl::getInstance();
$cblock=new $classname($blockSet);
$cblock->updateContent();
jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['system']['block_edit_success'], jieqi_htmlstr($blockSet['blockname'])));
?>