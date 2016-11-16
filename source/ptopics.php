<?php 
/**
 * 显示和发表会客室主题
 *
 * 包括置顶、精华、锁定等功能
 * 
 * 调用模板：/templates/ptopics.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ptopics.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
//jieqi_checklogin();
if(!isset($_REQUEST['uid']) && isset($_REQUEST['oid'])) $_REQUEST['uid'] = $_REQUEST['oid'];
if($_REQUEST['uid']=='self') $_REQUEST['uid']=intval($_SESSION['jieqiUserId']);
if(empty($_REQUEST['uid']) && empty($_REQUEST['oname'])){
	if(!empty($_SESSION['jieqiUserId'])) $_REQUEST['uid']=$_SESSION['jieqiUserId'];
	else jieqi_printfail(LANG_ERROR_PARAMETER);
}
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_loadlang('parlar', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(jieqi_checkpower($jieqiPower['system']['parlorpost'], $jieqiUsersStatus, $jieqiUsersGroup, true)) $enablepost=1;
else $enablepost=0;

if(!empty($_POST['pcontent']) && $enablepost){
	//检查发表评论权限
	if(!$enablepost) jieqi_printfail($jieqiLang['system']['parlor_noper_post']);
	//检查发表评论需要积分
	if(!empty($jieqiConfigs['system']['ppostneedscore']) && $_SESSION['jieqiUserScore']<intval($jieqiConfigs['system']['ppostneedscore'])) jieqi_printfail(sprintf($jieqiLang['system']['parlor_score_limit'], intval($jieqiConfigs['system']['ppostneedscore'])));
}

//载入主题处理函数
include_once(JIEQI_ROOT_PATH.'/include/funpost.php');

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['uid'])) $owneruser=$users_handler->get($_REQUEST['uid']);
else $owneruser=$users_handler->getByname($_REQUEST['oname'], 2);
if(!$owneruser) jieqi_printfail($jieqiLang['system']['owner_not_exists']);
$_REQUEST['uid']=$owneruser->getVar('uid','n');
$owner_group = $owneruser->getVar('groupid', 'n');
$owner_status =  $owner_group == JIEQI_GROUP_ADMIN ? JIEQI_GROUP_ADMIN : JIEQI_GROUP_USER;
if(!jieqi_checkpower($jieqiPower['system']['haveparlor'], $owner_status, $owner_status, true)) jieqi_printfail($jieqiLang['system']['owner_no_parlor']);

include_once(JIEQI_ROOT_PATH.'/class/ptopics.php');
$ptopics_handler =& JieqiPtopicsHandler::getInstance('JieqiPtopicsHandler');
//检查是否有管理评论权力
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['system']['manageallparlor'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，会客室主人可以管理
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && $owneruser->getVar('uid')==$tmpvar)	$canedit=true;
}
//处理置顶、置后、加精、去精、删除
if($canedit && isset($_REQUEST['action']) && !empty($_REQUEST['tid'])){
	$actparlor=$ptopics_handler->get($_REQUEST['tid']);
	if(is_object($actparlor)){
		switch($_REQUEST['action']){
			case 'top':
				$actparlor->setVar('istop', 1);
				$ptopics_handler->insert($actparlor);
				break;
			case 'untop':
				$actparlor->setVar('istop', 0);
				$ptopics_handler->insert($actparlor);
				break;
			case 'good':
				$actparlor->setVar('isgood', 1);
				$ptopics_handler->insert($actparlor);
				//精华积分
				if(!empty($jieqiConfigs['system']['scoregoodptopic'])){
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
					$users_handler->changeScore($actparlor->getVar('posterid'), $jieqiConfigs['system']['scoregoodptopic'], true);
				}
				break;
			case 'normal':
				$actparlor->setVar('isgood', 0);
				$ptopics_handler->insert($actparlor);
				//精华积分
				if(!empty($jieqiConfigs['system']['scoregoodptopic'])){
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
					$users_handler->changeScore($actparlor->getVar('posterid'), $jieqiConfigs['system']['scoregoodptopic'], false);
				}
				break;
			case 'del':
				include_once(JIEQI_ROOT_PATH.'/class/pposts.php');
				$pposts_handler =& JieqiPpostsHandler::getInstance('JieqiPpostsHandler');
				$criteria=new Criteria('topicid', $_REQUEST['tid']);

				//删除主题减少积分
				if(!empty($jieqiConfigs['system']['scoreptopic'])){
					$pposts_handler->queryObjects($criteria);
					$posterary=array();
					while($ppostobj = $pposts_handler->getObject()){
						$posterid = intval($ppostobj->getVar('posterid'));
						if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['system']['scoreptopic'];
						else  $posterary[$posterid] = $jieqiConfigs['system']['scoreptopic'];
					}

					if($actparlor->getVar('isgood','n') == 1 && !empty($jieqiConfigs['system']['scoregoodptopic'])){
						$posterid = intval($actparlor->getVar('posterid'));
						if(isset($posterary[$posterid])) $posterary[$posterid] += $jieqiConfigs['system']['scoregoodptopic'];
						else  $posterary[$posterid] = $jieqiConfigs['system']['scoregoodptopic'];
					}

					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
					foreach($posterary as $pid=>$pscore){
						$users_handler->changeScore($pid, $pscore, false);
					}
				}
				$ptopics_handler->delete($_REQUEST['tid']);
				$pposts_handler->delete($criteria);
				break;
		}
	}
}

$criteria=new CriteriaCompo();
$criteria->add(new Criteria('ownerid', $_REQUEST['uid']));
if(strlen(trim($_POST['pcontent'])) > 0 && $enablepost){
	//校验错误信息数组
	$check_errors = array();
	//检查和过滤提交变量
	$post_set = array('module'=>JIEQI_MODULE_NAME, 'ownerid'=>intval($_REQUEST['uid']), 'topicid'=>0, 'postid'=>0, 'posttime'=>JIEQI_NOW_TIME, 'topictitle'=>&$_POST['ptitle'], 'posttext'=>&$_POST['pcontent'], 'attachment'=>'', 'emptytitle'=>true, 'isnew'=>true, 'istopic'=>1, 'istop'=>0, 'sname'=>'jieqiSystemParlorTime', 'attachfile'=>'', 'oldattach'=>'', 'checkcode'=>$_POST['checkcode']);
	jieqi_post_checkvar($post_set, $jieqiConfigs['system'], $check_errors);
	if(empty($check_errors)){

		$newTopic= $ptopics_handler->create();
		//主题表实例赋值
		jieqi_topic_newset($post_set, $newTopic);
		$ptopics_handler->insert($newTopic);
		//赋值主题id
		$post_set['topicid'] = $newTopic->getVar('topicid', 'n');

		include_once(JIEQI_ROOT_PATH.'/class/pposts.php');
		$pposts_handler =& JieqiPpostsHandler::getInstance('JieqiPpostsHandler');
		$newPost = $pposts_handler->create();
		//帖子内容赋值
		jieqi_post_newset($post_set, $newPost);
		$pposts_handler->insert($newPost);
		//增加评论积分
		if(!empty($jieqiConfigs['system']['scoreptopic'])){
			$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['system']['scoreptopic'], true);
		}
	}else{
		jieqi_printfail(implode('<br />', $check_errors));
	}
}

include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('ownerid',$owneruser->getVar('uid'));
$jieqiTpl->assign('oid',$_REQUEST['uid']);
$jieqiTpl->assign('owneruname',$owneruser->getVar('uname'));
$ownername =  strlen($owneruser->getVar('name'))==0 ? $owneruser->getVar('uname') : $owneruser->getVar('name');
$jieqiTpl->assign('ownername',$ownername);
if($canedit) $jieqiTpl->assign('ismaster',1);
else $jieqiTpl->assign('ismaster',0);

include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
if(isset($_REQUEST['type']) && $_REQUEST['type']=='good'){
	$jieqiTpl->assign('type', 'good');
	//精华评论
	$criteria->add(new Criteria('isgood', 1));
}else{
	$_REQUEST['type']='all';
	$jieqiTpl->assign('type', 'all');
}
//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
$_REQUEST['pagerows'] = intval($jieqiConfigs['system']['ptopicpnum']);
if(empty($_REQUEST['pagerows'])) $_REQUEST['pagerows'] = 10;

$criteria->setSort('istop DESC, replytime');
$criteria->setOrder('DESC');
$criteria->setLimit($_REQUEST['pagerows']);
$criteria->setStart(($_REQUEST['page']-1) * $_REQUEST['pagerows']);
$ptopics_handler->queryObjects($criteria);
$ptopicrows=array();
$k=0;
while($topic = $ptopics_handler->getObject()){
	$ptopicrows[$k] = jieqi_topic_vars($topic);
	$k++;
}
$jieqiTpl->assign_by_ref('ptopicrows', $ptopicrows);
$jieqiTpl->assign('enablepost', $enablepost);

//是否显示验证码
if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
$jieqiTpl->assign('postcheckcode', $jieqiConfigs['system']['postcheckcode']);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($ptopics_handler->getCount($criteria),$_REQUEST['pagerows'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/ptopics.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>