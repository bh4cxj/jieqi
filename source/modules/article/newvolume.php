<?php 
/**
 * 新建分卷
 *
 * 增加一个分卷
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newvolume.php 330 2009-02-09 16:07:35Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['aid']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);

jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $article->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
	    $canedit=true;	
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_manage_article']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'volume';

switch($_REQUEST['action']) {
	case 'newvolume':
	$_POST['chaptername'] = trim($_POST['chaptername']);
	$errtext='';
	
	//检查标题
	if (strlen($_POST['chaptername'])==0) $errtext .= $jieqiLang['article']['need_colume_title'].'<br />';
	
	if(empty($errtext)) {
		$from_draft=false;
		$_POST['chaptertype']=2;
	    $volumeid=$article->getVar('chapters')+1;
	    $_POST['chaptercontent']='';
	    $attachinfo='';
		include_once($jieqiModules['article']['path'].'/include/addchapter.php');
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'volume':
	default:
	//包含区块参数(定制区块)
	jieqi_getconfigs('article', 'authorblocks', 'jieqiBlocks');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('article_static_url',$article_static_url);
    $jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	
	$chapter_form = new JieqiThemeForm($jieqiLang['article']['add_volume'], 'newvolume', $article_static_url.'/newvolume.php');
	$chapter_form->addElement(new JieqiFormLabel($jieqiLang['article']['table_article_articlename'], '<a href="'.$article_static_url.'/articlemanage.php?id='.$_REQUEST['aid'].'">'.$article->getVar('articlename').'</a>'));
	include_once($jieqiModules['article']['path'].'/class/chapter.php');
	$chapter_handler=& JieqiChapterHandler::getInstance('JieqiChapterHandler');
	$criteria = new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
	$criteria->add(new Criteria('chaptertype', 1));
	$criteria->setSort('chapterorder');
	$criteria->setOrder('ASC');
	$chapter_handler->queryObjects($criteria);
	$tmpvar='';
	while($v = $chapter_handler->getObject()){
		$tmpvar.=$v->getVar('chaptername').'<br />';
	}
	$chapter_form->addElement(new JieqiFormLabel($jieqiLang['article']['this_article_colume'], $tmpvar));
	$chapter_form->addElement(new JieqiFormText($jieqiLang['article']['add_new_volume'], 'chaptername', 50, 50), true);
	
	$chapter_form->addElement(new JieqiFormHidden('action', 'newvolume'));
	$chapter_form->addElement(new JieqiFormHidden('aid', $_REQUEST['aid']));
	$chapter_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
	
	$jieqiTpl->assign('authorarea', 1);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/newvolume.html';
	$jieqiTpl->assign('contents', '<br />'.$chapter_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}


?>