<?php
/**
 * 处理发表帖子
 *
 * 处理发表帖子并返回消息
 * 
 * 调用模板：/modules/group/templates/topic.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('topic',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_getconfigs('group', 'configs');

//$jieqiConfigs['forum']['postnum'] = 20;
include_once(JIEQI_ROOT_PATH."/modules/".JIEQI_MODULE_NAME.'/include/functions.php');

//jieqi_checklogin();
setpower($gid);
if($allowmantopic) $ismaster = 1;
if($ismaster) $jieqiTpl->assign('ismaster', 1);
//判断提交参数
if(empty($_REQUEST['tid']) || !is_numeric($_REQUEST['tid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['tid']=intval($_REQUEST['tid']);
//检查主题是否存在
jieqi_loadlang('post', JIEQI_MODULE_NAME);
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('t.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('group_topics').' t LEFT JOIN '.jieqi_dbprefix('group_group').' f ON t.ownerid=f.gid');
$query->queryObjects($criteria);
$topic=$query->getObject();
unset($criteria);
if(!$topic) jieqi_printfail($jieqiLang['group']['post_not_exists']);


$jieqi_pagetitle=jieqi_substr($topic->getVar('title'),0,56);
//载入参数
include_once(JIEQI_ROOT_PATH.'/class/users.php');

if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
jieqi_getconfigs('system', 'honors'); //头衔

//是否使用徽章
if(defined('JIEQI_USE_BADGE') && JIEQI_USE_BADGE == 1 && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
	include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
	define('JIEQI_SHOW_BADGE', 1);
}else{
	define('JIEQI_SHOW_BADGE', 0);
}
$jieqiTpl->assign('jieqi_use_badge', JIEQI_SHOW_BADGE);
$jieqiTpl->assign('tid', $_REQUEST['tid']);

//赋值论坛信息
//$jieqiTpl->assign(jieqi_forum_vars($topic));
//赋值主题信息
include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
$jieqiTpl->assign(jieqi_topic_vars($topic));

$criteria=new CriteriaCompo(new Criteria('p.topicid', $_REQUEST['tid']));
$criteria->setTables(jieqi_dbprefix('group_posts').' p LEFT JOIN '.jieqi_dbprefix('system_users').' u ON p.posterid=u.uid');
$criteria->setSort('p.postid');
$criteria->setOrder('ASC');

$page_rowcount = $query->getCount($criteria);
if(isset($_REQUEST['page']) && $_REQUEST['page']=='last') $_REQUEST['page']=ceil($page_rowcount / $jieqiConfigs['group']['postnum']);
elseif(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$_REQUEST['pagerows'] = intval($jieqiConfigs['group']['postnum']);
if(empty($_REQUEST['pagerows'])) $_REQUEST['pagerows'] = 10;

$criteria->setLimit($_REQUEST['pagerows']);
$criteria->setStart(($_REQUEST['page']-1) * $_REQUEST['pagerows']);
$query->queryObjects($criteria);
include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
$jieqiTxtcvt=TextConvert::getInstance('TextConvert');
$postrowss=array();
$k=0;
$groupConfig = array();
$groupConfig = $jieqiConfigs['group'];
$groupConfig['attachurl'] = $groupUserfile['attachurl'];
while($post = $query->getObject()){
	$addvars = array('order'=>($_REQUEST['page'] - 1) * $_REQUEST['pagerows'] + $k + 1);
	$postrows[$k] = jieqi_post_vars($post, $groupConfig, $addvars, true);
	$k++;
}
$jieqiTpl->assign('postrows', $postrows);

if(!isset($_REQUEST['lpage']) || !is_numeric($_REQUEST['lpage'])) $_REQUEST['lpage']=1;
$jieqiTpl->assign('lpage', $_REQUEST['lpage']);
$jieqiTpl->assign('page', $_REQUEST['page']);

//是否显示验证码
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($page_rowcount,$_REQUEST['pagerows'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

//增加点击数
jieqi_topic_addviews($_REQUEST['tid'], jieqi_dbprefix('group_topics'));

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/topic.html';	
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>