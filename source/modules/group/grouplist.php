<?php
/**
 * 圈子首页
 *
 * 圈子首页
 * 
 * 调用模板：$jieqiModules['group']['path'].'/templates/grouplist.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
//载入语言
jieqi_loadlang('list', JIEQI_MODULE_NAME);
//包含区块参数(定制)
jieqi_getconfigs('group', 'grouplistblocks', 'jieqiBlocks');
//包含配置参数
jieqi_getconfigs('group', 'configs');
///jieqi_getconfigs('group', 'sort');
$catid = intval($_REQUEST['catid']);
	include_once(JIEQI_ROOT_PATH.'/header.php');
	include_once(JIEQI_ROOT_PATH.'/configs/group/gcats.php');
	if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
	$criteria = new CriteriaCompo();
	if($catid){
	   if($gcats[$catid]!=''){
		   $jieqiTpl->assign('catid',$catid);
		   $jieqiTpl->assign('catname',$gcats[$catid]);
		   $criteria->add(new Criteria('gcatid',$catid) );
	   }
	   $pagelink = '?catid='.$catid;
	}
	$pagenum = 30;
	$criteria->setLimit($pagenum);
	$criteria->setStart(($_REQUEST['page']-1) * $pagenum);	
	$count = $group_handler->getCount($criteria);
	$k = 0;
	$group_handler->queryObjects($criteria);
    while($v = $group_handler->getObject() ){
			$grouprows[$k]['gid']=$v->getVar('gid');
			$grouprows[$k]['gname'] = $v->getVar('gname');
			$grouprows[$k]['topicnum'] = $v->getVar('topicnum');
			$grouprows[$k]['gmembers'] = $v->getVar('gmembers');
			$grouprows[$k]['guname'] = $v->getVar('guname');
			$grouprows[$k]['catname'] = $gcats[$v->getVar('gcatid')];
		$k++;
	}
	$jieqiTpl->assign('grouprows',$grouprows);
	//处理页面跳转
	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	$jumppage = new JieqiPage($count,$pagenum,$_REQUEST['page']);
	if(empty($pagelink)) $pagelink.='?page=';
	else $pagelink.='&page=';
	$jumppage->setlink($jieqiModules['group']['url'].'/grouplist.php'.$pagelink, false, true);
	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());	
	$jieqiTpl->setCaching(0);
  $jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/grouplist.html';	
    require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>