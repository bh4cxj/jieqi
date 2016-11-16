<?php
/**
 * 文章排行榜
 *
 * 支持按照点击数、推荐数等排行
 * 
 * 调用模板：/modules/article/templates/toplist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: toplist.php 337 2009-03-07 00:51:05Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
jieqi_loadlang('list', JIEQI_MODULE_NAME);

//包含配置参数
jieqi_getconfigs('article', 'configs');
jieqi_getconfigs('article', 'sort');
include_once(JIEQI_ROOT_PATH.'/header.php');
//排序方式
if(empty($_REQUEST['sort']) || !in_array($_REQUEST['sort'], array('allvisit', 'monthvisit', 'mouthvisit', 'weekvisit', 'dayvisit', 'allvote', 'monthvote', 'mouthvote', 'weekvote', 'dayvote', 'allauthorvisit', 'monthauthorvisit', 'mouthauthorvisit', 'weekauthorvisit', 'dayauthorvisit', 'postdate', 'toptime', 'goodnum', 'size', 'authorupdate', 'masterupdate', 'lastupdate', 'goodnew'))) $_REQUEST['sort']='lastupdate';
//类别
if(empty($_REQUEST['class']) || !is_numeric($_REQUEST['class']) || !isset($jieqiSort['article'][$_REQUEST['class']])) $_REQUEST['class']=0;
//文章子类
if(empty($_REQUEST['type']) || !is_numeric($_REQUEST['type']) || !isset($jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']])) $_REQUEST['type']=0;
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
if(defined('JIEQI_MAX_PAGES') && JIEQI_MAX_PAGES > 0 && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > JIEQI_MAX_PAGES) $_REQUEST['page'] = intval(JIEQI_MAX_PAGES);

if(!isset($jieqiConfigs['article']['toppagenum'])) $jieqiConfigs['article']['toppagenum']=$jieqiConfigs['article']['pagenum'];
if(!isset($jieqiConfigs['article']['topcachenum'])) $jieqiConfigs['article']['topcachenum']=$jieqiConfigs['article']['cachenum'];
//是否缓存
$content_used_cache=false;
// 个人修改
if(in_array($_REQUEST['sort'], array('allvote', 'monthvote', 'mouthvote', 'weekvote', 'dayvote'))) {
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/toplist.html';
}else{
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/toplist1.html';
}

//$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/toplist.html';
$jieqiTset['jieqi_contents_cacheid'] =  'toplist_'.$_REQUEST['sort'].'_'.$_REQUEST['class'].'_'.$_REQUEST['page'];

if (JIEQI_USE_CACHE && $_REQUEST['page']<=$jieqiConfigs['article']['topcachenum']){
	jieqi_getcachevars('article', 'articleuplog');
	if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	$uptime = $jieqiArticleuplog['articleuptime'] > $jieqiArticleuplog['chapteruptime'] ? $jieqiArticleuplog['articleuptime'] : $jieqiArticleuplog['chapteruptime'];
	$cachedtime = $jieqiTpl->get_cachedtime($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid']);

	if(in_array($_REQUEST['sort'], array('lastupdate', 'authorupdate', 'masterupdate', 'postdate'))){
		if($uptime - $cachedtime < 60 && JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME) $content_used_cache=true;
	}else{
		if(JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME) $content_used_cache=true;
	}
	if(!$content_used_cache){
		$jieqiTpl->update_cachedtime($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid']);
		$jieqiTpl->setCaching(2);
	}else{
		$jieqiTpl->setCaching(1);
	}
	$jieqiTpl->setCacheTime(99999999);
}else{
	$jieqiTpl->setCaching(0);
}

if(!$content_used_cache){
	
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
	$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	//载入相关处理函数
	include_once($jieqiModules['article']['path'].'/include/funarticle.php');
	//是否使用伪静态页面
	$jieqiTpl->assign('fakefile', $jieqiConfigs['article']['fakefile']);
	$jieqiTpl->assign('fakeinfo', $jieqiConfigs['article']['fakeinfo']);
	$jieqiTpl->assign('fakesort', $jieqiConfigs['article']['fakesort']);
	$jieqiTpl->assign('fakeinitial', $jieqiConfigs['article']['fakeinitial']);
	$jieqiTpl->assign('faketoplist', $jieqiConfigs['article']['faketoplist']);


	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria=new CriteriaCompo(new Criteria('display','0','='));
	$criteria->add(new Criteria('size','0','>'));
	//类别
	//jieqi_getconfigs('article', 'sort');
	$classinfo = '';
	if(!empty($_REQUEST['class']) && is_numeric($_REQUEST['class'])){
		$criteria->add(new Criteria('sortid',$_REQUEST['class'],'='));
		$classinfo .= ' - '.$jieqiSort['article'][$_REQUEST['class']]['caption'];
	}else{
		$_REQUEST['class']=0;
	}
	
	if(!empty($_REQUEST['type']) && is_numeric($_REQUEST['type'])){
		$criteria->add(new Criteria('typeid',$_REQUEST['type'],'='));
		$classinfo .= ' - '.$jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']];
	}else{
		$_REQUEST['type']=0;
	}

	$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
	$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
	$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
	$tmpvar=date('w',JIEQI_NOW_TIME);
	if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
	$weekstart=$daystart;
	if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;
	switch($_REQUEST['sort']){
		case 'allvisit':
		$criteria->setSort('allvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_allvisit_title'], $classinfo));
		break;
		case 'monthvisit':
		case 'mouthvisit':
		$criteria->add(new Criteria('lastvisit',$monthstart,'>='));
		$criteria->setSort('monthvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_monthvisit_title'], $classinfo));
		break;
		case 'weekvisit':
		$criteria->add(new Criteria('lastvisit',$weekstart,'>='));
		$criteria->setSort('weekvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_weekvisit_title'], $classinfo));
		break;
		case 'dayvisit':
		$criteria->add(new Criteria('lastvisit',$daystart,'>='));
		$criteria->setSort('dayvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_dayvisit_title'], $classinfo));
		break;
		case 'allauthorvisit':
		$criteria->add(new Criteria('authorid','0','>'));
		$criteria->setSort('allvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_avall_title'], $classinfo));
		break;
		case 'monthauthorvisit':
		case 'mouthauthorvisit':
		$criteria->add(new Criteria('lastvisit',$monthstart,'>='));
		$criteria->add(new Criteria('authorid','0','>'));
		$criteria->setSort('monthvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_avmonth_title'], $classinfo));
		break;
		case 'weekauthorvisit':
		$criteria->add(new Criteria('lastvisit',$weekstart,'>='));
		$criteria->add(new Criteria('authorid','0','>'));
		$criteria->setSort('weekvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_avweek_title'], $classinfo));
		break;
		case 'dayauthorvisit':
		$criteria->add(new Criteria('lastvisit',$daystart,'>='));
		$criteria->add(new Criteria('authorid','0','>'));
		$criteria->setSort('dayvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_avday_title'], $classinfo));
		break;
		case 'allvote':
		$criteria->setSort('allvote');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_voteall_title'], $classinfo));
		break;
		case 'monthvote':
		case 'mouthvote':
		$criteria->add(new Criteria('lastvote',$monthstart,'>='));
		$criteria->setSort('monthvote');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_votemonth_title'], $classinfo));
		break;
		case 'weekvote':
		$criteria->add(new Criteria('lastvote',$weekstart,'>='));
		$criteria->setSort('weekvote');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_voteweek_title'], $classinfo));
		break;
		case 'dayvote':
		$criteria->add(new Criteria('lastvote',$daystart,'>='));
		$criteria->setSort('dayvote');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_voteday_titile'], $classinfo));
		break;
		case 'postdate':
		$criteria->setSort('postdate');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_postdate_title'], $classinfo));
		break;
		case 'toptime':
		$criteria->setSort('toptime');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_toptime_title'], $classinfo));
		break;
		case 'goodnum':
		$criteria->setSort('goodnum');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_goodnum_title'], $classinfo));
		break;
		case 'size':
		$criteria->setSort('size');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_size_title'], $classinfo));
		break;
		case 'authorupdate':
		$criteria->setSort('lastupdate');
		$criteria->add(new Criteria('authorid', '0', '>'));
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_authorupdate_title'], $classinfo));
		break;
		case 'masterupdate':
		$criteria->setSort('lastupdate');
		$criteria->add(new Criteria('authorid', '0', '='));
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_masterupdate_title'], $classinfo));
		break;
		case 'goodnew':
		$criteria->setSort('allvisit + allvote * 10 + goodnum * 20');
		$criteria->add(new Criteria('postdate', JIEQI_NOW_TIME-(30 * 3600 * 24), '>'));
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_goodnew_title'], $classinfo));
		break;
		case 'lastupdate':
		default:
		$_REQUEST['sort']='lastupdate';
		$criteria->setSort('lastupdate');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_lastupdate_title'], $classinfo));
		break;
	}
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['article']['toppagenum']);
	$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['toppagenum']);
	$article_handler->queryObjects($criteria);
	$articlerows=array();
	$k=0;
	while($v = $article_handler->getObject()){
		$articlerows[$k] = jieqi_article_vars($v);
		$k++;
	}

	$jieqiTpl->assign_by_ref('articlerows', $articlerows);

	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	if (JIEQI_USE_CACHE){
		$pagecacheid = $_REQUEST['sort'].'_'.$_REQUEST['class'];
		jieqi_getcachevars('article', 'toplistlog');
		if(!is_array($jieqiToplistlog)) $jieqiToplistlog=array();
		if(!isset($jieqiToplistlog[$pagecacheid]) ||  JIEQI_NOW_TIME - $jieqiToplistlog[$pagecacheid]['time'] > JIEQI_CACHE_LIFETIME){
			$jieqiToplistlog[$pagecacheid] = array('rows' => $article_handler->getCount($criteria), 'time' => JIEQI_NOW_TIME);
			jieqi_setcachevars('toplistlog', 'jieqiToplistlog', $jieqiToplistlog, 'article');
		}
		$toplistrows = $jieqiToplistlog[$pagecacheid]['rows'];
	}else{
		$toplistrows = $article_handler->getCount($criteria);
	}

	$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['toppagenum'],$_REQUEST['page']);
	if(!empty($jieqiConfigs['article']['faketoplist'])){
		$jumppage->setlink(jieqi_geturl('article', 'toplist', 0, $_REQUEST['sort']));
	}
	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
}
//$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/toplist.html';
//$jieqiTset['jieqi_contents_cacheid'] =  'toplist_'.$_REQUEST['sort'].'_'.$_REQUEST['class'].'_'.$_REQUEST['page'];

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>