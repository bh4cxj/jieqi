<?php 
/**
 * 删除章节
 *
 * 删除某一个章节，然后重新生成阅读页面
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapterdel.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_loadlang('article', JIEQI_MODULE_NAME);
if($_GET['chaptertype']==1) $typename=$jieqiLang['article']['volume_name'];
else $typename=$jieqiLang['article']['chapter_name'];
if(empty($_REQUEST['id'])) jieqi_printfail(sprintf($jieqiLang['article']['chapter_volume_notexists'], $typename));
$_REQUEST['id']=intval($_REQUEST['id']);
//删除章节
include_once($jieqiModules['article']['path'].'/class/chapter.php');
$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
$chapter=$chapter_handler->get($_REQUEST['id']);
if(!$chapter) jieqi_printfail(sprintf($jieqiLang['article']['chapter_volume_notexists'], $typename));
include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
$article=$article_handler->get($chapter->getVar('articleid'));
if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改文章
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($article->getVar('authorid')==$tmpvar || $chapter->getVar('posterid')==$tmpvar || $article->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail(sprintf($jieqiLang['article']['noper_delete_chapter'], $typename));

$chapter_handler->delete($_REQUEST['id']);
//后面章节的序号前移一位
if($chapter->getVar('chapterorder')<$article->getVar('chapters')){
	$criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid')));
	$criteria->add(new Criteria('chapterorder', $chapter->getVar('chapterorder'), '>'));
	$chapter_handler->updatefields('chapterorder=chapterorder-1', $criteria);
}
unset($criteria);
//如果删除最后卷或者章节
$updateblock=false;
if($_REQUEST['id']>0 && $_REQUEST['id']==$article->getVar('lastchapterid')){
	$criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid')));
	$criteria->add(new Criteria('chaptertype', 0, '='));
	$criteria->setSort('chapterorder');
	$criteria->setOrder('DESC');
	$chapter_handler->queryObjects($criteria);
	$tmpchapter=$chapter_handler->getObject();
	if($tmpchapter){
		$article->setVar('lastchapter', $tmpchapter->getVar('chaptername', 'n'));
		$article->setVar('lastchapterid', $tmpchapter->getVar('chapterid', 'n'));
		unset($tmpchapter);
	}else{
		$article->setVar('lastchapter', '');
		$article->setVar('lastchapterid', 0);
	}
	$updateblock=true;
}elseif($_REQUEST['id']>0 && $_REQUEST['id']==$article->getVar('lastvolumeid')){
	$criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid')));
	$criteria->add(new Criteria('chaptertype', 1, '='));
	$criteria->setSort('chapterorder');
	$criteria->setOrder('DESC');
	$chapter_handler->queryObjects($criteria);
	$tmpchapter=$chapter_handler->getObject();
	if($tmpchapter){
		$article->setVar('lastvolume', $tmpchapter->getVar('chaptername', 'n'));
		$article->setVar('lastvolumeid', $tmpchapter->getVar('chapterid', 'n'));
		unset($tmpchapter);
	}else{
		$article->setVar('lastvolume', '');
		$article->setVar('lastvolumeid', 0);
	}
	$updateblock=true;
}
$article->setVar('chapters', $article->getVar('chapters')-1);
$article->setVar('size', $article->getVar('size') - $chapter->getVar('size'));
$article_handler->insert($article);
//更新最新文章
if($updateblock && $article->getVar('display')=='0'){
	jieqi_getcachevars('article', 'articleuplog');
	if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
	jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
}
include_once($jieqiModules['article']['path'].'/class/package.php');
$package=new JieqiPackage($article->getVar('articleid'));
$package->delChapter($chapter->getVar('chapterorder'), $chapter->getVar('chapterid'));
//删除附件记录
include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
$criteria=new CriteriaCompo(new Criteria('chapterid', $_REQUEST['id']));
$attachs_handler->delete($criteria);
//减少章节积分
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if(!empty($jieqiConfigs['article']['scorechapter'])){
	$users_handler->changeScore($chapter->getVar('posterid'), $jieqiConfigs['article']['scorechapter'], false);
}

jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$article->getVar('articleid'), LANG_DO_SUCCESS, sprintf($jieqiLang['article']['chapter_delete_success'], $typename));


?>