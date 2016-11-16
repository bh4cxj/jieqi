<?php 
/**
 * 增加章节
 *
 * 增加章节
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newchapter.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');
if(empty($_REQUEST['aid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('obook', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['aid']);
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
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'chapter';

switch ( $_REQUEST['action'] ) {
	case 'newchapter':
		$_POST['chaptername'] = trim($_POST['chaptername']);
		$errtext='';
		if(empty($_POST['chaptertype'])) $_POST['chaptertype']=0;
		//检查标题
		if (strlen($_POST['chaptername'])==0) $errtext .= $jieqiLang['obook']['need_chapter_title'].'<br />';
		if (strlen($_POST['chaptercontent'])==0) $errtext .= $jieqiLang['obook']['need_chapter_content'].'<br />';
		//if (!is_numeric($_POST['saleprice'])) $errtext .= '本章定价必须是一个整数！<br />';

		if(empty($errtext)) {
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
			//增加章节
			$from_draft=false;
			include_once($jieqiModules['obook']['path'].'/include/addchapter.php');
		}else{
			jieqi_printfail($errtext);
		}
		break;
	case 'chapter':
	default:
		//包含区块参数(定制区块)
		jieqi_getconfigs('obook', 'authorblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');

		$chapter_form = new JieqiThemeForm($jieqiLang['obook']['add_chapter'], 'newchapter', $obook_static_url.'/newchapter.php');
		$chapter_form->addElement(new JieqiFormLabel($jieqiLang['obook']['table_ochapter_obookname'], '<a href="'.$obook_static_url.'/obookmanage.php?id='.$_REQUEST['aid'].'">'.$obook->getVar('obookname').'</a>'));
		$volumeid=$obook->getVar('chapters')+1;
		//$volumeid=$obook->getVar('lastvolumeid');
		//echo $volumeid;
		include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
		$chapter_handler=& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
		/*
		$chapter_list = new JieqiFormSelect('分卷名称', 'volumeid', $volumeid);
		$criteria = new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
		$criteria->setSort('chapterorder');
		$criteria->setOrder('DESC');
		$chapter_handler->queryObjects($criteria);
		//显示的分卷序号是本序号最后章节＋1
		$tmpvar=$volumeid;
		$k=0;
		while($v = $chapter_handler->getObject()){
		if($v->getVar('chaptertype')==1){
		$chapter_list->addOption($tmpvar, $v->getVar('chaptername'));
		$tmpvar=$volumeid-$k-1;
		}
		$k++;
		}

		$chaptername='';
		$chaptercontent='';
		if(!empty($draftid)){
		include_once($jieqiModules['obook']['path'].'/class/draft.php');
		$draft_handler =& JieqiDraftHandler::getInstance('JieqiDraftHandler');
		$draft=$draft_handler->get($draftid);
		if(is_object($draft)){
		$chaptername=$draft->getVar('draftname', 'e');
		$chaptercontent=$draft->getVar('content', 'e');
		}
		}
		$chapter_form->addElement($chapter_list);
		*/
		$chapter_form->addElement(new JieqiFormText($jieqiLang['obook']['table_ochapter_chaptername'], 'chaptername', 50, 50, $chaptername), true);
		if(jieqi_checkpower($jieqiPower['obook']['customprice'], $jieqiUsersStatus, $jieqiUsersGroup, true)){
			$priceobj=new JieqiFormText($jieqiLang['obook']['table_ochapter_saleprice'], 'saleprice', 20, 10, '');
			$priceobj->setDescription(JIEQI_EGOLD_NAME.$jieqiLang['obook']['chapter_saleprice_note']);
			$chapter_form->addElement($priceobj, false);
		}
		$chapter_type=new JieqiFormRadio($jieqiLang['obook']['table_ochapter_chaptertype'], 'chaptertype', '0');
		$chapter_type->addOption('0', $jieqiLang['obook']['chapter_not_last']);
		$chapter_type->addOption('1', $jieqiLang['obook']['chapter_is_last']);
		$chapter_form->addElement($chapter_type);
		if($jieqiConfigs['obook']['authtypeset']==1){
			$typeset=new JieqiFormRadio($jieqiLang['obook']['chapter_typeset'], 'typeset', $jieqiConfigs['obook']['autotypeset']);
			$typeset->addOption('1', $jieqiLang['obook']['auto_typeset']);
			$typeset->addOption('0', $jieqiLang['obook']['no_typeset']);
			$chapter_form->addElement($typeset);
		}

		$chapter_form->addElement(new JieqiFormTextArea($jieqiLang['obook']['table_ochapter_chaptercontent'], 'chaptercontent', $chaptercontent, 15, 60));
		$chapter_form->addElement(new JieqiFormHidden('action', 'newchapter'));
		$chapter_form->addElement(new JieqiFormHidden('aid', $_REQUEST['aid']));
		$chapter_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));

		$jieqiTpl->assign('jieqi_contents', '<br />'.$chapter_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}


?>