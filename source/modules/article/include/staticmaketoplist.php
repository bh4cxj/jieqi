<?php
/**
 * 生成静态的排行榜
 *
 * 生成静态的排行榜
 * 
 * 调用模板：/modules/article/templates/toplist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: staticmaketoplist.php 332 2009-02-23 09:15:08Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs('article', 'guideblocks', 'jieqiBlocks');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
//载入相关处理函数
include_once($jieqiModules['article']['path'].'/include/funarticle.php');
function makestatictoplist($sort='',$class='',$thispage=0,$maxpage=0){
	global $jieqiConfigs;
	global $jieqiSort;
	global $jieqiTpl;
	global $jieqiBlocks;
	if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
	//排序方式
	if(empty($sort)) $sort='lastupdate';
	//类别
	if(empty($class) || !is_numeric($class)) $class=0;
	//页码
	if (empty($thispage) || !is_numeric($thispage)) $thispage=1;

	//是否缓存
	$content_used_cache=false;
	$jieqiTpl->setCaching(0);
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
	$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
	//是否使用伪静态页面
	$jieqiTpl->assign('fakefile', $jieqiConfigs['article']['fakefile']);
	$jieqiTpl->assign('fakeinfo', $jieqiConfigs['article']['fakeinfo']);
	$jieqiTpl->assign('fakesort', $jieqiConfigs['article']['fakesort']);
	$jieqiTpl->assign('fakeinitial', $jieqiConfigs['article']['fakeinitial']);
	$jieqiTpl->assign('faketoplist', $jieqiConfigs['article']['faketoplist']);

	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	$criteria=new CriteriaCompo(new Criteria('display','0','='));
	$criteria->add(new Criteria('size','0','>'));
	//类别
	jieqi_getconfigs('article', 'sort');
	if(!empty($class) && is_numeric($class)){
		$criteria->add(new Criteria('sortid',$class,'='));
		$classinfo=' - '.$jieqiSort['article'][$class]['caption'];
	}else{
		$class=0;
		$classinfo='';
	}

	$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
	$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
	$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
	$tmpvar=date('w',JIEQI_NOW_TIME);
	if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
	$weekstart=$daystart;
	if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;
	switch($sort){
		case 'allvisit':
		$criteria->setSort('allvisit');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_allvisit_title'], $classinfo));
		break;
		case 'monthvisit':
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
		case 'lastupdate':
		default:
		$_REQUEST['sort']='lastupdate';
		$criteria->setSort('lastupdate');
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['top_lastupdate_title'], $classinfo));
		break;
	}
	$criteria->setOrder('DESC');
	$criteria->setLimit($jieqiConfigs['article']['pagenum']);
	$criteria->setStart(($thispage-1) * $jieqiConfigs['article']['pagenum']);
	$article_handler->queryObjects($criteria);
	$articlerows=array();
	$k=0;
	while($v = $article_handler->getObject()){
		$articlerows[$k] = jieqi_article_vars($v);
		$k++;
	}

	$jieqiTpl->assign_by_ref('articlerows', $articlerows);

	//处理页面跳转
	$toplistrows = $article_handler->getCount($criteria);
	$truemaxpage=ceil($toplistrows / $jieqiConfigs['article']['pagenum']);
	$maxpage=intval($maxpage);
	if(!empty($maxpage) && $maxpage<$truemaxpage) $toplistrows = $maxpage * $jieqiConfigs['article']['pagenum'];
	else $maxpage=$truemaxpage;

	$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['pagenum'],$thispage,10,$jieqiConfigs['article']['fakefile']);
	if(!empty($jieqiConfigs['article']['fakeprefix'])) $dirname='/'.$jieqiConfigs['article']['fakeprefix'].'top'.$sort;
	else $dirname='/files/article/top'.$sort;
	$jumppage->setlink($article_dynamic_url.$dirname);

	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
	$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/toplist.html'));
	ob_start();
	include(JIEQI_ROOT_PATH.'/footer.php');
	$pagecontent=ob_get_contents();
	ob_end_clean();
	$dirname=JIEQI_ROOT_PATH.$dirname;
	if(!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname=$dirname.jieqi_getsubdir($thispage);
	if (!file_exists($dirname)) jieqi_createdir($dirname);
	$dirname.='/'.$thispage.$jieqiConfigs['article']['fakefile'];
	jieqi_writefile($dirname, $pagecontent);
	return $maxpage;
}
?>