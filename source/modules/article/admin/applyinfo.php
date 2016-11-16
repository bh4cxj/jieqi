<?php 
/**
 * 显示作家申请信息
 *
 * 显示作家申请信息
 * 
 * 调用模板：/modules/article/templates/admin/applyinfo.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: applyinfo.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
if(empty($_REQUEST['id'])) jieqi_printfail(LANG_ERROR_PARAMETER);
jieqi_loadlang('applywriter', JIEQI_MODULE_NAME);
include_once($jieqiModules['article']['path'].'/class/applywriter.php');
$apply_handler =& JieqiApplywriterHandler::getInstance('JieqiApplywriterHandler');
$applywriter=$apply_handler->get($_REQUEST['id']);
if(!is_object($applywriter)) jieqi_printfail($jieqiLang['article']['applywriter_not_exists']);

//jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');

//包含页头处理
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->setCaching(0);

$jieqiTpl->assign('applyid', $applywriter->getVar('applyid'));
$jieqiTpl->assign('applytime', date(JIEQI_DATE_FORMAT.' '.JIEQI_TIME_FORMAT, $applywriter->getVar('applytime')));
$jieqiTpl->assign('applyuid', $applywriter->getVar('applyuid'));
$jieqiTpl->assign('applyname', $applywriter->getVar('applyname'));

$jieqiTpl->assign('applytitle', $applywriter->getVar('applytitle'));
$jieqiTpl->assign('applytext', $applywriter->getVar('applytext'));
$jieqiTpl->assign('applysize', $applywriter->getVar('applysize'));

$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/applyinfo.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>