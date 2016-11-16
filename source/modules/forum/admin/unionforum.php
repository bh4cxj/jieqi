<?php 
/**
 * 合并论坛帖子
 *
 * 合并论坛帖子
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: unionforum.php 327 2009-02-05 09:30:51Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['forum']['manageforum'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if(empty($_REQUEST['fromid']) || empty($_REQUEST['toid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
elseif($_REQUEST['fromid']==$_REQUEST['toid']) jieqi_printfail($jieqiLang['forum']['fromto_is_same']);
include_once($jieqiModules['forum']['path'].'/class/forums.php');
$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
$fromforum=$forums_handler->get($_REQUEST['fromid']);
if(!is_object($fromforum)) jieqi_printfail($jieqiLang['forum']['fromforum_not_exists']);
$toforum=$forums_handler->get($_REQUEST['toid']);
if(!is_object($toforum)) jieqi_printfail($jieqiLang['forum']['toforum_not_exists']);

//合并帖子
include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
$topics_handler=JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
$criteria=new CriteriaCompo(new Criteria('ownerid', $_REQUEST['fromid'], '='));
$topics_handler->updatefields(array('ownerid'=>$_REQUEST['toid']), $criteria);
include_once($jieqiModules['forum']['path'].'/class/forumposts.php');
$posts_handler=JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
$posts_handler->updatefields(array('ownerid'=>$_REQUEST['toid']), $criteria);

//改变统计
$toforum->setVar('forumtopics', $toforum->getVar('forumtopics') + $fromforum->getVar('forumtopics'));
$toforum->setVar('forumposts', $toforum->getVar('forumposts') + $fromforum->getVar('forumposts'));
$forums_handler->insert($toforum);

//删除来源论坛
$forums_handler->delete($_REQUEST['fromid']);
include_once($jieqiModules['forum']['path'].'/include/upforumset.php');  //更新配置文件
jieqi_jumppage($jieqiModules['forum']['url'].'/admin/forumlist.php', LANG_DO_SUCCESS, $jieqiLang['forum']['union_forum_success']);
?>