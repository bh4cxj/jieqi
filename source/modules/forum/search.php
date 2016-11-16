<?php
/**
 * 论坛帖子搜索
 *
 * 论坛帖子搜索
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: search.php 329 2009-02-07 01:21:38Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
jieqi_checklogin();
jieqi_loadlang('search', JIEQI_MODULE_NAME);
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'search';

switch($_REQUEST['action']){
	case 'show':
		if(empty($_REQUEST['skey'])) jieqi_printfail($jieqiLang['forum']['need_search_keywords']);
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
		//关键字长度
		if(!empty($jieqiConfigs['forum']['minsearchlen']) && strlen($_REQUEST['skey'])<intval($jieqiConfigs['forum']['minsearchlen']) && $_REQUEST['type']!=2) jieqi_printfail(sprintf($jieqiLang['forum']['min_search_keywords'], $jieqiConfigs['forum']['minsearchlen']));

		//检查时间，是否允许搜索
		if(!empty($jieqiConfigs['forum']['minsearchtime']) && empty($_REQUEST['page'])){
			$jieqi_visit_time=jieqi_strtosary($_COOKIE['jieqiVisitTime']);
			if(!empty($_SESSION['jieqiForumsearchTime'])) $logtime=$_SESSION['jieqiForumsearchTime'];
			elseif(!empty($jieqi_visit_time['jieqiForumsearchTime'])) $logtime=$jieqi_visit_time['jieqiForumsearchTime'];
			else $logtime=0;
			if(($logtime>0) && JIEQI_NOW_TIME-$logtime < intval($jieqiConfigs['forum']['minsearchtime'])) jieqi_printfail(sprintf($jieqiLang['forum']['search_time_limit'], $jieqiConfigs['forum']['minsearchtime']));

			$_SESSION['jieqiForumsearchTime']=JIEQI_NOW_TIME;
			$jieqi_visit_time['jieqiForumsearchTime']=JIEQI_NOW_TIME;
			setcookie("jieqiVisitTime",jieqi_sarytostr($jieqi_visit_time),JIEQI_NOW_TIME+3600, '/', JIEQI_COOKIE_DOMAIN, 0);
		}

		include_once(JIEQI_ROOT_PATH.'/header.php');

		if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
		$_REQUEST['pagerows'] = intval($jieqiConfigs['forum']['topicnum']);
		if(empty($_REQUEST['pagerows'])) $_REQUEST['pagerows'] = 10;
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$criteria=new CriteriaCompo();
		$criteria->setSort('t.replytime');
		$criteria->setOrder('DESC');
		$criteria->setLimit($_REQUEST['pagerows']);
		$criteria->setStart(($_REQUEST['page']-1) * $_REQUEST['pagerows']);
		$criteria->setTables(jieqi_dbprefix('forum_forumtopics').' t LEFT JOIN '.jieqi_dbprefix('forum_forums').' f ON t.ownerid=f.forumid');
		if(!empty($_REQUEST['area'])){
			$tmpary=explode('|', $_REQUEST['area']);
			if(isset($tmpary[1]) && !empty($tmpary[1])) $criteria->add(new Criteria('t.ownerid', $tmpary[1]));
			elseif(isset($tmpary[0]) && !empty($tmpary[0])) $criteria->add(new Criteria('f.catid', $tmpary[0]));
		}
		if(isset($_REQUEST['type']) && $_REQUEST['type']==2) $criteria->add(new Criteria('posterid', intval($_REQUEST['skey'])));
		elseif(isset($_REQUEST['type']) && $_REQUEST['type']==1) $criteria->add(new Criteria('poster', $_REQUEST['skey']));
		else{
			if($jieqiConfigs['forum']['searchtype']==1) $criteria->add(new Criteria('title', $_REQUEST['skey'].'%', 'like'));
			elseif($jieqiConfigs['forum']['searchtype']==2) $criteria->add(new Criteria('title', $_REQUEST['skey'], '='));
			else $criteria->add(new Criteria('title', '%'.$_REQUEST['skey'].'%', 'like'));
		}
		$query->queryObjects($criteria);

		include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
		$topicrows=array();
		$k=0;
		while($topic = $query->getObject()){
			$topicrows[$k] = jieqi_topic_vars($topic);
			$k++;
		}
		$jieqiTpl->assign_by_ref('topicrows',$topicrows);

		//处理页面跳转
		$page_rowcount = $query->getCount($criteria); //总记录数
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		if(!empty($jieqiConfigs['forum']['maxsearchres']) && $page_rowcount > intval($jieqiConfigs['forum']['maxsearchres'])) $page_rowcount=intval($jieqiConfigs['forum']['maxsearchres']);
		$jumppage = new JieqiPage($page_rowcount,$_REQUEST['pagerows'],$_REQUEST['page']);
		$jumppage->setlink('', true, true);
		$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/searchlist.html';

		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
	case 'search':
	default:
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$search_form = new JieqiThemeForm($jieqiLang['forum']['forum_search'], 'search', $jieqiModules['forum']['url'].'/search.php');
		$search_form->addElement(new JieqiFormText($jieqiLang['forum']['search_keywords'], 'skey', 25, 60), true);
		$type_option = new JieqiFormRadio($jieqiLang['forum']['search_type'], 'type', 0);
		$type_option->addOption(0, $jieqiLang['forum']['search_post_title']);
		$type_option->addOption(1, $jieqiLang['forum']['search_post_author']);
		$search_form->addElement($type_option);
		$forum_select=new JieqiFormSelect($jieqiLang['forum']['search_area'], 'area');
		$forum_select->addOption('0|0', $jieqiLang['forum']['area_whole_forum']);
		include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
		include_once($jieqiModules['forum']['path'].'/class/forums.php');
		$criteria = new CriteriaCompo;
		$criteria->setSort('catorder');
		$criteria->setOrder('ASC');
		$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');
		$forumcat_handler->queryObjects($criteria);
		$forumcats=array();
		$i=0;
		while($v = $forumcat_handler->getObject()){
			$forumcats[$i]['catid'] = $v->getVar('catid');
			$forumcats[$i]['cattitle'] = $v->getVar('cattitle');
			$forumcats[$i]['cattitle_e'] = $v->getVar('cattitle','e');
			$i++;
		}
		unset($criteria);
		$criteria = new CriteriaCompo;
		$criteria->setSort('catid ASC, forumorder');
		$criteria->setOrder('ASC');
		$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
		$forums_handler->queryObjects($criteria);
		$forums=array();
		$i=0;
		while($v = $forums_handler->getObject()){
			$forums[$i]['catid'] = $v->getVar('catid');
			$forums[$i]['forumid'] = $v->getVar('forumid');
			$forums[$i]['forumname_e'] = $v->getVar('forumname', 'e');
			$forums[$i]['authview_n'] = $v->getVar('authview', 'n');
			$i++;
		}
		//论坛类别循环
		foreach($forumcats as $forumcat){
			$forum_select->addOption($forumcat['catid'].'|0', $forumcat['cattitle_e']);
			//论坛循环
			foreach($forums as $forum){
				if($forum['catid'] == $forumcat['catid']){
					//查看权限
					$viewpower['groups']=unserialize($forum['authview_n']);
					if(!is_array($viewpower['groups'])) $viewpower['groups']=array();
					if(jieqi_checkpower($viewpower, $jieqiUsersStatus, $jieqiUsersGroup, true)){
						$forum_select->addOption($forumcat['catid'].'|'.$forum['forumid'], '&nbsp;&gt; '.$forum['forumname_e']);
					}
				}
			}
		}
		$search_form->addElement($forum_select);
		$search_form->addElement(new JieqiFormHidden('action', 'show'));
		$search_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['forum']['search_button'], 'submit'));
		$jieqiTpl->assign('jieqi_contents', '<br />'.$search_form->render(JIEQI_FORM_MIDDLE).'<br />');

		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>