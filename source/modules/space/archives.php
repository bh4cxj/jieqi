<?php 
/**
 * 显示博客的详细信息
 *
 * 显示博客的详细信息
 * 
 * 调用模板：$jieqiModules['space']['path'].'/templates/userinfo.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME', 'space');
require_once('../../global.php');
jieqi_checklogin();
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
if(!empty($_REQUEST['uid'])){
	$userobj=$users_handler->get($_REQUEST['uid']);
}else{
	//$_REQUEST['username']=strtolower(trim($_REQUEST['username']));
	$_REQUEST['username']=trim($_REQUEST['username']);
	$userobj=$users_handler->getByname($_REQUEST['username']);
}
if(is_object($userobj)){
	jieqi_getconfigs('system', 'honors');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	jieqi_getconfigs('space', 'archivesblocks','jieqiBlocks');
	$jieqiTpl->assign('uid', $userobj->getVar('uid'));
	$jieqiTpl->assign('uname', $userobj->getVar('uname'));
	$jieqiTpl->assign('name', $userobj->getVar('name'));
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
	if($avatar>0){
		if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
		if(empty($jieqi_image_type)) $image_types=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
		else $image_types=$jieqi_image_type;
		$jieqiTpl->assign('url_avatar', jieqi_uploadurl($jieqiConfigs['system']['avatardir'], '', 'system').jieqi_getsubdir($userobj->getVar('uid')).'/'.$userobj->getVar('uid').$image_types[$avatar]);
	}else{
		$jieqiTpl->assign('url_avatar', '');
	}

	//徽章
	if(defined('JIEQI_USE_BADGE') && JIEQI_USE_BADGE == 1 && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
		include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
		//等级徽章
		$jieqiTpl->assign('jieqi_group_imageurl', getbadgeurl(1, $userobj->getVar('groupid'), 0, true));
		//头衔徽章
		$jieqiTpl->assign('jieqi_honor_imageurl', getbadgeurl(2, $honorid, 0, true));
		//自定义徽章
		$jieqi_jieqi_badgerows=array();
		$badgeary=unserialize($userobj->getVar('badges', 'n'));
		if(is_array($badgeary) && count($badgeary)>0){
			$award_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
			$criteria=new CriteriaCompo();
			$criteria->setTables(jieqi_dbprefix('badge_award').' a LEFT JOIN '.jieqi_dbprefix('badge_badge').' b ON a.badgeid=b.badgeid');
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
	include_once($jieqiModules['space']['path'].'/spaceheader.php');
	$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch($jieqiModules['space']['path'].'/templates/userinfo.html'));
	include_once($jieqiModules['space']['path'].'/spacefooter.php');
}else{
	jieqi_printfail(LANG_NO_USER);
}
?>