<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

//处理置顶、加精、锁定、提前
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('topic',JIEQI_MODULE_NAME);
if(empty($_REQUEST['tid']) || !is_numeric($_REQUEST['tid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('post', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
//检查权限
include_once("include/functions.php");
setpower($gid);
if(!$allowmantopic){
	jieqi_printfail($jieqiLang['group']['noper_delete_post']);
}

jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('t.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('group_topics').' t LEFT JOIN '.jieqi_dbprefix('group_group').' f ON t.ownerid=f.gid');
$query->queryObjects($criteria);
$topic=$query->getObject();
unset($criteria);
if(!$topic) jieqi_printfail($jieqiLang['group']['post_not_exists']);

switch($_REQUEST['action']){
	case 'top':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET istop=1 WHERE topicid='.$_REQUEST['tid']);
	break;
    case 'untop':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET istop=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'good':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET isgood=1 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'nogood':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET isgood=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'lock':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET islock=1 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'unlock':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET islock=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'push':
	$query->execute('UPDATE '.jieqi_dbprefix('group_topics').' SET replytime='.JIEQI_NOW_TIME.' WHERE topicid='.$_REQUEST['tid']);
	break;
}
if(empty($_REQUEST['ajax_request'])) header('Location: '.$jieqiModules['group']['url'].'/topiclist.php?g='.$gid.'&fid='.$topic->getVar('forumid'));
else echo '1';
exit;
?>