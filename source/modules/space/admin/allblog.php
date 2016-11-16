<?php 
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

define('JIEQI_MODULE_NAME', 'space');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['blog']['allblog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once(JIEQI_ROOT_PATH.'/admin/header.php');

include_once($jieqiModules['space']['path'].'/class/blog.php');
$blog_handler =& JieqiSpaceBlogHandler::getInstance('JieqiSpaceBlogHandler');

if($_REQUEST['action'] === 'del' && is_numeric($_REQUEST['id']) ){
	$blog = $blog_handler->get( $_REQUEST['id'] );
	if($blog){
		$uid = $blog->getVar($uid);
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid'),$uid );
		require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/space.php');
		$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
		$space_handler->updatefields('`blog_num`=`blog_num`-1',$criteria);
		$cat_id = $blog->getVar('cat_id');
		$criteria->add(new Criteria('id',$cat_id) );
		include_once($jieqiModules['space']['path'].'/class/blogcat.php');
		$blog_cat_handler = JieqiSpaceBlogCatHandler::getInstance('JieqiSpaceBlogCatHandler');
		$blog_cat_handler->updatefields('`num`=`num`-1',$criteria);
		$blog_handler->delete($_REQUEST['id']);
	}
}

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
	case 'hot':
		$criteria->setSort('hit_num');
		$criteria->setOrder('desc');
		break;
	case 'cool':
		$criteria->setSort('hit_num');
		$criteria->setOrder('asc');
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
	default:
		break;
}

$pagenum = 20;
$criteria->setLimit($pagenum);
$criteria->setStart(($_REQUEST['page']-1) * $pagenum);
$blog_handler->queryObjects($criteria,false);
$k = 0;
while($v = $blog_handler->getObject() ){
	$blogs[$k]['id'] = $v->getVar('id');
	$blogs[$k]['uid'] = $v->getVar('uid');
	$blogs[$k]['name'] = $v->getVar('name','s');
	$blogs[$k]['title'] = $v->getVar('title','s');
	$blogs[$k]['review_num'] = $v->getVar('review_num');
	$blogs[$k]['hit_num'] = $v->getVar('hit_num');
	$blogs[$k]['ar_commend'] = $v->getVar('ar_commend');
	$blogs[$k]['time'] = $v->getVar('time','s');
	$k++;
}

$jieqiTpl->assign('blogs',$blogs);
$jieqiTpl->assign('jieqi_space_url',$jieqiModules['space']['url']);


//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($blog_handler->getCount($criteria),$pagenum,$_REQUEST['page']);
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
$jumppage->setlink($jieqiModules['space']['url'].'/admin/allblog.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('state',$_REQUEST['state']);
$jieqiTpl->assign('page',$_REQUEST['page']);
$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($jieqiModules['space']['path'].'/templates/admin/allblog.html'));
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>