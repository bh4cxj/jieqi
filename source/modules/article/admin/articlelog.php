<?php
/**
 * 文章管理日志类表
 *
 * 文章管理日志类表
 * 
 * 调用模板：/modules/article/templates/admin/articlelog.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articlelog.php 326 2009-02-04 00:26:22Z juny $
 */


define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

include_once($jieqiModules['article']['path'].'/class/articlelog.php');
$articlelog_handler=JieqiArticlelogHandler::getInstance('JieqiArticlelogHandler');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$criteria=new CriteriaCompo();
if(!empty($_REQUEST['keyword'])){
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('articlename', $_REQUEST['keyword'], '='));
	else $criteria->add(new Criteria('username', $_REQUEST['keyword'], '='));
}
$criteria->setSort('logid');
$criteria->setOrder('DESC');
if(empty($jieqiConfigs['article']['articlelogpnum'])) $jieqiConfigs['article']['articlelogpnum']=$jieqiConfigs['article']['pagenum'];
$criteria->setLimit($jieqiConfigs['article']['articlelogpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['article']['articlelogpnum']);
$articlelog_handler->queryObjects($criteria);
$logrows=array();
$k=0;
while($v = $articlelog_handler->getObject()){
	$logrows[$k]['logtime']=$v->getVar('logtime');
	$logrows[$k]['date']=date(JIEQI_DATE_FORMAT, $v->getVar('logtime'));
	$logrows[$k]['time']=date(JIEQI_TIME_FORMAT, $v->getVar('logtime'));
	$logrows[$k]['userid']=$v->getVar('userid');
	$logrows[$k]['username']=$v->getVar('username');
	$logrows[$k]['articleid']=$v->getVar('articleid');
	$logrows[$k]['articlename']=$v->getVar('articlename');
	$logrows[$k]['chapterid']=$v->getVar('chapterid');
	$logrows[$k]['chaptername']=$v->getVar('chaptername');
	$logrows[$k]['reason']=$v->getVar('reason');
	$logrows[$k]['chginfo']=$v->getVar('chginfo');
	$logrows[$k]['chglog']=$v->getVar('chglog');
	$logrows[$k]['isdel']=$v->getVar('isdel');
	$logrows[$k]['ischapter']=$v->getVar('ischapter');
	$k++;
}
$jieqiTpl->assign_by_ref('logrows', $logrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($articlelog_handler->getCount($criteria),$jieqiConfigs['article']['articlelogpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/articlelog.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>