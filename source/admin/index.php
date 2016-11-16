<?php
/**
 * 后台首页
 *
 * 后台默认首页
 * 
 * 调用模板：/templates/admin/index.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: index.php 178 2008-11-24 08:19:25Z juny $
 */

define('JIEQI_MODULE_NAME', 'system'); 
define('JIEQI_ADMIN_LOGIN', 1);
require_once('../global.php');

include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminpanel'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

//包含页头页尾

include_once(JIEQI_ROOT_PATH.'/admin/header.php');

//框架左边页面
$jieqiTpl->assign('jieqi_adminleft', JIEQI_URL.'/admin/left.php');

//框架右边页面
if(isset($_SESSION['adminurl']) && !empty($_SESSION['adminurl'])){
	$jieqiTpl->assign('jieqi_adminmain', $_SESSION['adminurl']);
}else{
	$jieqiTpl->assign('jieqi_adminmain', JIEQI_URL.'/admin/main.php');
}

if (!empty($_SESSION['jieqiUserId'])){
	$jieqiTpl->assign('username', jieqi_htmlstr($_SESSION['jieqiUserName']));
	$jieqiTpl->assign('usergroup', $jieqiGroups[$_SESSION['jieqiUserGroup']]);
}

include_once JIEQI_ROOT_PATH.'/class/modules.php';
$modules_handler =& JieqiModulesHandler::getInstance('JieqiModulesHandler');
$criteria=new CriteriaCompo(new Criteria('publish',1,'='));
$criteria->setSort('weight');
$criteria->setOrder('ASC');
$modules_handler->queryObjects($criteria);
unset($criteria);
$jieqiModary=array();
while($v = $modules_handler->getObject()){
	$jieqiModary[$v->getVar('name','n')] = array('name' => $v->getVar('name','n'), 'caption' => $v->getVar('caption', 'n'), 'description' => $v->getVar('description', 'n'), 'version'=>sprintf("%0.2f", intval($v->getVar('version', 'n'))/100), 'vtype'=>$v->getVar('vtype', 'n'), 'publish' => $v->getVar('publish', 'n'));
}

$jieqiTpl->assign_by_ref('jieqimodules', $jieqiModary);
if(defined('JIEQI_VERSION_TYPE') && defined('LANG_VERSION_'.strtoupper(JIEQI_VERSION_TYPE))) $jieqiTpl->assign('jieqi_vtype', constant('LANG_VERSION_'.strtoupper(JIEQI_VERSION_TYPE)));
else $jieqiTpl->assign('jieqi_vtype', '');
$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/admin/index.html');


?>