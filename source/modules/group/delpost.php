<?php 
/**
 * 删除圈子贴子
 *
 * 删除圈子贴子
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('topic',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
if($gid == 0){
	header("Location: ".JIEQI_URL);
}
if(empty($_REQUEST['pid']) || !is_numeric($_REQUEST['pid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('post', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
//检查权限
include_once("include/functions.php");
setpower($gid);


if(!$allowmantopic){
	jieqi_printfail($jieqiLang['group']['noper_delete_post']);
}

//查询帖子记录
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
$criteria=new CriteriaCompo(new Criteria('p.postid', $_REQUEST['pid']));
$criteria->setTables(jieqi_dbprefix('group_posts').' p LEFT JOIN '.jieqi_dbprefix('group_group').' f ON p.ownerid=f.gid');
$query->queryObjects($criteria);
$post=$query->getObject();
unset($criteria);
if(!$post) jieqi_printfail($jieqiLang['group']['post_not_exists']);

$tid=$post->getVar('topicid');
$fid=$post->getVar('forumid');

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$jieqiConfigs['group']['attachdir'] = $groupUserfile['attachdir'];
//主题帖子则删除整个帖子及回复
if($post->getVar('istopic')==1){
	//删除主题
	if(!jieqi_forum_deltopic($post->getVar('topicid'), $post->getVar('forumid'), $jieqiConfigs['group'])) jieqi_printfail($jieqiLang['group']['delete_post_failure']);
	$group_handler->updatefields('topicnum=topicnum-1',$criteria);
	jieqi_jumppage($jieqiModules['group']['url'].'/topiclist.php?g='.$gid, LANG_DO_SUCCESS, $jieqiLang['group']['delete_post_success']);
}else{
	//删除回复贴
	if(!jieqi_forum_delpost($post->getVar('postid'), $post->getVar('topicid'), $post->getVar('forumid'), $jieqiConfigs['group'])) jieqi_printfail($jieqiLang['group']['delete_post_failure']);
	$group_handler->updatefields('gtopics=gtopics-1',$criteria);
	jieqi_jumppage($jieqiModules['group']['url'].'/topic.php?g='.$gid.'&tid='.$post->getVar('topicid'), LANG_DO_SUCCESS, $jieqiLang['group']['delete_post_success']);
}
/**
 * 删除主题帖子
 * 
 * @param      int         $tid 帖子主题id
 * @param      int         $fid 论坛ID
 * @param      array       $configs 配置参数
 * @access     public
 * @return     bool
 */
function jieqi_forum_deltopic($tid, $fid, $configs){
	global $query;
	if(!is_a($query, 'JieqiQueryHandler')){
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
	
	//删除主题
	$sql="DELETE FROM ".jieqi_dbprefix('group_topics')." WHERE topicid=".intval($tid);
	$res=$query->execute($sql);
	if(!$res) return false;

	//统计回复人，用于处理积分
	$posterary=array();
	if(!empty($configs['scoretopic']) || !empty($configs['scorereply'])){
		include_once($GLOBALS['jieqiModules']['group']['path'].'/class/groupposts.php');
		$post_handler =& JieqiGrouppostHandler::getInstance('JieqiGrouppostHandler');
		$criteria = new CriteriaCompo(new Criteria('topicid', intval($tid)));
		$post_handler->queryObjects($criteria);
		while($postobj = $post_handler->getObject()){
			$posterid = intval($postobj->getVar('posterid'));
			$tmpscore = intval($postobj->getVar('istopic','n')) == 0 ? intval($configs['scorereply']) : intval($configs['scoretopic']);
			if(isset($posterary[$posterid])) $posterary[$posterid] += $tmpscore;
			else $posterary[$posterid] = $tmpscore;
		}
	}
	//删除回复
	$sql="DELETE FROM ".jieqi_dbprefix('group_posts')." WHERE topicid=".intval($tid);
	$res=$query->execute($sql);
	$postnum=intval($query->db->getAffectedRows());
	if(!$res) return false;
	else{
		$sql="UPDATE ".jieqi_dbprefix('group_group')." SET gtopics=gtopics-1 WHERE gid=".intval($gid);
		$query->execute($sql);
		//减少用户积分
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($posterary)){
			foreach($posterary as $pid=>$pscore){
				$users_handler->changeScore($pid, $pscore, false);
			}
		}

		include_once($GLOBALS['jieqiModules']['group']['path'].'/class/groupattachs.php');
		$attachs_handler =& JieqiGroupattachsHandler::getInstance('JieqiGroupattachsHandler');
		$criteria=new CriteriaCompo(new Criteria('topicid', $tid));
		$attachs_handler->queryObjects($criteria);
		while($attchobj=$attachs_handler->getObject()){
			//删除附件
			$afname = jieqi_uploadpath($configs['attachdir'], 'group').'/'.date('Ymd',$attchobj->getVar('uptime','n')).'/'.$attchobj->getVar('postid','n').'_'.$attchobj->getVar('attachid','n').'.'.$attchobj->getVar('postfix','n');
			if(file_exists($afname)) jieqi_delfile($afname);
		}
		//删除附件数据库
		$attachs_handler->delete($criteria);
	}
	return true;
}

/**
 * 删除回复帖子
 * 
 * @param      int         $pid 帖子id
 * @param      int         $tid 帖子主题id
 * @param      int         $fid 论坛ID
 * @param      array       $configs 配置参数
 * @access     public
 * @return     bool
 */
function jieqi_forum_delpost($pid, $tid, $fid, $configs){
	global $query;
	if(!is_a($query, 'JieqiQueryHandler')){
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
	$sql="DELETE FROM ".jieqi_dbprefix('group_posts')." WHERE postid=".intval($pid);
	$res=$query->execute($sql);
	if(!$res) return false;
	else{
		//减少帖子的回复数
		$sql="UPDATE ".jieqi_dbprefix('group_topics')." SET replies=replies-1 WHERE topicid=".intval($tid);
		$query->execute($sql);
		//减少用户积分
		if(!empty($configs['scorereply'])){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$users_handler->changeScore($pid, $configs['scorereply'], false);
		}
		include_once($GLOBALS['jieqiModules']['group']['path'].'/class/groupattachs.php');
		$attachs_handler =& JieqiGroupattachsHandler::getInstance('JieqiGroupattachsHandler');
		$criteria=new CriteriaCompo(new Criteria('postid', $pid));
		$attachs_handler->queryObjects($criteria);
		while($attchobj=$attachs_handler->getObject()){
			//删除附件
			$afname = jieqi_uploadpath($configs['attachdir'], 'group').'/'.date('Ymd',$attchobj->getVar('uptime','n')).'/'.$attchobj->getVar('postid','n').'_'.$attchobj->getVar('attachid','n').'.'.$attchobj->getVar('postfix','n');
			if(file_exists($afname)) jieqi_delfile($afname);
		}
		//删除附件数据库
		$attachs_handler->delete($criteria);
	}
	return true;
}

?>