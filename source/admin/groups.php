<?php
/**
 * 用户组管理
 *
 * 用户组的增加、修改、删除
 * 
 * 调用模板：/templates/admin/groups.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: groups.php 326 2009-02-04 00:26:22Z juny $
 */

/*
* 管理用户组
*/

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
//检查权限
include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
//载入语言
jieqi_loadlang('groups', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
include_once(JIEQI_ROOT_PATH.'/class/groups.php');
$groups_handler =& JieqiGroupsHandler::getInstance('JieqiGroupsHandler');
if(empty($_REQUEST['action'])) $_REQUEST['action']='show';
switch($_REQUEST['action']){
    case 'new':
    if(empty($_POST['groupname'])) jieqi_printfail($jieqiLang['system']['need_group_name']);
    else{
        $groups= $groups_handler->create();
        $groups->setVar('name', $_POST['groupname']);
        $groups->setVar('description', $_POST['description']);
        $groups->setVar('grouptype','0');
        if(!$groups_handler->insert($groups)) jieqi_printfail($jieqiLang['system']['add_group_failure']);
    }
    break;
    case 'delete':
    if(!empty($_REQUEST['id'])){
        $groups_handler->delete($_REQUEST['id']);
    }
    break;
    case 'update':
    if(!empty($_REQUEST['id']) && !empty($_POST['groupname'])){
        $groups=$groups_handler->get($_REQUEST['id']);
        if(is_object($groups)){
            $groups->setVar('name',$_POST['groupname']);
            $groups->setVar('description',$_POST['description']);
            if(!$groups_handler->insert($groups)) jieqi_printfail($jieqiLang['system']['edit_group_failure']);
        }
    }
    break;
    case 'edit';
    if(!empty($_REQUEST['id'])){
        $groups=$groups_handler->get($_REQUEST['id']);
        if(is_object($groups)){
            include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		    $groups_form = new JieqiThemeForm($jieqiLang['system']['edit_group'], 'groupsedit', JIEQI_URL.'/admin/groups.php');
		    $groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, $groups->getVar('name','e')), true);
		    $groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', $groups->getVar('description','e'), 5, 50));
		    $groups_form->addElement(new JieqiFormHidden('action', 'update'));
		    $groups_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));
		    $groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SAVE, 'submit'));
		    $jieqiTpl->assign('jieqi_contents', '<br />'.$groups_form->render(JIEQI_FORM_MIDDLE).'<br />');
		    include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		    exit;
        }
    }
    break;
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$criteria=new CriteriaCompo();
$criteria->setSort('groupid');
$criteria->setOrder('ASC');
$groups_handler->queryObjects($criteria);
$groups=array();
$groupary=array();
$i=0;
while($v = $groups_handler->getObject()){
    $groupary[$v->getVar('groupid')]=$v->getVar('name');
    $groups[$i]['groupid']=$v->getVar('groupid');
    $groups[$i]['name']=$v->getVar('name');
    $groups[$i]['description']=$v->getVar('description');
    $groups[$i]['grouptype']=$v->getVar('grouptype');
    $i++;
}
$jieqiTpl->assign_by_ref('groups', $groups);
$groups_form = new JieqiThemeForm($jieqiLang['system']['add_group'], 'groupsnew', JIEQI_URL.'/admin/groups.php');
$groups_form->addElement(new JieqiFormText($jieqiLang['system']['table_groups_groupname'], 'groupname', 30, 50, ''), true);
$groups_form->addElement(new JieqiFormTextArea($jieqiLang['system']['table_groups_description'], 'description', '', 5, 50));
$groups_form->addElement(new JieqiFormHidden("action", "new"));
$groups_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['system']['add_group'], 'submit'));
$jieqiTpl->assign('form_addgroup', "<br />".$groups_form->render(JIEQI_FORM_MIDDLE)."<br />");
$jieqiTpl->setCaching(0);		    
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/groups.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

//数据有变动。更新文件
if((!empty($_REQUEST['id']) || !empty($_POST['groupname'])) && count($groupary)>0){
    jieqi_setconfigs('groups', 'jieqiGroups', $groupary, 'system');
    $publicdata=str_replace('?><?php', '', jieqi_readfile(JIEQI_ROOT_PATH.'/configs/system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/lang/lang_system.php').jieqi_readfile(JIEQI_ROOT_PATH.'/configs/groups.php'));
	jieqi_writefile(JIEQI_ROOT_PATH.'/configs/define.php', $publicdata);
}
?>