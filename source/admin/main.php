<?php
/**
 * 后台默认内容页
 *
 * 显示功能提示，官方新闻等
 * 
 * 调用模板：/templates/admin/default.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: main.php 322 2009-01-13 11:28:29Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');

include_once(JIEQI_ROOT_PATH.'/class/power.php');
$power_handler =& JieqiPowerHandler::getInstance('JieqiPowerHandler');
$power_handler->getSavedVars('system');
jieqi_checkpower($jieqiPower['system']['adminpanel'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

//包含页头页尾
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$siteurl='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
$siteurl=dirname(dirname($siteurl));
$siteip=@gethostbyname($_SERVER['SERVER_NAME']);

$jieqi_license_domain = '';
$matchs=array();
if(floatval(JIEQI_VERSION) >= 1.6){
	$jieqi_license_ary=jieqi_funtoarray('base64_decode', explode('@', JIEQI_LICENSE_KEY));
}else{
	$jieqi_license_ary=explode('@', base64_decode(JIEQI_LICENSE_KEY));
}
if (!empty($jieqi_license_ary[1]) && preg_match('/^(http:\/\/|https:\/\/)?[^\/\?]*('.$jieqi_license_ary[1].')/i', JIEQI_LOCAL_HOST, $matchs)) $jieqi_license_domain = $jieqi_license_ary[1];

$jieqiTpl->assign('jieqi_customerurl', 'http://www.jieqi.com/modules/customer/siteback.php?sitename='.urlencode(JIEQI_SITE_NAME).'&siteurl='.urlencode($siteurl).'&jieqiversion='.urlencode(JIEQI_VERSION).'&versiontype='.urlencode(JIEQI_VERSION_TYPE).'&licensedomain='.urlencode($jieqi_license_domain).'&siteip='.$siteip.'&userip='.urlencode(jieqi_userip()).'&jieqicharset='.urlencode(JIEQI_CHAR_SET).'&phpversion='.urlencode(PHP_VERSION).'&system='.urlencode(PHP_OS).'&freespace='.intval(@disk_free_space($_SERVER['DOCUMENT_ROOT'])/1048576).'&zendoptimizer='.urlencode(jieqi_zendoptimizerver()));

$jieqiTpl->display(JIEQI_ROOT_PATH.'/templates/admin/default.html');

function jieqi_zendoptimizerver(){
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	preg_match('/Zend(\s|&nbsp;)Optimizer(\s|&nbsp;)v([\.\d]*),/is', $phpinfo, $matches);
	if(!empty($matches[3])) return $matches[3];
	else return '';
}
?>