<?php
/**
 * 修改圈子活动
 *
 * 修改圈子活动
 * 
 * 调用模板：/modules/group/templates/editparty.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_loadlang('editparty',JIEQI_MODULE_NAME);

$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);
$pid = intval($_REQUEST['pid']);

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/party.php');
$party_handler = JieqipartyHandler::getInstance('JieqipartyHandler');


//update party
if($_REQUEST['ptitle']){
	jieqi_checklogin();
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	setpower($gid);
	if(!$alloweditparty) {
		jieqi_jumppage("party.php?g=$gid&pid=$pid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['no_right_edit_party']);
	}
	$newparty = $party_handler->create($isNew=false);
	$newparty->setVar('pid',$pid);
	$newparty->setvar('ptitle',$_REQUEST['ptitle']);
	$newparty->setvar('pcontent',$_REQUEST['pcontent']);
	$newparty->setvar('pplace',$_REQUEST['pplace'] );
	$newparty->setvar('pstart',mktime(intval($_REQUEST['hour1']),intval($_REQUEST['minute1']),0,intval($_REQUEST['month1']),intval($_REQUEST['day1']),intval($_REQUEST['year1']) ) );
	$newparty->setvar('pstop',mktime(intval($_REQUEST['hour2']),intval($_REQUEST['minute2']),0,intval($_REQUEST['month2']),intval($_REQUEST['day2']),intval($_REQUEST['year2']) ) );
	$newparty->setvar('pmaxnums',intval($_REQUEST['pmaxnums']) );
	$newparty->setvar('gid',intval($_REQUEST['gid']) );
	$newparty->setvar('uname',$_SESSION['jieqiUserName'] );
	$newparty->setVar('gname',$_REQUEST['gname']);
	$newparty->setvar('ptime',time() );
	if( $party_handler->insert($newparty) ){
		jieqi_jumppage("party.php?g=$gid&pid=$pid&page=$_REQUEST[page]",LANG_DO_SUCCESS,$jieqiLang['g']['edit_party_success'] );
	}
}






//party
$criteria_party = new CriteriaCompo(gid,$gid );
$criteria_party->add(new Criteria(pid,$pid) );
$party_handler->queryObjects($criteria_party);
$party = $party_handler->getObject();
if(empty($party) ){
	jieqi_printfail($jieqiLang['g']['party_not_exist']);
}

$jieqiTpl->assign('pid',$pid);
$jieqiTpl->assign('ptitle',$party->getVar('ptitle') );
$jieqiTpl->assign('puname',$party->getVar('uname') );
$jieqiTpl->assign('ptime',date('Y-m-d H:i',$party->getVar('ptime') ) );
$pstart = $party->getVar('pstart');
$jieqiTpl->assign('year1',date('Y',$pstart) );
$jieqiTpl->assign('month1',date('m',$pstart) );
$jieqiTpl->assign('day1',date('d',$pstart) );
$jieqiTpl->assign('hour1',date('H',$pstart) );
$jieqiTpl->assign('minute1',date('i',$pstart) );
$pstop = $party->getVar('pstop');
$jieqiTpl->assign('year2',date('Y',$pstop) );
$jieqiTpl->assign('month2',date('m',$pstop) );
$jieqiTpl->assign('day2',date('d',$pstop) );
$jieqiTpl->assign('hour2',date('H',$pstop) );
$jieqiTpl->assign('minute2',date('i',$pstop) );
$jieqiTpl->assign('pnums',$party->getVar('pnums') );
$jieqiTpl->assign('pmaxnums',$party->getVar('pmaxnums') );
$jieqiTpl->assign('pcontent',$party->getVar('pcontent',n) );
$jieqiTpl->assign('pplace',$party->getVar('pplace') );
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/editparty.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>