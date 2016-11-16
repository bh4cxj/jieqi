<?php 
/**
 * 共享电子书订阅记录
 *
 * 共享电子书订阅记录
 * 
 * 调用模板：/modules/obook/templates/share/sharecbuylog.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sharecbuylog.php 326 2009-02-04 00:26:22Z juny $
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
elseif($obook->getVar('publishid') != $_SESSION['jieqiPublishid']) jieqi_printfail($jieqiLang['obook']['noper_view_stat']);

//页码
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
jieqi_getconfigs('obook', 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
if(!empty($_REQUEST['uname'])) $jieqiTpl->assign('uname',htmlspecialchars($_REQUEST['uname'], ENT_QUOTES));
if(!empty($_REQUEST['oname'])) $jieqiTpl->assign('oname',htmlspecialchars($_REQUEST['oname'], ENT_QUOTES));

include_once($jieqiModules['obook']['path'].'/class/osale.php');
$osale_handler =& JieqiOsaleHandler::getInstance('JieqiOsaleHandler');	
$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['cid']));
$criteria->setSort('osaleid');
$criteria->setOrder('DESC');
$criteria->setLimit($jieqiConfigs['obook']['pagenum']);
$criteria->setStart(($_REQUEST['page']-1) * $jieqiConfigs['obook']['pagenum']);
$osale_handler->queryObjects($criteria);
    
$osalerows=array();
$k=0;
while($v = $osale_handler->getObject()){
	$osalerows[$k]['osaleid']=$v->getVar('osaleid');  //销售序号
	$osalerows[$k]['buytime']=date(JIEQI_DATE_FORMAT, $v->getVar('buytime'));  //购买日期
	$osalerows[$k]['osaleid']=$v->getVar('osaleid');
	$osalerows[$k]['accountid']=$v->getVar('accountid');
	$osalerows[$k]['account']=$v->getVar('account');
	$osalerows[$k]['obookid']=$v->getVar('obookid');
	$osalerows[$k]['ochapterid']=$v->getVar('ochapterid');
	$osalerows[$k]['obookname']=$v->getVar('obookname');
	$osalerows[$k]['chaptername']=$v->getVar('chaptername');
	$osalerows[$k]['saleprice']=$v->getVar('saleprice');

	$k++;
}

$jieqiTpl->assign_by_ref('osalerows', $osalerows);

//处理页面跳转
include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
$jumppage = new JieqiPage($osale_handler->getCount($criteria),$jieqiConfigs['obook']['pagenum'],$_REQUEST['page']);
$jumppage->setlink('', true, true);
$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());


$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['obook']['path'].'/templates/share/sharecbuylog.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>