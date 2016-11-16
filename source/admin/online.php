<?php
/**
 * 在线用户管理
 *
 * 显示在线用户列表
 * 
 * 调用模板：/templates/admin/online.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: online.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminuser'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');

include_once(JIEQI_ROOT_PATH.'/class/online.php');
$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');

if(isset($_REQUEST['action']) && $_REQUEST['action']=='del' && !empty($_REQUEST['sid'])){
	$mysid=session_id();
	@session_id($_REQUEST['sid']);
	@session_destroy();
	@session_id($mysid);
	$criteria=new CriteriaCompo(new Criteria('sid', $_REQUEST['sid'], '='));
	$online_handler->delete($criteria);
	unset($criteria);
}

$criteria=new CriteriaCompo(new Criteria('updatetime', JIEQI_NOW_TIME-$jieqiConfigs['system']['onlinetime'], '>'));
$allnum=$online_handler->getCount($criteria);
$criteria->add(new Criteria('uid', '0'), '>');

if(isset($_REQUEST['username']) && !empty($_REQUEST['username'])){
	$criteria->add(new Criteria('uname', $_REQUEST['username'], '='));
}elseif(isset($_REQUEST['groupid']) && !empty($_REQUEST['groupid'])){
    $criteria->add(new Criteria('groupid', $_REQUEST['groupid'], '='));	
}
$criteria->setSort('updatetime');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['useradminpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['useradminpnum']);
$result = $online_handler->queryObjects($criteria);
$userrows=array();
$k=0;
while($v = $online_handler->getObject()){
	$userrows[$k]['sid']=$v->getVar('sid');
	$userrows[$k]['userid']=$v->getVar('uid');
	$userrows[$k]['username']=$v->getVar('uname');
	$userrows[$k]['name']=$v->getVar('name');
	if(strlen($userrows[$k]['name']) == 0) $userrows[$k]['name'] = $userrows[$k]['username'];
	$userrows[$k]['group']=$jieqiGroups[$v->getVar('groupid')];
	$userrows[$k]['email']=$v->getVar('email');
	$userrows[$k]['logintime']=date(JIEQI_TIME_FORMAT, $v->getVar('logintime'));
	$userrows[$k]['updatetime']=date(JIEQI_TIME_FORMAT, $v->getVar('updatetime'));
	$userrows[$k]['operate']=$v->getVar('operate');
	$userrows[$k]['ip']=$v->getVar('ip');
	$userrows[$k]['browser']=$v->getVar('browser');
	$userrows[$k]['os']=$v->getVar('os');
	$userrows[$k]['location']=$v->getVar('location');
	//$userrows[$k]['action']='<a href="'.JIEQI_URL.'/admin/online.php?action=del&sid='.$v->getVar('sid').'">强制下线</a>';
	$k++;
}
$jieqiTpl->assign_by_ref('userrows', $userrows);
$rowcount=$online_handler->getCount($criteria);
$jieqiTpl->assign_by_ref('rowcount', $rowcount);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($rowcount,$jieqiConfigs['system']['useradminpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, false);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/online.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>