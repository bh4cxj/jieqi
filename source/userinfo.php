<?php 
/**
 * 查看用户信息
 *
 * 显示某一个人的用户信息
 * 
 * 调用模板：/templates/userinfo.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userinfo.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
//<!--jieqi insert check code-->
jieqi_checklogin();
if(empty($_REQUEST['id']) && empty($_REQUEST['username'])) jieqi_printfail(LANG_NO_USER);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['id'])){
	$userobj=$users_handler->get($_REQUEST['id']);
}else{
	//$_REQUEST['username']=strtolower(trim($_REQUEST['username']));
	$_REQUEST['username']=trim($_REQUEST['username']);
	$userobj=$users_handler->getByname($_REQUEST['username']);
	
}
if(is_object($userobj)){
	if(!isset($_REQUEST['id'])) $_REQUEST['id']=$userobj->getVar('uid');
	jieqi_getconfigs('system', 'honors');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('uid', $userobj->getVar('uid'));
	$jieqiTpl->assign('uname', $userobj->getVar('uname'));
	$tmpvar = strlen($userobj->getVar('name')) > 0 ? $userobj->getVar('name') : $userobj->getVar('uname');
	$jieqiTpl->assign('name', $tmpvar);
	$jieqiTpl->assign('groupid', $userobj->getVar('groupid'));
	$jieqiTpl->assign('group', $userobj->getGroup());
	$jieqiTpl->assign('sex', $userobj->getSex());

	if($userobj->getVar('viewemail')==1){
		$jieqiTpl->assign('viewemail', 1);
		$jieqiTpl->assign('email', $userobj->getVar('email'));
	}else{
		$jieqiTpl->assign('viewemail', 0);
		$jieqiTpl->assign('email', '');
	}
	$jieqiTpl->assign('qq', $userobj->getVar('qq'));
	$jieqiTpl->assign('icq', $userobj->getVar('icq'));
	$jieqiTpl->assign('msn', $userobj->getVar('msn'));
	$jieqiTpl->assign('url', $userobj->getVar('url'));

	$jieqiTpl->assign('regdate', date(JIEQI_DATE_FORMAT, $userobj->getVar('regdate')));
	$jieqiTpl->assign('experience', $userobj->getVar('experience'));
	$jieqiTpl->assign('score', $userobj->getVar('score'));
	$jieqiTpl->assign('monthscore', $userobj->getVar('monthscore'));
	$jieqiTpl->assign('weekscore', $userobj->getVar('weekscore'));
	$jieqiTpl->assign('dayscore', $userobj->getVar('dayscore'));
	$jieqiTpl->assign('credit', $userobj->getVar('credit'));
	$jieqiTpl->assign('isvip', $userobj->getVar('isvip'));
	$jieqiTpl->assign('viptype', $userobj->getViptype());
	$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);

	$honorid=jieqi_gethonorid($userobj->getVar('score'), $jieqiHonors);
	$jieqiTpl->assign('honor', $jieqiHonors[$honorid]['name'][intval($userobj->getVar('workid'))]);

	$egold=$userobj->getVar('egold');
	$esilver=$userobj->getVar('esilver');
	$emoney=$egold+$esilver;
	$jieqiTpl->assign('egold', $egold);
	$jieqiTpl->assign('esilver', $esilver);
	$jieqiTpl->assign('emoney', $emoney);

	$jieqiTpl->assign('sign', $userobj->getVar('sign'));
	$jieqiTpl->assign('intro', $userobj->getVar('intro'));

	//头像
	$avatar=$userobj->getVar('avatar');
	$jieqiTpl->assign('avatar', $avatar);
	//徽章
	if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
		include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
		if($_REQUEST['upbadges']) upuserbadge($_REQUEST['id']);
		//等级徽章
		$jieqiTpl->assign('jieqi_group_imageurl', getbadgeurl(1, $userobj->getVar('groupid'), 0, true));
		//头衔徽章
		$jieqiTpl->assign('jieqi_honor_imageurl', getbadgeurl(2, $honorid, 0, true));
		//自定义徽章
		$jieqi_badgerows=array();
		$badgeary=unserialize($userobj->getVar('badges', 'n'));
		if(is_array($badgeary) && count($badgeary)>0){
			$award_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			$criteria=new CriteriaCompo();
			$criteria->setTables(jieqi_dbprefix('badge_badge').' b LEFT JOIN '.jieqi_dbprefix('badge_award').' a ON b.badgeid=a.badgeid');
			$criteria->add(new Criteria('a.toid', $_REQUEST['id']));
			$criteria->setSort('b.btypeid ASC, a.awardid');
			$criteria->setOrder('ASC');
			$award_query->queryObjects($criteria);
			$k=0;
			while($award = $award_query->getObject()){
				$jieqi_badgerows[$k]['imageurl']=getbadgeurl($award->getVar('btypeid','n'), $award->getVar('linkid','n'), $award->getVar('imagetype','n'));
				$jieqi_badgerows[$k]['caption']=jieqi_htmlstr($award->getVar('caption'));
				$k++;
			}
		}
		$jieqiTpl->assign_by_ref('jieqi_badgerows', $jieqi_badgerows);
		$jieqiTpl->assign('jieqi_use_badge', 1);
	}else{
		$jieqiTpl->assign('jieqi_use_badge', 0);
	}

	//检查是否斑竹权限
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
	if(jieqi_checkpower($jieqiPower['system']['adminuser'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
		$ismaster=1;
	}else{
		$ismaster=0;
	}
	$jieqiTpl->assign('ismaster', $ismaster);

	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userinfo.html';
	include_once(JIEQI_ROOT_PATH.'/footer.php');
}else{
	jieqi_printfail(LANG_NO_USER);
}
?>