<?php 
/**
 * 文章管理列表
 *
 * 文章管理列表
 * 
 * 调用模板：/modules/article/templates/admin/articlelist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: article.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
//处理审核、删除
if(isset($_REQUEST['action']) && !empty($_REQUEST['id'])){
	$criteria=new CriteriaCompo(new Criteria('articleid', $_REQUEST['id']));
	jieqi_getcachevars('article', 'articleuplog');
	if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
	switch($_REQUEST['action']){
		case 'show':
			$article_handler->updatefields(array('display'=>0), $criteria);
			//更新最新章节
			$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
			jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
			break;
		case 'hide':
			$article_handler->updatefields(array('display'=>2), $criteria);
			//更新最新章节
			$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
			jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
			break;
		case 'ready':
			$article_handler->updatefields(array('display'=>1), $criteria);
			//更新最新章节
			$jieqiArticleuplog['articleuptime']=JIEQI_NOW_TIME;
			jieqi_setcachevars('articleuplog', 'jieqiArticleuplog', $jieqiArticleuplog, 'article');
			break;
		case 'del':
			$canedit=jieqi_checkpower($jieqiPower['article']['delallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, true, true);
			if($canedit){
				$article=$article_handler->get($_REQUEST['id']);
				if(is_object($article)){
					//删除文章
					include_once($jieqiModules['article']['path'].'/include/operatefunction.php');
					jieqi_article_delete($_REQUEST['id'], true);
				}
			}
			break;
	}
	unset($criteria);
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
jieqi_getconfigs('article', 'sort');
//文章类别
if(empty($_REQUEST['class'])) $_REQUEST['class']=0;
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
if(isset($_REQUEST['keyword'])) $_REQUEST['keyword']=trim($_REQUEST['keyword']);
if(!empty($_REQUEST['keyword'])){
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('author', $_REQUEST['keyword'], '='));
	elseif($_REQUEST['keytype']==2) $criteria->add(new Criteria('poster', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('articlename', '%'.$_REQUEST['keyword'].'%', 'LIKE'));
}
$articletitle=$jieqiLang['article']['all_article'];
if(!empty($_REQUEST['class'])){
	$criteria->add(new Criteria('sortid', $_REQUEST['class'], '='));
	$articletitle=$jieqiSort['article'][$_REQUEST['class']]['caption'];
}

if(!empty($_REQUEST['display'])){
	switch ($_REQUEST['display']){
		case 'unshow':
			$criteria->add(new Criteria('display', 0, '>'));
			$articletitle=$jieqiLang['article']['no_audit_article'];
			break;
		case 'ready':
			$criteria->add(new Criteria('display', 1, '='));
			$articletitle=$jieqiLang['article']['no_audit_article'];
			break;
		case 'hide':
			$criteria->add(new Criteria('display', 2, '='));
			$articletitle=$jieqiLang['article']['no_audit_article'];
			break;
		case 'show':
			$criteria->add(new Criteria('display', 0, '='));
			$articletitle=$jieqiLang['article']['audit_article'];
			break;
		case 'empty':
			$criteria->add(new Criteria('size', 0, '='));
			$articletitle=$jieqiLang['article']['empty_article'];
			break;
		case 'cool':
			$criteria->add(new Criteria('postdate', time()-3600*24*30, '<'));
			$articletitle=$jieqiLang['article']['cool_article'];
			break;
		default:
			$_REQUEST['display']='';
			break;

	}
}
if(!empty($_REQUEST['permission']) && is_numeric($_REQUEST['permission'])) $criteria->add(new Criteria('permission', intval($_REQUEST['permission']), '='));

//载入相关处理函数
include_once($jieqiModules['article']['path'].'/include/funarticle.php');
	
$jieqiTpl->assign('articletitle', $articletitle);
$jieqiTpl->assign('display', $_REQUEST['display']);

$jieqiTpl->assign('url_article', $jieqiModules['article']['url'].'/admin/article.php');
$jieqiTpl->assign('url_batchdel', $article_static_url.'/admin/batchdel.php');
$jieqiTpl->assign('url_jump', jieqi_addurlvars(array()));
$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
if($_REQUEST['display']=='cool'){
	$criteria->setSort('allvisit');
	$criteria->setOrder('ASC');
}else{
	$criteria->setSort('lastupdate');
	$criteria->setOrder('DESC');
}
$criteria->setLimit($jieqiConfigs['article']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$article_handler->queryObjects($criteria);
$articlerows=array();
$k=0;
while($v = $article_handler->getObject()){
	$articlerows[$k] = jieqi_article_vars($v);
	$articlerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.$v->getVar('articleid').'">';  //选择框
	$articlerows[$k]['checkid']=$k;  //显示序号
	$k++;
}

$jieqiTpl->assign_by_ref('articlerows', $articlerows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($article_handler->getCount($criteria),$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
$pagelink='';
if(!empty($_REQUEST['class'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='class='.$_REQUEST['class'];
}elseif(!empty($_REQUEST['display'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='display='.$_REQUEST['display'];
}
if(!empty($_REQUEST['keyword'])){
	if(empty($pagelink)) $pagelink.='?';
	else $pagelink.='&';
	$pagelink.='keyword='.$_REQUEST['keyword'];
	$pagelink.='&keytype='.$_REQUEST['keytype'];
}
if(empty($pagelink)) $pagelink.='?page=';
else $pagelink.='&page=';
$jumppage->setlink($jieqiModules['article']['url'].'/admin/article.php'.$pagelink, false, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/articlelist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>