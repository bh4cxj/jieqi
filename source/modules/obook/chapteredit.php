<?php 
/**
 * 编辑章节
 *
 * 编辑章节
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapteredit.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
if($_POST['chaptertype']==1) $typename=$jieqiLang['obook']['volume_name'];
else $typename=$jieqiLang['obook']['chapter_name'];
if(empty($_REQUEST['id'])) jieqi_printfail(sprintf($jieqiLang['obook']['chapter_volume_notexists'], $typename));
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$chapter=$chapter_handler->get($_REQUEST['id']);
if(!$chapter) jieqi_printfail(sprintf($jieqiLang['obook']['chapter_volume_notexists'], $typename));
if($chapter->getVar('chaptertype')==1) {
	$_POST['chaptertype']=1;
	$typename=$jieqiLang['obook']['volume_name'];
}else{
	$typename=$jieqiLang['obook']['chapter_name'];
	$_POST['chaptertype']=0;
}

include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($chapter->getVar('obookid'));
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以修改电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $chapter->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
		$canedit=true;
	}
}
if(!$canedit) jieqi_printfail(sprintf($jieqiLang['obook']['noper_edit_chapter'], $typename));
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'edit';

switch ( $_REQUEST['action'] ) {
	case 'update':
		$_POST['chaptername'] = trim($_POST['chaptername']);
		$errtext='';
		//检查标题
		if (strlen($_POST['chaptername'])==0) $errtext .= sprintf($jieqiLang['obook']['need_chapvol_title'], $typename).'<br />';
		if ($_POST['chaptertype'] == 0){
			if (strlen($_POST['chaptercontent'])==0) $errtext .= $jieqiLang['obook']['need_chapter_content'].'<br />';
			//if (!is_numeric($_POST['saleprice'])) $errtext .= '本章定价必须是一个整数！<br />';
		}

		if(empty($errtext)) {
			$chapter->setVar('obookid', $obook->getVar('obookid'));
			if(!empty($_SESSION['jieqiUserId'])){
				$chapter->setVar('posterid', $_SESSION['jieqiUserId']);
				$chapter->setVar('poster', $_SESSION['jieqiUserName']);
			}else{
				$chapter->setVar('posterid', 0);
				$chapter->setVar('poster', '');
			}
			$chapter->setVar('lastupdate', JIEQI_NOW_TIME);
			$chapter->setVar('chaptername', $_POST['chaptername']);
			if($_POST['chaptertype']==0){
				//文字过滤
				if(!empty($jieqiConfigs['obook']['hideobookwords'])){
					$filterary=explode(' ', $jieqiConfigs['obook']['hideobookwords']);
					$_POST['chaptercontent']=str_replace($filterary, '', $_POST['chaptercontent']);
				}

				//内容排版
				if($jieqiConfigs['obook']['authtypeset']==2 || ($jieqiConfigs['obook']['authtypeset']==1 && $jieqiConfigs['obook']['autotypeset']==1 && $_POST['typeset']==1)){
					include_once(JIEQI_ROOT_PATH.'/lib/text/texttypeset.php');
					$texttypeset=new TextTypeset();
					$_POST['chaptercontent']=$texttypeset->doTypeset($_POST['chaptercontent']);
				}
				$chaptersize=strlen(str_replace(array(" ","　","\r","\n"),'',$_POST['chaptercontent']));
				if(trim($_POST['saleprice']) != '' && jieqi_checkpower($jieqiPower['obook']['customprice'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
					$_POST['saleprice']=intval($_POST['saleprice']);
				}elseif(is_numeric($jieqiConfigs['obook']['wordsperegold']) && $jieqiConfigs['obook']['wordsperegold']>0){
					$jieqiConfigs['obook']['wordsperegold']=ceil($jieqiConfigs['obook']['wordsperegold']) * 2;
					if($jieqiConfigs['obook']['priceround']==1) $_POST['saleprice']=floor($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
					elseif($jieqiConfigs['obook']['priceround']==2) $_POST['saleprice']=ceil($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
					else $_POST['saleprice']=round($chaptersize / $jieqiConfigs['obook']['wordsperegold']);
				}else{
					$_POST['saleprice']=0;
				}
				
				$beforesize=$chapter->getVar('size');
				$chapter->setVar('size', $chaptersize);
				$chapter->setVar('saleprice', $_POST['saleprice']);
			}else{
				$_POST['chaptercontent']='';
			}
			if (!$chapter_handler->insert($chapter)) jieqi_printfail($jieqiLang['obook']['chapter_edit_failure']);
			else {
				if($_POST['chaptertype']==0) {
					$obook->setVar('size', $obook->getVar('size')+$chaptersize-$beforesize);
					if($chapter->getVar('chapterid')==$obook->getVar('lastchapterid')) $obook->setVar('lastchapter', $_POST['chaptername']);
				}else{
					if($chapter->getVar('chapterid')==$obook->getVar('lastvolumeid')) $obook->setVar('lastvolume', $_POST['chaptername']);
				}
				$obook_handler->insert($obook);
				include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
				$content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
				$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['id']));
				$content_handler->updatefields(array('ocontent'=>$_POST['chaptercontent']), $criteria);
				jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$obook->getVar('obookid'), LANG_DO_SUCCESS, $jieqiLang['obook']['chapter_edit_success']);
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
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

		$chapter_form = new JieqiThemeForm(sprintf($jieqiLang['obook']['chapter_edit'], $typename), 'chapteredit', $obook_static_url.'/chapteredit.php');
		$chapter_form->addElement(new JieqiFormLabel($jieqiLang['obook']['table_ochapter_obookname'], $obook->getVar('obookname')));
		$chapter_form->addElement(new JieqiFormText(sprintf($jieqiLang['obook']['chapter_volume_title'], $typename), 'chaptername', 50, 50, $chapter->getVar('chaptername', 'e')), true);
		if($chapter->getVar('chaptertype')==1) $tmpvar='1';
		else{
			if(jieqi_checkpower($jieqiPower['obook']['customprice'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
				$priceobj=new JieqiFormText($jieqiLang['obook']['table_ochapter_saleprice'], 'saleprice', 20, 10, $chapter->getVar('saleprice','e'));
				$priceobj->setDescription(JIEQI_EGOLD_NAME.$jieqiLang['obook']['chapter_saleprice_note']);
				$chapter_form->addElement($priceobj, false);
			}

			if($jieqiConfigs['obook']['authtypeset']==1){
				$typeset=new JieqiFormRadio($jieqiLang['obook']['table_ochapter_typeset'], 'typeset', $jieqiConfigs['obook']['autotypeset']);
				$typeset->addOption('1', $jieqiLang['obook']['auto_typeset']);
				$typeset->addOption('0', $jieqiLang['obook']['no_typeset']);
				$chapter_form->addElement($typeset);
			}
			$tmpvar='0';
			include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
			$content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
			$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['id']));
			$criteria->setLimit(1);
			$content_handler->queryObjects($criteria);
			$content=$content_handler->getObject();
			if(is_object($content))	$chapter_form->addElement(new JieqiFormTextArea($jieqiLang['obook']['table_ochapter_chaptercontent'], 'chaptercontent', $content->getVar('ocontent', 'e'), 15, 60));
			else $chapter_form->addElement(new JieqiFormTextArea($jieqiLang['obook']['table_ochapter_chaptercontent'], 'chaptercontent', '', 15, 60));
		}
		$chapter_form->addElement(new JieqiFormHidden('action', 'update'));
		$chapter_form->addElement(new JieqiFormHidden('id', $_REQUEST['id']));

		$chapter_form->addElement(new JieqiFormHidden('chaptertype', $tmpvar));
		$chapter_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));

		$jieqiTpl->assign('jieqi_contents', '<br />'.$chapter_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>