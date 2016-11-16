<?php 
/**
 * 用户会客室主题列表
 *
 * 显示所有人的会客室主题
 * 
 * 调用模板：/templates/passedit.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ptopiclist.php 329 2009-02-07 01:21:38Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
include_once(JIEQI_ROOT_PATH.'/header.php');
include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');

$criteria=new CriteriaCompo();
$criteria->setFields("t.*,u.uname,u.name");
$criteria->setTables(jieqi_dbprefix('system_ptopics')." AS t LEFT JOIN ".jieqi_dbprefix('system_users')." AS u ON t.ownerid=u.uid");

if(!empty($_REQUEST['keyword'])){
	$_REQUEST['keyword']=trim($_REQUEST['keyword']);
	if($_REQUEST['keytype']==1) $criteria->add(new Criteria('t.poster', $_REQUEST['keyword'],'='));
	if($_REQUEST['keytype']==2) $criteria->add(new Criteria('t.title', '%'.$_REQUEST['keyword'].'%','LIKE'));
	else $criteria->add(new Criteria('u.uname', $_REQUEST['keyword'],'='));
}

if(isset($_REQUEST['type']) && $_REQUEST['type']=='good'){
	$jieqiTpl->assign('type', 'good');
	//精华书评
	$criteria->add(new Criteria('isgood', 1));
}else{
	$_REQUEST['type']='all';
	$jieqiTpl->assign('type', 'all');
}
//页码
include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
if(empty($jieqiConfigs['system']['ptopicpnum'])) $jieqiConfigs['system']['ptopicpnum']=20;
$criteria->setSort('topicid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['system']['ptopicpnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['ptopicpnum']);
$query->queryObjects($criteria);
$ptopicrows=array();
$k=0;
while($v = $query->getObject()){
	$ptopicrows[$k] = jieqi_topic_vars($v);
	$ptopicrows[$k]['ownername']=strlen($v->getVar('name'))==0 ? $v->getVar('uname') : $v->getVar('name');
	$k++;
}
$jieqiTpl->assign_by_ref('ptopicrows', $ptopicrows);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($query->getCount($criteria),$jieqiConfigs['system']['ptopicpnum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/ptopiclist.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>