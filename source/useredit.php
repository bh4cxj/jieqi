<?php 
/**
 * 编辑用户资料
 *
 * 编辑用户资料
 * 
 * 调用模板：/templates/userdetail.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: useredit.php 274 2008-12-09 06:34:24Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
jieqi_loadlang('users', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
switch ( $_REQUEST['action'] ) {
	case 'update':
		$_REQUEST['email'] = trim($_REQUEST['email']);
		//$_REQUEST['pass'] = trim($_REQUEST['pass']);
		//$_REQUEST['repass'] = trim($_REQUEST['repass']);
		$errtext='';
		//检查Email格式
		if (strlen($_REQUEST['email'])==0) $errtext .= $jieqiLang['system']['need_email'].'<br />';
		elseif ( !preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$_REQUEST['email']) ) $errtext .= $jieqiLang['system']['error_email_format'].'<br />';

		//检查密码
		//if ($_REQUEST['pass'] != $_REQUEST['repass']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';

		//检查Email是否已注册
		if($_REQUEST['email'] != $jieqiUsers->getVar('email','n')){
			if($users_handler->getCount(new Criteria('email', $_REQUEST['email'], '=')) > 0) $errtext .= $jieqiLang['system']['email_has_registered'].'<br />';
		}

		//修改昵称
		$changenick=false;
		if($jieqiUsers->getVar('name', 'n') != $_REQUEST['nickname']){
			if($_REQUEST['nickname'] != ''){
				if($users_handler->getByname($_REQUEST['nickname'], 3) != false) $errtext .= $jieqiLang['system']['user_name_exists'].'<br />';
			}
			$changenick=true;
		}

		//记录注册信息
		if(empty($errtext)) {
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('name', $_REQUEST['nickname']);
			//if(strlen($_REQUEST['pass'])>0){
			//	$jieqiUsers->setVar('pass',$users_handler->encryptPass($_REQUEST['pass']));
			//}
			$jieqiUsers->setVar('sex', $_REQUEST['sex']);
			$jieqiUsers->setVar('email', $_REQUEST['email']);
			$jieqiUsers->setVar('url', $_REQUEST['url']);
			$jieqiUsers->setVar('qq', $_REQUEST['qq']);
			$jieqiUsers->setVar('msn', $_REQUEST['msn']);
			if($_REQUEST['viewemail'] != 1) $_REQUEST['viewemail']=0;
			$jieqiUsers->setVar('viewemail', $_REQUEST['viewemail']);
			$jieqiUsers->setVar('adminemail', $_REQUEST['adminemail']);
			if(isset($_REQUEST['workid']) && intval($jieqiUsers->getVar('workid', 'n')) != intval($_REQUEST['workid'])){
				$jieqiUsers->setVar('workid', $_REQUEST['workid']);
				$changework=true;
			}else{
				$changework=false;
			}
			$jieqiUsers->setVar('sign', $_REQUEST['sign']);
			$jieqiUsers->setVar('intro', $_REQUEST['intro']);
			if (!$users_handler->insert($jieqiUsers)) jieqi_printfail($jieqiLang['system']['user_edit_failure']);
			else {
				if($changework && $_SESSION['jieqiUserId'] == $jieqiUsers->getVar('uid')){
					jieqi_getconfigs('system', 'honors');
					$honorid=jieqi_gethonorid($jieqiUsers->getVar('score'), $jieqiHonors);
					$_SESSION['jieqiUserHonor'] = $jieqiHonors[$honorid]['name'][intval($jieqiUsers->getVar('workid', 'n'))];
				}
				if($changenick && $_SESSION['jieqiUserId'] == $jieqiUsers->getVar('uid')){
					$_SESSION['jieqiUserName']=(strlen($jieqiUsers->getVar('name', 'n')) > 0) ? $jieqiUsers->getVar('name', 'n') : $jieqiUsers->getVar('uname', 'n');
				}
				$jieqiUsers->saveToSession();
				jieqi_jumppage(JIEQI_URL.'/userdetail.php', LANG_DO_SUCCESS, $jieqiLang['system']['user_edit_success']);
			}
		} else {
			jieqi_printfail($errtext);
		}
		break;
	case 'edit':
	default:
		//包含区块参数(定制区块)
		jieqi_getconfigs('system', 'userblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$edit_form = new JieqiThemeForm($jieqiLang['system']['user_edit'], 'useredit', JIEQI_URL.'/useredit.php');
		$edit_form->addElement(new JieqiFormLabel($jieqiLang['system']['table_users_uname'], $jieqiUsers->getVar('uname','s')));
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_name'], 'nickname', 25, 39, $jieqiUsers->getVar('name','e')));

		//$pass_tray = new JieqiFormElementTray($jieqiLang['system']['new_password']);
		//$pass_tray->addElement(new JieqiFormPassword($jieqiLang['system']['new_password'], 'pass', 25, 20));
		//$pass_tray->addElement(new JieqiFormLabel('', $jieqiLang['system']['not_change_password']));
		//$edit_form->addElement($pass_tray);
		//$edit_form->addElement(new JieqiFormPassword($jieqiLang['system']['confirm_new_password'], 'repass', 25, 20));
		$email_tray = new JieqiFormElementTray($jieqiLang['system']['table_users_email']);
		$email_text = new JieqiFormText($jieqiLang['system']['table_users_email'], 'email', 25, 60, $jieqiUsers->getVar('email','e'));
		$email_option = new JieqiFormCheckBox('', 'viewemail', $jieqiUsers->getVar('viewemail','e'));
		$email_option->addOption(1, $jieqiLang['system']['publish_email']);
		$email_tray->addElement($email_text);
		$email_tray->addElement($email_option);
		$edit_form->addElement($email_tray);
		$edit_form->setRequired($email_text);
		$sex_option = new JieqiFormRadio($jieqiLang['system']['table_users_sex'], 'sex', $jieqiUsers->getVar('sex','e'));
		$sex_option->addOption(1, $jieqiLang['system']['sex_man']);
		$sex_option->addOption(2, $jieqiLang['system']['sex_woman']);
		$sex_option->addOption(0, $jieqiLang['system']['sex_unset']);
		$edit_form->addElement($sex_option);
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_qq'], 'qq', 25, 15, $jieqiUsers->getVar('qq','e')));
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_msn'], 'msn', 25, 30, $jieqiUsers->getVar('msn','e')));
		$edit_form->addElement(new JieqiFormText($jieqiLang['system']['table_users_url'], 'url', 25, 100, $jieqiUsers->getVar('url','e')));
		$yesno=new JieqiFormRadio($jieqiLang['system']['table_users_adminemail'], 'adminemail', $jieqiUsers->getVar('adminemail','e'));
		$yesno->addOption(1, LANG_YES);
		$yesno->addOption(0, LANG_NO);
		$edit_form->addElement($yesno);
		//职业选择
		jieqi_getconfigs('system', 'works');
		if(is_array($jieqiWorks) && count($jieqiWorks)>1){
			$work_select = new JieqiFormSelect($jieqiLang['system']['table_users_workid'], 'workid', intval($jieqiUsers->getVar('workid','e')));
			foreach($jieqiWorks as $k=>$v){
				$work_select->addOption($k, jieqi_htmlstr($v));
			}
			$edit_form->addElement($work_select);
		}


		$edit_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_users_sign'], 'sign', $jieqiUsers->getVar('sign', 'e'), 6, 60));
		$edit_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_users_intro'], 'intro', $jieqiUsers->getVar('intro', 'e'), 6, 60));
		$edit_form->addElement(new JieqiFormHidden('action', 'update'));
		$edit_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
		$jieqiTpl->setCaching(0);
		$jieqiTpl->assign('jieqi_contents', $edit_form->render(JIEQI_FORM_MIDDLE));
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>