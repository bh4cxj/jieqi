<?php 
/**
 * 删除帖子
 *
 * 删除帖子
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: delpost.php 328 2009-02-06 09:24:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
//检查参数
if(empty($_REQUEST['pid']) || !is_numeric($_REQUEST['pid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
//载入语言包
jieqi_loadlang('post', JIEQI_MODULE_NAME);
//查询帖子记录
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('p.postid', $_REQUEST['pid']));
$criteria->setTables(jieqi_dbprefix('forum_forumposts').' p LEFT JOIN '.jieqi_dbprefix('forum_forums').' f ON p.ownerid=f.forumid');
$query->queryObjects($criteria);
$post=$query->getObject();
unset($criteria);
if(!$post) jieqi_printfail($jieqiLang['forum']['post_not_exists']);

$tid=$post->getVar('topicid');
$fid=$post->getVar('forumid');

//检查访问权限
include_once($jieqiModules['forum']['path'].'/include/funforum.php');
if(!jieqi_forum_checkpower($post, 'authdelete', true)) jieqi_printfail($jieqiLang['forum']['noper_delete_post']);
//获取论坛类型
$forum_type=intval($post->getVar('forumtype', 'n'));
//载入参数设置
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

//主题帖子则删除整个帖子及回复
if($post->getVar('istopic')==1){
	//删除主题
	if(!jieqi_forum_deltopic($post->getVar('topicid'), $post->getVar('forumid'), $jieqiConfigs['forum'])) jieqi_printfail($jieqiLang['forum']['delete_post_failure']);
	//发表全区公告主题时候更新公告缓存
	if($forum_type==1) jieqi_forum_uptoptopic();
	jieqi_jumppage($jieqiModules['forum']['url'].'/topiclist.php?fid='.$post->getVar('forumid'), LANG_DO_SUCCESS, $jieqiLang['forum']['delete_post_success']);
}else{
	//删除回复贴
	if(!jieqi_forum_delpost($post->getVar('postid'), $post->getVar('topicid'), $post->getVar('forumid'), $jieqiConfigs['forum'])) jieqi_printfail($jieqiLang['forum']['delete_post_failure']);
	jieqi_jumppage($jieqiModules['forum']['url'].'/showtopic.php?tid='.$post->getVar('topicid'), LANG_DO_SUCCESS, $jieqiLang['forum']['delete_post_success']);
}

?>