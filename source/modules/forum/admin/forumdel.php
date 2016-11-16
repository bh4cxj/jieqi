<?php 
/**
 * 论坛版块删除
 *
 * 论坛版块删除
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumdel.php 327 2009-02-05 09:30:51Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['forum']['manageforum'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if($_REQUEST['action']=='del'){
	switch($_REQUEST['type']){
		case 'forum':
		//删除论坛
		include_once($jieqiModules['forum']['path'].'/class/forums.php');
		$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
		$forums_handler->delete($_REQUEST['id']);
		//删除主题
		include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
		$topics_handler=JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
		$criteria=new CriteriaCompo(new Criteria('ownerid', $_REQUEST['id'], '='));
		$topics_handler->delete($criteria);
		//删除帖子
		include_once($jieqiModules['forum']['path'].'/class/forumposts.php');
		$posts_handler=JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
		$posts_handler->delete($criteria);

		jieqi_jumppage($jieqiModules['forum']['url'].'/admin/forumlist.php', LANG_DO_SUCCESS, $jieqiLang['forum']['delete_forum_success']);
		break;
		case 'forumcat':
		//删除分类
		include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
		$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');
		$forumcat_handler->delete($_REQUEST['id']);
		//删除论坛
		include_once($jieqiModules['forum']['path'].'/class/forums.php');
		$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
		$criteria=new CriteriaCompo(new Criteria('catid', $_REQUEST['id'], '='));
		$forums_handler->queryObjects($criteria);
		$forums=array();
		$i=0;
		while($v = $forums_handler->getObject()){
			$forums[$i]['forumid'] = $v->getVar('forumid');
			$i++;
		}
		$forums_handler->delete($criteria);
		//删除主题和帖子
		if(is_array($forums) && count($forums)>0){
			$criteria=new CriteriaCompo();
			foreach($forums as $forum){
				$criteria->add(new Criteria('ownerid', $forum['forumid'], '='), 'OR');
			}
			include_once($jieqiModules['forum']['path'].'/class/forumtopics.php');
			$topics_handler=JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
			$topics_handler->delete($criteria);
			include_once($jieqiModules['forum']['path'].'/class/forumposts.php');
			$posts_handler=JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
			$posts_handler->delete($criteria);
		}
		include_once($jieqiModules['forum']['path'].'/include/upforumset.php');  //更新配置文件
		jieqi_jumppage($jieqiModules['forum']['url'].'/admin/forumlist.php', LANG_DO_SUCCESS, $jieqiLang['forum']['delete_forumcat_success']);
		break;
		default:
		jieqi_printfail(LANG_ERROR_PARAMETER);
		break;
	}
}else{
	jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['forum']['delete_forum_confirm'], $jieqiModules['forum']['url'].'/admin/forumdel.php?type='.$_REQUEST['type'].'&id='.$_REQUEST['id'].'&action=del', $jieqiModules['forum']['url'].'/admin/forumlist.php'));
}
?>