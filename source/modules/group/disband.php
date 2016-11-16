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

$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}

jieqi_loadlang('disband',JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
setpower($gid);

//echo $admingid;
//exit;

jieqi_includedb();
$query_handler = JieqiQueryHandler::getInstance('JieqiQueryHandler');

if( $admingid == 1){
	$groupUserfile['dir'] = jieqi_uploadpath('userdir',JIEQI_MODULE_NAME);
	$groupUserfile['dir'].= jieqi_getsubdir($gid).'/';
	$groupUserfile['dir'].= $gid.'/';
	jieqi_delfolder($groupUserfile['dir']);
        //jeqi_group_admingroup
        //jieqi_group_album
	
	$query_handler->execute("delete from ".jieqi_dbprefix("group_album")." where gid=$gid");
        //jieqi_group_attach
	$query_handler->execute("delete from ".jieqi_dbprefix("group_attach")." where gid=$gid");
        //jieqi_group_city
        //jieqi_group_gcat
        //jieqi_group_group
	$query_handler->execute("delete from ".jieqi_dbprefix("group_group")." where gid=$gid");
        //jieqi_group_member
	$query_handler->execute("delete from ".jieqi_dbprefix("group_member")." where gid=$gid");
        //jieqi_group_membergroup
        //jieqi_group_party
	$query_handler->execute("delete from ".jieqi_dbprefix("group_party")." where gid=$gid");
        //jieqi_group_partyreply
	$query_handler->execute("delete from ".jieqi_dbprefix("group_partyreply")." where gid=$gid");
        //jieqi_group_photo
	$query_handler->execute("delete from ".jieqi_dbprefix("group_photo")." where gid=$gid");
        //jieqi_group_poll
	$query_handler->execute("delete from ".jieqi_dbprefix("group_poll")." where gid=$gid");
        //jieqi_group_polloption
	$query_handler->execute("delete from ".jieqi_dbprefix("group_polloption")." where gid=$gid");
        //jieqi_group_post
	$query_handler->execute("delete from ".jieqi_dbprefix("group_post")." where gid=$gid");
        //jieqi_group_province
        //jieqi_group_sign
	$query_handler->execute("delete from ".jieqi_dbprefix("group_sign")." where gid=$gid");
        //jieqi_group_topic
	$query_handler->execute("delete from ".jieqi_dbprefix("group_topic")." where gid=$gid");

	jieqi_jumppage("../../",LANG_DO_SUCCESS,$jieqiLang['g']['disaband_success']);
}else{
    jieqi_printfail($jieqiLang['g']['disaband_no_right']);
}


?>