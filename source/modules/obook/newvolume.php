<?php 
/**
 * 新建分卷
 *
 * 新建分卷
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: newvolume.php 231 2008-11-27 08:46:26Z juny $
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
if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'volume';

switch ( $_REQUEST['action'] ) {
	case 'newvolume':
	$_POST['chaptername'] = trim($_POST['chaptername']);
	$errtext='';
	
	//检查标题
	if (strlen($_POST['chaptername'])==0) $errtext .= $jieqiLang['obook']['need_volume_name'].'<br />';
	
	if(empty($errtext)) {
		$from_draft=false;
		$_POST['from_draft']=$from_draft;
		$chaptertype=2;
		$_POST['chaptertype']=$chaptertype;
	    $volumeid=$obook->getVar('chapters')+1;
	    $_POST['volumeid']=$volumeid;
	    $chaptercontent='';
	    $_POST['chaptercontent']=$chaptercontent;
	    
		include_once($jieqiModules['obook']['path'].'/include/addchapter.php');
	}else{
		jieqi_printfail($errtext);
	}
	break;
	case 'volume':
	default:
	//包含区块参数(定制区块)
	jieqi_getconfigs('obook', 'authorblocks', 'jieqiBlocks');
	include_once(JIEQI_ROOT_PATH.'/header.php');
	$jieqiTpl->assign('obook_static_url',$obook_static_url);
    $jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
	include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
	
	$chapter_form = new JieqiThemeForm($jieqiLang['obook']['add_volume'], 'newvolume', $obook_static_url.'/newvolume.php');
	$chapter_form->addElement(new JieqiFormLabel($jieqiLang['obook']['table_ochapter_obookname'], '<a href="'.$obook_static_url.'/obookmanage.php?id='.$_REQUEST['aid'].'">'.$obook->getVar('obookname').'</a>'));
	include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
	$chapter_handler=& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
	$criteria = new CriteriaCompo(new Criteria('obookid', $_REQUEST['aid']));
	$criteria->add(new Criteria('chaptertype', 1));
	$criteria->setSort('chapterorder');
	$criteria->setOrder('ASC');
	$chapter_handler->queryObjects($criteria);
	$tmpvar='';
	while($v = $chapter_handler->getObject()){
		$tmpvar.=$v->getVar('chaptername').'<br />';
	}
	$chapter_form->addElement(new JieqiFormLabel($jieqiLang['obook']['now_volume'], $tmpvar));
	$chapter_form->addElement(new JieqiFormText($jieqiLang['obook']['new_volume'], 'chaptername', 50, 50), true);
	
	$chapter_form->addElement(new JieqiFormHidden('action', 'newvolume'));
	$chapter_form->addElement(new JieqiFormHidden('aid', $_REQUEST['aid']));
	$chapter_form->addElement(new JieqiFormButton('&nbsp;', 'submit', LANG_SUBMIT, 'submit'));
	
	$jieqiTpl->assign('jieqi_contents', '<br />'.$chapter_form->render(JIEQI_FORM_MIDDLE).'<br />');
	include_once(JIEQI_ROOT_PATH.'/footer.php');
	break;
}


?>