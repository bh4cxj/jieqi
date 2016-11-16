<?php
/**
 * 用户管理日志
 *
 * 记录后台修改用户信息的日志
 * 
 * 调用模板：/templates/admin/userlog.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userlog.php 326 2009-02-04 00:26:22Z juny $
 */

//用户日志
define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminuserlog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

include_once(JIEQI_ROOT_PATH.'/class/userlog.php');
$userlog_handler=JieqiUserlogHandler::getInstance('JieqiUserlogHandler');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword'])){
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('toname', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('fromname', $_REQUEST['keyword'], '='));
}
$criteria->setSort('logid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['userlogpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['userlogpnum']);
$userlog_handler->queryObjects($criteria);
$logrows=array();
$k=0;
while($v = $userlog_handler->getObject()){
	$logrows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('logtime'));
	$logrows[$k]['time']=date(JIEQI_TIME_FORMAT, $v->getVar('logtime'));
	$logrows[$k]['fromid']=$v->getVar('fromid');
	$logrows[$k]['fromname']=$v->getVar('fromname');
	$logrows[$k]['toid']=$v->getVar('toid');
	$logrows[$k]['toname']=$v->getVar('toname');
	$logrows[$k]['reason']=$v->getVar('reason');
	$logrows[$k]['chginfo']=$v->getVar('chginfo');
	$k++;
}
$jieqiTpl->assign_by_ref('logrows', $logrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($userlog_handler->getCount($criteria),$jieqiConfigs['system']['userlogpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/userlog.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>