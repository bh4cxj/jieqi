<?php 
/**
 * 编辑电子书信息
 *
 * 编辑电子书信息
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookedit.php 286 2008-12-23 03:04:17Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['id']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//管理别人电子书权限
$canedit=jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $obook->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_edit_obook']);


if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
switch ( $_REQUEST['action'] ) {
	case 'update':
	$_REQUEST['obookname'] = trim($_REQUEST['obookname']);
	$_POST['author'] = trim($_POST['author']);
	$_REQUEST['agent'] = trim($_REQUEST['agent']);
	$errtext='';
	include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
	//检查标题
	if (strlen($_REQUEST['obookname'])==0) $errtext .= $jieqiLang['obook']['need_obook_title'].'<br />';
	elseif (!jieqi_safestring($_REQUEST['obookname'])) $errtext .= $jieqiLang['obook']['limit_obook_title'].'<br />';
	if (!empty($_FILES['obookspic']['name'])){
		if(eregi("\.(gif|jpg|jpeg|png|bmp|swf|svg)$",$_FILES['obookspic']['name'])){
			if(strstr(strtolower($_FILES['obookspic']['name']),strtolower($jieqiConfigs['obook']['imagetype'])) != strtolower($jieqiConfigs['obook']['imagetype'])) $errtext .= sprintf($jieqiLang['obook']['simage_type_error'], $jieqiConfigs['obook']['imagetype']).'<br />';
		}else{
			$errtext .= sprintf($jieqiLang['obook']['simage_not_image'], $_FILES['obookspic']['name']).'<br />';
		}
		if(!empty($errtext)) jieqi_delfile($_FILES['obookspic']['tmp_name']);
	}
	if (!empty($_FILES['obooklpic']['name'])){
		if(eregi("\.(gif|jpg|jpeg|png|bmp|swf|svg)$",$_FILES['obooklpic']['name'])){
			if(strstr(strtolower($_FILES['obooklpic']['name']),strtolower($jieqiConfigs['obook']['imagetype'])) != strtolower($jieqiConfigs['obook']['imagetype'])) $errtext .= sprintf($jieqiLang['obook']['limage_type_error'], $jieqiConfigs['obook']['imagetype']).'<br />';
		}else{
			$errtext .= sprintf($jieqiLang['obook']['limage_not_image'], $_FILES['obooklpic']['name']).'<br />';
		}
		if(!empty($errtext)) jieqi_delfile($_FILES['obooklpic']['tmp_name']);
	}
	if(empty($errtext)) {
		//检查是否要关联文章
		if($_REQUEST['freechapter'] != 1) $_REQUEST['freechapter']=0;
		if($obook->getVar('articleid')>0) $hasfree=1;
		else $hasfree=0;
		if($_REQUEST['freechapter'] != $hasfree){
			$needupdatearticle=false;
			if($_REQUEST['freechapter']==0){
				$articleid=$obook->getVar('articleid', 'n');
				$obook->setVar('articleid', 0);
				if(is_file($jieqiModules['article']['path'].'/class/article.php')){
					include_once($jieqiModules['article']['path'].'/class/article.php');
					$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
					$article=$article_handler->get($articleid);
					if(is_object($article)){
						if(($article->getVar('articletype', 'n') & 2) == 2){
							$article->setVar('articletype', ($article->getVar('articletype', 'n') & 253));
							$article_handler->insert($article);
						}
					}
				}
			}else{
				$articleid=0;
				if(is_file($jieqiModules['article']['path'].'/class/article.php')){
					include_once($jieqiModules['article']['path'].'/class/article.php');
					$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
					$criteria=new CriteriaCompo(new Criteria('display', '0', '='));
					$criteria->add(new Criteria('articlename', $_REQUEST['obookname'], '='));
					$article_handler->queryObjects($criteria);
					$article=$article_handler->getObject();
					if(is_object($article)){
						$articleid=$article->getVar('articleid', 'n');
						if(($article->getVar('articletype', 'n') & 2) != 2){
							$article->setVar('articletype', ($article->getVar('articletype', 'n') | 2));
							$article_handler->insert($article);
						}
					}
				}
				$obook->setVar('articleid', $articleid);
			}
		}
		$obook->setVar('obookname', $_REQUEST['obookname']);
		$obook->setVar('keywords', trim($_POST['keywords']));
		$obook->setVar('initial', jieqi_getinitial($_REQUEST['obookname']));
		if(!isset($_POST['publishid'])) $_POST['publishid']=0;
		$obook->setVar('publishid', $_POST['publishid']);
		include_once(JIEQI_ROOT_PATH.'/class/users.php');
		$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
		$agentobj=false;
		if(!empty($_REQUEST['agent'])) $agentobj=$users_handler->getByname($_REQUEST['agent']);
		if(is_object($agentobj)){
			$obook->setVar('agentid', $agentobj->getVar('uid'));
			$obook->setVar('agent', $agentobj->getVar('uname', 'n'));
		}else{
			$obook->setVar('agentid', 0);
			$obook->setVar('agent', '');
		}

		if(jieqi_checkpower($jieqiPower['obook']['transobook'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
			//允许转载的情况
			if(empty($_POST['author'])){
				if(!empty($_SESSION['jieqiUserId'])){
					$obook->setVar('authorid', $_SESSION['jieqiUserId']);
					$obook->setVar('author', $_SESSION['jieqiUserName']);
				}else{
					$obook->setVar('authorid', 0);
					$obook->setVar('author', '');
				}
			}else{
				//转载作品
				$obook->setVar('author', $_POST['author']);
				if($_POST['authorflag']){
					include_once(JIEQI_ROOT_PATH.'/class/users.php');
					$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
					$authorobj=$users_handler->getByname($_POST['author']);
					if(is_object($authorobj)) $obook->setVar('authorid', $authorobj->getVar('uid'));
				}else{
					$obook->setVar('authorid', 0);
				}
			}
		}else{
			//不允许转载的情况
			if(!empty($_SESSION['jieqiUserId'])){
				$obook->setVar('authorid', $_SESSION['jieqiUserId']);
				$obook->setVar('author', $_SESSION['jieqiUserName']);
			}else{
				$obook->setVar('authorid', 0);
				$obook->setVar('author', '');
			}
		}

		if(!empty($_SESSION['jieqiUserId'])){
			$obook->setVar('posterid', $_SESSION['jieqiUserId']);
			$obook->setVar('poster', $_SESSION['jieqiUserName']);
		}else{
			$obook->setVar('posterid', 0);
			$obook->setVar('poster', '');
		}

		$obook->setVar('fullflag', $fullflag);
		$obook->setVar('sortid', $_POST['sortid']);
		$obook->setVar('intro', $_POST['intro']);
		$obook->setVar('notice', $_POST['notice']);
		$imgflag=$obook->getVar('imgflag');
		if (!empty($_FILES['obookspic']['name'])) $imgflag =$imgflag | 1;
		if (!empty($_FILES['obooklpic']['name'])) $imgflag =$imgflag | 2;
		$obook->setVar('imgflag', $imgflag);

		if (!$obook_handler->insert($obook)) jieqi_printfail($jieqiLang['obook']['edit_obook_failure']);
		else {
			$_REQUEST['id']=$obook->getVar('obookid');
			$imagedir=jieqi_uploadpath($jieqiConfigs['obook']['imagedir'], 'obook');
			if (!file_exists($imagedir)) jieqi_createdir($imagedir);
			$imagedir .= jieqi_getsubdir($_REQUEST['id']);
			if (!file_exists($imagedir)) jieqi_createdir($imagedir);
			$imagedir .= '/'.$_REQUEST['id'];
			if (!file_exists($imagedir)) jieqi_createdir($imagedir);

			//保存小图
			if (!empty($_FILES['obookspic']['name'])){
				if(strstr(strtolower($_FILES['obookspic']['name']),strtolower($jieqiConfigs['obook']['imagetype']))==strtolower($jieqiConfigs['obook']['imagetype'])){
					jieqi_copyfile($_FILES['obookspic']['tmp_name'], $imagedir.'/'.$_REQUEST['id'].'s'.$jieqiConfigs['obook']['imagetype'], 0777, true);
				}
			}
			//保存大图
			if (!empty($_FILES['obooklpic']['name'])){
				if(strstr(strtolower($_FILES['obooklpic']['name']),strtolower($jieqiConfigs['obook']['imagetype']))==strtolower($jieqiConfigs['obook']['imagetype'])){
					jieqi_copyfile($_FILES['obooklpic']['tmp_name'], $imagedir.'/'.$_REQUEST['id'].'l'.$jieqiConfigs['obook']['imagetype'], 0777, true);
				}
			}
			jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$_REQUEST['id'], LANG_DO_SUCCESS, $jieqiLang['obook']['edit_obook_success']);
		}
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'edit':
	default:
	//包含区块参数(定制区块)
	jieqi_getconfigs('obook', 'authorblocks', 'jieqiBlocks');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('obook_static_url',$obook_static_url);
	$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	$obook_form = new JieqiThemeForm($jieqiLang['obook']['edit_obook'], 'obookedit', $obook_static_url.'/obookedit.php');
	$obook_form->setExtra('enctype="multipart/form-data"');

	$obook_form->addElement(new JieqiFormText($jieqiLang['obook']['table_obook_obookname'], 'obookname', 30, 50, $obook->getVar('obookname', 'e')), true);
	$sort_select = new JieqiFormSelect($jieqiLang['obook']['table_obook_sortid'], 'sortid', $obook->getVar('sortid','e'));
	foreach($jieqiSort['obook'] as $key => $val){
		$tmpstr = '';
		if ($val['layer']>0){
			for($i=0; $i<$val['layer']; $i++) $tmpstr .= '&nbsp;&nbsp;';
			$tmpstr .= '├';
		}
		$tmpstr .= $val['caption'];
		$sort_select->addOption($key, $tmpstr);
	}
	$obook_form->addElement($sort_select, true);
	jieqi_getconfigs(JIEQI_MODULE_NAME, 'publisher');
	if(isset($jieqiPublisher) && count($jieqiPublisher)>1){
		$publisher_select = new JieqiFormSelect($jieqiLang['obook']['table_obook_publishid'],'publishid',$obook->getVar('publishid', 'e'));
		foreach($jieqiPublisher as $key => $val){
			$publisher_select->addOption($key, $val['name']);
		}
		$obook_form->addElement($publisher_select, false);
	}

	$_POST['obookname']=new JieqiFormText($jieqiLang['obook']['table_obook_keywords'], 'keywords', 30, 50, $obook->getVar('keywords', 'e'));
	$_POST['obookname']->setDescription($jieqiLang['obook']['keywords_note']);
	$obook_form->addElement($_POST['obookname']);
	if(jieqi_checkpower($jieqiPower['obook']['transobook'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
		$authorname=new JieqiFormText($jieqiLang['obook']['table_obook_author'], 'author', 30, 30, $obook->getVar('author', 'e'));
		//$authorname->setDescription('发表自己作品请留空');
		$obook_form->addElement($authorname);
		if($obook->getVar('authorid')>0) $tmpvar='1';
		else $tmpvar='0';
		$authorflag=new JieqiFormRadio($jieqiLang['obook']['obook_author_flag'], 'authorflag', $tmpvar);
		$authorflag->addOption('1', $jieqiLang['obook']['auth_to_author']);
		$authorflag->addOption('0', $jieqiLang['obook']['not_auth_author']);
		$obook_form->addElement($authorflag);
	}
	$agent=new JieqiFormText($jieqiLang['obook']['table_obook_agent'], 'agent', 30, 30, $obook->getVar('agent', 'e'));
	$agent->setDescription($jieqiLang['obook']['author_note']);
	$obook_form->addElement($agent);

	$fullflag=new JieqiFormRadio($jieqiLang['obook']['table_obook_fullflag'], 'fullflag', $obook->getVar('fullflag', 'e'));
	$fullflag->addOption('0', $jieqiLang['obook']['obook_not_full']);
	$fullflag->addOption('1', $jieqiLang['obook']['obook_is_full']);
	$obook_form->addElement($fullflag);

	if($obook->getVar('articleid', 'n')>0) $freechapter=1;
	else $freechapter=0;
	$freecheck=new JieqiFormCheckBox($jieqiLang['obook']['article_link'], 'freechapter', $freechapter);
	$freecheck->addOption(1, $jieqiLang['obook']['link_to_article']);
	$obook_form->addElement($freecheck);
	$obook_form->addElement(new JieqiFormTextArea($jieqiLang['obook']['table_obook_intro'], 'intro', $obook->getVar('intro', 'e'), 6, 60));
	$obook_form->addElement(new JieqiFormTextArea($jieqiLang['obook']['table_obook_notice'], 'notice', $obook->getVar('notice', 'e'), 6, 60));
	$spic=new JieqiFormFile($jieqiLang['obook']['obook_small_image'], 'obookspic', 30);
	$spic->setDescription(sprintf($jieqiLang['obook']['obook_image_type'], $jieqiConfigs['obook']['imagetype']));
	$obook_form->addElement($spic);
	$lpic=new JieqiFormFile($jieqiLang['obook']['obook_large_image'], 'obooklpic', 30);
	$lpic->setDescription(sprintf($jieqiLang['obook']['obook_image_type'], $jieqiConfigs['obook']['imagetype']));
	$obook_form->addElement($lpic);
	$obook_form->addElement(new JieqiFormHidden('action', 'update'));
	$obook_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));
	$obook_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));

	$jieqiTpl->assign('jieqi_contents', '<br />'.$obook_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}


?>