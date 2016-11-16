<?php
/**
 * 按页面批量采集
 *
 * 按页面批量采集
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectpage.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('collect', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite');
if(empty($_REQUEST['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php')) jieqi_printfail($jieqiLang['article']['rule_not_exists']);
include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php');
include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
switch($_REQUEST['action']){
	case 'new':
		$tmpary=array();
		$tmpary['title']=trim($_POST['title']); //采集规则名称
		$tmpary['urlpage']=trim($_POST['urlpage']); //采集地址
		$tmpary['articleid']=jieqi_collectptos($_POST['articleid']);  //获取文章id规则
		$tmpary['startpageid']=trim($_POST['startpageid']);  //第一页变量
		$tmpary['nextpageid']=jieqi_collectptos($_POST['nextpageid']); //获取下一页变量
		$_POST['maxpagenum']=trim($_POST['maxpagenum']);
		if(is_numeric($_POST['maxpagenum'])) $tmpary['maxpagenum']=intval($_POST['maxpagenum']);  //最多采集几页
		else $tmpary['maxpagenum']='';
		$jieqiCollect['listcollect'][]=$tmpary;
		jieqi_setconfigs('site_'.$_POST['config'], 'jieqiCollect', $jieqiCollect, JIEQI_MODULE_NAME);
		break;
	case 'del':
		if(isset($_REQUEST['cid']) && isset($jieqiCollect['listcollect'][$_REQUEST['cid']])){
			unset($jieqiCollect['listcollect'][$_REQUEST['cid']]);
			jieqi_setconfigs('site_'.$_REQUEST['config'], 'jieqiCollect', $jieqiCollect, JIEQI_MODULE_NAME);
		}
		break;
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

$jieqiTpl->assign('sitename', $jieqiCollect['sitename']);
$jieqiTpl->assign('config', $_REQUEST['config']);
$jieqiTpl->assign_by_ref('collectrows', $jieqiCollect['listcollect']);
//增加规则的表
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
$collect_form = new JieqiThemeForm($jieqiLang['article']['add_batch_collectrule'], 'collectnew', $article_static_url.'/admin/collectpage.php');
$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_name'], 'title', 60, 60, ''), true);
$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_url'], 'urlpage', 60, 250, ''), true);
$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['collect_rule_articleid'], 'articleid', '', 5, 60), true);
$nextpageid=new JieqiFormTextArea($jieqiLang['article']['rule_next_pageid'], 'nextpageid', '', 5, 60);
$nextpageid->setDescription($jieqiLang['article']['rule_nextpage_note']);
$collect_form->addElement($nextpageid);
$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_start_pageid'], 'startpageid', 60, 60, ''));
$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_max_pagenum'], 'maxpagenum', 60, 10, ''));

$collect_form->addElement(new JieqiFormHidden('config', htmlspecialchars($_REQUEST['config'], ENT_QUOTES)));
$collect_form->addElement(new JieqiFormHidden('action', 'new'));
$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_add_new'], 'submit'));

$jieqiTpl->assign('addnewtable', $collect_form->render(JIEQI_FORM_MIDDLE));

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/collectpage.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>