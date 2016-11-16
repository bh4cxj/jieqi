<?php
/**
 * 论坛版块列表
 *
 * 论坛版块列表
 * 
 * 调用模板：/modules/forum/templates/admin/forumlist.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumlist.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['forum']['manageforum'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
include_once($jieqiModules['forum']['path'].'/class/forums.php');
$criteria = new CriteriaCompo;
$criteria->setSort('catorder');
$criteria->setOrder('ASC');
$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');
$forumcat_handler->queryObjects($criteria);

$forumcatary=array();
$i=0;
while($v = $forumcat_handler->getObject()){
	$forumcatary[$i]['catid']=$v->getVar('catid');
	$forumcatary[$i]['cattitle']=$v->getVar('cattitle');
	$forumcatary[$i]['name']=$v->getVar('cattitle');
	$forumcatary[$i]['order']=$v->getVar('catorder');
	$forumcatary[$i]['manage']='<a href="'.$jieqiModules['forum']['url'].'/admin/forumcatmanage.php?id='.$v->getVar('catid').'">'.$jieqiLang['forum']['manage_forumcat'].'</a> | <a href="'.$jieqiModules['forum']['url'].'/admin/forumdel.php?type=forumcat&id='.$v->getVar('catid').'">'.$jieqiLang['forum']['delete_forumcat'].'</a>';
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
	$forums[$i]['catid']=$v->getVar('catid');
	$forums[$i]['forumid']=$v->getVar('forumid');
	$forums[$i]['forumname']=$v->getVar('forumname');
	$forums[$i]['forumname_e']=$v->getVar('forumname', 'e');
	$forums[$i]['forumorder']=$v->getVar('forumorder');
	$forums[$i]['forumdesc']=$v->getVar('forumdesc');
	$forums[$i]['forumtopics']=$v->getVar('forumtopics');
	$forums[$i]['forumposts']=$v->getVar('forumposts');
	$i++;
}
$forumary=array();
$i=0;
//论坛类别循环
$forumlist=array();
$k=0;
foreach($forumcatary as $forumcat){
	$j=0;
	//论坛循环
	foreach($forums as $forum){
		if($forum['catid'] == $forumcat['catid']){
			$forumary[$i][$j]['icon']='<img src="'.$jieqiModules['forum']['url'].'/images/nonew_big.gif">';
			$forumary[$i][$j]['name']='<a href="'.$jieqiModules['forum']['url'].'/topiclist.php?fid='.$forum['forumid'].'" target="_blank"><strong>'.$forum['forumname'].'</strong></a>';
			$forumary[$i][$j]['order']=$forum['forumorder'];
			$forumary[$i][$j]['desc']=$forum['forumdesc'];
			$forumary[$i][$j]['topics']=$forum['forumtopics'];
			$forumary[$i][$j]['posts']=$forum['forumposts'];
			$forumary[$i][$j]['manage']='<a href="'.$jieqiModules['forum']['url'].'/admin/forummanage.php?id='.$forum['forumid'].'">'.$jieqiLang['forum']['manage_forum'].'</a> | <a href="'.$jieqiModules['forum']['url'].'/admin/forumdel.php?type=forum&id='.$forum['forumid'].'">'.$jieqiLang['forum']['delete_forum'].'</a>';
			$j++;
			$forumlist[$k]['id']=$forum['forumid'];
			$forumlist[$k]['title']=$forum['forumname_e'];
			$k++;
		}
	}
	$i++;
}

include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
//论坛分类
$forumcat_form = new JieqiThemeForm($jieqiLang['forum']['add_forumcat'], 'newforumcat', $jieqiModules['forum']['url'].'/admin/newforumcat.php');
$forumcat_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumcat_cattitle'], 'cattitle', 30, 50), true);
$forumcat_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumcat_catorder'], 'catorder', 30, 50, '0'));
$forumcat_form->addElement(new JieqiFormHidden('action', 'newforumcat'));
$forumcat_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
$jieqiTpl->assign('forumcat_form', $forumcat_form->render(JIEQI_FORM_MAX));

//论坛
$forum_form = new JieqiThemeForm($jieqiLang['forum']['add_forum'], 'newforum', $jieqiModules['forum']['url'].'/admin/newforum.php');
$sort_select = new JieqiFormSelect($jieqiLang['forum']['table_forums_catid'],'catid');
foreach($forumcatary as $forumcat){
	$sort_select->addOption($forumcat['catid'], $forumcat['cattitle']);
}
$forum_form->addElement($sort_select);
$forum_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forums_forumname'], 'forumname', 30, 50), true);
$forum_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forums_forumorder'], 'forumorder', 30, 50, '0'));
$forum_form->addElement(new JieqiFormTextArea($jieqiLang['forum']['table_forums_forumdesc'], 'forumdesc', '', 6, 60));
$forum_form->addElement(new JieqiFormHidden('action', 'newforum'));
$forum_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
$jieqiTpl->assign('forum_form', $forum_form->render(JIEQI_FORM_MAX));

//合并论坛
$union_form = new JieqiThemeForm($jieqiLang['forum']['union_forum'], 'unionforum', $jieqiModules['forum']['url'].'/admin/unionforum.php');
$from_select = new JieqiFormSelect($jieqiLang['forum']['union_fromid'],'fromid');
$to_select = new JieqiFormSelect($jieqiLang['forum']['union_toid'],'toid');
foreach($forumlist as $forum){
	$from_select->addOption($forum['id'], $forum['title']);
	$to_select->addOption($forum['id'], $forum['title']);
}
$union_form->addElement($from_select);
$union_form->addElement($to_select);
$union_form->addElement(new JieqiFormHidden('action', 'union'));
$union_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
$jieqiTpl->assign('union_form', $union_form->render(JIEQI_FORM_MAX));

$jieqiTpl->assign_by_ref('forumcats',$forumcatary);
$jieqiTpl->assign_by_ref('forums',$forumary);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/admin/forumlist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>