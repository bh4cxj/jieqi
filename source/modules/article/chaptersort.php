<?php 
/**
 * 章节排序
 *
 * 一篇文章的章节调换顺序后重新排序和生成阅读页
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chaptersort.php 228 2008-11-27 06:44:31Z juny $
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
if(!$canedit) jieqi_printfail($jieqiLang['article']['noper_edit_article']);


$chaptercount=$article->getVar('chapters');
if(!isset($_REQUEST['fromid']) || $_REQUEST['fromid']<1 || $_REQUEST['fromid']>$chaptercount || !isset($_REQUEST['toid']) || $_REQUEST['toid']<0 || $_REQUEST['toid']>$chaptercount){
	jieqi_printfail($jieqiLang['article']['chapter_sort_errorpar']);
}

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

//无需排序
if($_REQUEST['fromid']==$_REQUEST['toid'] || $_REQUEST['fromid']==$_REQUEST['toid']+1){
	jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, $jieqiLang['article']['chapter_sort_success']);
}else{
	include_once($jieqiModules['article']['path'].'/class/chapter.php');
	$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
	$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid']));
	$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid']));
	$chapter_handler->queryObjects($criteria);
	$chapter=$chapter_handler->getObject();
	unset($criteria);
	if($chapter){
		$cid=$chapter->getVar('chapterid');
		if($_REQUEST['fromid']<$_REQUEST['toid']){
			$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '>'));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '<='));
			$chapter_handler->updatefields('chapterorder=chapterorder-1', $criteria);
			unset($criteria);
			$criteria=new CriteriaCompo(new Criteria('chapterid', $cid, '='));
			$chapter_handler->updatefields('chapterorder='.$_REQUEST['toid'], $criteria);
			unset($criteria);
		}else{
			$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['fromid'], '<'));
			$criteria->add(new Criteria('chapterorder', $_REQUEST['toid'], '>'));
			$chapter_handler->updatefields('chapterorder=chapterorder+1', $criteria);
			unset($criteria);
			$criteria=new CriteriaCompo(new Criteria('chapterid', $cid, '='));
			$chapter_handler->updatefields('chapterorder='.($_REQUEST['toid']+1), $criteria);
			unset($criteria);
		}
		include_once($jieqiModules['article']['path'].'/class/package.php');
		$package=new JieqiPackage($_REQUEST['aid']);
		$package->sortChapter($_REQUEST['fromid'], $_REQUEST['toid']);
		//检查最新卷和最新章节
		$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['aid'], '='));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$chapter_handler->queryObjects($criteria);
		$v=$chapter_handler->getObject();
		if($v){
			$nolastchapter=true;
			$nolastvolume=true;
			$lastchapter='';
			$lastchapterid=0;
			$lastvolume='';
			$lastvolumeid=0;
			do{
				if(!$nolastchapter && !$nolastvolume) break;
				if($v->getVar('chaptertype')==1){
					if($nolastvolume){
						$lastvolume=$v->getVar('chaptername', 'n');
						$lastvolumeid=$v->getVar('chapterid', 'n');
						$nolastvolume=false;
					}
				}else{
					if($nolastchapter){
						$lastchapter=$v->getVar('chaptername', 'n');
						$lastchapterid=$v->getVar('chapterid', 'n');
						$nolastchapter=false;
					}
				}
			}while($v = $chapter_handler->getObject());
			$updateblock=false;
			if($article->getVar('lastchapterid') != $lastchapterid){
				$article->setVar('lastchapterid', $lastchapterid);
				$article->setVar('lastchapter', $lastchapter);
				$updateblock=true;
			}
			if($article->getVar('lastvolumeid') != $lastvolumeid){
				$article->setVar('lastvolumeid', $lastvolumeid);
				$article->setVar('lastvolume', $lastvolume);
				$updateblock=true;
			}
			if($updateblock){
				$article_handler->insert($article);
				//更新最新文章
				if($article->getVar('display')=='0'){
					jieqi_getcachevars('article', 'articleuplog');
	                if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	                $jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
	                jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
				}
			}
		}
		jieqi_jumppage($article_static_url.'/articlemanage.php?id='.$_REQUEST['aid'], LANG_DO_SUCCESS, $jieqiLang['article']['chapter_sort_success']);
	}else jieqi_printfail($jieqiLang['article']['chapter_sort_notexists']);
	
}
?>