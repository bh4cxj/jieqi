<?php 
/**
 * 文章列表
 *
 * 文章分类列表
 * 
 * 调用模板：/modules/article/templates/articlelist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articlelist.php 337 2009-03-07 00:51:05Z juny $
 */
define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');
//载入语言
jieqi_loadlang('list', JIEQI_MODULE_NAME);

//包含配置参数
jieqi_getconfigs('article', 'configs');
jieqi_getconfigs('article', 'sort');
//文章类别
if(empty($_REQUEST['class']) || !is_numeric($_REQUEST['class']) || !isset($jieqiSort['article'][$_REQUEST['class']])) $_REQUEST['class']=0;
$_REQUEST['sortid'] = $_REQUEST['class'];
//文章子类
if(empty($_REQUEST['type']) || !is_numeric($_REQUEST['type']) || !isset($jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']])) $_REQUEST['type']=0;

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
if(defined('JIEQI_MAX_PAGES') && JIEQI_MAX_PAGES > 0 && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > JIEQI_MAX_PAGES) $_REQUEST['page'] = intval(JIEQI_MAX_PAGES);

if(empty($_REQUEST['fullflag'])) $_REQUEST['fullflag']=0;
else $_REQUEST['fullflag']=1;

include_once(JIEQI_ROOT_PATH.'/header.php');

//cache名称
$jieqiTset['jieqi_contents_cacheid']='f'.$_REQUEST['fullflag'];
$jieqiTset['jieqi_contents_cacheid'].='_s'.$_REQUEST['class'];
$jieqiTset['jieqi_contents_cacheid'].='_t'.$_REQUEST['type'];
if(isset($_REQUEST['initial']) && trim(strval($_REQUEST['initial'])) != ''){
	$_REQUEST['initial']=substr($_REQUEST['initial'], 0, 1);
	if($_REQUEST['initial']=='~' || $_REQUEST['initial']=='0') $jieqiTset['jieqi_contents_cacheid'].='_i0';
	else $jieqiTset['jieqi_contents_cacheid'].='_i'.$_REQUEST['initial'];
}
$pagecacheid=$jieqiTset['jieqi_contents_cacheid'];
$jieqiTset['jieqi_contents_cacheid'].='_p'.$_REQUEST['page'];

if(!empty($_REQUEST['class'])) $jieqi_pagetitle=$jieqiSort['article'][$_REQUEST['class']]['caption'].'-'.JIEQI_SITE_NAME;

//是否缓存
$content_used_cache=false;
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/articlelist.html';

if (JIEQI_USE_CACHE && $_REQUEST['page']<=$jieqiConfigs['article']['cachenum']){
	jieqi_getcachevars('article', 'articleuplog');
	if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	$uptime = $jieqiArticleuplog['articleuptime'] > $jieqiArticleuplog['chapteruptime'] ? $jieqiArticleuplog['articleuptime'] : $jieqiArticleuplog['chapteruptime'];
	$cachedtime = $jieqiTpl->get_cachedtime($jieqiTset['jieqi_contents_template'], $jieqiTset['jieqi_contents_cacheid']);
	if($cachedtime > $uptime && JIEQI_NOW_TIME - $cachedtime < JIEQI_CACHE_LIFETIME) $content_used_cache=true;
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
	
	//载入相关处理函数
	include_once($jieqiModules['article']['path'].'/include/funarticle.php');
	
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
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
	if(!empty($_REQUEST['fullflag'])){
		$criteria->add(new Criteria('fullflag','1','='));
		$jieqiTpl->assign('fullflag', 1);
	}else{
		$jieqiTpl->assign('fullflag', 0);
	}
	
	if(!empty($_REQUEST['initial'])){
		$criteria->add(new Criteria('initial', strtoupper($_REQUEST['initial']), '='));
		$jieqiTpl->assign('initial',$_REQUEST['initial']);
	}else{
		$jieqiTpl->assign('initial','');
	}
	
	if(!empty($_REQUEST['class'])){
		$criteria->add(new Criteria('sortid', $_REQUEST['class'], '='));
		$jieqiTpl->assign('sort', $jieqiSort['article'][$_REQUEST['class']]['caption']);
	}else{
		$jieqiTpl->assign('sort', '');
	}
	
	if(!empty($_REQUEST['type'])){
		$criteria->add(new Criteria('typeid', $_REQUEST['type'], '='));
		$jieqiTpl->assign('type', $jieqiSort['article'][$_REQUEST['class']]['types'][$_REQUEST['type']]);
	}else{
		$jieqiTpl->assign('type', '');
	}

	//if(empty($_REQUEST['sort'])) $_REQUEST['sort']='lastupdate';
	//if(empty($_REQUEST['order'])) $_REQUEST['order']='DESC';
	$criteria->setSort('lastupdate');
	$criteria->setOrder('DESC');
	
	$criteria->setLimit($jieqiConfigs['article']['pagenum']);
	$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
	$article_handler->queryObjects($criteria);
	$articlerows=array();
	$k=0;
	while($v = $article_handler->getObject()){
		$articlerows[$k] = jieqi_article_vars($v);
		$k++;
	}
	$jieqiTpl->assign_by_ref('articlerows', $articlerows);
	$jieqiTpl->assign('url_initial', $article_dynamic_url.'/articlelist.php?initial=');
	//处理页面跳转
	include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
	if (JIEQI_USE_CACHE){
		jieqi_getcachevars('article', 'articlelistlog');
		if(!is_array($jieqiArticlelistlog)) $jieqiArticlelistlog=array();
		if(!isset($jieqiArticlelistlog[$pagecacheid]) ||  JIEQI_NOW_TIME - $jieqiArticlelistlog[$pagecacheid]['time'] > JIEQI_CACHE_LIFETIME){
			$jieqiArticlelistlog[$pagecacheid] = array('rows' => $article_handler->getCount($criteria), 'time' => JIEQI_NOW_TIME);
			jieqi_setcachevars('articlelistlog', 'jieqiArticlelistlog', $jieqiArticlelistlog, 'article');
		}
		$toplistrows = $jieqiArticlelistlog[$pagecacheid]['rows'];
	}else{
		$toplistrows = $article_handler->getCount($criteria);
	}

	$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
	if(!empty($_REQUEST['initial']) && !empty($jieqiConfigs['article']['fakeinitial'])){
		$jumppage->setlink(jieqi_geturl('article', 'initial', 0, $_REQUEST['initial']));
	}elseif(empty($_REQUEST['fullflag']) && !empty($jieqiConfigs['article']['fakesort'])){
		$jumppage->setlink(jieqi_geturl('article', 'articlelist', 0, $_REQUEST['class']));
	}

	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
}

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>