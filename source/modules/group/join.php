<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

/**
 *  
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_loadlang('join',JIEQI_MODULE_NAME);
jieqi_loadlang('member',JIEQI_MODULE_NAME);
include_once($jieqiModules['group']['path']."/include/functions.php");


jieqi_checklogin();
$uid = $_SESSION['jieqiUserId'];
$uname = $_SESSION['jieqiUserName'];
include('class/member.php');
$gid = intval($_REQUEST['g']); 
$member_handler = JieqimemberHandler::getInstance('JIeqimemberHandler');
$criteria = new  CriteriaCompo(new Criteria('uid',$uid) ) ;
$criteria->add(new Criteria('gid',$gid) );
$member_handler->queryObjects($criteria); 
if($member_handler->getObject() ){
	if($_REQUEST['quite']){
	    if($guid==$uid) jieqi_printfail($jieqiLang['g']['out_failure']);
		$criteria = new CriteriaCompo(new Criteria('gid',$gid ) );
		$criteria->add(new Criteria('uid',$uid ) );
		$criteria->add(new Criteria('creater','1','!=') );
		if($member_handler->delete($criteria) ) { 
			include_once("./include/functions.php");
			//user_group($uid,$gid,'delete');
			update_ginfo('gmembers=gmembers-1',$gid);
			jieqi_jumppage($_REQUEST['url'] ?$_REQUEST['url'] :'index.php?g='.$gid,LANG_DO_SUCCESS,$jieqiLang['g']['quite_success']);
		}
	}
	jieqi_printfail($jieqiLang['g']['have_joind_group']);	

}
if($_REQUEST['quite']) jieqi_printfail($jieqiLang['g']['error_no_member']);
$newmember = $member_handler->create();
$newmember->setVar('uid',$uid);
$newmember->setVar('uname',$uname);
$newmember->setVar('mtime',time() );
$newmember->setVar('membergid',4);
$newmember->setVar('gid',$gid);
if(!$gaudit){
  $newmember->setVar('mswitch',1);
  $success_message = $jieqiLang['g']['joind_success_y'];
}else{
  $newmember->setVar('mswitch',0);
  $success_message = $jieqiLang['g']['joind_success_n'];
}
if($member_handler->insert($newmember) ){
	update_ginfo('gmembers=gmembers+1',$gid);
	//user_group($uid,$gid,'add');
	jieqi_jumppage('index.php?g='.$gid,LANG_DO_SUCCESS,$success_message );
}else{
	jieqi_printfail($jieqiLang['g']['system_error']);
} 


?>