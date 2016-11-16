<?php
/**
 * 圈子活动
 *
 * 圈子活动
 * 
 * 调用模板：/modules/group/templates/parties.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

jieqi_loadlang('parties',JIEQI_MODULE_NAME);
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/party.php');
$party_handler = JieqipartyHandler::getInstance('JieqipartyHandler');
//add new party
if($_REQUEST['ptitle']){
	jieqi_checklogin();
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	setpower($gid);
	if($allowpostparty!=1) {
		jieqi_printfail($jieqiLang['g']['no_right_post_party']);
	}else {
		$newparty = $party_handler->create();
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
		include_once("./include/functions.php");
		update_ginfo('gparties=gparties+1',$gid);
		if( $party_handler->insert($newparty) ){
			jieqi_jumppage("?g=$gid&page=$_REQUEST[page] ",LANG_DO_SUCCESS,$jieqiLang['g']['post_party_success'] );
		}
	}
}elseif($_REQUEST['action']='delete' && !empty($_REQUEST['pid'])){
	jieqi_checklogin();
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	
	$_REQUEST['pid']=intval($_REQUEST['pid']);
	//$party = $party_handler($_REQUEST['pid']);
	//if($party->getVar('gid') != $gid) jieqi_printfail($jieqiLang['g']['party_not_exists']);
	setpower($gid);
	if($allowmanparty!=1) {
		jieqi_printfail($jieqiLang['g']['no_right_delete_party']);
	}else {
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('pid', $_REQUEST['pid']));
		$criteria->add(new Criteria('gid', $gid));
		$party_handler->delete($criteria);
		
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/sign.php');
		$sign_handler = JieqisignHandler::getInstance('JieqisignHandler');
		$sign_handler->delete($criteria);
		
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/partyreply.php');
		$partyreply_handler = JieqipartyreplyHandler::getInstance('JieqipartyreplyHandler');
		$partyreply_handler->delete($criteria);
	}
}

$jieqiTpl->assign('gid', $gid);
if(!isset($allowmanparty) && !empty($_SESSION['jieqiUserId'])){
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	setpower($gid);
}
$jieqiTpl->assign('manage_party', $allowmanparty);
// all parties
$criteria = new Criteria(gid,$gid );
$count = $party_handler->getCount($criteria);
$jieqiTpl->assign('count',$count);

//分页
$onepage = 10;
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($count,$onepage,$_REQUEST['page']);
$jieqiTpl->assign('jumppage',$jumppage->whole_num_bar() );


$criteria->setStart( $onepage*($_REQUEST['page']-1) );
$criteria->setLimit($onepage);
$criteria->setSort('ptop desc, pid');
$criteria->setOrder('desc');
$party_handler->queryObjects($criteria);
$parties = array();
$k = 0;
while($v = $party_handler->getObject() ) {
	$parties[$k]['pid'] = $pid = $v->getVar('pid');
	$parties[$k]['ptitle'] = "<a href='./party.php?pid=$pid&g=$gid'>".$v->getVar('ptitle')."</a>";
	if($v->getVar('ptop') ) {
		$parties[$k]['ptop'] = $jieqiLang['g']['set_top'];
	}
	$parties[$k]['uname'] = $v->getVar('uname');
	$parties[$k]['pmaxnums'] = $v->getVar('pmaxnums');
	$parties[$k]['pnums'] = $v->getVar('pnums');
	$parties[$k]['ptime'] = date('m-d H:i',$v->getVar('ptime') );
	$parties[$k]['pstart'] = date('m-d H:i',$v->getVar('pstart') );
	$parties[$k]['pstop'] = date('m-d H:i',$v->getVar('pstop') );
	$parties[$k]['replies'] = $v->getVar('replies');
	$parties[$k]['sign_href'] = "./sign.php?g=$gid&pid=$pid&page=$_REQUEST[page]";
	$k++;
}
$jieqiTpl->assign('parties',$parties);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/parties.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>