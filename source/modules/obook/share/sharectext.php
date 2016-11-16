<?php 
/**
 * 共享电子书章节内容显示
 *
 * 共享电子书章节内容显示
 * 
 * 调用模板：/modules/obook/templates/share/sharectext.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sharectext.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../../global.php');
if(!isset($_SESSION['jieqiPublishid'])){
	$local_domain_url=(empty($_SERVER['HTTP_HOST'])) ? '' : 'http://'.$_SERVER['HTTP_HOST'];
	header('Location: '.$jieqiModules['obook']['url'].'/share/sharelogin.php?jumpurl='.urlencode($local_domain_url.jieqi_addurlvars(array())));
	exit;
}
if(empty($_REQUEST['cid']) || !is_numeric($_REQUEST['cid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['cid']=intval($_REQUEST['cid']);
if(empty($_REQUEST['oid']) || !is_numeric($_REQUEST['oid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
$_REQUEST['oid']=intval($_REQUEST['oid']);
jieqi_loadlang('share', JIEQI_MODULE_NAME);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['oid']);
if(!is_object($obook)) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
//elseif($obook->getVar('publishid') != $_SESSION['jieqiPublishid']) jieqi_printfail('对不起，您没有查看该电子书信息的权限！');

include_once($jieqiModules['obook']['path'].'/class/ochapter.php');
$chapter_handler =& JieqiOchapterHandler::getInstance('JieqiOchapterHandler');
$chapter=$chapter_handler->get($_REQUEST['cid']);
if(!$chapter) jieqi_printfail($jieqiLang['obook']['chapter_not_exists']);

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
jieqi_getconfigs('obook', 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

$jieqiTpl->assign('obookid',$chapter->getVar('obookid'));
$jieqiTpl->assign('obookname',$chapter->getVar('obookname'));
$jieqiTpl->assign('ochapterid',$chapter->getVar('ochapterid'));
$jieqiTpl->assign('chaptername',$chapter->getVar('chaptername'));

include_once($jieqiModules['obook']['path'].'/class/ocontent.php');
$content_handler =& JieqiOcontentHandler::getInstance('JieqiOcontentHandler');
$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
$criteria->setLimit(1);
$content_handler->queryObjects($criteria);
$content=$content_handler->getObject();
if(is_object($content))	$jieqiTpl->assign('chaptercontent',$content->getVar('ocontent'));
else $jieqiTpl->assign('chaptercontent', '');

$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/share/sharectext.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>