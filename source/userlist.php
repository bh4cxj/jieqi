<?php 
/**
 * 用户分类列表
 *
 * 按用户组分类显示
 * 
 * 调用模板：/templates/userlist.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userlist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['system']['viewuser'], $jieqiUsersStatus, $jieqiUsersGroup, false);
//包含区块参数(定制)
jieqi_getconfigs('system', 'memberblocks', 'jieqiBlocks');
//包含配置参数
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
//用户类别
if(empty($_REQUEST['group'])) $_REQUEST['group']=0;
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['group'])){
	$criteria->add(new Criteria('groupid', $_REQUEST['group']));
	$criteria->setSort('uid');
    $criteria->setOrder('DESC');
}elseif(!empty($_REQUEST['initial'])){
    $criteria->add(new Criteria('initial', strtoupper($_REQUEST['initial']), '='));
	$criteria->setSort('uname');
	$criteria->setOrder('ASC');	
}
if(isset($_REQUEST['isvip']) && is_numeric($_REQUEST['isvip'])){
	$_REQUEST['isvip']=intval($_REQUEST['isvip']);
	$criteria->add(new Criteria('isvip', $_REQUEST['isvip']));
}
$criteria->setLimit($jieqiConfigs['system']['userpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['userpnum']);
$users_handler->queryObjects($criteria);
$userrows=array();

$i=0;
while($v = $users_handler->getObject()){
	$userrows[$i]['uid']=$v->getVar('uid');
	$userrows[$i]['uname']=$v->getVar('uname');
	$userrows[$i]['name']=$v->getVar('name');
	if(strlen($userrows[$i]['name'])==0) $userrows[$i]['name']=$userrows[$i]['uname'];
	$userrows[$i]['qq']=$v->getVar('qq');
	$userrows[$i]['icq']=$v->getVar('icq');
	$userrows[$i]['man']=$v->getVar('man');
	$userrows[$i]['email']=$v->getVar('email');
	$userrows[$i]['viewemail']=$v->getVar('viewemail');
	$userrows[$i]['url']=$v->getVar('url');
	$userrows[$i]['regdate']=$v->getVar('regdate');
	$userrows[$i]['groupid']=$v->getVar('groupid');
	$userrows[$i]['groupname']=$jieqiGroups[$userrows[$i]['groupid']];
	$userrows[$i]['avatar']=$v->getVar('avatar');
	$userrows[$i]['score']=$v->getVar('score');
	$userrows[$i]['experience']=$v->getVar('experience');
	$userrows[$i]['workid']=$v->getVar('workid');
	$i++;
}

$jieqiTpl->assign_by_ref('userrows', $userrows);
$jieqiTpl->assign('url_initial', JIEQI_URL.'/userlist.php?initial=');
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($users_handler->getCount($criteria),$jieqiConfigs['system']['userpnum'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userlist.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>