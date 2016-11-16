<?php 
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

define('JIEQI_MODULE_NAME', 'space');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['space']['allspace'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/space.php');
$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
if(isset($_REQUEST['keyword'])) $_REQUEST['keyword']=trim($_REQUEST['keyword']);
if(!empty($_REQUEST['keyword'])){
	if($_REQUEST['keytype']=='title') $criteria->add(new Criteria('title', '%'.$_REQUEST['keyword'].'%', 'like'));
	elseif($_REQUEST['keytype']=='uid') $criteria->add(new Criteria('uid', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('name', '%'.$_REQUEST['keyword'].'%', 'LIKE'));
}
if( empty($_REQUEST['state']) ){
	$_REQUEST['state'] = 'all';
}
switch( $_REQUEST['state'] ){
	case 'all':
		$criteria->setSort('uid');
		$criteria->setOrder('desc');
		break;
	case 'open':
		$criteria->add( new Criteria('open',1,'=') );	
		$criteria->setSort('uid');
		$criteria->setOrder('desc');
		break;
	case 'close':
		$criteria->add( new Criteria('open',1,'!=') );	
		$criteria->setSort('uid');
		$criteria->setOrder('desc');
		break;
	case 'cool':
		$criteria->setSort('visit_num');
		$criteria->setOrder('asc');
		break;		
	case 'hot':
		$criteria->setSort('visit_num');
		$criteria->setOrder('desc');
		break;		
	case 'comm':
		$criteria->add( new Criteria('commend',1,'=') );	
		$criteria->setSort('uid');
		$criteria->setOrder('desc');
		break;	
	case 'nocomm':
		$criteria->add( new Criteria('commend',1,'!=') );	
		$criteria->setSort('uid');
		$criteria->setOrder('desc');
		break;					
}

$pagenum = 20;
$criteria->setLimit($pagenum);
$criteria->setStart(($_REQUEST['page']-1) * $pagenum);
$space_handler->queryObjects($criteria);
$k = 0;
while($v = $space_handler->getObject() ){
	$spaces[$k]['uid'] = $v->getVar('uid');
	$spaces[$k]['name'] = $v->getVar('name','s');
	$spaces[$k]['title'] = $v->getVar('title','s');
	$spaces[$k]['brief'] = $v->getVar('brief','s');
	$spaces[$k]['visit_num'] = $v->getVar('visit_num');
	$spaces[$k]['blog_num'] = $v->getVar('blog_num');
	$spaces[$k]['sp_commend'] = $v->getVar('sp_commend');
	$spaces[$k]['sp_open'] = $v->getVar('sp_open');
	$spaces[$k]['update_time'] = $v->getVar('update_time','s');
	$k++;
}
$jieqiTpl->assign('spaces',$spaces);


//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($space_handler->getCount($criteria),$pagenum,$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['state'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='state='.$_REQUEST['state'];
}elseif(!empty($_REQUEST['display'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='display='.$_REQUEST['display'];
}
if(!empty($_REQUEST['keyword'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='keyword='.$_REQUEST['keyword'];
	$pagelink.='&keytype='.$_REQUEST['keytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($jieqiModules['space']['url'].'/admin/allspace.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->assign('page',$_REQUEST['page']);
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiModules['space']['path'].'/templates/admin/allspace.html'));
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>