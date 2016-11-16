<?php 
/**
 * 用户密码修改
 *
 * 用户密码修改，需要输入原密码和两遍新密码
 * 
 * 调用模板：/templates/passedit.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: passedit.php 326 2009-02-04 00:26:22Z juny $
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
		$_REQUEST['oldpass'] = trim($_REQUEST['oldpass']);
		$_REQUEST['newpass'] = trim($_REQUEST['newpass']);
		$_REQUEST['repass'] = trim($_REQUEST['repass']);
		$errtext='';
		
		//检查密码
		if ($_REQUEST['newpass'] != $_REQUEST['repass']) $errtext .= $jieqiLang['system']['password_not_equal'].'<br />';
		elseif(strlen($_REQUEST['newpass']) == 0) $errtext .= $jieqiLang['system']['need_pass_repass'].'<br />';
		elseif($jieqiUsers->getVar('pass', 'n') != $users_handler->encryptPass($_REQUEST['oldpass'])) $errtext .= $jieqiLang['system']['error_old_pass'].'<br />';

		//更新密码
		if(empty($errtext)) {
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('pass',$users_handler->encryptPass($_REQUEST['newpass']));
			if (!$users_handler->insert($jieqiUsers)) jieqi_printfail($jieqiLang['system']['pass_edit_failure']);
			else {
				jieqi_jumppage(JIEQI_URL.'/userdetail.php', LANG_DO_SUCCESS, $jieqiLang['system']['pass_edit_success']);
			}
		} else {
			jieqi_printfail($errtext);
		}
		break;
	case 'edit':
	default:
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('url_passedit', JIEQI_USER_URL.'/passedit.php?do=submit');
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/passedit.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>