<?php
/**
 * 显示一个书评及回复
 *
 * 显示一个书评及回复
 * 
 * 调用模板：/modules/article/templates/reviewshow.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: reviewshow.php 337 2009-03-07 00:51:05Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['rid']) || !is_numeric($_REQUEST['rid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('review', JIEQI_MODULE_NAME);
//判断评论是否存在
jieqi_includedb();
$article_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('r.topicid', $_REQUEST['rid']));
$criteria->setTables(jieqi_dbprefix('article_reviews').' r LEFT JOIN '.jieqi_dbprefix('article_article').' a ON r.ownerid=a.articleid');
$article_query->queryObjects($criteria);
$topic=$article_query->getObject();
unset($criteria);
if(!$topic) jieqi_printfail($jieqiLang['article']['review_not_exists']);
$ownerid = $topic->getVar('ownerid', 'n');

//检查是否有发贴权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(jieqi_checkpower($jieqiPower['article']['newreview'], $jieqiUsersStatus, $jieqiUsersGroup, true)) $enablepost=1;
else $enablepost=0;
if(!empty($_POST['pcontent'])){
	//检查发表书评权限
	if(!$enablepost) jieqi_printfail($jieqiLang['article']['review_noper_post']);
	//检查发表书评需要积分
	if(!empty($jieqiConfigs['article']['reviewneedscore']) && $_SESSION['jieqiUserScore']<intval($jieqiConfigs['article']['reviewneedscore'])) jieqi_printfail(sprintf($jieqiLang['article']['review_score_limit'], intval($jieqiConfigs['article']['reviewneedscore'])));
}

//主题处理相关函数
include_once(JIEQI_ROOT_PATH.'/include/funpost.php');

//发表书评
$addnewreply=0;
if(!empty($_POST['pcontent']) && $enablepost){
	//校验错误信息数组
	$check_errors = array();
	//检查和过滤提交变量
	$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($ownerid), 'topicid'=>intval($_REQUEST['rid']), 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME, 'topictitle'=>&$_POST['ptitle'], 'posttext'=>&$_POST['pcontent'], 'attachment'=>'', 'isnew'=>true, 'istopic'=>0, 'istop'=>0, 'sname'=>'jieqiArticleReviewTime', 'attachfile'=>'', 'oldattach'=>'', 'checkcode'=>$_POST['checkcode']);
	jieqi_post_checkvar($post_set, $jieqiConfigs['article'], $check_errors);

	if(empty($check_errors)) {
		include_once($jieqiModules['article']['path'].'/class/replies.php');
		$replies_handler =& JieqiRepliesHandler::getInstance('JieqiRepliesHandler');
		$newReply = $replies_handler->create();
		jieqi_post_newset($post_set, $newReply);
		$replies_handler->insert($newReply);

		$addnewreply=1;
		$_REQUEST['page']='last';
		//增加书评积分
		if(!empty($jieqiConfigs['article']['scorereview'])){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['article']['scorereview'], true);
		}
	}else{
		jieqi_printfail(implode('<br />', $check_errors));
	}
}

//检查是否有管理书评权力
$canedit=jieqi_checkpower($jieqiPower['article']['manageallreview'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以管理书评
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($topic->getVar('authorid')==$tmpvar || $topic->getVar('posterid')==$tmpvar || $topic->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}

//处理删除回复
if($canedit && isset($_REQUEST['action']) && isset($_REQUEST['did']) && $_REQUEST['action']=='del' && is_numeric($_REQUEST['did'])){
	include_once($jieqiModules['article']['path'].'/class/replies.php');
	$replies_handler =& JieqiRepliesHandler::getInstance('JieqiRepliesHandler');
	//减少书评积分
	if(!empty($jieqiConfigs['article']['scorereview'])){
		$replyobj = $replies_handler->get(intval($_REQUEST['did']));
		if(is_object($replyobj)){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$users_handler->changeScore(intval($replyobj->getVar('posterid','n')), $jieqiConfigs['article']['scorereview'], false);
		}
	}
	$replies_handler->delete(intval($_REQUEST['did']));
	$addnewreply=-1;
}

//显示页面
include_once(JIEQI_ROOT_PATH.'/header.php');


$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
$jieqiTpl->assign('ownerid',$topic->getVar('ownerid'));
$jieqiTpl->assign('articleid',$topic->getVar('articleid'));
$jieqiTpl->assign('articlename',$topic->getVar('articlename'));
$jieqiTpl->assign('topicid',$topic->getVar('topicid'));
$jieqiTpl->assign('title',$topic->getVar('title'));
if($canedit) $jieqiTpl->assign('ismaster',1);
else $jieqiTpl->assign('ismaster',0);
$jieqiTpl->assign('url_articleinfo',jieqi_geturl('article', 'article', $topic->getVar('ownerid'), 'info'));

//显示列表

$_REQUEST['pagerows'] = intval($jieqiConfigs['article']['reviewnum']);
if(empty($_REQUEST['pagerows'])) $_REQUEST['pagerows'] = 10;

include_once(JIEQI_ROOT_PATH.'/class/users.php');
jieqi_getconfigs('system', 'honors'); //头衔
//头像参数
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
//是否使用徽章
if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
	include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
	$jieqi_use_badge=1;
	$jieqiTpl->assign('jieqi_use_badge', 1);
}else{
	$jieqi_use_badge=0;
	$jieqiTpl->assign('jieqi_use_badge', 0);
}
$criteria=new CriteriaCompo(new Criteria('r.topicid', $_REQUEST['rid']));
$criteria->setTables(jieqi_dbprefix('article_replies').' r LEFT JOIN '.jieqi_dbprefix('system_users').' u ON r.posterid=u.uid');
$criteria->setSort('r.postid');
$criteria->setOrder('ASC');
$criteria->setLimit($_REQUEST['pagerows']);

$query_count = $article_query->getCount($criteria);
if(isset($_REQUEST['page']) && $_REQUEST['page']=='last') $_REQUEST['page']=ceil($query_count / $_REQUEST['pagerows']);
elseif(empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

$criteria->setStart(($_REQUEST['page']-1) * $_REQUEST['pagerows']);
$article_query->queryObjects($criteria);
include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');
$ts=TextConvert::getInstance('TextConvert');
$replyrows=array();
$k=0;
while($review = $article_query->getObject()){
	$addvars = array('order'=>($_REQUEST['page'] - 1) * $_REQUEST['pagerows'] + $k + 1);
	$replyrows[$k] = jieqi_post_vars($review, $jieqiConfigs['article'], $addvars, true);
	$k++;
}
$jieqiTpl->assign_by_ref('replyrows', $replyrows);

$jieqiTpl->assign('enablepost', $enablepost);
//是否显示验证码
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($query_count,$_REQUEST['pagerows'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/reviewshow.html';
//增加点击数
if($addnewreply > 0){
	$lastinfo=serialize(array('time'=>JIEQI_NOW_TIME, 'uid'=>intval($_SESSION['jieqiUserId']), 'uname'=>strval($_SESSION['jieqiUserName'])));
	$article_query->execute('UPDATE '.jieqi_dbprefix('article_reviews').' SET views=views+1,replies=replies+1,replytime='.JIEQI_NOW_TIME.",lastinfo='".jieqi_dbslashes($lastinfo)."' WHERE topicid=".$_REQUEST['rid']);
}elseif($addnewreply < 0){
	$article_query->execute('UPDATE '.jieqi_dbprefix('article_reviews').' SET views=views+1,replies=replies-1 WHERE topicid='.$_REQUEST['rid']);
}else{
	//载入统计处理函数
	include_once(JIEQI_ROOT_PATH.'/include/funstat.php');
	jieqi_visit_stat($_REQUEST['rid'], jieqi_dbprefix('article_reviews'), 'views', 'topicid', $article_query);
}
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>