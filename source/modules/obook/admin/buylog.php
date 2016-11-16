<?php 
/**
 * 后台电子书购买记录
 *
 * 后台电子书购买记录
 * 
 * 调用模板：/modules/obook/templates/admin/buylog.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: buylog.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['obook']['viewbuylog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
jieqi_getconfigs('obook', 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
if(!empty($_REQUEST['uname'])) $jieqiTpl->assign('uname',htmlspecialchars($_REQUEST['uname'], ENT_QUOTES));
if(!empty($_REQUEST['oname'])) $jieqiTpl->assign('oname',htmlspecialchars($_REQUEST['oname'], ENT_QUOTES));

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

include_once($jieqiModules['obook']['path'].'/class/osale.php');
$osale_handler =& JieqiOsaleHandler::getInstance('JieqiOsaleHandler');	
$criteria=new CriteriaCompo();

if(!empty($_REQUEST['uid'])){
	$criteria->add(new Criteria('accountid', intval($_REQUEST['uid'])));
}elseif(!empty($_REQUEST['uname'])){
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	$userobj = $users_handler->getByname($_REQUEST['uname'],3);
	if(is_object($userobj)) $criteria->add(new Criteria('accountid', intval($userobj->getVar('uid','n'))));
	//$criteria->add(new Criteria('account', $_REQUEST['uname']));
}
if(!empty($_REQUEST['oid'])){
	$criteria->add(new Criteria('obookid', intval($_REQUEST['oid'])));
}elseif(!empty($_REQUEST['oname'])){
	include_once($jieqiModules['obook']['path'].'/class/obook.php');
	$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
	$criteria1=new CriteriaCompo(new Criteria('obookname', $_REQUEST['oname']));
	$obook_handler->queryObjects($criteria1);
	$obookobj = $obook_handler->getObject();
	if(is_object($obookobj)) $criteria->add(new Criteria('obookid', intval($obookobj->getVar('obookid', 'n'))));
	//$criteria->add(new Criteria('obookname', $_REQUEST['oname']));
}
$criteria->setSort('osaleid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['obook']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['obook']['pagenum']);
$osale_handler->queryObjects($criteria);
    
$osalerows=array();
$k=0;
while($v = $osale_handler->getObject()){
	$osalerows[$k]['osaleid']=$v->getVar('osaleid');  //销售序号
	$osalerows[$k]['buytime']=date(JIEQI_DATE_FORMAT, $v->getVar('buytime'));  //购买日期
	$osalerows[$k]['osaleid']=$v->getVar('osaleid');
	$osalerows[$k]['accountid']=$v->getVar('accountid');
	$osalerows[$k]['account']=$v->getVar('account');
	$osalerows[$k]['obookid']=$v->getVar('obookid');
	$osalerows[$k]['ochapterid']=$v->getVar('ochapterid');
	$osalerows[$k]['obookname']=$v->getVar('obookname');
	$osalerows[$k]['chaptername']=$v->getVar('chaptername');
	$osalerows[$k]['saleprice']=$v->getVar('saleprice');

	$k++;
}

$jieqiTpl->assign_by_ref('osalerows', $osalerows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($osale_handler->getCount($criteria),$jieqiConfigs['obook']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['oid'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='oid='.$_REQUEST['oid'];
}elseif(!empty($_REQUEST['oname'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='oname='.$_REQUEST['oname'];
}
if(!empty($_REQUEST['uid'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='ud='.$_REQUEST['uid'];
}elseif(!empty($_REQUEST['uname'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='uname='.$_REQUEST['uname'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($obook_dynamic_url.'/admin/buylog.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/admin/buylog.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>