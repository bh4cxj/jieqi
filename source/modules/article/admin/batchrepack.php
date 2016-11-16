<?php 
/**
 * 批量重新生成文章阅读格式
 *
 * 批量重新生成文章阅读格式
 * 
 * 调用模板：/modules/article/templates/admin/batchrepack.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: batchrepack.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_USE_GZIP','0');
define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人文章权限
jieqi_checkpower($jieqiPower['article']['manageallarticle'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if(isset($_REQUEST['action']) && $_REQUEST['action']=='packwithid'){
	if(!empty($_REQUEST['flagary'])){
		$_REQUEST['flagary']=unserialize(urldecode($_REQUEST['flagary']));
	}else $_REQUEST['flagary']=$_REQUEST['packflag'];
	if(!is_array($_REQUEST['flagary']) || count($_REQUEST['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_repack_option']);
	if(empty($_REQUEST['fromid']) || !is_numeric($_REQUEST['fromid'])) jieqi_printfail($jieqiLang['article']['repack_start_id']);
	if(empty($_REQUEST['toid'])) $_REQUEST['toid']=0;
	if($_REQUEST['fromid']>$_REQUEST['toid']){
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_repack_success'], $article_static_url.'/admin/batchrepack.php'));
		exit;
	}
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');

	$article=$article_handler->get($_REQUEST['fromid']);
	if(is_object($article)){
		$articlename=$article->getVar('articlename');
		include_once($jieqiModules['article']['path'].'/include/repack.php');
		$ptypes=array();
		foreach($_REQUEST['flagary'] as $v) $ptypes[$v]=1;
		echo '                                                                                                                                                                                                                                                                ';
		echo sprintf($jieqiLang['article']['repack_fromto_id'], $articlename, $_REQUEST['fromid'], $_REQUEST['toid']);
		ob_flush();
flush();
		article_repack($_REQUEST['fromid'], $ptypes, 1);
		$showinfo=$jieqiLang['article']['repack_success_next'];
	}else{
		$showinfo=$jieqiLang['article']['repack_noid_next'];
	}
	$_REQUEST['fromid']++;
	$url=$article_static_url.'/admin/batchrepack.php?action=packwithid&fromid='.$_REQUEST['fromid'].'&toid='.$_REQUEST['toid'];
	foreach($_REQUEST['packflag'] as $k=>$v){
		$url.='&packflag['.$k.']='.$v;
	}
	echo sprintf($jieqiLang['article']['repack_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=='packwithtime'){
	if(!empty($_REQUEST['flagary'])){
		$_REQUEST['flagary']=unserialize(urldecode($_REQUEST['flagary']));
	}else $_REQUEST['flagary']=$_REQUEST['packflag'];
	if(!is_array($_REQUEST['flagary']) || count($_REQUEST['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_repack_option']);
	$starttime=trim($_REQUEST['starttime']);
	$stoptime=trim($_REQUEST['stoptime']);
	$startlimit=trim($_REQUEST['startlimit']);
	if(empty($starttime)) jieqi_printfail($jieqiLang['article']['need_time_format']);
	if(!is_numeric($starttime)){
	    $starttime=mktime((int)substr($starttime,11,2), (int)substr($starttime,14,2), (int)substr($starttime,17,2), (int)substr($starttime,5,2), (int)substr($starttime,8,2), (int)substr($starttime,0,5));
	}
	if(empty($stoptime)) $stoptime=JIEQI_NOW_TIME;
	if(!is_numeric($stoptime)){
	    $stoptime=mktime((int)substr($stoptime,11,2), (int)substr($stoptime,14,2), (int)substr($stoptime,17,2), (int)substr($stoptime,5,2), (int)substr($stoptime,8,2), (int)substr($stoptime,0,5));
	}
	
	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	if(empty($startlimit)) $startlimit=0;
	$criteria=new CriteriaCompo(new Criteria('lastupdate', $starttime, '>='));
	$criteria->add(new Criteria('lastupdate', $stoptime, '<='));
	$criteria->setSort('lastupdate');
	$criteria->setOrder('ASC');
	$criteria->setStart($startlimit);
	$criteria->setLimit(1);
	$article_handler->queryObjects($criteria);
	$article=$article_handler->getObject();
	if(is_object($article)){
		$articlename=$article->getVar('articlename');
		include_once($jieqiModules['article']['path'].'/include/repack.php');
		$ptypes=array();
		foreach($_REQUEST['flagary'] as $v) $ptypes[$v]=1;
		echo '                                                                                                                                                                                                                                                                ';
		echo sprintf($jieqiLang['article']['batch_repack_doing'], $articlename, date('Y-m-d H:i:s', $starttime), date('Y-m-d H:i:s', $stoptime), date('Y-m-d H:i:s', $article->getVar('lastupdate')), $article->getVar('articleid'));
		ob_flush();
flush();
		article_repack($article->getVar('articleid'), $ptypes, 1);
		$showinfo=$jieqiLang['article']['repack_success_next'];
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_repack_success'], $article_static_url.'/admin/batchrepack.php'));
		exit;
	}
	$startlimit++;
	$url=$article_static_url.'/admin/batchrepack.php?action=packwithtime&starttime='.$starttime.'&stoptime='.$stoptime.'&startlimit='.$startlimit;
	foreach($_REQUEST['packflag'] as $k=>$v){
		$url.='&packflag['.$k.']='.$v;
	}
	echo sprintf($jieqiLang['article']['repack_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
}else{
	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$jieqiTpl->assign('article_static_url',$article_static_url);
    $jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
    
	//重新生成
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	$repack_form = new JieqiThemeForm($jieqiLang['article']['repack_use_id'], 'batchrepack', $article_static_url.'/admin/batchrepack.php');
	$repack_form->addElement(new JieqiFormText($jieqiLang['article']['repack_start_id'], 'fromid', 20, 15),true);
	$repack_form->addElement(new JieqiFormText($jieqiLang['article']['repack_end_id'], 'toid', 20, 15));
	$checkbox=new JieqiFormCheckBox($jieqiLang['article']['repack_select'], 'packflag');
	$checkbox->addOption('makeopf', $jieqiLang['article']['repack_opf']);
	$checkbox->addOption('makehtml', $jieqiLang['article']['repack_html']);
	$checkbox->addOption('makezip', $jieqiLang['article']['repack_zip']);
	$checkbox->addOption('makefull', $jieqiLang['article']['repack_fullpage']);
	$checkbox->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
	$checkbox->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
	$checkbox->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
	$repack_form->addElement($checkbox, false);
	$repack_form->addElement(new JieqiFormHidden('action', 'packwithid'));
	$repack_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['repack_start_button'], 'submit'));
	
	$timepack_form = new JieqiThemeForm($jieqiLang['article']['repack_use_time'], 'timerepack', $article_static_url.'/admin/batchrepack.php');
	$starttime=new JieqiFormText($jieqiLang['article']['repack_start_time'], 'starttime', 20, 20,  date('Y-m-d 00:00:00'));
	$starttime->setDescription($jieqiLang['article']['repack_time_format']);
	$timepack_form->addElement($starttime,true);
	$stoptime=new JieqiFormText($jieqiLang['article']['repack_end_time'], 'stoptime', 20, 20, date('Y-m-d H:i:s'));
	$stoptime->setDescription($jieqiLang['article']['repack_time_format']);
	$timepack_form->addElement($stoptime);
	$checkbox1=new JieqiFormCheckBox($jieqiLang['article']['repack_select'], 'packflag');
	$checkbox1->addOption('makeopf', $jieqiLang['article']['repack_opf']);
	$checkbox1->addOption('makehtml', $jieqiLang['article']['repack_html']);
	$checkbox1->addOption('makezip', $jieqiLang['article']['repack_zip']);
	$checkbox1->addOption('makefull', $jieqiLang['article']['repack_fullpage']);
	$checkbox1->addOption('maketxtfull', $jieqiLang['article']['repack_txtfullpage']);
	$checkbox1->addOption('makeumd', $jieqiLang['article']['repack_umdpage']);
	$checkbox1->addOption('makejar', $jieqiLang['article']['repack_jarpage']);
	$timepack_form->addElement($checkbox1, false);
	$timepack_form->addElement(new JieqiFormHidden('action', 'packwithtime'));
	$timepack_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['repack_start_button'], 'submit'));
	$jieqiTpl->assign('repack_form', $repack_form->render(JIEQI_FORM_MIDDLE).'<br /><br />'.$timepack_form->render(JIEQI_FORM_MIDDLE));

	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/batchrepack.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
}

?>