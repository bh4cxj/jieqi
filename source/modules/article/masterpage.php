<?php 
/**
 * 我的文章管理
 *
 * 显示作家自己的文章
 * 
 * 调用模板：/modules/article/templates/masterpage.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: masterpage.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
jieqi_getconfigs('article', 'power');
jieqi_checkpower($jieqiPower['article']['authorpanel'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('list', JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/header.php');

include_once($jieqiModules['article']['path'].'/class/article.php');
$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

//载入相关处理函数
include_once($jieqiModules['article']['path'].'/include/funarticle.php');

$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$criteria=new CriteriaCompo();
$articletitle=$jieqiLang['article']['my_all_article'];
if(empty($_GET['display'])) $_GET['display']='all';
switch($_GET['display']){
	case 'author':
		$criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '='));
		$articletitle=$jieqiLang['article']['my_post_article'];
		break;
	case 'poster':
		$criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '!='));
		$criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '='));
		$articletitle=$jieqiLang['article']['my_trans_article'];
		break;
	case 'agent':
		$criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '!='));
		$criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '!='));
		$criteria->add(new Criteria('agentid', $_SESSION['jieqiUserId'], '='));
		$articletitle=$jieqiLang['article']['my_agent_article'];
		break;
	case 'all':
	default:
		$criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '='), 'OR');
		$criteria->add(new Criteria('posterid', $_SESSION['jieqiUserId'], '='), 'OR');
		$criteria->add(new Criteria('agentid', $_SESSION['jieqiUserId'], '='), 'OR');
		$articletitle=$jieqiLang['article']['my_all_article'];
}

$jieqiTpl->assign('articletitle', $articletitle);

$jieqiTpl->assign('url_article', $article_dynamic_url.'/masterpage.php');

$criteria->setSort('initial ASC,articlename');
$criteria->setOrder('ASC');
$criteria->setLimit($jieqiConfigs['article']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['pagenum']);
$article_handler->queryObjects($criteria);
$articlerows=array();
$k=0;
while($v = $article_handler->getObject()){
	$articlerows[$k] = jieqi_article_vars($v);
	$articlerows[$k]['checkid']=$k;  //显示序号
	$articlerows[$k]['operate']=' <a href="'.$article_static_url.'/articlemanage.php?id='.$v->getVar('articleid').'" target="_blank">'.$jieqiLang['article']['my_article_manage'].'</a>';
	$k++;
}

$jieqiTpl->assign_by_ref('articlerows', $articlerows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($article_handler->getCount($criteria),$jieqiConfigs['article']['pagenum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->assign('authorarea', 1);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/masterpage.html';


include_once(JIEQI_ROOT_PATH.'/footer.php');
?>