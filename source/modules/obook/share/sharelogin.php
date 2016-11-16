<?php
/**
 * 电子书共享平台登录
 *
 * 电子书共享平台登录
 * 
 * 调用模板：/modules/obook/templates/share/sharelogin.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sharelogin.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
define('JIEQI_ADMIN_LOGIN', 1);
//if(JIEQI_LOCAL_URL != JIEQI_USER_URL) header('Location: '.JIEQI_USER_URL.jieqi_addurlvars(array()));
if($_REQUEST['action']=='login') define('JIEQI_NEED_SESSION', 1);
require_once('../../../global.php');
jieqi_loadlang('share', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'publisher');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
if(!isset($_REQUEST['action'])) $_REQUEST['action']='';
if($_REQUEST['action']=='login' && isset($_REQUEST['publishid'])){
	
	$_REQUEST['publishid']=intval($_REQUEST['publishid']);
	if(isset($jieqiPublisher[$_REQUEST['publishid']]) && $jieqiPublisher[$_REQUEST['publishid']]['password']==$_REQUEST['password'] && !empty($_REQUEST['password'])){
		$_SESSION['jieqiPublishid']=$_REQUEST['publishid'];
		if (empty($_REQUEST['jumpurl'])) {
			$_REQUEST['jumpurl']=$jieqiModules['obook']['url'].'/share/sharestat.php';
		}
		jieqi_jumppage($_REQUEST['jumpurl'], LANG_DO_SUCCESS, $jieqiLang['obook']['share_login_success']);
		
	}else{
		jieqi_printfail($jieqiLang['obook']['share_login_error']);
	}
}else {
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$publishrows=array();
	foreach($jieqiPublisher as $k=>$v){
		$publishrows[]=array('id'=>$k, 'name'=>$v['name']);
	}
	$jieqiTpl->assign_by_ref('publishrows', $publishrows);
	if (!empty($_REQUEST['jumpurl'])) {
		$jieqiTpl->assign('url_login', $jieqiModules['obook']['url'].'/share/sharelogin.php?do=submit&jumpurl='.urlencode($_REQUEST['jumpurl']));
	}else{
		$jieqiTpl->assign('url_login', $jieqiModules['obook']['url'].'/share/sharelogin.php?do=submit');
	}
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/share/sharelogin.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
}
//包含页头页尾


?>