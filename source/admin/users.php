<?php
/**
 * 后台用户列表
 *
 * 后台用户列表
 * 
 * 调用模板：/templates/admin/users.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: users.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminuser'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');

$criteria=new CriteriaCompo();
if(isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword'])){
	if($_REQUEST['keytype']=='name') $criteria->add(new Criteria('name', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('uname', $_REQUEST['keyword'], '='));
}elseif(isset($_REQUEST['groupid']) && !empty($_REQUEST['groupid'])){
	$criteria->add(new Criteria('groupid', $_REQUEST['groupid'], '='));
}
$criteria->setSort('uid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['useradminpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['useradminpnum']);
$users_handler->queryObjects($criteria);
$userrows=array();
$k=0;
while($v = $users_handler->getObject()){
	$userrows[$k]['checkbox']='<input type="checkbox" id="checkid'.$k.'" name="checkid'.$k.'" value="'.$v->getVar('uid').'">';
	$userrows[$k]['userid']=$v->getVar('uid');
	$userrows[$k]['username']=$v->getVar('uname');
	$userrows[$k]['name']=$v->getVar('name');
	if($userrows[$k]['name']=='') $userrows[$k]['name']=$userrows[$k]['username'];
	$userrows[$k]['group']=$v->getGroup();
	$userrows[$k]['email']=$v->getVar('email');
	$userrows[$k]['regdate']=date(JIEQI_DATE_FORMAT, $v->getVar('regdate'));
	$userrows[$k]['experience']=$v->getVar('experience');
	$userrows[$k]['score']=$v->getVar('score');
	$userrows[$k]['egold']=$v->getVar('egold');
	$userrows[$k]['esilver']=$v->getVar('esilver');
	$userrows[$k]['emoney']=$userrows[$k]['egold']+$userrows[$k]['esilver'];
	$k++;
}
$jieqiTpl->assign_by_ref('userrows', $userrows);

$grouprows=array();
$i=0;
foreach($jieqiGroups as $k=>$v){
	if($k>1){
		$grouprows[$i]['groupid']=$k;
		$grouprows[$i]['groupname']=$v;
		$i++;
	}
}
$jieqiTpl->assign_by_ref('grouprows', $grouprows);

$rowcount=$users_handler->getCount($criteria);
$jieqiTpl->assign_by_ref('rowcount', $rowcount);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($rowcount,$jieqiConfigs['system']['useradminpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/users.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>