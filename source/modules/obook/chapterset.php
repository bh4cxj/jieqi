<?php 
/**
 * 章节状态设置
 *
 * 章节状态设置
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapterset.php 231 2008-11-27 08:46:26Z juny $
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
	case 'publish':
	break;
	default:
	jieqi_printfail(LANG_ERROR_PARAMETER);
}
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$ochapter=$chapter_handler->get($_REQUEST['id']);
if(!$ochapter) jieqi_printfail($jieqiLang['obook']['chapter_not_exists']);
$obookid=$ochapter->getVar('obookid', 'n');

include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($obookid);
if(!$obook) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
$canedit=jieqi_checkpower($jieqiPower['obook']['delallobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
$candel=$canedit;
if(!$canedit && !empty($_SESSION['jieqiUserId'])){
	//除了斑竹，作者、发表者和代理人可以删除电子书
	$tmpvar=$_SESSION['jieqiUserId'];
	if($tmpvar>0 && ($obook->getVar('authorid')==$tmpvar || $chapter->getVar('posterid')==$tmpvar || $obook->getVar('agentid')==$tmpvar)){
	    //$canedit=jieqi_checkpower($jieqiPower['obook']['delmyobook'], $jieqiUsersStatus, $jieqiUsersGroup, true);
	    $canedit=true;
	    //还没销售过的话作者可以删除
	    if($canedit && $ochapter->getVar('totalsale', 'n')==0) $candel=$canedit;
	}
}
if(!$canedit) jieqi_printfail($jieqiLang['obook']['noper_manage_obook']);
if($_REQUEST['action']=='delete' && !$candel) jieqi_printfail($jieqiLang['obook']['noper_delete_chapter']);

if($_REQUEST['action']=='publish' && $obook->getVar('articleid','n') <= 0) jieqi_printfail($jieqiLang['obook']['not_link_article']);

if($_REQUEST['action']=='sale'){
	$chapter_handler->db->query('UPDATE '.jieqi_dbprefix('obook_ochapter').' SET display=0 WHERE ochapterid='.intval($_REQUEST['id']));
}elseif($_REQUEST['action']=='unsale' || $_REQUEST['action']=='publish'){
	$chapter_handler->db->query('UPDATE '.jieqi_dbprefix('obook_ochapter').' SET display=2 WHERE ochapterid='.intval($_REQUEST['id']));
}elseif($_REQUEST['action']=='delete'){
	$chapter_handler->db->query('DELETE FROM '.jieqi_dbprefix('obook_ochapter').' WHERE ochapterid='.intval($_REQUEST['id']));
	$chapter_handler->db->query('UPDATE '.jieqi_dbprefix('obook_ochapter').' SET chapterorder=chapterorder-1 WHERE obookid='.intval($obookid).' AND chapterorder>'.intval($ochapter->getVar('chapterorder', 'n')));
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

if(!empty($jieqiConfigs['obook']['scorechapter'])){
	if($obook->getVar('posterid')==$_SESSION['jieqiUserId']){
		$users_handler->changeScore($_SESSION['jieqiUserId'], $jieqiConfigs['obook']['scorechapter'], false);
	}else{
		$users_handler->changeScore($obook->getVar('posterid'), $jieqiConfigs['obook']['scorechapter'], false);	
	}
}
*/
if($_REQUEST['action']=='publish'){
	$linkfile=JIEQI_ROOT_PATH.'/files/obook/articlelink'.jieqi_getsubdir($obook->getVar('articleid', 'n')).'/'.$obook->getVar('articleid', 'n').'.php';
	if(is_file($linkfile)){
		include_once($linkfile);
		$jieqiObookdata['ochapter'][$ochapter->getVar('chapterorder','n') - 1]['display'] = '1';
		$varstring="<?php\n".jieqi_extractvars('jieqiObookdata', $jieqiObookdata)."\n?>";
		jieqi_writefile($linkfile, $varstring);
	}
		
	jieqi_getconfigs('article', 'configs');
	$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
	header('Location: '.$article_static_url.'/newchapter.php?aid='.$obook->getVar('articleid','n').'&ochapterid='.$ochapter->getVar('ochapterid','n'));
}else{
	jieqi_jumppage($obook_static_url.'/obookmanage.php?id='.$obookid.'&updatelink=1', LANG_DO_SUCCESS, $jieqiLang['obook']['chapter_set_success']);
}

?>