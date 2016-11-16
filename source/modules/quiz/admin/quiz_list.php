<?php 
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../../global.php');
require_once('../../../lib/template/template.php');
//jieqi_checkpower($jieqiPower['article']['setwriter'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('admin', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();

include_once($jieqiModules['quiz']['path'].'/class/problems.php');
$problems_handler =& JieqiProblemsHandler::getInstance('JieqiProblemsHandler');

jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');//实例化数据库类

$action=$_REQUEST['action']==""?'list':$_REQUEST['action'];

switch ($action)
{
	case 'delete';
	$problems_handler->delete($_REQUEST['id']);
	$sql='delete from '.jieqi_dbprefix('quiz_answer').' where problemid ='.(int)$_REQUEST['id'];
	$query->execute($sql);
	break;

	case 'answer';
	include_once($jieqiModules['quiz']['path'].'/class/answer.php');
	$answer_handler =& JieqiAnswerHandler::getInstance('JieqiAnswerHandler');
	$criteria = new Criteria('problemid',$_REQUEST['id'],'=');
	$answer_handler -> queryObjects($criteria);
	$answerrows=array();
	$k=0;
	while($v = $answer_handler->getObject())
	{
		$answerrows[$k]=$v->getVars();
		$k++;
	}
	$jieqiTpl->assign('answer',$answerrows);
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['quiz']['path'].'/templates/admin/answerlist.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
	exit;
	
	case 'delcache';
	jieqi_delfolder(JIEQI_ROOT_PATH.'/blockcache/quiz',false);
	jieqi_jumppage($jieqiModules['quiz']['url'].'/admin/quiz_list.php',LANG_DO_SUCCESS,LANG_DO_SUCCESS);
	exit;
}

$page=$_REQUEST['page']==""?1:(int)$_REQUEST['page']==0?'1':(int)$_REQUEST['page'];;
$criteria=new CriteriaCompo();
$criteria->setFields('count(*)');
if($_REQUEST['btnsearch'])
{
$key=$_REQUEST['keytype']==0?'title':'username';
$criteria->add(new Criteria($key,$_REQUEST['keyword'],'='));
}

$row=$problems_handler->getCount($criteria);//一共有数据

include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($row,$jieqiConfigs['quiz']['adminlist'],$page,10);
$pages=$jumppage->whole_bar();
$jieqiTpl->assign('pages',$pages);

$criteria->setFields('quizid ,title,addtime,username,typeid');
$criteria->setSort('addtime');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['quiz']['adminlist']);
$criteria->setStart(($page-1) * $jieqiConfigs['quiz']['adminlist']);

$problems_handler->queryObjects($criteria);
$quizrows=array();
$k=0;
while($v = $problems_handler->getObject())
{
	$quizrows[$k]=$v->getVars();
	$k++;
}
$jieqiTpl->assign('quizrows',$quizrows);
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTset['jieqi_contents_template'] = $jieqiModules['quiz']['path'].'/templates/admin/quizlist.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>