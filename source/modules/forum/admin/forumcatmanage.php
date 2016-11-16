<?php 
/**
 * 论坛分类管理
 *
 * 论坛分类管理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: forumcatmanage.php 253 2008-11-28 03:21:13Z juny $
 */

define('JIEQI_MODULE_NAME', 'forum');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['forum']['manageforum'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['forum']['select_forumcat']);
include_once($jieqiModules['forum']['path'].'/class/forumcat.php');
$forumcat_handler=JieqiForumcatHandler::getInstance('JieqiForumcatHandler');
$forumcat=$forumcat_handler->get($_REQUEST['id']);
if(!is_object($forumcat)){
	jieqi_printfail($jieqiLang['forum']['forumcat_not_exists']);
}else{
	if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
	switch($_REQUEST['action']){
		case 'update':
		$_POST['cattitle']=trim($_POST['cattitle']);
		$errtext='';
		if (strlen($_POST['cattitle'])==0) $errtext .= $jieqiLang['forum']['need_forumcat'].'<br />';
		if(empty($errtext)) {
			$forumcat->setVar('cattitle', $_POST['cattitle']);
			$forumcat->setVar('catorder', $_POST['catorder']);
			if(!$forumcat_handler->insert($forumcat)) jieqi_printfail($jieqiLang['forum']['save_forumcat_failure']);
			else{
				include_once($jieqiModules['forum']['path'].'/include/upforumcatset.php');  //更新配置文件
				jieqi_jumppage($jieqiModules['forum']['url'].'/admin/forumlist.php', LANG_DO_SUCCESS, $jieqiLang['forum']['save_forumcat_success']);
			}
		}else{
			jieqi_printfail($errtext);
		}
		break;
		case 'edit':
		default:
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$edit_form = new JieqiThemeForm($jieqiLang['forum']['forumcat_manage'], 'forumcatmanage', $jieqiModules['forum']['url'].'/admin/forumcatmanage.php');
		$edit_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumcat_cattitle'], 'cattitle', 25, 15, $forumcat->getVar('cattitle','e')), true);
		$edit_form->addElement(new JieqiFormText($jieqiLang['forum']['table_forumcat_catorder'], 'catorder', 25, 15, $forumcat->getVar('catorder','e')), true);
		$edit_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));
		$edit_form->addElement(new JieqiFormHidden('action', 'update'));
		$edit_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['forum']['forumcat_save_button'], 'submit'));
		$jieqiTpl->setCaching(0);
		$jieqiTpl->assign('jieqi_contents', $edit_form->render(JIEQI_FORM_MIDDLE));
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		break;
	}
}
?>