<?php 
/**
 * 显示搜索关键字缓存
 *
 * 显示搜索关键字缓存
 * 
 * 调用模板：/modules/article/templates/admin/searchcache.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: searchcache.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('search', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

include_once($jieqiModules['article']['path'].'/class/searchcache.php');
$searchcache_handler =& JieqiSearchcacheHandler::getInstance('JieqiSearchcacheHandler');

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

$criteria=new CriteriaCompo();
switch($_REQUEST['flag']){
	case 'title':
		$criteria->add(new Criteria('searchtype', 1, '=')); 
		break;
	case 'author':
		$criteria->add(new Criteria('searchtype', 2, '=')); 
		break;
	case 'notitle':
		$criteria->add(new Criteria('searchtype', 1, '=')); 
		$criteria->add(new Criteria('results', 0, '=')); 
		break;
	case 'noauthor':
		$criteria->add(new Criteria('searchtype', 2, '=')); 
		$criteria->add(new Criteria('results', 0, '=')); 
		break;
	default:
		break;
}

$criteria->setSort('cacheid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['article']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$searchcache_handler->queryObjects($criteria);
$cacherows=array();
$k=0;
while($v = $searchcache_handler->getObject()){
	$cacherows[$k]['searchtime']=date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $v->getVar('searchtime'));  //搜索时间
	$cacherows[$k]['keywords']=$v->getVar('keywords');  //关键字
	//搜索类型
	$searchtype=$v->getVar('searchtype');
	if($searchtype==1) $cacherows[$k]['searchtype']=$jieqiLang['article']['search_with_article'];
	elseif($searchtype==2) $cacherows[$k]['searchtype']=$jieqiLang['article']['search_with_author'];
	else $cacherows[$k]['searchtype']=$jieqiLang['article']['search_with_all'];
	$cacherows[$k]['results']=$v->getVar('results');  //结果数
	$k++;
}

$jieqiTpl->assign_by_ref('cacherows', $cacherows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($searchcache_handler->getCount($criteria),$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['flag'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='flag='.$_REQUEST['flag'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($article_dynamic_url.'/admin/searchcache.php'.$pagelink, false, false);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/searchcache.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>