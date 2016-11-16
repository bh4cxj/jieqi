<?php
/**
 * 后台程序头包含文件
 *
 * 后台程序的预处理部分，载入模板，默认赋值等
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: header.php 178 2008-11-24 08:19:25Z juny $
 */

include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
$jieqiTpl =& JieqiTpl::getInstance();

$jieqiTpl->setCaching(0);

$jieqiTpl->assign(array('jieqi_themeurl' => JIEQI_URL.'/templates/admin/',  'jieqi_sitename' => JIEQI_SITE_NAME, 'jieqi_email' => JIEQI_CONTACT_EMAIL, 'meta_keywords' => JIEQI_META_KEYWORDS, 'meta_description' => JIEQI_META_DESCRIPTION, 'meta_copyright' => JIEQI_META_COPYRIGHT));
$jieqiTpl->assign_by_ref('jieqi_modules', $jieqiModules);
if(defined('JIEQI_SILVER_USAGE') && JIEQI_SILVER_USAGE==1) $jieqiTpl->assign('jieqi_silverusage', 1);
else $jieqiTpl->assign('jieqi_silverusage', 0);
$jieqiTpl->assign('jieqi_thisurl', jieqi_addurlvars(array(),true,false));
if (!empty($modconfig['title'])) $jieqiTpl->assign('jieqi_pagetitle', $modconfig['title']);

if(empty($jieqi_pagetitle)) $jieqi_pagetitle=JIEQI_SITE_NAME;
$jieqiTpl->assign('jieqi_pagetitle', $jieqi_pagetitle);
//头部附加内容（javascript等）
if(!empty($jieqi_pagehead)) $jieqiTpl->assign('jieqi_head', $jieqi_pagehead);
else $jieqiTpl->assign('jieqi_head', '');

//设置当前显示的页面
$norecord=array('index.php', 'top.php', 'left.php', 'login.php');
$tmpstr = $_SERVER['PHP_SELF'] ? strtolower(basename($_SERVER['PHP_SELF'])) : strtolower(basename($_SERVER['SCRIPT_NAME']));

if(!in_array($tmpstr, $norecord)) {
	$_SESSION['adminurl']=jieqi_addurlvars(array());
}

?>