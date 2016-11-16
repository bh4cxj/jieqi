<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

/**
 *  网站首页
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
define('JIEQI_MODULE_NAME','group');
jieqi_loadlang('setbasic',JIEQI_MODULE_NAME);

//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');



//up group face
$gid = intval($_REQUEST['g']);
include_once("./include/functions.php");
setpower($gid);

if($allowmanbasic!=1){
	jieqi_printfail($jieqiLang['g']['no_right']);
	exit;
}


if($_REQUEST['docreate']){
	//必用数据句柄初始化
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');

	$gid = intval($_REQUEST['gid'] );
	$criteria = new Criteria('gid',$gid);
	$_REQUEST['gbrief']=jieqi_setslashes($_REQUEST['gbrief']);
	$updatefields = array(
			'gcatid'=>intval($_REQUEST['gcatid']), 
			'gprovince'=>$_REQUEST['province'], 
			'gcity'=>$_REQUEST['city'],
			'gbrief'=>$_REQUEST['gbrief'], 
			'gaudit'=>intval($_REQUEST['gaudit']) 
			);

	include_once("./include/functions.php");
	update_ginfo($updatefields,$gid);
	jieqi_jumppage("man.php?g=$gid&set=basic",LANG_DO_SUCCESS,$jieqiLang['g']['set_sucess'] );
}

?>