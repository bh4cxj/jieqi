<?php
/**
 * 权限管理
 *
 * 通用的权限设置程序
 * 
 * 调用模板：/templates/admin/online.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: power.php 179 2008-11-24 08:34:43Z juny $
 */

if(empty($_REQUEST['mod'])) $_REQUEST['mod']='system';
define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars($_REQUEST['mod']);
jieqi_checkpower($jieqiPower[$_REQUEST['mod']]['adminpower'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
//载入语言
jieqi_loadlang('power', JIEQI_MODULE_NAME);
if(count($jieqiPower[$_REQUEST['mod']])>0){
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='update'){
		foreach($jieqiPower[$_REQUEST['mod']] as $k => $v){
			if(!isset($_POST[$k])) $_POST[$k]='';
			if($v['groups'] != $_POST[$k]){
				$jieqiPower[$_REQUEST['mod']][$k]['groups']=$_POST[$k];
				$power_handler->db->query("UPDATE ".jieqi_dbprefix('system_power')." SET pgroups='".jieqi_dbslashes(serialize($_POST[$k]))."' WHERE modname='".jieqi_dbslashes($_REQUEST['mod'])."' AND pname='".jieqi_dbslashes($k)."'");
			}
		}
		jieqi_setconfigs('power', 'jieqiPower', $jieqiPower, $_REQUEST['mod']);
		jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['system']['edit_power_success']);
	}else{
		//显示权限设置
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		include_once(JIEQI_ROOT_PATH.'/class/groups.php');
		$groups_handler =& JieqiGroupsHandler::getInstance('JieqiGroupsHandler');
		$criteria=new CriteriaCompo();
		$criteria->setSort('groupid');
		$criteria->setOrder('ASC');
		$groups_handler->queryObjects($criteria);
		while($v = $groups_handler->getObject()){
			if($v->getVar('groupid') != JIEQI_GROUP_ADMIN) $groups[]=array('groupid'=>$v->getVar('groupid'), 'name'=>$v->getVar('name'));
		}
		unset($criteria);
		$power_form = new JieqiThemeForm($jieqiLang['system']['edit_power'], 'power', JIEQI_URL.'/admin/power.php');
		foreach($jieqiPower[$_REQUEST['mod']] as $k => $v){
			$_POST[$k]=new JieqiFormCheckBox($v['caption'], $k, $v['groups']);
			//$_POST[$k]->setDescription($v['description']);
			foreach($groups as $group){
				$_POST[$k]->addOption($group['groupid'], $group['name']);
			}
			$power_form->addElement($_POST[$k], false);
		}
		$power_form->addElement(new JieqiFormHidden('mod', $_REQUEST['mod']));
		$power_form->addElement(new JieqiFormHidden('action', 'update'));
		$power_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['save_power'], 'submit'));
		$jieqiTpl->setCaching(0);
		$jieqiTpl->assign('jieqi_contents', '<br />'.$power_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

	}
}else{
	jieqi_msgwin(LANG_NOTICE, $jieqiLang['system']['no_usage_power']);
}


?>