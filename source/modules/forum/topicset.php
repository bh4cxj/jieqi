<?php
/**
 * 帖子属性设置
 *
 * 处理置顶、加精、锁定、提前
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: topicset.php 327 2009-02-05 09:30:51Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
if(empty($_REQUEST['tid']) || !is_numeric($_REQUEST['tid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('t.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('forum_forumtopics').' t LEFT JOIN '.jieqi_dbprefix('forum_forums').' f ON t.ownerid=f.forumid');
$query->queryObjects($criteria);
$topic=$query->getObject();
unset($criteria);
if(!$topic) jieqi_printfail($jieqiLang['forum']['post_not_exists']);

//检查权限(管理)
$editpower['groups']=unserialize($topic->getVar('authedit', 'n'));
if(!is_array($editpower['groups'])) $editpower['groups']=array();
$canedit = jieqi_checkpower($editpower, $jieqiUsersStatus, $jieqiUsersGroup, true);
//检查是否斑竹
if(!$canedit && !empty($_SESSION['jieqiUserName'])){
	$tmpary=unserialize($topic->getVar('master','n'));
	if(is_array($tmpary) && count($tmpary)>0){
		$masterary='';
		foreach($tmpary as $v){
			if($v['uname'] != ''){
				$masterary[]=$v['uname'];
			}
		}
		if(in_array($_SESSION['jieqiUserName'],$masterary)) $canedit=true;
	}
}

switch($_REQUEST['action']){
	case 'top':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET istop=1 WHERE topicid='.$_REQUEST['tid']);
	break;
    case 'untop':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET istop=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'good':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET isgood=1 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'nogood':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET isgood=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'lock':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET islock=1 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'unlock':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET islock=0 WHERE topicid='.$_REQUEST['tid']);
	break;
	case 'push':
	$query->execute('UPDATE '.jieqi_dbprefix('forum_forumtopics').' SET replytime='.JIEQI_NOW_TIME.' WHERE topicid='.$_REQUEST['tid']);
	break;
}
if(empty($_REQUEST['ajax_request'])) header('Location: '.$jieqiModules['forum']['url'].'/topiclist.php?fid='.$topic->getVar('forumid'));
else echo '1';
exit;
?>