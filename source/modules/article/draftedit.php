<?php 
/**
 * 编辑草稿
 *
 * 编辑一篇草稿
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: draftedit.php 300 2008-12-26 04:36:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['newdraft'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('draft', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/draft.php');
$draft_handler =& JieqiDraftHandler::getInstance('JieqiDraftHandler');
$draft=$draft_handler->get($_REQUEST['id']);
if(!$draft) jieqi_printfail($jieqiLang['article']['draft_not_exists']);

if($draft->getVar('posterid') != $_SESSION['jieqiUserId']) jieqi_printfail($jieqiLang['article']['noper_manage_draft']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
switch ( $_REQUEST['action'] ) {
	case 'update':
	$_POST['chaptername'] = trim($_POST['chaptername']);
	$errtext='';
	//检查标题
	if (strlen($_POST['chaptername'])==0) $errtext .= $jieqiLang['article']['need_draft_title'].'<br />';
	if ($chaptertype != 2 && strlen($_POST['chaptercontent'])==0) $errtext .= $jieqiLang['article']['need_draft_content'].'<br />';
	
	if(empty($errtext)) {
		include_once($jieqiModules['article']['path'].'/class/draft.php');
		$draft_handler =& JieqiDraftHandler::getInstance('JieqiDraftHandler');
		$draft=$draft_handler->get($_REQUEST['id']);
		if(!$draft) jieqi_printfail($jieqiLang['article']['draft_not_exists']);
		//保存到草稿箱
		$draftsize=strlen($_POST['chaptercontent']);
		include_once($jieqiModules['article']['path'].'/class/article.php');
        $article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
        $article=$article_handler->get($_POST['articleid']);
		$draft->setVar('articleid', $_POST['articleid']);
		if(is_object($article))	$draft->setVar('articlename', $article->getVar('articlename', 'n'));
		else $draft->setVar('articlename', '');
		/*
		if(!empty($_SESSION['jieqiUserId'])){
		$draft->setVar('posterid', $_SESSION['jieqiUserId']);
		$draft->setVar('poster', $_SESSION['jieqiUserName']);
		}else{
		$draft->setVar('posterid', 0);
		$draft->setVar('poster', '');
		}
		*/
		$draft->setVar('lastupdate', JIEQI_NOW_TIME);
		$draft->setVar('draftname', $_POST['chaptername']);
		$draft->setVar('content', $_POST['chaptercontent']);
		$draft->setVar('size', $draftsize);
		$draft->setVar('drafttype', 0);
		if (!$draft_handler->insert($draft)) jieqi_printfail($jieqiLang['article']['draft_edit_failure']);
		else {
			jieqi_jumppage($article_dynamic_url.'/draft.php', LANG_DO_SUCCESS, $jieqiLang['article']['draft_edit_success']);
		}
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'edit':
	default:
	//包含区块参数(定制区块)
	jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	
	$draft_form = new JieqiThemeForm($jieqiLang['article']['draft_edit'], 'newchapter', $article_dynamic_url.'/draftedit.php');
	$aid=$draft->getVar('articleid');
	if(empty($aid)) $aid=NULL;
	$article_list = new JieqiFormSelect($jieqiLang['article']['table_draft_articleid'], 'articleid', $aid);
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria = new CriteriaCompo(new Criteria('authorid', $_SESSION['jieqiUserId']));
	//if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
	//	$criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId']), 'OR');
	//}
	$criteria->setLimit(100);
	//$criteria->setSort('lastupdate');
	//$criteria->setOrder('DESC');
	$article_handler->queryObjects($criteria);
    while($v = $article_handler->getObject()){
		$article_list->addOption($v->getVar('articleid'), $v->getVar('articlename'));
	}
	$draft_form->addElement($article_list);
	$draft_form->addElement(new JieqiFormText($jieqiLang['article']['table_draft_chaptername'], 'chaptername', 50, 50, $draft->getVar('draftname', 'e')), true);
	$draft_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_draft_chaptercontent'], 'chaptercontent', $draft->getVar('content', 'e'), 15, 60));
	$draft_form->addElement(new JieqiFormHidden('action', 'update'));
	$draft_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));
	$draft_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
	
	$jieqiTpl->assign('authorarea', 1);
	$jieqiTpl->assign('jieqi_contents', '<br />'.$draft_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}


?>