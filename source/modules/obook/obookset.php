<?php 
/**
 * 电子书销售状态设置
 *
 * 电子书销售状态设置
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obookset.php 231 2008-11-27 08:46:26Z juny $
 */
define('JIEQI_MODULE_NAME', 'obook');
require_once('../../global.php');

if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
switch($_REQUEST['action']){
	case 'sale':
	break;
	case 'unsale':
	break;
	case 'delete':
	break;
	default:
	jieqi_printfail(LANG_ERROR_PARAMETER);
}
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['id']);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['obook']['delallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
$candel=$canedit;
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以删除电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $chapter->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
	    $canedit=jieqi_checkpower($jieqiPower['obook']['delmyobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
	    //还没销售过的话作者可以删除
	    if($canedit && $obook->getVar('totalsale', 'n')==0) $candel=$canedit;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);
if($_REQUEST['action']=='delete' && !$candel) jieqi_printfail($jieqiLang['obook']['noper_delete_obook']);

if($obook->getVar('articleid') > 0) $linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($obook->getVar('articleid')).'/'.$obook->getVar('articleid').'.php';
else $linkfile='';
if($_REQUEST['action']=='sale'){
	$obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_obook').' SET display=0 WHERE obookid='.intval($_REQUEST['id']));
	$obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_ochapter').' SET display=state WHERE obookid='.intval($_REQUEST['id']));
	if(!empty($linkfile) && file_exists($linkfile)){
		global $jieqiObookdata;
		include_once($linkfile);
		$jieqiObookdata['obook']['display']=0;
		$varstring="<?php\n".jieqi_extractvars('jieqiObookdata', $jieqiObookdata)."\n?>";
		jieqi_writefile($linkfile, $varstring);
	}
}elseif($_REQUEST['action']=='unsale'){
	$obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_obook').' SET display=2 WHERE obookid='.intval($_REQUEST['id']));
	$obook_handler->db->query('UPDATE '.jieqi_dbprefix('obook_ochapter').' SET state=display, display=2 WHERE obookid='.intval($_REQUEST['id']));
	if(!empty($linkfile) && file_exists($linkfile)){
		global $jieqiObookdata;
		include_once($linkfile);
		$jieqiObookdata['obook']['display']=2;
		$varstring="<?php\n".jieqi_extractvars('jieqiObookdata', $jieqiObookdata)."\n?>";
		jieqi_writefile($linkfile, $varstring);
	}
}elseif($_REQUEST['action']=='delete'){
	$obook_handler->db->query('DELETE FROM '.jieqi_dbprefix('obook_obook').' WHERE obookid='.intval($_REQUEST['id']));
	$obook_handler->db->query('DELETE FROM '.jieqi_dbprefix('obook_ochapter').' WHERE obookid='.intval($_REQUEST['id']));
	if(!empty($linkfile) && file_exists($linkfile)) jieqi_delfile($linkfile);
}

//更新最新电子书
jieqi_getcachevars('obook', 'obookuplog');
if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
$jieqiObookuplog['obookuptime']=JIEQI_NOW_TIME;
jieqi_setcachevars('obookuplog', 'jieqiObookuplog', $jieqiObookuplog, 'obook');

jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

//减少电子书和章节积分
/*
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');

if(!empty($jieqiConfigs['obook']['scoreobook']) || !empty($jieqiConfigs['obook']['scorechapter'])){
	if($obook->getVar('posterid')==$_SESSION['jieqiUserId']){
		$users_handler->changeScore($_SESSION['jieqiUserId'], $obook->getVar('chapters') * $jieqiConfigs['obook']['scorechapter'] + $jieqiConfigs['obook']['scoreobook'], false);
	}else{
		$users_handler->changeScore($obook->getVar('posterid'), $obook->getVar('chapters') * $jieqiConfigs['obook']['scorechapter'] + $jieqiConfigs['obook']['scoreobook'], false);	
	}
}
*/
jieqi_jumppage($obook_static_url.'/masterpage.php', LANG_DO_SUCCESS, $jieqiLang['obook']['obook_set_success']);

?>