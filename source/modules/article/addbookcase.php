<?php
/**
 * 加入书架
 *
 * 加入书架或者书签的程序处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: addbookcase.php 322 2009-01-13 11:28:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
//<!--jieqi insert check code-->
if(empty($_REQUEST['bid']) && empty($_REQUEST['cid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_checklogin();
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
jieqi_loadlang('bookcase', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxnum=$jieqiConfigs['article']['maxbookmarks'];
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['article']['maxbookmarks']['honors'][$honorid]) && is_numeric($jieqiRight['article']['maxbookmarks']['honors'][$honorid])) $maxnum = intval($jieqiRight['article']['maxbookmarks']['honors'][$honorid]);

include_once($jieqiModules['article']['path'].'/class/bookcase.php');
$bookcase_handler =& JieqiBookcaseHandler::getInstance('JieqiBookcaseHandler');
$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
$cot=$bookcase_handler->getCount($criteria);
unset($criteria);

//超出收藏量加入书架需要积分
if(isset($jieqiConfigs['article']['addcasescore'])) $jieqiConfigs['article']['addcasescore']=intval($jieqiConfigs['article']['addcasescore']);
else $jieqiConfigs['article']['addcasescore']=0;
$needscore=false; //需要扣积分

if(!empty($_REQUEST['cid'])){
	//加入书签
	$article_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$criteria=new CriteriaCompo(new Criteria('c.chapterid', $_REQUEST['cid']));
	$criteria->setTables(jieqi_dbprefix('article_chapter').' c LEFT JOIN '.jieqi_dbprefix('article_article').' a ON c.articleid=a.articleid');
	$article_query->queryObjects($criteria);
	$chapter=$article_query->getObject();
	unset($criteria);
	if(!$chapter) jieqi_printfail($jieqiLang['article']['chapter_not_exists']);
	$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
	$criteria->add(new Criteria('articleid', $chapter->getVar('articleid')));
	$bookcase_handler->queryObjects($criteria);
	$bookcase=$bookcase_handler->getObject();
	if(!$bookcase){
		
		if($cot >= $maxnum){
			if($jieqiConfigs['article']['addcasescore']>0){
				if($_REQUEST['confirm']==1){
					if($_SESSION['jieqiUserScore']<$jieqiConfigs['article']['addcasescore']) jieqi_printfail($jieqiLang['article']['low_addcase_score']);
					else $voteneedscore=true;
				}else{
					jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['article']['addcase_need_score'], $maxnum, $jieqiConfigs['article']['addcasescore'], jieqi_addurlvars(array('confirm'=>1))));
				}
			}else{
				jieqi_printfail(sprintf($jieqiLang['article']['bookcase_is_full'], $maxnum));
			}
		}
		//没有在书架
		//增加文章的收藏量
        $article_query->execute('UPDATE '.jieqi_dbprefix('article_article').' SET goodnum=goodnum+1 WHERE articleid='.$chapter->getVar('articleid', 'n'));
		$bookcase=$bookcase_handler->create();
		$bookcase->setVar('joindate', JIEQI_NOW_TIME);
		$bookcase->setVar('lastvisit', JIEQI_NOW_TIME);
		$bookcase->setVar('flag', 0);
	}
	$bookcase->setVar('articleid', $chapter->getVar('articleid', 'n'));
	$bookcase->setVar('articlename', $chapter->getVar('articlename', 'n'));
	$bookcase->setVar('userid', $jieqiUsers->getVar('uid', 'n'));
	$bookcase->setVar('username', $jieqiUsers->getVar('uname', 'n'));
	$bookcase->setVar('chapterid', $chapter->getVar('chapterid', 'n'));
	$bookcase->setVar('chaptername', $chapter->getVar('chaptername', 'n'));
	$bookcase->setVar('chapterorder', $chapter->getVar('chapterorder', 'n'));
	if(!$bookcase_handler->insert($bookcase)) {
		jieqi_printfail($jieqiLang['article']['add_chaptermark_failure']);
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['add_chaptermark_success']);
	}
}elseif(!empty($_REQUEST['bid'])){
	//加入书架
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$article=$article_handler->get($_REQUEST['bid']);
	if(!$article) jieqi_printfail($jieqiLang['article']['article_not_exists']);
	
	$criteria=new CriteriaCompo(new Criteria('userid', $jieqiUsers->getVar('uid')));
	$criteria->add(new Criteria('articleid', $article->getVar('articleid')));
	$bookcase_handler->queryObjects($criteria);
	$bookcase=$bookcase_handler->getObject();
	if($bookcase){
		//已经在书架
		//$bookcase=$bookcases[0];
		jieqi_printfail($jieqiLang['article']['article_has_incase']);
	}else{
		if($cot >= $maxnum){
			if($jieqiConfigs['article']['addcasescore']>0){
				if($_REQUEST['confirm']==1){
					if($_SESSION['jieqiUserScore']<$jieqiConfigs['article']['addcasescore']) jieqi_printfail($jieqiLang['article']['low_addcase_score']);
					else $voteneedscore=true;
				}else{
					jieqi_msgwin(LANG_NOTICE, sprintf($jieqiLang['article']['addcase_need_score'], $maxnum, $jieqiConfigs['article']['addcasescore'], jieqi_addurlvars(array('confirm'=>1))));
				}
			}else{
				jieqi_printfail(sprintf($jieqiLang['article']['bookcase_is_full'], $maxnum));
			}
		}
		//没有在书架
		//增加文章的收藏量
        $article_handler->db->query('UPDATE '.jieqi_dbprefix('article_article').' SET goodnum=goodnum+1 WHERE articleid='.$_REQUEST['bid']);
		$bookcase=$bookcase_handler->create();
		$bookcase->setVar('joindate', JIEQI_NOW_TIME);
		$bookcase->setVar('lastvisit', JIEQI_NOW_TIME);
		$bookcase->setVar('flag', 0);
	}
	$bookcase->setVar('articleid', $article->getVar('articleid', 'n'));
	$bookcase->setVar('articlename', $article->getVar('articlename', 'n'));
	$bookcase->setVar('userid', $jieqiUsers->getVar('uid', 'n'));
	$bookcase->setVar('username', $jieqiUsers->getVar('uname', 'n'));
	$bookcase->setVar('chapterid', 0);
	$bookcase->setVar('chaptername', '');
	$bookcase->setVar('chapterorder', 0);
	if(!$bookcase_handler->insert($bookcase)) {
		jieqi_printfail($jieqiLang['article']['add_articlemark_failure']);
	}else{
		if($voteneedscore){
			//扣积分
			$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['addcasescore'], false, false);
		}
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['article']['add_articlemark_success']);
	}
	
}else{
	jieqi_printfail($jieqiLang['article']['article_not_exists']);
}

?>