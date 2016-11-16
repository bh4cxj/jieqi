<?php 
/**
 * 作家文章列表
 *
 * 根据作家名列出本作家所有文章（不一定是本站作家）
 * 
 * 调用模板：/modules/article/templates/authorarticle.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: authorarticle.php 332 2009-02-23 09:15:08Z juny $
 */

//根据作家名列出本作家所有文章（不一定是本站作家）
define('JIEQI_MODULE_NAME', 'article');
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

if(empty($_REQUEST['author'])) jieqi_printfail(LANG_ERROR_PARAMETER);
//载入语言
jieqi_loadlang('list', JIEQI_MODULE_NAME);

//包含配置参数
jieqi_getconfigs('article', 'configs');
jieqi_getconfigs('article', 'sort');


//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
if(defined('JIEQI_MAX_PAGES') && JIEQI_MAX_PAGES > 0 && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > JIEQI_MAX_PAGES) $_REQUEST['page'] = intval(JIEQI_MAX_PAGES);

include_once(JIEQI_ROOT_PATH.'/header.php');
//cache名称
$jieqiTset['jieqi_contents_cacheid']=base64_encode($_REQUEST['author']);
$jieqiTset['jieqi_contents_cacheid'].='_p'.$_REQUEST['page'];

$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/authorarticle.html';


//是否缓存
$content_used_cache=false;
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
	$jieqiTpl->assign('author', jieqi_htmlstr($_REQUEST['author']));

	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');

	$criteria=new CriteriaCompo(new Criteria('display','0','='));
	$criteria->add(new Criteria('size','0','>'));
	$criteria->add(new Criteria('author',$_REQUEST['author'],'='));
	
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

	$toplistrows = $article_handler->getCount($criteria);
	$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);


	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
}
//$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/authorarticle.html';
//$jieqiTset['jieqi_contents_cacheid'] =  $jieqiTset['jieqi_contents_cacheid'];
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>