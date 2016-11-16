<?php 
/**
 * 根据阅读图片中的校验码查询电子书
 *
 * 根据阅读图片中的校验码查询电子书
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: checkcode.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../../global.php');

jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['obook']['viewbuylog'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('checkcode', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $jieqiModules['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('obook_static_url',$obook_static_url);
$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);
include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');

$showstr='';
if(is_numeric($_REQUEST['checkchapterid']) && !empty($_REQUEST['checkstring'])){
	include_once($jieqiModules['obook']['path'].'/class/obuyinfo.php');
	$buyinfo_handler =& JieqiObuyinfoHandler::getInstance('JieqiObuyinfoHandler');
	$criteria=new CriteriaCompo(new Criteria('ochapterid', $_REQUEST['checkchapterid']));
	$criteria->add(new Criteria('checkcode', $_REQUEST['checkstring']));
	$criteria->setSort('obuyinfoid');
	$criteria->setOrder('DESC');
	$criteria->setLimit(100);
	$buyinfo_handler->queryObjects($criteria);
	$resnum=0;
	$showstr.=$jieqiLang['obook']['check_search_result'].'<br />';
	while($buyinfo = $buyinfo_handler->getObject()){
		if($resnum == 0){
			$showstr.=$jieqiLang['obook']['check_obook_id'].$buyinfo->getVar('ochapterid').' | '.$jieqiLang['obook']['check_obook_name'].$buyinfo->getVar('obookname').' '.$buyinfo->getVar('chaptername').' | '.$jieqiLang['obook']['check_check_code'].$buyinfo->getVar('checkcode').'<br />';
		}
		$showstr.=$jieqiLang['obook']['check_buy_time'].date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $buyinfo->getVar('buytime','n')).' | '.$jieqiLang['obook']['check_buy_name'].'<a href="'.jieqi_geturl('system', 'user', $buyinfo->getVar('userid'), 'info').'" target="_blank">'.$buyinfo->getVar('username').'</a> | '.$jieqiLang['obook']['check_sale_id'].$buyinfo->getVar('osaleid').'<br />';
		$resnum++;
	}
}
if(!empty($showstr)) $showstr='<div style="text-align:left">'.$showstr.'</div>';
$check_form = new JieqiThemeForm($jieqiLang['obook']['check_form_title'], 'frmcheckcode', $obook_dynamic_url.'/admin/checkcode.php');
$check_form->addElement(new JieqiFormText($jieqiLang['obook']['check_chapter_id'], 'checkchapterid', 30, 50, ''), true);
$check_form->addElement(new JieqiFormText($jieqiLang['obook']['check_check_string'], 'checkstring', 30, 50, ''), true);
$check_form->addElement(new JieqiFormHidden('action', 'checkcode'));
$check_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['obook']['check_search_button'], 'submit'));
$jieqiTpl->assign('jieqi_contents', $showstr.'<br />'.$check_form->render(JIEQI_FORM_MIDDLE).'<br />');
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>