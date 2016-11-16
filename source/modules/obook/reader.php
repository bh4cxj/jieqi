<?php 
/**
 * 电子书阅读
 *
 * 电子书阅读
 * 
 * 调用模板：/modules/obook/templates/reader.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: reader.php 231 2008-11-27 08:46:26Z juny $
 */

$logstart = explode(' ', microtime());
define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_checklogin();
if(empty($_REQUEST['cid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['cid']=intval($_REQUEST['cid']);
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqiConfigs['obook']['obkpictxt'] = '20000'; //一张图片显示多少字节
$jieqiConfigs['obook']['obkpictxt'] = intval($jieqiConfigs['obook']['obkpictxt']);
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
jieqi_includedb();
$obook_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('c.ochapterid', $_REQUEST['cid']));
$criteria->setTables(jieqi_dbprefix('obook_ochapter').' c LEFT JOIN '.jieqi_dbprefix('obook_obook').' a ON c.obookid=a.obookid');
$obook_query->queryObjects($criteria);
$ochapter=$obook_query->getObject();
if(!is_object($ochapter) || $ochapter->getVar('display') != 0){
	jieqi_printfail($jieqiLang['obook']['chapter_not_insale']);
}else{
	//检查有没免费阅读权限
	$freeread=false;
	if(!empty($_SESSION['jieqiUserId']) && ($_SESSION['jieqiUserId']==$ochapter->getVar('authorid') || $_SESSION['jieqiUserId']==$ochapter->getVar('agentid') || $_SESSION['jieqiUserId']==$ochapter->getVar('posterid'))) $freeread=true;
	else{
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
		if(isset($jieqiPower['obook']['freeread'])) $freeread=jieqi_checkpower($jieqiPower['obook']['freeread'], $jieqiUsersStatus, $jieqiUsersGroup, true);
	}
}

//没免费阅读，检查有没购买
if(!$freeread){
	include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
	$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
	$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
	$criteria->add(new Criteria('userid', $_SESSION['jieqiUserId']));
	$criteria->setLimit(1);
	$buyinfo_handler->queryObjects($criteria);
	unset($criteria);
	$buyinfo=$buyinfo_handler->getObject();
	if(!is_object($buyinfo)){
		header('Location: '.$obook_static_url.'/buychapter.php?cid='.$_REQUEST['cid']);
		exit;
	}
}

//检查书的访问状态
if(isset($_SESSION['jieqiVisitedObooks'])) $arysession=unserialize($_SESSION['jieqiVisitedObooks']);
else $arysession=array();
if(!is_array($arysession)) $arysession=array();
$arysession[$_REQUEST['cid']]=1;
$_SESSION['jieqiVisitedObooks']=serialize($arysession);
@session_write_close();

//显示阅读界面
include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
$content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
$criteria->setLimit(1);
$content_handler->queryObjects($criteria);
unset($criteria);
$content=$content_handler->getObject();
if(!is_object($content)){
	jieqi_printfail($jieqiLang['obook']['chapter_not_exists']);
}else{
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('obookid', $ochapter->getVar('obookid'));
	$jieqiTpl->assign('ochapterid', $_REQUEST['cid']);
	$jieqiTpl->assign('cid', $_REQUEST['cid']);
	$jieqiTpl->assign('chaptertitle', $ochapter->getVar('obookname').' - '.$ochapter->getVar('chaptername'));
	$jieqiTpl->assign('obookbgcolor', $jieqiConfigs['obook']['obkimagecolor']);
	$jieqiTpl->assign('static_url', $obook_static_url);
	$jieqiTpl->assign('dynamic_url', $obook_dynamic_url);
	$jieqiTpl->assign('url_obookroom', $obook_dynamic_url.'/');
	$jieqiTpl->assign('url_obookinfo', $obook_dynamic_url.'/obookinfo.php?id='.$ochapter->getVar('obookid'));
	$jieqiTpl->assign('url_index', $obook_static_url.'/obookread.php?oid='.$ochapter->getVar('obookid').'&page=index');
	$jieqiTpl->assign('url_preview', $obook_static_url.'/obookread.php?oid='.$ochapter->getVar('obookid').'&cid='.$_REQUEST['cid'].'&page=preview');
	$jieqiTpl->assign('url_next', $obook_static_url.'/obookread.php?oid='.$ochapter->getVar('obookid').'&cid='.$_REQUEST['cid'].'&page=next');
	$jieqiTpl->assign('obookname', $ochapter->getVar('obookname'));
	$jieqiTpl->assign('url_obookimage', $obook_static_url.'/obookimage.php?cid='.$_REQUEST['cid']);
	$jieqiTpl->setCaching(0);
	if($jieqiConfigs['obook']['obkimagetype']=='txt') $jieqiTpl->display($jieqiModules['obook']['path'].'/templates/readtext.html');
	else{	
		if($jieqiConfigs['obook']['obkpictxt'] > 0)
		$picnum = ceil(strlen($content->getVar('ocontent', 'n')) / $jieqiConfigs['obook']['obkpictxt']);
		$jieqiTpl->assign('picnum', $picnum);
		$picrows = array();
		for($i=1; $i<=$picnum; $i++) $picrows[$i]=$i;
		$jieqiTpl->assign_by_ref('picrows', $picrows);
		$jieqiTpl->display($jieqiModules['obook']['path'].'/templates/reader.html');
	}
}

?>