<?php 
/**
 * 批量删除章节
 *
 * 可以删除一篇文章的多个章节
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chaptersdel.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['articleid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('article', JIEQI_MODULE_NAME);
if(empty($_REQUEST['chapterid'])) jieqi_printfail($jieqiLang['article']['noselect_delete_chapter']);
$_REQUEST['articleid'] = intval($_REQUEST['articleid']);
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($_REQUEST['articleid']);
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);

//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_delete_chapters']);

//执行删除
$cids='';
foreach ($_REQUEST['chapterid'] as $cid){
	$cid = intval($cid);
	if($cid){
		if($cids != '') $cids .= ', ';
		$cids .= $cid;
	}
}
if($cids != ''){
	include_once($jieqiModules['article']['path'].'/include/operatefunction.php');
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('chapterid', '('.$cids.')', 'IN'));
	jieqi_article_delchapter($_REQUEST['articleid'], $criteria, true);
}
jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$_REQUEST['articleid'], LANG_DO_SUCCESS, $jieqiLang['article']['chapter_batchdel_success']);


?>