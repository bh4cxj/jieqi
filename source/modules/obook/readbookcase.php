<?php 
/**
 * 阅读书架中的电子书
 *
 * 记录阅读标志，跳转到阅读页面
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: readbookcase.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['oid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['oid']=intval($_REQUEST['oid']);
//更新书架的最后访问
if(!empty($_REQUEST['bid'])){
	$_REQUEST['bid']=intval($_REQUEST['bid']);
	include_once($jieqiModules['obook']['path'].'/class/obookcase.php');
	$obookcase_handler =& JieqiObookcaseHandler::getInstance('JieqiObookcaseHandler');
	$obookcase_handler->db->query('UPDATE '.jieqi_dbprefix('obook_obookcase').' SET lastvisit='.JIEQI_NOW_TIME.' WHERE ocaseid='.$_REQUEST['bid']);
}
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
	$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
if(!empty($_REQUEST['cid'])){
	header('Location: '.$obook_static_url.'/reader.php?oid='.$_REQUEST['oid'].'&cid='.$_REQUEST['cid']);
	exit;
}else{
	if(empty($indexflag)){
		if($jieqiConfigs['obook']['fakeinfo']==1){
			if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
			else $tmpvar='/files/obook/info';
			header('Location: '.$obook_dynamic_url.$tmpvar.jieqi_getsubdir($_REQUEST['oid']).'/'.$_REQUEST['oid'].$jieqiConfigs['obook']['fakefile']);
		}else{
			header('Location: '.$obook_dynamic_url.'/obookinfo.php?id='.$_REQUEST['oid']);
		}
	}else{
		header('Location: '.$obook_static_url.'/reader.php?oid='.$_REQUEST['oid']);
	}
	exit;
}
?>