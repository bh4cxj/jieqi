<?php
/**
 * 论坛内容赋值相关函数
 *
 * 论坛内容赋值相关函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: topiclist.php 286 2008-12-23 03:04:17Z juny $
 */

/**
 * 传入论坛实例对象，返回适合模板赋值的论坛信息数组
 * 
 * @param      object      $forum 论坛实例
 * @access     public
 * @return     array
 */
function jieqi_forum_vars(&$forum){
	$ret = array();
	$ret['ownerid'] = $forum->getVar('forumid');
	$ret['forumid'] = $forum->getVar('forumid');
	$ret['catid'] = $forum->getVar('catid');
	$ret['forumname'] = $forum->getVar('forumname');
	$ret['forumdesc'] = $forum->getVar('forumdesc');
	$ret['forumstatus'] = $forum->getVar('forumstatus');
	$ret['forumtype'] = $forum->getVar('forumtype');
	$ret['forumtopics'] = $forum->getVar('forumtopics');
	$ret['forumposts'] = $forum->getVar('forumposts');
	$masterary=unserialize($forum->getVar('master','n'));
	if(!is_array($masterary)) $masterary=array();
	foreach($masterary as $k=>$v){
		$masterary[$k]['uname'] = jieqi_htmlstr($masterary[$k]['uname']);
		$masterary[$k]['uid'] = intval($masterary[$k]['uid']);
	}
	$ret['masters'] = $masterary;
	return $ret;
}

/**
 * 检查论坛权限
 * 
 * @param      object      $forum 论坛实例
 * @param      string      $power 权限名称（authview - 是否允许查看主题, authread - 是否允许阅读内容, authpost - 是否允许发表内容, authreply - 是否允许发表回复, authupload - 是否允许上传文件, authedit - 是否允许编辑所有帖子， authdelete - 是否允许删除所有帖子）
 * @param      bool        $return 是否返回，默认否
 * @access     public
 * @return     array
 */
function jieqi_forum_checkpower(&$forum, $power, $return = false){
	global $jieqiUsersStatus;
	global $jieqiUsersGroup;
	$powerary['groups']=unserialize($forum->getVar($power, 'n'));
	if(!is_array($powerary['groups'])) $powerary['groups']=array();
	$ret = jieqi_checkpower($powerary, $jieqiUsersStatus, $jieqiUsersGroup, true);
	//检查是否斑竹
	if(!$ret && !empty($_SESSION['jieqiUserId'])){
		$tmpary=unserialize($forum->getVar('master','n'));
		if(is_array($tmpary) && count($tmpary)>0){
			foreach($tmpary as $v){
				if(!empty($v['uid']) && $_SESSION['jieqiUserId'] == $v['uid']){
					$ret=true;
					break;
				}
			}
		}
		if(!$ret){
			//发表者自己有编辑删除权限
			if(($power == 'authedit' || $power == 'authdelete') && $_SESSION['jieqiUserId'] == $forum->getVar('posterid')) $ret=true;
		}
	}
	if($return) return $ret;
	elseif(!$ret) jieqi_printfail(LANG_NO_PERMISSION);
}

/**
 * 发帖后更新论坛信息
 * 
 * @param      int         $fid 论坛ID
 * @param      array       $postinfo 提交信息数组
 * @access     public
 * @return     void
 */
function jieqi_forum_upnewpost($fid, $postinfo=array()){
	global $query;
	if(!is_a($query, 'JieqiQueryHandler')){
		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	}
	$lastinfo=serialize(array('time'=>$postinfo['time'], 'uid'=>$postinfo['uid'], 'uname'=>$postinfo['uname'], 'tid'=>$postinfo['tid']));
	$sql="UPDATE ".jieqi_dbprefix('forum_forums')." SET ";
	if(isset($postinfo['istopic'])) $sql.="forumtopics=forumtopics+1, ";
	$sql.="forumposts=forumposts+1, forumlastinfo='".jieqi_dbslashes($lastinfo)."' WHERE forumid=".intval($fid);
	$query->execute($sql);
}

/**
 * 更新全区公告缓存
 * 
 * @param      int         $maxnum 最多缓存几条记录
 * @access     public
 * @return     void
 */
function jieqi_forum_uptoptopic($maxnum=0){
	global $topic_handler;
	if(empty($maxnum) || !is_numeric($maxnum)) $maxnum=10;
	if(!is_object($topic_handler)){
		include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumtopics.php');
		$topic_handler =& JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');
	}
	$criteria=new CriteriaCompo(new Criteria('istop', 2));
	$criteria->setSort('topicid');
	$criteria->setOrder('DESC');
	$criteria->setLimit($maxnum);

	$topic_handler->queryObjects($criteria);
	$jieqiForumtops=array();
	while($topic = $topic_handler->getObject()){
		$jieqiForumtops[]=array('topicid'=>$topic->getVar('topicid', 'n'), 'ownerid'=>$topic->getVar('forumid', 'n'), 'title'=>$topic->getVar('title'), 'iconid'=>$topic->getVar('iconid', 'n'), 'posterid'=>$topic->getVar('posterid', 'n'), 'poster'=>$topic->getVar('poster'), 'posttime'=>$topic->getVar('posttime', 'n'), 'replytime'=>$topic->getVar('replytime', 'n'), 'replies'=>$topic->getVar('replies', 'n'), 'views'=>$topic->getVar('views', 'n'), 'replierid'=>$topic->getVar('replierid', 'n'), 'replier'=>$topic->getVar('replier', 'n'));
	}
	jieqi_setcachevars('forumtops', 'jieqiForumtops', $jieqiForumtops, 'forum');
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
	$sql="DELETE FROM ".jieqi_dbprefix('forum_forumtopics')." WHERE topicid=".intval($tid);
	$res=$query->execute($sql);
	if(!$res) return false;

	//统计回复人，用于处理积分
	$posterary=array();
	if(!empty($configs['scoretopic']) || !empty($configs['scorereply'])){
		include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumposts.php');
		$post_handler =& JieqiForumpostsHandler::getInstance('JieqiForumpostsHandler');
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
	$sql="DELETE FROM ".jieqi_dbprefix('forum_forumposts')." WHERE topicid=".intval($tid);
	$res=$query->execute($sql);
	$postnum=intval($query->db->getAffectedRows());
	if(!$res) return false;
	else{
		$sql="UPDATE ".jieqi_dbprefix('forum_forums')." SET forumtopics=forumtopics-1, forumposts=forumposts-".$postnum." WHERE forumid=".intval($fid);
		$query->execute($sql);
		//减少用户积分
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		if(!empty($posterary)){
			foreach($posterary as $pid=>$pscore){
				$users_handler->changeScore($pid, $pscore, false);
			}
		}

		include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumattachs.php');
		$attachs_handler =& JieqiForumattachsHandler::getInstance('JieqiForumattachsHandler');
		$criteria=new CriteriaCompo(new Criteria('topicid', $tid));
		$attachs_handler->queryObjects($criteria);
		while($attchobj=$attachs_handler->getObject()){
			//删除附件
			$afname = jieqi_uploadpath($configs['attachdir'], 'forum').'/'.date('Ymd',$attchobj->getVar('uptime','n')).'/'.$attchobj->getVar('postid','n').'_'.$attchobj->getVar('attachid','n').'.'.$attchobj->getVar('postfix','n');
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
	$sql="DELETE FROM ".jieqi_dbprefix('forum_forumposts')." WHERE postid=".intval($pid);
	$res=$query->execute($sql);
	if(!$res) return false;
	else{
		//减少帖子的回复数
		$sql="UPDATE ".jieqi_dbprefix('forum_forumtopics')." SET replies=replies-1 WHERE topicid=".intval($tid);
		$query->execute($sql);
		$sql="UPDATE ".jieqi_dbprefix('forum_forums')." SET forumposts=forumposts-1 WHERE forumid=".intval($fid);
		$query->execute($sql);
		//减少用户积分
		if(!empty($configs['scorereply'])){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$users_handler->changeScore($pid, $configs['scorereply'], false);
		}
		include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumattachs.php');
		$attachs_handler =& JieqiForumattachsHandler::getInstance('JieqiForumattachsHandler');
		$criteria=new CriteriaCompo(new Criteria('postid', $pid));
		$attachs_handler->queryObjects($criteria);
		while($attchobj=$attachs_handler->getObject()){
			//删除附件
			$afname = jieqi_uploadpath($configs['attachdir'], 'forum').'/'.date('Ymd',$attchobj->getVar('uptime','n')).'/'.$attchobj->getVar('postid','n').'_'.$attchobj->getVar('attachid','n').'.'.$attchobj->getVar('postfix','n');
			if(file_exists($afname)) jieqi_delfile($afname);
		}
		//删除附件数据库
		$attachs_handler->delete($criteria);
	}
	return true;
}
?>