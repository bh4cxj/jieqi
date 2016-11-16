<?php 
/**
 * 新建草稿
 *
 * 增加一篇草稿
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newdraft.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['newdraft'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('draft', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'draft';

switch($_REQUEST['action']) {
	case 'newdraft':
	$_POST['draftname'] = trim($_POST['draftname']);
	$errtext='';
	//检查标题
	if (strlen($_POST['draftname'])==0) $errtext .= $jieqiLang['article']['need_draft_title'].'<br />';
	if (strlen($_POST['draftcontent'])==0) $errtext .= $jieqiLang['article']['need_draft_content'].'<br />';
	
	if(empty($errtext)) {
		//内容排版
		if($jieqiConfigs['article']['authtypeset']==2 || ($jieqiConfigs['article']['authtypeset']==1 && $_POST['typeset']==1)){
			include_once(JIEQI_ROOT_PATH.'/lib/text/texttypeset.php');
	        $texttypeset=new TextTypeset();
	        $_POST['draftcontent']=$texttypeset->doTypeset($_POST['draftcontent']);
		}
		//保存到草稿箱
		include_once($jieqiModules['article']['path'].'/class/draft.php');
		$draft_handler =& JieqiDraftHandler::getInstance('JieqiDraftHandler');
		$newDraft = $draft_handler->create();
		$draftsize=strlen($_POST['draftcontent']);
		include_once($jieqiModules['article']['path'].'/class/article.php');
        $article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
        $article=$article_handler->get($_POST['articleid']);
		$newDraft->setVar('articleid', $_POST['articleid']);
		if(is_object($article))	$newDraft->setVar('articlename', $article->getVar('articlename', 'n'));
		else $newDraft->setVar('articlename', '');
		if(!empty($_SESSION['jieqiUserId'])){
			$newDraft->setVar('posterid', $_SESSION['jieqiUserId']);
			$newDraft->setVar('poster', $_SESSION['jieqiUserName']);
		}else{
			$newDraft->setVar('posterid', 0);
			$newDraft->setVar('poster', '');
		}
		$newDraft->setVar('postdate', JIEQI_NOW_TIME);
		$newDraft->setVar('lastupdate', JIEQI_NOW_TIME);
		$newDraft->setVar('draftname', $_POST['draftname']);
		$newDraft->setVar('content', $_POST['draftcontent']);
		$newDraft->setVar('size', $draftsize);
		$newDraft->setVar('note', '');
		$newDraft->setVar('drafttype', 0);
		if (!$draft_handler->insert($newDraft)) jieqi_printfail($jieqiLang['article']['draft_add_failure']);
		else {
			jieqi_jumppage($article_dynamic_url.'/draft.php', LANG_DO_SUCCESS, $jieqiLang['article']['draft_add_success']);
		}
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'draft':
	default:
	//包含区块参数(定制区块)
	jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('article_static_url',$article_static_url);
    $jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	
	$draft_form = new JieqiThemeForm($jieqiLang['article']['draft_new'], 'newdraft', $article_dynamic_url.'/newdraft.php');
	$article_list = new JieqiFormSelect($jieqiLang['article']['table_draft_articleid'], 'articleid');
	$article_list->addOption(0, ' ');
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria = new CriteriaCompo(new Criteria('authorid', $_SESSION['jieqiUserId']));
	//if(jieqi_checkpower($jieqiPower['article']['transarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
	//	$criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId']), 'OR');
	//}
	$criteria->setLimit(100);
	$article_handler->queryObjects($criteria);
	while($v = $article_handler->getObject()){
		$article_list->addOption($v->getVar('articleid'), $v->getVar('articlename'));
	}
	$draft_form->addElement($article_list);
	$draft_form->addElement(new JieqiFormText($jieqiLang['article']['table_draft_chaptername'], 'draftname', 50, 50), true);
	if($jieqiConfigs['article']['authtypeset']==1){
		$typeset=new JieqiFormRadio($jieqiLang['article']['draft_typeset'], 'typeset', $jieqiConfigs['article']['autotypeset']);
		$typeset->addOption('1', $jieqiLang['article']['draft_auto_typeset']);
		$typeset->addOption('0', $jieqiLang['article']['draft_no_typeset']);
		$draft_form->addElement($typeset);
	}
	$draft_form->addElement(new JieqiFormTextArea($jieqiLang['article']['table_draft_chaptercontent'], 'draftcontent', '', 15, 60));
	$draft_form->addElement(new JieqiFormHidden('action', 'newdraft'));
	$draft_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
	
	$jieqiTpl->assign('authorarea', 1);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/newdraft.html';
	$jieqiTpl->assign('contents', $draft_form->render(JIEQI_FORM_MIDDLE));
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}


?>