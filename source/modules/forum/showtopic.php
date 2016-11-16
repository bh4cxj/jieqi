<?php
/**
 * 显示帖子主题和回复
 *
 * 显示帖子主题和回复
 * 
 * 调用模板：/modules/forum/templates/showtopic.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: showtopic.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
//判断提交参数
if(empty($_REQUEST['tid']) || !is_numeric($_REQUEST['tid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['tid']=intval($_REQUEST['tid']);
//检查主题是否存在
jieqi_loadlang('list', JIEQI_MODULE_NAME);
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('t.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('forum_forumtopics').' t LEFT JOIN '.jieqi_dbprefix('forum_forums').' f ON t.ownerid=f.forumid');
$query->queryObjects($criteria);
$topic=$query->getObject();
unset($criteria);
if(!$topic) jieqi_printfail($jieqiLang['forum']['post_not_exists']);

//检查阅读权限
include_once($jieqiModules['forum']['path'].'/include/funforum.php');
jieqi_forum_checkpower($topic, 'authread', false);
//检查管理权限
$ismaster = jieqi_forum_checkpower($topic, 'authedit', true);

$jieqi_pagetitle=jieqi_substr($topic->getVar('title'),0,56);
include_once(JIEQI_ROOT_PATH.'/header.php');
if($ismaster) $jieqiTpl->assign('ismaster', 1);
else $jieqiTpl->assign('ismaster', 0);

//载入参数
include_once(JIEQI_ROOT_PATH.'/class/users.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
jieqi_getconfigs('system', 'honors'); //头衔

//是否使用徽章
if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
	include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
	define('JIEQI_SHOW_BADGE', 1);
}else{
	define('JIEQI_SHOW_BADGE', 0);
}
$jieqiTpl->assign('jieqi_use_badge', JIEQI_SHOW_BADGE);
$jieqiTpl->assign('tid', $_REQUEST['tid']);

//赋值论坛信息
$jieqiTpl->assign(jieqi_forum_vars($topic));
//赋值主题信息
include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
$jieqiTpl->assign(jieqi_topic_vars($topic));

$criteria=new CriteriaCompo(new Criteria('p.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('forum_forumposts').' p LEFT JOIN '.jieqi_dbprefix('system_users').' u ON p.posterid=u.uid');
$criteria->setSort('p.postid');
$criteria->setOrder('ASC');

$page_rowcount = $query->getCount($criteria);
if(isset($_REQUEST['page']) && $_REQUEST['page']=='last') $_REQUEST['page']=ceil($page_rowcount / $jieqiConfigs['forum']['postnum']);
elseif(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$_REQUEST['pagerows'] = intval($jieqiConfigs['forum']['postnum']);
if(empty($_REQUEST['pagerows'])) $_REQUEST['pagerows'] = 10;

$criteria->setLimit($_REQUEST['pagerows']);
$criteria->setStart(($_REQUEST['page']-1) * $_REQUEST['pagerows']);
$query->queryObjects($criteria);
include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
$jieqiTxtcvt=TextConvert::getInstance('TextConvert');
$postrowss=array();
$k=0;

while($post = $query->getObject()){
	$addvars = array('order'=>($_REQUEST['page'] - 1) * $_REQUEST['pagerows'] + $k + 1);
	$postrows[$k] = jieqi_post_vars($post, $jieqiConfigs['forum'], $addvars, true);
	$k++;
}
$jieqiTpl->assign('postrows', $postrows);

//是否显示验证码
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);

if(!isset($_REQUEST['lpage']) || !is_numeric($_REQUEST['lpage'])) $_REQUEST['lpage']=1;
$jieqiTpl->assign('lpage', $_REQUEST['lpage']);
$jieqiTpl->assign('page', $_REQUEST['page']);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($page_rowcount,$_REQUEST['pagerows'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

//增加点击数
jieqi_topic_addviews($_REQUEST['tid'], jieqi_dbprefix('forum_forumtopics'));

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/showtopic.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>