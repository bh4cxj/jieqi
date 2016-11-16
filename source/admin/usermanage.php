<?php
/**
 * 用户资料管理
 *
 * 修改用户资料
 * 
 * 调用模板：/templates/admin/topuser.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: usermanage.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminuser'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_NO_USER);
jieqi_loadlang('users', JIEQI_MODULE_NAME);
$_REQUEST['id'] = intval($_REQUEST['id']);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$user=$users_handler->get($_REQUEST['id']);
if(!is_object($user)) jieqi_printfail(LANG_NO_USER);

if($user->getVar('groupid') == JIEQI_GROUP_ADMIN && $jieqiUsersGroup != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['system']['cant_manage_admin']);

if(jieqi_checkpower($jieqiPower['system']['deluser'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=4;
elseif(jieqi_checkpower($jieqiPower['system']['adminvip'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=3;
elseif(jieqi_checkpower($jieqiPower['system']['changegroup'], $jieqiUsersStatus, $jieqiUsersGroup, true, true)) $adminlevel=2;
else $adminlevel=1;


if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
switch ( $_REQUEST['action'] ) {
	case 'update':
	$_POST['reason'] = trim($_POST['reason']);
	$_POST['pass'] = trim($_POST['pass']);
	$_POST['repass'] = trim($_POST['repass']);
	
	if (strlen($_POST['reason'])==0) $errtext .= $jieqiLang['system']['change_user_reason'].'<br />';
	//检查密码
	if ($_POST['pass'] != $_POST['repass']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';
	//记录注册信息
	if(empty($errtext)) {
		//处理删除
		if($adminlevel>=4 && isset($_POST['deluser']) && $_POST['deluser']==1){
			if(!$users_handler->delete($user->getVar('uid'))) jieqi_printfail($jieqiLang['system']['delete_user_failure']);
			else{
				include_once(JIEQI_ROOT_PATH.'/class/userlog.php');
				//记录日志
				$userlog_handler = JieqiUserlogHandler::getInstance('JieqiUserlogHandler');
				$newlog=$userlog_handler->create();
				$newlog->setVar('siteid', JIEQI_SITE_ID);
				$newlog->setVar('logtime', JIEQI_NOW_TIME);
				$newlog->setVar('fromid', $_SESSION['jieqiUserId']);
				$newlog->setVar('fromname', $_SESSION['jieqiUserName']);
				$newlog->setVar('toid', $user->getVar('uid', 'n'));
				$newlog->setVar('toname', $user->getVar('uname', 'n'));
				$newlog->setVar('reason', $_POST['reason']);
				$newlog->setVar('chginfo', $jieqiLang['system']['delete_user']);
				$newlog->setVar('chglog', '');
				$newlog->setVar('isdel', '1');
				$newlog->setVar('userlog', serialize($user->getVars()));
				$userlog_handler->insert($newlog);
				jieqi_jumppage(JIEQI_URL.'/admin/users.php', LANG_DO_SUCCESS, $jieqiLang['system']['delete_user_success']);
				exit;
			}
		}
		
		$chglog=array();
		$chginfo='';
		//修改密码
		if(strlen($_POST['pass'])>0){
			$user->setVar('pass',$users_handler->encryptPass($_POST['pass']));
			$chginfo.=$jieqiLang['system']['userlog_change_password'];
		}
		//经验值
		if(is_numeric($_POST['experience']) && $_POST['experience'] != $user->getVar('experience')){
			$chglog['experience']['from']=$user->getVar('experience');
			$chglog['experience']['to']=$_POST['experience'];
			$user->setVar('experience', $_POST['experience']);
			if($chglog['experience']['from'] > $chglog['experience']['to']){
				$chginfo.=sprintf($jieqiLang['system']['userlog_less_experience'], $chglog['experience']['from'] - $chglog['experience']['to']);
			}else{
				$chginfo.=sprintf($jieqiLang['system']['userlog_add_experience'], $chglog['experience']['to'] - $chglog['experience']['from']);
			}
		}
		//积分
		if(is_numeric($_POST['score']) && $_POST['score'] != $user->getVar('score')){
			$chglog['score']['from']=$user->getVar('score');
			$chglog['score']['to']=$_POST['score'];
			$user->setVar('score', $_POST['score']);
			if($chglog['score']['from'] > $chglog['score']['to']){
				$chginfo.=sprintf($jieqiLang['system']['userlog_less_score'], $chglog['score']['from'] - $chglog['score']['to']);
			}else{
				$chginfo.=sprintf($jieqiLang['system']['userlog_add_score'], $chglog['score']['to'] - $chglog['score']['from']);
			}
		}
		
		if($adminlevel>=2){
			//会员等级
			if(is_numeric($_POST['groupid']) && $_POST['groupid'] != $user->getVar('groupid')){
				if($_POST['groupid'] == JIEQI_GROUP_ADMIN && $jieqiUsersGroup != JIEQI_GROUP_ADMIN) jieqi_printfail($jieqiLang['system']['cant_set_admin']);
				$chglog['groupid']['from']=$user->getVar('groupid');
				$chglog['groupid']['to']=$_POST['groupid'];
				$user->setVar('groupid', $_POST['groupid']);
				$chginfo.=sprintf($jieqiLang['system']['userlog_change_group'], $jieqiGroups[$chglog['groupid']['from']], $jieqiGroups[$chglog['groupid']['to']]);
			}
		}
		
		if($adminlevel>=3){
			//虚拟货币
			if(is_numeric($_POST['egold']) && $_POST['egold'] != $user->getVar('egold')){
				$chglog['egold']['from']=$user->getVar('egold');
				$chglog['egold']['to']=$_POST['egold'];
				$user->setVar('egold', $_POST['egold']);
				if($chglog['egold']['from'] > $chglog['egold']['to']){
					$chginfo.=sprintf($jieqiLang['system']['userlog_less_egold'], JIEQI_EGOLD_NAME, $chglog['egold']['from'] - $chglog['egold']['to']);
				}else{
					$chginfo.=sprintf($jieqiLang['system']['userlog_add_egold'], JIEQI_EGOLD_NAME, $chglog['egold']['to'] - $chglog['egold']['from']);
				}
			}
			//银币
			if(is_numeric($_POST['esilver']) && $_POST['esilver'] != $user->getVar('esilver')){
				$chglog['esilver']['from']=$user->getVar('esilver');
				$chglog['esilver']['to']=$peyment;
				$user->setVar('esilver', $_POST['esilver']);
				if($chglog['esilver']['from'] > $chglog['esilver']['to']){
					$chginfo.=sprintf($jieqiLang['system']['userlog_less_esilver'], $chglog['esilver']['from'] - $chglog['esilver']['to']);
				}else{
					$chginfo.=sprintf($jieqiLang['system']['userlog_add_esilver'], $chglog['esilver']['to'] - $chglog['esilver']['from']);
				}
			}
			
  		    //VIP状态
			if(is_numeric($_POST['isvip']) && $_POST['isvip'] != $user->getVar('isvip')){
				$tmpstr=$user->getViptype();
				$chglog['isvip']['from']=$user->getVar('isvip');
				$chglog['isvip']['to']=$_POST['groupid'];
				$user->setVar('isvip', $_POST['isvip']);
				$chginfo.=sprintf($jieqiLang['system']['userlog_change_vip'], $tmpstr, $user->getViptype());
			}
			
		}
		
		if (!$users_handler->insert($user)) jieqi_printfail($jieqiLang['system']['change_user_failure']);
		else {
			include_once(JIEQI_ROOT_PATH.'/class/userlog.php');
			//记录日志
			$userlog_handler = JieqiUserlogHandler::getInstance('JieqiUserlogHandler');
			$newlog=$userlog_handler->create();
			$newlog->setVar('siteid', JIEQI_SITE_ID);
			$newlog->setVar('logtime', JIEQI_NOW_TIME);
			$newlog->setVar('fromid', $_SESSION['jieqiUserId']);
			$newlog->setVar('fromname', $_SESSION['jieqiUserName']);
			$newlog->setVar('toid', $user->getVar('uid', 'n'));
			$newlog->setVar('toname', $user->getVar('uname', 'n'));
			$newlog->setVar('reason', $_POST['reason']);
			$newlog->setVar('chginfo', $chginfo);
			$newlog->setVar('chglog', serialize($chglog));
			$newlog->setVar('isdel', '0');
			$newlog->setVar('userlog', '');
			$userlog_handler->insert($newlog);
			jieqi_jumppage(JIEQI_URL.'/admin/users.php', LANG_DO_SUCCESS, $jieqiLang['system']['change_user_success']);
		}
	} else {
		jieqi_printfail($errtext);
	}
	break;
	case 'edit':
	default:
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	$edit_form = new JieqiThemeForm($jieqiLang['system']['user_manage'], 'usermanage', JIEQI_URL.'/admin/usermanage.php');
	$edit_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_users_uname'], $user->getVar('uname')));
	$pass=new JieqiFormPassword($jieqiLang['system']['table_users_pass'], 'pass', 25, 20);
	$pass->setDescription($jieqiLang['system']['not_change_password']);
	$edit_form->addElement($pass);
	$edit_form->addElement(new JieqiFormPassword($jieqiLang['system']['confirm_password'], 'repass', 25, 20));
	if($adminlevel >= 2){
		$group_select = new JieqiFormSelect($jieqiLang['system']['table_users_groupid'],'groupid', $user->getVar('groupid', 'e'));
		foreach($jieqiGroups as $key => $val){
			$group_select->addOption($key, $val);
		}
		$edit_form->addElement($group_select, true);
	}
	$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_experience'], 'experience', 25, 11, $user->getVar('experience','e')));
	$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_score'], 'score', 25, 11, $user->getVar('score','e')));
		
	if($adminlevel>=3){
		$edit_form->addElement(new JieqiFormText(JIEQI_EGOLD_NAME, 'egold', 25, 11, $user->getVar('egold','e')));
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_esilver'], 'esilver', 25, 11, $user->getVar('esilver','e')));
		$isvip=new JieqiFormRadio($jieqiLang['system']['table_users_isvip'], 'isvip', $user->getVar('isvip', 'e'));
		$isvip->addOption(0, $jieqiLang['system']['user_no_vip']);
		$isvip->addOption(1, $jieqiLang['system']['user_is_vip']);
		$isvip->addOption(2, $jieqiLang['system']['user_super_vip']);
		$edit_form->addElement($isvip);
	}
	if($adminlevel>=4){
		$yesno=new JieqiFormRadio($jieqiLang['system']['delete_user'], 'deluser', 0);
		$yesno->addOption(0, LANG_NO);
		$yesno->addOption(1, LANG_YES);
		$edit_form->addElement($yesno);
	}
	$edit_form->addElement(new JieqiFormTextArea($jieqiLang['system']['user_change_reason'], 'reason', '', 6, 60), true);
	$edit_form->addElement(new JieqiFormHidden('action', 'update'));
	$edit_form->addElement(new JieqiFormHidden('id',$_REQUEST['id']));
	$edit_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['user_save_change'], 'submit'));
	$jieqiTpl->assign('jieqi_contents', '<br />'.$edit_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
	break;
}
?>