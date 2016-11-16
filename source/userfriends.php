<?php 
/**
 * 用户好友列表
 *
 * 显示一个用户的好友列表
 * 
 * 调用模板：/templates/userfriends.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userfriends.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
if($_REQUEST['uid']=='self') $_REQUEST['uid']=intval($_SESSION['jieqiUserId']);
if(empty($_REQUEST['uid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['uid']=intval($_REQUEST['uid']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1; //页码

include_once(JIEQI_ROOT_PATH.'/header.php');

include_once(JIEQI_ROOT_PATH.'/class/friends.php');
$friends_handler =& JieqiFriendsHandler::getInstance('JieqiFriendsHandler');

$criteria=new CriteriaCompo(new Criteria('myid', $_REQUEST['uid']));
$criteria->setSort('friendsid');
$criteria->setOrder('ASC');
if(!isset($jieqiConfigs['system']['friendspnum'])) $jieqiConfigs['system']['friendspnum']=20;
$criteria->setLimit($jieqiConfigs['system']['friendspnum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['system']['friendspnum']);
$friends_handler->queryObjects($criteria);
$friendsrows=array();
$k=0;
$ownername='';
while($v = $friends_handler->getObject()){
	$friendsrows[$k]['myid']=$v->getVar('myid');
	$friendsrows[$k]['myname']=$v->getVar('myname');
	if(empty($ownername) && !empty($friendsrows[$k]['myname'])) $ownername=$v->getVar('myname');
	$friendsrows[$k]['yourid']=$v->getVar('yourid');
	$friendsrows[$k]['yourname']=$v->getVar('yourname');
	$friendsrows[$k]['adddate']=date(JIEQI_DATE_FORMAT, $v->getVar('adddate'));
	$k++;
}
$jieqiTpl->assign_by_ref('friendsrows', $friendsrows);
$jieqiTpl->assign('owner', $ownername);
$jieqiTpl->assign('ownerid', $_REQUEST['uid']);
$friendsnum=$friends_handler->getCount($criteria);
$jieqiTpl->assign('nowfriends', $friendsnum);
jieqi_getconfigs('system', 'honors');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'right');
$maxfriendsnum=intval($jieqiConfigs['system']['maxfriends']); //默认好友数
$honorid=jieqi_gethonorid($_SESSION['jieqiUserScore'], $jieqiHonors);
if($honorid && isset($jieqiRight['system']['maxfriends']['honors'][$honorid]) && is_numeric($jieqiRight['system']['maxfriends']['honors'][$honorid])) $maxfriendsnum = intval($jieqiRight['system']['maxfriends']['honors'][$honorid]); //根据头衔设置的好友数
$jieqiTpl->assign('maxfriends', $maxfriendsnum);
//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($friendsnum,$jieqiConfigs['system']['friendspnum'],$_REQUEST['page']);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userfriends.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>