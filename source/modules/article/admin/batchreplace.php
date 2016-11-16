<?php 
/**
 * 批量文章内容替换
 *
 * 批量文章内容替换
 * 
 * 调用模板：/modules/article/templates/admin/batchreplace.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: batchreplace.php 330 2009-02-09 16:07:35Z juny $
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
$fromary=array();
$toary=array();
if(isset($_REQUEST['action']) && $_REQUEST['action']=='replacewithid'){
	$_REQUEST['txtsearch']=trim($_REQUEST['txtsearch']);
	$_REQUEST['txtreplace']=trim($_REQUEST['txtreplace']);
	if(empty($_REQUEST['txtsearch'])) jieqi_printfail($jieqiLang['article']['need_replace_from']);
	if($_REQUEST['replacetype']==1){
		$fromary=explode("\n", $_REQUEST['txtsearch']);
		$toary=explode("\n", $_REQUEST['txtreplace']);
		if(count($fromary) != count($toary)) jieqi_printfail($jieqiLang['article']['replace_lines_difference']);
		else{
			foreach($fromary as $k=>$v){
				$fromary[$k]=trim($fromary[$k]);
				if($fromary[$k]=='') jieqi_printfail($jieqiLang['article']['replace_lines_empay']);
			}
			foreach($toary as $k=>$v) $toary[$k]=trim($toary[$k]);
		}
	}

	if(!empty($_REQUEST['flagary'])){
		$_REQUEST['flagary']=unserialize(urldecode($_REQUEST['flagary']));
	}else $_REQUEST['flagary']=$_REQUEST['replaceflag'];
	if(!is_array($_REQUEST['flagary']) || count($_REQUEST['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_replace_filetype']);
	if(empty($_REQUEST['fromid']) || !is_numeric($_REQUEST['fromid'])) jieqi_printfail($jieqiLang['article']['need_replace_startid']);
	if(empty($_REQUEST['toid'])) $_REQUEST['toid']=0;
	if($_REQUEST['fromid']>$_REQUEST['toid']){
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_replace_success'], $article_static_url.'/admin/batchreplace.php'));
		exit;
	}
	if(in_array('filetxt',$_REQUEST['flagary'])) replace_path($_REQUEST['fromid'], $jieqiConfigs['article']['txtdir'], $jieqi_file_postfix['txt']);
	if(in_array('filehtml',$_REQUEST['flagary'])) replace_path($_REQUEST['fromid'], $jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlfile']);
	if(in_array('filefull',$_REQUEST['flagary'])) replace_path($_REQUEST['fromid'], $jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['htmlfile'],false);
	$_REQUEST['fromid']++;
	$url=$article_static_url.'/admin/batchreplace.php?fromid='.$_REQUEST['fromid'].'&toid='.$_REQUEST['toid'];
	foreach($_REQUEST['replaceflag'] as $k=>$v){
		$url.='&replaceflag['.$k.']='.$v;
	}
	$url.='&txtsearch='.urlencode($_REQUEST['txtsearch']).'&txtreplace='.urlencode($_REQUEST['txtreplace']).'&action=replacewithid&filesize='.urlencode($_REQUEST['filesize']).'&replacetype='.urlencode($_REQUEST['replacetype']);
	echo sprintf($jieqiLang['article']['replace_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
}elseif(isset($_REQUEST['action']) && $_REQUEST['action']=='replacewithtime'){
	$_REQUEST['txtsearch']=trim($_REQUEST['txtsearch']);
	$_REQUEST['txtreplace']=trim($_REQUEST['txtreplace']);
	if(empty($_REQUEST['txtsearch'])) jieqi_printfail($jieqiLang['article']['need_replace_from']);
	if($_REQUEST['replacetype']==1){
		$fromary=explode("\n", $_REQUEST['txtsearch']);
		$toary=explode("\n", $_REQUEST['txtreplace']);
		if(count($fromary) != count($toary)) jieqi_printfail($jieqiLang['article']['replace_lines_difference']);
		else{
			foreach($fromary as $k=>$v){
				$fromary[$k]=trim($fromary[$k]);
				if($fromary[$k]=='') jieqi_printfail($jieqiLang['article']['replace_lines_empay']);
			}
			foreach($toary as $k=>$v) $toary[$k]=trim($toary[$k]);
		}
	}
	if(!empty($_REQUEST['flagary'])){
		$_REQUEST['flagary']=unserialize(urldecode($_REQUEST['flagary']));
	}else $_REQUEST['flagary']=$_REQUEST['replaceflag'];
	if(!is_array($_REQUEST['flagary']) || count($_REQUEST['flagary'])<1) jieqi_printfail($jieqiLang['article']['need_replace_filetype']);
	$_REQUEST['starttime']=trim($_REQUEST['starttime']);
	$_REQUEST['stoptime']=trim($_REQUEST['stoptime']);
	if(empty($_REQUEST['starttime'])) jieqi_printfail($jieqiLang['article']['need_replace_starttime']);
	if(!is_numeric($_REQUEST['starttime'])){
		$_REQUEST['starttime']=mktime((int)substr($_REQUEST['starttime'],11,2), (int)substr($_REQUEST['starttime'],14,2), (int)substr($_REQUEST['starttime'],17,2), (int)substr($_REQUEST['starttime'],5,2), (int)substr($_REQUEST['starttime'],8,2), (int)substr($_REQUEST['starttime'],0,5));
	}
	if(empty($_REQUEST['stoptime'])) $_REQUEST['stoptime']=JIEQI_NOW_TIME;
	if(!is_numeric($_REQUEST['stoptime'])){
		$_REQUEST['stoptime']=mktime((int)substr($_REQUEST['stoptime'],11,2), (int)substr($_REQUEST['stoptime'],14,2), (int)substr($_REQUEST['stoptime'],17,2), (int)substr($_REQUEST['stoptime'],5,2), (int)substr($_REQUEST['stoptime'],8,2), (int)substr($_REQUEST['stoptime'],0,5));
	}

	include_once($jieqiModules['article']['path'].'/class/article.php');
	$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
	if(empty($_REQUEST['startlimit'])) $_REQUEST['startlimit']=0;
	$criteria=new CriteriaCompo(new Criteria('lastupdate', $_REQUEST['starttime'], '>='));
	$criteria->add(new Criteria('lastupdate', $_REQUEST['stoptime'], '<='));
	$criteria->setSort('lastupdate');
	$criteria->setOrder('ASC');
	$criteria->setStart($_REQUEST['startlimit']);
	$criteria->setLimit(1);
	$article_handler->queryObjects($criteria);
	$article=$article_handler->getObject();
	if(is_object($article)){
		if(in_array('filetxt',$_REQUEST['flagary'])) replace_path($article->getVar('articleid'), $jieqiConfigs['article']['txtdir'], $jieqi_file_postfix['txt']);
		if(in_array('filehtml',$_REQUEST['flagary'])) replace_path($article->getVar('articleid'), $jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlfile']);
		if(in_array('filefull',$_REQUEST['flagary'])) replace_path($article->getVar('articleid'), $jieqiConfigs['article']['fulldir'], $jieqiConfigs['article']['htmlfile'],false);
	}else{
		jieqi_msgwin(LANG_DO_SUCCESS, sprintf($jieqiLang['article']['batch_replace_success'], $article_static_url.'/admin/batchreplace.php'));
		exit;
	}
	$_REQUEST['startlimit']++;
	$url=$article_static_url.'/admin/batchreplace.php?starttime='.$_REQUEST['starttime'].'&stoptime='.$_REQUEST['stoptime'].'&startlimit='.$_REQUEST['startlimit'];
	foreach($_REQUEST['replaceflag'] as $k=>$v){
		$url.='&replaceflag['.$k.']='.$v;
	}
	$url.='&txtsearch='.urlencode($_REQUEST['txtsearch']).'&txtreplace='.urlencode($_REQUEST['txtreplace']).'&action=replacewithtime&filesize='.urlencode($_REQUEST['filesize']).'&replacetype='.urlencode($_REQUEST['replacetype']);
	echo sprintf($jieqiLang['article']['replace_next_html'], JIEQI_CHAR_SET, $showinfo, $url, $url);
}else{

	include_once(JIEQI_ROOT_PATH.'/admin/header.php');
	$jieqiTpl->assign('article_static_url',$article_static_url);
	$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

	//重新生成
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	$replace_form = new JieqiThemeForm($jieqiLang['article']['replace_use_id'], 'batchreplace', $article_static_url.'/admin/batchreplace.php');
	$replace_form->addElement(new JieqiFormText($jieqiLang['article']['replace_start_id'], 'fromid', 20, 15),true);
	$replace_form->addElement(new JieqiFormText($jieqiLang['article']['replace_end_id'], 'toid', 20, 15));

	$replace_form->addElement(new JieqiFormTextArea($jieqiLang['article']['replace_search_string'], 'txtsearch', '', 5, 60), false);
	$replace_form->addElement(new JieqiFormTextArea($jieqiLang['article']['replace_to'], 'txtreplace', '', 5, 60), false);

	$radio=new JieqiFormRadio($jieqiLang['article']['replace_type'], 'replacetype',0);
	$radio->addOption('0', $jieqiLang['article']['replace_as_block']);
	$radio->addOption('1', $jieqiLang['article']['replace_as_line']);
	$replace_form->addElement($radio, false);

	$checkbox=new JieqiFormCheckBox($jieqiLang['article']['replace_filetype'], 'replaceflag');
	$checkbox->addOption('filetxt', $jieqiLang['article']['replace_file_txt']);
	$checkbox->addOption('filehtml', $jieqiLang['article']['replace_file_html']);
	$checkbox->addOption('filefull', $jieqiLang['article']['replace_file_full']);
	$replace_form->addElement($checkbox, false);

	$sizebox=new JieqiFormSelect($jieqiLang['article']['replace_filesize'], 'filesize');
	$sizebox->addOption('sizeunlimit', $jieqiLang['article']['replace_size_nolimit']);
	$sizebox->addOption('sizeless', $jieqiLang['article']['replace_size_less']);
	$sizebox->addOption('sizemore', $jieqiLang['article']['replace_size_more']);
	$replace_form->addElement($sizebox, false);

	$replace_form->addElement(new JieqiFormHidden('action', 'replacewithid'));
	$replace_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['replace_start_button'], 'submit'));

	$timereplace_form = new JieqiThemeForm($jieqiLang['article']['replace_use_time'], 'timereplace', $article_static_url.'/admin/batchreplace.php');
	$starttime=new JieqiFormText($jieqiLang['article']['replace_start_time'], 'starttime', 20, 20,  date('Y-m-d 00:00:00'));
	$starttime->setDescription($jieqiLang['article']['replace_time_format']);
	$timereplace_form->addElement($starttime,true);
	$stoptime=new JieqiFormText($jieqiLang['article']['replace_end_time'], 'stoptime', 20, 20, date('Y-m-d H:i:s'));
	$stoptime->setDescription($jieqiLang['article']['replace_time_format']);
	$timereplace_form->addElement($stoptime);

	$timereplace_form->addElement(new JieqiFormTextArea($jieqiLang['article']['replace_search_string'], 'txtsearch', '', 5, 60), true);
	$timereplace_form->addElement(new JieqiFormTextArea($jieqiLang['article']['replace_to'], 'txtreplace', '', 5, 60), false);

	$radio=new JieqiFormRadio($jieqiLang['article']['replace_type'], 'replacetype',0);
	$radio->addOption('0', $jieqiLang['article']['replace_as_block']);
	$radio->addOption('1', $jieqiLang['article']['replace_as_line']);
	$timereplace_form->addElement($radio, false);

	$checkbox=new JieqiFormCheckBox($jieqiLang['article']['replace_filetype'], 'replaceflag');
	$checkbox->addOption('filetxt', $jieqiLang['article']['replace_file_txt']);
	$checkbox->addOption('filehtml', $jieqiLang['article']['replace_file_html']);
	$checkbox->addOption('filefull', $jieqiLang['article']['replace_file_full']);
	$timereplace_form->addElement($checkbox, false);

	$sizebox=new JieqiFormSelect($jieqiLang['article']['replace_filesize'], 'filesize');
	$sizebox->addOption('sizeunlimit', $jieqiLang['article']['replace_size_nolimit']);
	$sizebox->addOption('sizeless', $jieqiLang['article']['replace_size_less']);
	$sizebox->addOption('sizemore', $jieqiLang['article']['replace_size_more']);
	$timereplace_form->addElement($sizebox, false);

	$timereplace_form->addElement(new JieqiFormHidden('action', 'replacewithtime'));
	$timereplace_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['replace_start_button'], 'submit'));
	$jieqiTpl->assign('replace_form', $replace_form->render(JIEQI_FORM_MIDDLE).'<br /><br />'.$timereplace_form->render(JIEQI_FORM_MIDDLE));

	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/batchreplace.html';
	include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
}

function replace_path($articleid, $dirtype, $filetype, $idisdir=true){
	global $showinfo;
	global $fromary;
	global $toary;
	global $jieqiLang;
	$dirname=jieqi_uploadpath($dirtype, 'article').jieqi_getsubdir($articleid);
	if($idisdir) $dirname.='/'.$articleid;
	else $dirname.='/'.$articleid.$filetype;

	if(file_exists($dirname)){
		echo '                                                                                                                                                                                                                                                                ';
		echo sprintf($jieqiLang['article']['replace_id_doing'], $articleid);
		ob_flush();
		flush();
		if(is_dir($dirname)){
			$handle = @opendir($dirname);
			while ($file = @readdir($handle)) {
				if($file != '.' && $file != '..'){
					$filename=$dirname.'/'.$file;
					if (is_file($filename) && is_writable($filename) && substr($filename, 0 - strlen($filetype)) == $filetype){
						if(empty($_REQUEST['filesize']) || $_REQUEST['filesize']=='sizeunlimit' || ($_REQUEST['filesize']=='sizeless' && filesize($filename)<=1024) || ($_REQUEST['filesize']=='sizemore' && filesize($filename)>=1024)){
							$filedata=jieqi_readfile($filename);
							if($_REQUEST['replacetype']==1) $filedata=str_replace($fromary, $toary, $filedata);
							else $filedata=str_replace($_REQUEST['txtsearch'], $_REQUEST['txtreplace'], $filedata);
							jieqi_writefile($filename, $filedata);

						}
					}
				}
			}
		}elseif(is_file($dirname)){
			$filename=$dirname;
			if (is_file($filename) && is_writable($filename) && substr($filename, 0 - strlen($filetype)) == $filetype){
				if(empty($_REQUEST['filesize']) || $_REQUEST['filesize']=='sizeunlimit' || ($_REQUEST['filesize']=='sizeless' && filesize($filename)<=1024) || ($_REQUEST['filesize']=='sizemore' && filesize($filename)>=1024)){
					$filedata=jieqi_readfile($filename);
					if($_REQUEST['replacetype']==1) $filedata=str_replace($fromary, $toary, $filedata);
					else $filedata=str_replace($_REQUEST['txtsearch'], $_REQUEST['txtreplace'], $filedata);
					jieqi_writefile($filename, $filedata);

				}
			}
		}
		$showinfo=$jieqiLang['article']['replace_success_next'];
	}else{
		$showinfo=$jieqiLang['article']['replace_noid_next'];
	}
}
?>