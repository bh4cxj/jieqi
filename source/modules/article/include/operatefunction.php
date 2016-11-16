<?php
/**
 * 删除文章、章节时候的通用函数
 *
 * 删除文章、章节时候的通用函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: operatefunction.php 330 2009-02-09 16:07:35Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;

include_once($jieqiModules['article']['path'].'/class/article.php');
if(!isset($article_handler)) $article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
include_once($jieqiModules['article']['path'].'/class/package.php');
include_once($jieqiModules['article']['path'].'/class/chapter.php');
if(!isset($chapter_handler)) $chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');

jieqi_getcachevars('article', 'articleuplog');
if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
if(!isset($jieqiConfigs['article'])) jieqi_getconfigs('article', 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

function jieqi_article_delete($aid, $usescore = true){
	global $jieqiModules;
	global $article_handler;
	global $chapter_handler;
	global $jieqiArticleuplog;
	global $jieqiConfigs;
	global $jieqi_file_postfix;

	$article=$article_handler->get($aid);
	if(!is_object($article)) return false;

	//删除文章
	$article_handler->delete($aid);
	//更新最新文章
	$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
	jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
	//删除文本、html及zip
	$package=new JieqiPackage($aid);
	$package->delete();
	//删除章节
	//检查这篇文章章节发表人，扣积分用
	if($usescore){
		$posterary=array();
		if(!empty($jieqiConfigs['article']['scorechapter'])){
			$criteria0=new CriteriaCompo(new Criteria('articleid', $aid, '='));
			$chapter_handler->queryObjects($criteria0);
			while($chapterobj = $chapter_handler->getObject()){
				$posterid = intval($chapterobj->getVar('posterid'));
				if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorechapter'];
				else  $posterary[$posterid] = $jieqiConfigs['article']['scorechapter'];
			}
			unset($criteria0);
		}
	}

	//真正删除章节
	$criteria=new CriteriaCompo(new Criteria('articleid', $aid, '='));
	$chapter_handler->delete($criteria);
	//删除附件
	include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
	$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
	$attachs_handler->delete($criteria);
	//删除书评
	$criteria1=new CriteriaCompo(new Criteria('ownerid', $aid, '='));
	include_once($jieqiModules['article']['path'].'/class/reviews.php');
	$reviews_handler =& JieqiReviewsHandler::getInstance('JieqiReviewsHandler');
	$reviews_handler->delete($criteria1);
	include_once($jieqiModules['article']['path'].'/class/replies.php');
	$replies_handler =& JieqiRepliesHandler::getInstance('JieqiRepliesHandler');
	$replies_handler->delete($criteria1);
	/*
	include_once($jieqiModules['article']['path'].'/class/review.php');
	$review_handler =& JieqiReviewHandler::getInstance('JieqiReviewHandler');
	$review_handler->delete($criteria);
	*/
	//删除封面
	$imagedir=jieqi_uploadpath($jieqiConfigs['article']['imagedir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
	if(is_dir($imagedir)) jieqi_delfolder($imagedir, true);

	//记录删除日志
	include_once($jieqiModules['article']['path'].'/class/articlelog.php');
	$articlelog_handler =& JieqiArticlelogHandler::getInstance('JieqiArticlelogHandler');
	$newlog=$articlelog_handler->create();
	$newlog->setVar('siteid', JIEQI_SITE_ID);
	$newlog->setVar('logtime', JIEQI_NOW_TIME);
	$newlog->setVar('userid', $_SESSION['jieqiUserId']);
	$newlog->setVar('username', $_SESSION['jieqiUserName']);
	$newlog->setVar('articleid', $article->getVar('articleid', 'n'));
	$newlog->setVar('articlename', $article->getVar('articlename', 'n'));
	$newlog->setVar('chapterid', 0);
	$newlog->setVar('chaptername', '');
	$newlog->setVar('reason', '');
	$newlog->setVar('chginfo', $jieqiLang['article']['delete_article']);
	$newlog->setVar('chglog', '');
	$newlog->setVar('ischapter', '0');
	$newlog->setVar('isdel', '1');
	$newlog->setVar('databak', serialize($article->getVars()));
	$articlelog_handler->insert($newlog);

	//减少文章和章节积分
	if($usescore){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($jieqiConfigs['article']['scorearticle'])){
			$posterid = intval($article->getVar('posterid'));
			if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorearticle'];
			else  $posterary[$posterid] = $jieqiConfigs['article']['scorearticle'];
		}
		foreach($posterary as $pid=>$pscore){
			$users_handler->changeScore($pid, $pscore, false);
		}
	}
	return $article;
}

//清理所有章节
function jieqi_article_clean($aid, $usescore = false){
	global $jieqiModules;
	global $article_handler;
	global $chapter_handler;
	global $jieqiArticleuplog;
	global $jieqiConfigs;

	$article=$article_handler->get($aid);
	if(!is_object($article)) return false;

	//清除文章统计
	$criteria = new CriteriaCompo(new Criteria('article', $aid));
	$fields=array('lastchapter'=>'', 'lastchapterid'=>0, 'lastvolume'=>'', 'lastvolumeid'=>0, 'chapters'=>0, 'size'=>0);
	$article_handler->updatefields($fields, $criteria);

	//更新最新文章
	$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
	$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
	jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');

	//删除文本、html及zip
	$package=new JieqiPackage($_REQUEST['id']);
	$package->delete();
	$package->initPackage(array('id'=>$article->getVar('articleid','n'), 'title'=>$article->getVar('articlename', 'n'), 'creatorid'=>$article->getVar('authorid','n'), 'creator'=>$article->getVar('author','n'), 'subject'=>$article->getVar('keywords','n'), 'description'=>$article->getVar('intro', 'n'), 'publisher'=>JIEQI_SITE_NAME, 'contributorid'=>$article->getVar('posterid', 'n'), 'contributor'=>$article->getVar('poster', 'n'), 'sortid'=>$article->getVar('sortid', 'n'), 'typeid'=>$article->getVar('typeid', 'n'), 'articletype'=>$article->getVar('articletype', 'n'), 'permission'=>$article->getVar('permission', 'n'), 'firstflag'=>$article->getVar('firstflag', 'n'), 'fullflag'=>$article->getVar('fullflag', 'n'), 'imgflag'=>$article->getVar('imgflag', 'n'), 'power'=>$article->getVar('power', 'n'), 'display'=>$article->getVar('display', 'n')));

	//删除章节

	//检查这篇文章章节发表人，扣积分用
	if($usescore){
		$posterary=array();
		if(!empty($jieqiConfigs['article']['scorechapter'])){
			$criteria0=new CriteriaCompo(new Criteria('articleid', $aid, '='));
			$chapter_handler->queryObjects($criteria0);
			while($chapterobj = $chapter_handler->getObject()){
				$posterid = intval($chapterobj->getVar('posterid'));
				if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorechapter'];
				else  $posterary[$posterid] = $jieqiConfigs['article']['scorechapter'];
			}
			unset($criteria0);
		}
	}

	//真正删除章节
	$criteria=new CriteriaCompo(new Criteria('articleid', $aid, '='));
	$chapter_handler->delete($criteria);
	//删除附件
	include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
	$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
	$attachs_handler->delete($criteria);

	//减少文章和章节积分
	if($usescore){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($jieqiConfigs['article']['scorearticle'])){
			$posterid = intval($article->getVar('posterid'));
			if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorearticle'];
			else  $posterary[$posterid] = $jieqiConfigs['article']['scorearticle'];
		}
		foreach($posterary as $pid=>$pscore){
			$users_handler->changeScore($pid, $pscore, false);
		}
	}
	return $article;
}

//清理一本书符合条件章节
function jieqi_article_delchapter($aid, $criteria, $usescore = false){
	global $jieqiModules;
	global $article_handler;
	global $chapter_handler;
	global $jieqiArticleuplog;
	global $jieqiConfigs;

	if(!is_object($criteria)) return false;
	$criteria->add(new Criteria('articleid', intval($aid)));
	$article=$article_handler->get($aid);
	if(!is_object($article)) return false;

	//查询符合条件章节
	$posterary=array();
	$chapter_handler->queryObjects($criteria);
	$chapterary = array();
	$k = 0;
	$cids = '';
	while($chapterobj = $chapter_handler->getObject()){
		$chapterary[$k]['id'] = intval($chapterobj->getVar('chapterid'));
		if($cids != '') $cids .= ',';
		$cids .= $chapterary[$k]['id'];
		$chapterary[$k]['size'] = $chapterobj->getVar('size');
		$chapterary[$k]['attach'] = $chapterobj->getVar('attachment', 'n') == '' ? 0 : 1;

		$k++;
		if(!empty($jieqiConfigs['article']['scorechapter'])){
			$posterid = intval($chapterobj->getVar('posterid'));
			if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorechapter'];
			else  $posterary[$posterid] = $jieqiConfigs['article']['scorechapter'];
		}
	}
	//删除章节
	$chapter_handler->delete($criteria);

	//删除附件数据库
	if($cids != ''){
		$criteria1 = new CriteriaCompo();
		$criteria1->add(new Criteria('chapterid', '('.$cids.')', 'IN'));
		include_once($jieqiModules['article']['path'].'/class/articleattachs.php');
		$attachs_handler =& JieqiArticleattachsHandler::getInstance('JieqiArticleattachsHandler');
		$attachs_handler->delete($criteria1);
	}
	//删除文本文件、附件文件、html
	$txtdir = jieqi_uploadpath($jieqiConfigs['article']['txtdir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
	$htmldir = jieqi_uploadpath($jieqiConfigs['article']['htmldir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
	$attachdir = jieqi_uploadpath($jieqiConfigs['article']['attachdir'], 'article').jieqi_getsubdir($aid).'/'.$aid;
	foreach($chapterary as $c){
		if(is_file($txtdir.'/'.$c['id'].$jieqi_file_postfix['txt'])) jieqi_delfile($txtdir.'/'.$c['id'].$jieqi_file_postfix['txt']);
		if(is_file($htmldir.'/'.$c['id'].$jieqiConfigs['article']['htmlfile'])) jieqi_delfile($htmldir.'/'.$c['id'].$jieqiConfigs['article']['htmlfile']);
		if(is_dir($attachdir.'/'.$c['id'])) jieqi_delfolder($attachdir.'/'.$c['id']);
	}
	//重新生成网页和打包
	include_once($jieqiModules['article']['path'].'/include/repack.php');
	$ptypes=array('makeopf'=>1, 'makehtml'=>$jieqiConfigs['article']['makehtml'], 'makezip'=>$jieqiConfigs['article']['makezip'], 'makefull'=>$jieqiConfigs['article']['makefull'], 'maketxtfull'=>$jieqiConfigs['article']['maketxtfull'], 'makeumd'=>$jieqiConfigs['article']['makeumd'], 'makejar'=>$jieqiConfigs['article']['makejar']);
	article_repack($aid, $ptypes, 0);
	//减少文章和章节积分
	if($usescore){
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($jieqiConfigs['article']['scorearticle'])){
			$posterid = intval($article->getVar('posterid'));
			if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['article']['scorearticle'];
			else  $posterary[$posterid] = $jieqiConfigs['article']['scorearticle'];
		}
		foreach($posterary as $pid=>$pscore){
			$users_handler->changeScore($pid, $pscore, false);
		}
	}

	//更新最新文章
	$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
	$jieqiArticleuplog['chapteruptime']=JIEQI_NOW_TIME;
	jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');

	return $article;
}
?>