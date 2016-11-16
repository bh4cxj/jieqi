<?php 
/**
 * 论坛版块列表
 *
 * 论坛版块列表
 * 
 * 调用模板：/modules/forum/templates/forumlist.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumlist.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../global.php');
jieqi_loadlang('list', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/header.php');
include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
include_once($jieqiModules['forum']['path'].'/class/forums.php');
$criteria = new CriteriaCompo;
$criteria->setSort('catorder');
$criteria->setOrder('ASC');
$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');
$forumcat_handler->queryObjects($criteria);
$forumcats=array();
$i=0;
while($v = $forumcat_handler->getObject()){
	$forumcats[$i]['catid'] = $v->getVar('catid');
	$forumcats[$i]['cattitle'] = $v->getVar('cattitle');
	$i++;
}
unset($criteria);
$criteria = new CriteriaCompo;
$criteria->setSort('catid ASC, forumorder');
$criteria->setOrder('ASC');
$forums_handler=JieqiForumsHandler::getInstance('JieqiForumsHandler');
$forums_handler->queryObjects($criteria);
$forums=array();
$i=0;
while($v = $forums_handler->getObject()){
	$forums[$i]['catid'] = $v->getVar('catid');
	$forums[$i]['authview_n'] = $v->getVar('authview', 'n');
	$forums[$i]['forumid'] = $v->getVar('forumid');
	$forums[$i]['forumname'] = $v->getVar('forumname');
	$forums[$i]['forumdesc'] = $v->getVar('forumdesc');
	$forums[$i]['forumtopics'] = $v->getVar('forumtopics');
	$forums[$i]['forumposts'] = $v->getVar('forumposts');
	$forums[$i]['master_n'] = $v->getVar('master', 'n');
	$forums[$i]['forumlastinfo_n'] = $v->getVar('forumlastinfo', 'n');
	$i++;
}
$forumcatary=array();
$forumary=array();
$i=0;
//论坛类别循环
foreach($forumcats as $forumcat){
	$forumcatary[$i]=$forumcat['cattitle'];
	$j=0;
	//论坛循环
	foreach($forums as $forum){
		if($forum['catid'] == $forumcat['catid']){
			//查看权限
			$viewpower['groups']=unserialize($forum['authview_n']);
			if(!is_array($viewpower['groups'])) $viewpower['groups']=array();
			if(jieqi_checkpower($viewpower, $jieqiUsersStatus, $jieqiUsersGroup, true)){
				$forumary[$i][$j]['icon']='<img src="'.$jieqiModules['forum']['url'].'/images/nonew_big.gif">';
				$forumary[$i][$j]['name']='<a href="'.$jieqiModules['forum']['url'].'/topiclist.php?fid='.$forum['forumid'].'"><strong>'.$forum['forumname'].'</strong></a>';
				
				$forumary[$i][$j]['forumname']=$forum['forumname'];
				$forumary[$i][$j]['forumid']=$forum['forumid'];
				$forumary[$i][$j]['desc']=$forum['forumdesc'];
				$forumary[$i][$j]['topics']=$forum['forumtopics'];
				$forumary[$i][$j]['posts']=$forum['forumposts'];
				$masterstr='';
				$masterary=unserialize($forum['master_n']);
				if(!is_array($masterary)) $masterary=array();
				foreach($masterary as $k=>$v){
					$masterary[$k]['uname'] = jieqi_htmlstr($masterary[$k]['uname']);
					$masterary[$k]['uid'] = intval($masterary[$k]['uid']);
				}
				$forumary[$i][$j]['masters']=$masterary;
				
				$tmpary=unserialize($forum['forumlastinfo_n']);
				if(is_array($tmpary)){
					$forumary[$i][$j]['lastuid'] = intval($tmpary['uid']);
					$forumary[$i][$j]['lastuname'] = jieqi_htmlstr($tmpary['uname']);
					$forumary[$i][$j]['lasttime'] = intval($tmpary['time']);
					
					if(isset($tmpary['uid']) && $tmpary['uid']>0){
						$tmpvar=sprintf($jieqiLang['forum']['last_post_info'], date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT,$tmpary['time']), '<a href="'.jieqi_geturl('system', 'user', $tmpary['uid']).'" target="_blank">'.$tmpary['uname'].'</a>');
					}else{
						$tmpvar=sprintf($jieqiLang['forum']['last_post_info'], date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT,$tmpary['time']), '<em>'.$jieqiLang['forum']['user_guest'].'</em>');
					}
				}else{
					$forumary[$i][$j]['lastuid'] = 0;
					$forumary[$i][$j]['lastuname'] = '';
					$forumary[$i][$j]['lasttime'] = 0;
				}
				$forumary[$i][$j]['lastinfo']=$tmpvar;
				$j++;
			}
		}
	}
	$i++;

}
$jieqiTpl->assign('forumguide','');
$jieqiTpl->assign('url_search',$jieqiModules['forum']['url'].'/search.php');
$jieqiTpl->assign_by_ref('forumcats',$forumcatary);
$jieqiTpl->assign_by_ref('forums',$forumary);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['forum']['path'].'/templates/forumlist.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>