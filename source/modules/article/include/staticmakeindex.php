<?php 
/**
 * 生成静态的文章列表
 *
 * 生成静态的文章列表
 * 
 * 调用模板：/modules/article/templates/articlelist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: staticmakeindex.php 332 2009-02-23 09:15:08Z juny $
 */

if(!defined('JIEQI_ROOT_PATH')) exit;
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs('article', 'guideblocks', 'jieqiBlocks');
jieqi_getconfigs('article', 'sort');
include_once(JIEQI_ROOT_PATH.'/header.php');
include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
//载入相关处理函数
include_once($jieqiModules['article']['path'].'/include/funarticle.php');
function makestaticindex($class,$initial,$fullflag,$thispage,$maxpage=0){
	global $jieqiConfigs;
	global $jieqiSort;
	global $jieqiTpl;
	global $jieqiBlocks;
	if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
	//文章类别
	if(empty($class) || !is_numeric($class)) $class=0;
	//页码
	if (empty($thispage) || !is_numeric($thispage)) $thispage=1;

	if(empty($fullflag)) $fullflag=0;
	else $fullflag=1;
	//cache名称
	$cache_id=$fullflag;
	if(isset($initial) && trim(strval($initial)) != ''){
		if($initial=='~' || $initial=='0') $cache_id.='_i0';
		else $cache_id.='_i'.$initial;
	}else{
		$cache_id.='_s'.$class;
	}
	$pagecacheid=$cache_id;
	$cache_id.='_p'.$thispage;

	if(!empty($class)){
		$jieqi_pagetitle=$jieqiSort['article'][$class]['caption'].'&gt;&gt;'.JIEQI_SITE_NAME;
		$jieqiTpl->assign('jieqi_pagetitle', $jieqi_pagetitle);
	}

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
	if(!empty($fullflag)){
		$criteria->add(new Criteria('fullflag','1','='));
		$fulltitle=$jieqiLang['article']['list_full_title'];
	}else{
		$fulltitle='';
	}
	if(!empty($initial)){
		$criteria->add(new Criteria('initial', strtoupper($initial), '='));
		if($initial=='1'){
			$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['list_numeric_title'], $fulltitle));
		}elseif($initial=='~'){
			$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['list_other_title'], $fulltitle));
		}else{
			$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['list_char_title'], $initial, $fulltitle));
		}
		$criteria->setSort('articlename');
		$criteria->setOrder('ASC');
	}elseif(!empty($class)){
		$criteria->add(new Criteria('sortid', $class, '='));
		$jieqiTpl->assign('articletitle', $jieqiSort['article'][$class]['caption'].$fulltitle);
		$criteria->setSort('lastupdate');
		$criteria->setOrder('DESC');
	}else{
		$jieqiTpl->assign('articletitle', sprintf($jieqiLang['article']['list_all_title'], $fulltitle));
		$criteria->setSort('lastupdate');
		$criteria->setOrder('DESC');
	}
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
	$jieqiTpl->assign('url_initial', $article_dynamic_url.'/index.php?initial=');
	//处理页面跳转

	$toplistrows = $article_handler->getCount($criteria);
	$truemaxpage=ceil($toplistrows / $jieqiConfigs['article']['pagenum']);
	$maxpage=intval($maxpage);
	if(!empty($maxpage) && $maxpage<$truemaxpage) $toplistrows = $maxpage * $jieqiConfigs['article']['pagenum'];
	else $maxpage=$truemaxpage;
	if(!empty($initial)){
		$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['pagenum'],$thispage,10,$jieqiConfigs['article']['fakefile']);
		if($initial=='~') $tmpvar='0';
		else $tmpvar=$initial;
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $dirname='/'.$jieqiConfigs['article']['fakeprefix'].'initial'.$tmpvar;
		else $dirname='/files/article/initial'.$tmpvar;
		$jumppage->setlink($article_dynamic_url.$dirname);
	}else{
		$jumppage = new JieqiPage($toplistrows,$jieqiConfigs['article']['pagenum'],$thispage,10,$jieqiConfigs['article']['fakefile']);
		if(!empty($class)) $tmpvar=$class;
		else $tmpvar='';
		if(!empty($jieqiConfigs['article']['fakeprefix'])) $dirname='/'.$jieqiConfigs['article']['fakeprefix'].'sort'.$tmpvar;
		else $dirname='/files/article/sort'.$tmpvar;
		$jumppage->setlink($article_dynamic_url.$dirname);
	}

	$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
	$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch($GLOBALS['jieqiModules']['article']['path'].'/templates/articlelist.html'));
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