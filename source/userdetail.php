<?php 
/**
 * 用户资料详细信息
 *
 * 查看自己的用户资料
 * 
 * 调用模板：/templates/userdetail.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: userdetail.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();

include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);

jieqi_getconfigs('system', 'honors');
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('uid', $jieqiUsers->getVar('uid'));
$jieqiTpl->assign('uname', $jieqiUsers->getVar('uname'));
$jieqiTpl->assign('name', $jieqiUsers->getVar('name'));
$jieqiTpl->assign('group', $jieqiUsers->getGroup());
$jieqiTpl->assign('sex', $jieqiUsers->getSex());
$jieqiTpl->assign('email', $jieqiUsers->getVar('email'));
$jieqiTpl->assign('qq', $jieqiUsers->getVar('qq'));
$jieqiTpl->assign('icq', $jieqiUsers->getVar('icq'));
$jieqiTpl->assign('msn', $jieqiUsers->getVar('msn'));
$jieqiTpl->assign('url', $jieqiUsers->getVar('url'));
$jieqiTpl->assign('regdate', date(JIEQI_DATE_FORMAT, $jieqiUsers->getVar('regdate')));
$jieqiTpl->assign('experience', $jieqiUsers->getVar('experience'));
$jieqiTpl->assign('score', $jieqiUsers->getVar('score'));
$jieqiTpl->assign('monthscore', $jieqiUsers->getVar('monthscore'));
$jieqiTpl->assign('weekscore', $jieqiUsers->getVar('weekscore'));
$jieqiTpl->assign('dayscore', $jieqiUsers->getVar('dayscore'));
$jieqiTpl->assign('credit', $jieqiUsers->getVar('credit'));
$jieqiTpl->assign('isvip', $jieqiUsers->getVar('isvip'));
$jieqiTpl->assign('viptype', $jieqiUsers->getViptype());
$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
$honorid=jieqi_gethonorid($jieqiUsers->getVar('score'), $jieqiHonors);
$jieqiTpl->assign('honor', $jieqiHonors[$honorid]['name'][intval($jieqiUsers->getVar('workid','n'))]);

$egold=$jieqiUsers->getVar('egold');
$esilver=$jieqiUsers->getVar('esilver');
$emoney=$egold+$esilver;
$jieqiTpl->assign('egold', $egold);
$jieqiTpl->assign('esilver', $esilver);
$jieqiTpl->assign('emoney', $emoney);
$jieqiTpl->assign('flower', $jieqiUsers->getVar('flower'));
$jieqiTpl->assign('egg', $jieqiUsers->getVar('egg'));
$jieqiTpl->assign('sign', $jieqiUsers->getVar('sign'));
$jieqiTpl->assign('intro', $jieqiUsers->getVar('intro'));

//头像
$avatar=$jieqiUsers->getVar('avatar');
$jieqiTpl->assign('avatar', $avatar);
//徽章
if(!empty($jieqiModules['badge']['publish']) && is_file($jieqiModules['badge']['path'].'/include/badgefunction.php')){
	include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
	//等级徽章
	$jieqiTpl->assign('jieqi_group_imageurl', getbadgeurl(1, $jieqiUsers->getVar('groupid'), 0, true));
	//头衔徽章
	$jieqiTpl->assign('jieqi_honor_imageurl', getbadgeurl(2, $honorid, 0, true));
	//自定义徽章
	$jieqi_jieqi_badgerows=array();
	$badgeary=unserialize($jieqiUsers->getVar('badges', 'n'));
	if(is_array($badgeary) && count($badgeary)>0){
		$award_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$criteria=new CriteriaCompo();
		$criteria->setTables(jieqi_dbprefix('badge_award').' a LEFT JOIN '.jieqi_dbprefix('badge_badge').' b ON a.badgeid=b.badgeid');
		$criteria->add(new Criteria('a.toid', $_SESSION['jieqiUserId']));
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

//权利
jieqi_getconfigs('system', 'configs');
jieqi_getconfigs('system', 'right');
$handle = @opendir(JIEQI_ROOT_PATH.'/modules');
while ($file = @readdir($handle)) {
	if($file != '.' && $file != '..' && is_dir(JIEQI_ROOT_PATH.'/modules'.DIRECTORY_SEPARATOR.$file)){
		jieqi_getconfigs($file, 'configs');
		jieqi_getconfigs($file, 'right');
	}
}
@closedir($handle);
foreach($jieqiRight as $mod=>$t){
	foreach($t as $right=>$v){
		$tmpvar=0;
		if(isset($jieqiConfigs[$mod][$right])) $tmpvar=$jieqiConfigs[$mod][$right];
		if($honorid && isset($jieqiRight[$mod][$right]['honors'][$honorid]) && is_numeric($jieqiRight[$mod][$right]['honors'][$honorid])) $tmpvar = intval($jieqiRight[$mod][$right]['honors'][$honorid]);
		$jieqiTpl->assign($mod.'_'.$right, $tmpvar);
	}
}

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/userdetail.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');

?>