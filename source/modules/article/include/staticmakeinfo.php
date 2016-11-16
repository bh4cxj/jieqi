<?php 
/**
 * 生成静态文章信息页面
 *
 * 生成静态文章信息页面
 * 
 * 调用模板：/modules/article/templates/articleinfo.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: staticmakeinfo.php 332 2009-02-23 09:15:08Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
$jieqiConfigs['article']['infochapternum']=0; //文章信息页面显示几行最新章节
jieqi_loadlang('article', JIEQI_MODULE_NAME);
jieqi_getconfigs('article', 'guideblocks', 'jieqiBlocks');
include_once(JIEQI_ROOT_PATH.'/header.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
function makestaticinfo($article_id){
	global $jieqiConfigs;
	global $jieqiSort;
	global $jieqiTpl;
	global $jieqiBlocks;
	global $jieqi_file_postfix;
	if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
	if(empty($article_id) || !is_numeric($article_id)) return false;
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$article=$article_handler->get($article_id);
	if(!$article) return false;
	//包含区块参数(定制)
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
	$jieqi_pagetitle=$article->getVar('articlename');
	if($article->getVar('lastvolume') != '') $jieqi_pagetitle.='-'.$article->getVar('lastvolume');
	$jieqi_pagetitle.='-'.$article->getVar('lastchapter').'-'.JIEQI_SITE_NAME;
	$jieqiTpl->assign('jieqi_pagetitle',$jieqi_pagetitle);
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
	$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

	$jieqiTpl->assign('articlename', $article->getVar('articlename'));
	$jieqiTpl->assign('postdate', date('Y-m-d', $article->getVar('postdate')));
	$jieqiTpl->assign('lastupdate', date('Y-m-d', $article->getVar('lastupdate')));
	if($article->getVar('authorid') > 0){
		$jieqiTpl->assign('author', '<a href="'.jieqi_geturl('system', 'user', $article->getVar('authorid')).'" target="_blank">'.$article->getVar('author').'</a>');
	}else{
		$jieqiTpl->assign('author', $article->getVar('author'));
	}

	if($article->getVar('agentid') > 0){
		$jieqiTpl->assign('agent', '<a href="'.jieqi_geturl('system', 'user', $article->getVar('agentid')).'" target="_blank">'.$article->getVar('agent').'</a>');
	}else{
		$jieqiTpl->assign('agent', $article->getVar('agent'));
	}

	$jieqiTpl->assign('sortid', $article->getVar('sortid'));
	$jieqiTpl->assign('sort', $jieqiSort['article'][$article->getVar('sortid')]['caption']);
	$jieqiTpl->assign('intro',$article->getVar('intro'));
	$jieqiTpl->assign('notice', $article->getVar('notice'));
	$imgflag=$article->getVar('imgflag');
	//小图
	if(($imgflag & 1)>0){
		$jieqiTpl->assign('hasimage', 1);
		$jieqiTpl->assign('url_simage', jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').'/'.$article->getVar('articleid').'s'.$jieqiConfigs['article']['imagetype']);
	}else{
		$jieqiTpl->assign('hasimage', 0);
		$jieqiTpl->assign('url_simage','');
	}
	//大图
	if(($imgflag & 2)>0){
		$jieqiTpl->assign('url_limage', jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').'/'.$article->getVar('articleid').'l'.$jieqiConfigs['article']['imagetype']);
	}elseif(($imgflag & 1)>0){
		$jieqiTpl->assign('url_limage', jieqi_uploadurl($jieqiConfigs['article']['imagedir'], $jieqiConfigs['article']['imageurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').'/'.$article->getVar('articleid').'s'.$jieqiConfigs['article']['imagetype']);
	}else{
		$jieqiTpl->assign('url_limage','');
	}
	$lastchapter=$article->getVar('lastchapter');
	if($lastchapter != ''){
		if($article->getVar('lastvolume') != '') $lastchapter=$article->getVar('lastvolume').' '.$lastchapter;
		$jieqiTpl->assign('url_lastchapter', jieqi_geturl('article', 'chapter', $article->getVar('lastchapterid'), $article->getVar('articleid')));
	}else{
		$jieqiTpl->assign('url_lastchapter', '');
	}

	//显示多个最新章节
	if(is_numeric($jieqiConfigs['article']['infochapternum']) && intval($jieqiConfigs['article']['infochapternum'])>0){
		$jieqiConfigs['article']['infochapternum']=intval($jieqiConfigs['article']['infochapternum']);
		include_once($GLOBALS['jieqiModules']['article']['path'].'/class/chapter.php');
		$chapter_handler =& JieqiChapterHandler::getInstance('JieqiChapterHandler');
		$criteria=new CriteriaCompo(new Criteria('articleid', $article->getVar('articleid'), '='));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$criteria->setLimit($jieqiConfigs['article']['infochapternum']+ceil($jieqiConfigs['article']['infochapternum']/4));
		$chapter_handler->queryObjects($criteria);
		$chapterrows=array();
		$k=0;
		$volumename=$article->getVar('lastvolume');
		while($chapter = $chapter_handler->getObject()){
			if($chapter->getVar('chaptertype')==1){
				$volumename=$chapter->getVar('chaptername');
			}else{
				$chapterrows[$k]['volumename']=$volumename;
				$chapterrows[$k]['chaptername']=$chapter->getVar('chaptername');
				if($chapterrows[$k]['volumename'] != '') $chapterrows[$k]['chapter']=$chapterrows[$k]['volumename'].' '.$chapterrows[$k]['chaptername'];
				else $chapterrows[$k]['chapter']=$chapterrows[$k]['chaptername'];
				$chapterrows[$k]['chapterid']=$chapter->getVar('chapterid');
				$chapterrows[$k]['url_chapter']=jieqi_geturl('article', 'chapter', $chapter->getVar('chapterid'), $article->getVar('articleid'));

				$k++;
				if($k>=$jieqiConfigs['article']['infochapternum']) break;
			}
		}
		$jieqiTpl->assign_by_ref('chapterrows', $chapterrows);
	}

	$jieqiTpl->assign('lastchapter', $lastchapter);
	$jieqiTpl->assign('size', $article->getVar('size'));
	$jieqiTpl->assign('size_k', ceil($article->getVar('size')/1024));
	$jieqiTpl->assign('size_c', ceil($article->getVar('size')/2));
	$jieqiTpl->assign('dayvisit', $article->getVar('dayvisit'));
	$jieqiTpl->assign('weekvisit', $article->getVar('weekvisit'));
	$jieqiTpl->assign('monthvisit', $article->getVar('monthvisit'));
	$jieqiTpl->assign('allvisit', $article->getVar('allvisit'));
	$jieqiTpl->assign('dayvote', $article->getVar('dayvote'));
	$jieqiTpl->assign('weekvote', $article->getVar('weekvote'));
	$jieqiTpl->assign('monthvote', $article->getVar('monthvote'));
	$jieqiTpl->assign('allvote', $article->getVar('allvote'));
	$jieqiTpl->assign('goodnum', $article->getVar('goodnum'));
	$jieqiTpl->assign('badnum', $article->getVar('badnum'));
	if($article->getVar('fullflag')==0) $jieqiTpl->assign('fullflag', $jieqiLang['article']['article_not_full']);
	else $jieqiTpl->assign('fullflag', $jieqiLang['article']['article_is_full']);
	$tmpvar='';
	switch($article->getVar('permission')){
		case '3':
			$tmpvar=$jieqiLang['article']['article_permission_special'];
			break;
		case '2':
			$tmpvar=$jieqiLang['article']['article_permission_insite'];
			break;
		case '1':
			$tmpvar=$jieqiLang['article']['article_permission_yes'];
			break;
		case '0':
		default:
			$tmpvar=$jieqiLang['article']['article_permission_no'];
			break;
	}
	$jieqiTpl->assign('permission', $tmpvar);
	$tmpvar='';
	switch($article->getVar('firstflag')){
		case '1':
			$tmpvar=$jieqiLang['article']['article_site_publish'];
			break;
		case '0':
		default:
			$tmpvar=$jieqiLang['article']['article_other_publish'];
			break;
	}
	$jieqiTpl->assign('firstflag', $tmpvar);
	//管理
	$jieqiTpl->assign('url_manage', $article_static_url.'/articlemanage.php?id='.$article->getVar('articleid'));
	//举报
	$tmpstr=sprintf($jieqiLang['article']['article_report_reason'], jieqi_geturl('article', 'article', $article->getVar('articleid'), 'info'));
	$jieqiTpl->assign('url_report', $article_dynamic_url.'/newmessage.php?tosys=1&title='.urlencode(sprintf($jieqiLang['article']['article_report_title'], $article->getVar('articlename','n'))).'&content='.urlencode($tmpstr));
	//采集
	$setting=unserialize($article->getVar('setting', 'n'));	$url_collect=$article_static_url.'/collect.php?toid='.$article->getVar('articleid', 'n');
	if(is_numeric($setting['fromarticle'])) $url_collect.='&fromid='.$setting['fromarticle'];
	if(is_numeric($setting['fromsite'])) $url_collect.='&siteid='.$setting['fromsite'];
	$jieqiTpl->assign('url_collect', $url_collect);

	//文章序号
	$jieqiTpl->assign('articleid', $article->getVar('articleid'));
	//点击阅读,全文阅读
	if($article->getVar('chapters','n')>0){
		$jieqiTpl->assign('url_read', jieqi_geturl('article', 'article', $article->getVar('articleid'), 'index'));
		if($jieqiConfigs['article']['makefull']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
			$jieqiTpl->assign('url_fullpage', $article_static_url.'/reader.php?aid='.$article->getVar('articleid'));
		}else{
			$jieqiTpl->assign('url_fullpage', jieqi_uploadurl($jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['fullurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').$jieqiConfigs['article']['htmlfile']);
		}
	}else{
		$jieqiTpl->assign('url_read', '#');
		$jieqiTpl->assign('url_fullpage', '#');
	}


	//放入书架
	$jieqiTpl->assign('url_bookcase', $article_dynamic_url.'/addbookcase.php?bid='.$article->getVar('articleid'));
	//推荐本书
	$jieqiTpl->assign('url_uservote', $article_dynamic_url.'/uservote.php?id='.$article->getVar('articleid'));
	//作家专栏
	if($article->getVar('authorid')>0){
		$jieqiTpl->assign('url_authorpage', $article_dynamic_url.'/authorpage.php?id='.$article->getVar('authorid'));
	}else{
		$jieqiTpl->assign('url_authorpage','#');
	}
	//打包下载
	if($article->getVar('chapters','n')>0){
		if($jieqiConfigs['article']['makehtml']==0){
			$jieqiTpl->assign('url_download', '#');
		}else{
			$jieqiTpl->assign('url_download', jieqi_uploadurl($jieqiConfigs['article']['zipdir'], $jieqiConfigs['article']['zipurl'], 'article', $article_static_url).jieqi_getsubdir($article->getVar('articleid')).'/'.$article->getVar('articleid').$jieqi_file_postfix['zip']);
		}
	}else{
		$jieqiTpl->assign('url_download', '#');
	}
	//电子书部分
	$articletype=intval($article->getVar('articletype'));
	if(($articletype & 1)>0) $hasebook=1;
	else $hasebook=0;
	if(($articletype & 2)>0) $hasobook=1;
	else $hasobook=0;
	if(($articletype & 4)>0) $hastbook=1;
	else $hastbook=0;
	
	if($hasobook==1){
		include_once($GLOBALS['jieqiModules']['obook']['path'].'/class/obook.php');
		$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('articleid', $article->getVar('articleid'), '='));
		$obook_handler->queryObjects($criteria);
		$obook=$obook_handler->getObject();
		if(is_object($obook) && $obook->getVar('display')==0 && $obook->getVar('size')>0){
			$jieqiTpl->assign('obook_obookid', $obook->getVar('obookid'));
			$jieqiTpl->assign('obook_lastvolume', $obook->getVar('lastvolume'));
			$jieqiTpl->assign('obook_lastvolumeid', $obook->getVar('lastvolumeid'));
			$jieqiTpl->assign('obook_lastchapter', $obook->getVar('lastchapter'));
			$jieqiTpl->assign('obook_lastchapterid', $obook->getVar('lastchapterid'));
			$jieqiTpl->assign('obook_lastupdate', date(JIEQI_DATE_FORMAT, $obook->getVar('lastupdate')));
			$jieqiTpl->assign('obook_size', $obook->getVar('size'));
			$jieqiTpl->assign('obook_publishid', $obook->getVar('publishid'));
		}else{
			$hasobook=0;
		}
	}
	$jieqiTpl->assign('articletype', $articletype);
	$jieqiTpl->assign('hasebook', $hasebook);
	$jieqiTpl->assign('hasobook', $hasobook);
	$jieqiTpl->assign('hastbook', $hastbook);


	$jieqiTpl->assign('url_goodreview', $article_dynamic_url.'/review.php?aid='.$article->getVar('articleid').'&type=good');
	$jieqiTpl->assign('url_allreview', $article_dynamic_url.'/review.php?aid='.$article->getVar('articleid').'&type=all');
	$jieqiTpl->assign('url_review', $article_dynamic_url.'/review.php?aid='.$article->getVar('articleid'));

	if(!empty($_SESSION['jieqiUserId'])) $jieqiTpl->assign('enablepost', 1);
	else $jieqiTpl->assign('enablepost', 0);
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/staticinfo.html'));
	ob_start();
	include(JIEQI_ROOT_PATH.'/footer.php');
	$pagecontent=ob_get_contents();
	ob_end_clean();
	if(!empty($jieqiConfigs['article']['fakeprefix']))	$dirname=JIEQI_ROOT_PATH.'/'.$jieqiConfigs['article']['fakeprefix'].'info';
	else $dirname=JIEQI_ROOT_PATH.'/files/article/info';
	if(!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname=$dirname.jieqi_getsubdir($article->getVar('articleid', 'n'));
	if (!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname.='/'.$article->getVar('articleid', 'n').$jieqiConfigs['article']['fakefile'];
	jieqi_writefile($dirname, $pagecontent);
	return true;
}

?>