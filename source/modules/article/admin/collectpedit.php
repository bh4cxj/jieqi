<?php 
/**
 * 编辑批量采集规则
 *
 * 编辑批量采集规则
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectpedit.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('collect', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
if(empty($_REQUEST['config']) || !file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php')) jieqi_printfail($jieqiLang['article']['no_site_collectrule']);
include_once(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php');
if(!isset($_REQUEST['cid']) || !isset($jieqiCollect['listcollect'][$_REQUEST['cid']])) jieqi_printfail($jieqiLang['article']['no_batch_collectrule']);

include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

switch($_REQUEST['action']){
	case 'edit':
		$tmpary=array();
		$tmpary['title']=trim($_POST['title']); //采集规则名称
		$tmpary['urlpage']=trim($_POST['urlpage']); //采集地址
		$tmpary['articleid']=jieqi_collectptos($_POST['articleid']);  //获取文章id规则
		$tmpary['startpageid']=trim($_POST['startpageid']);  //第一页变量
		$tmpary['nextpageid']=jieqi_collectptos($_POST['nextpageid']); //获取下一页变量
		$_POST['maxpagenum']=trim($_POST['maxpagenum']);
		if(is_numeric($_POST['maxpagenum'])) $tmpary['maxpagenum']=intval($_POST['maxpagenum']);  //最多采集几页
		else $tmpary['maxpagenum']='';
		$jieqiCollect['listcollect'][$_REQUEST['cid']]=$tmpary;
		$configstr="<?php\n".jieqi_extractvars('jieqiCollect', $jieqiCollect)."\n?>";
		jieqi_writefile(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php', $configstr);
		
		jieqi_jumppage($jieqiModules['article']['url'].'/admin/collectpage.php?config='.$_REQUEST['config'], LANG_DO_SUCCESS, $jieqiLang['article']['batchcollect_edit_success']);
		break;
	case 'show':
	default:
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['batchcollect_edit'], 'collectedit', $article_static_url.'/admin/collectpedit.php');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_siteid'], $jieqiCollect['sitename']));

		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_name'], 'title', 60, 60, htmlspecialchars($jieqiCollect['listcollect'][$_REQUEST['cid']]['title'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['collect_rule_url'], 'urlpage', 60, 250, htmlspecialchars($jieqiCollect['listcollect'][$_REQUEST['cid']]['urlpage'], ENT_QUOTES)), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['collect_rule_articleid'], 'articleid', htmlspecialchars(jieqi_collectstop($jieqiCollect['listcollect'][$_REQUEST['cid']]['articleid']), ENT_QUOTES), 5, 60), true);
		$nextpageid=new JieqiFormTextArea($jieqiLang['article']['rule_next_pageid'], 'nextpageid', htmlspecialchars(jieqi_collectstop($jieqiCollect['listcollect'][$_REQUEST['cid']]['nextpageid']), ENT_QUOTES), 5, 60);
		$nextpageid->setDescription($jieqiLang['article']['rule_nextpage_note']);
		$collect_form->addElement($nextpageid);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_start_pageid'], 'startpageid', 60, 60, htmlspecialchars($jieqiCollect['listcollect'][$_REQUEST['cid']]['startpageid'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_max_pagenum'], 'maxpagenum', 60, 10, htmlspecialchars($jieqiCollect['listcollect'][$_REQUEST['cid']]['maxpagenum'], ENT_QUOTES)));

		$collect_form->addElement(new JieqiFormHidden('config', htmlspecialchars($_REQUEST['config'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormHidden('cid', htmlspecialchars($_REQUEST['cid'], ENT_QUOTES)));
		$collect_form->addElement(new JieqiFormHidden('action', 'edit'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_save_edit'], 'submit'));

		$jieqiTpl->assign('jieqi_contents', '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		break;
}
?>