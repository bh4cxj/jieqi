<?php 
/**
 * 电子书本站推荐标志记录
 *
 * 电子书本站推荐标志记录
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: setgood.php 231 2008-11-27 08:46:26Z juny $
 */

define('JIEQI_MODULE_NAME', 'obook');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower['obook']['manageallobook'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('manage', JIEQI_MODULE_NAME);
if(empty($_REQUEST['id'])) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
include_once($jieqiModules['obook']['path'].'/class/obook.php');
$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
$obook=$obook_handler->get($_REQUEST['id']);
if(!is_object($obook)) jieqi_printfail($jieqiLang['obook']['obook_not_exists']);
if($_REQUEST['action']=='no') $obook->setVar('toptime', 0);
else $obook->setVar('toptime', JIEQI_NOW_TIME);
$obook_handler->insert($obook);

if($_REQUEST['action']=='no') jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['obook']['obook_notgood_success']);
else jieqi_msgwin(LANG_DO_SUCCESS, $jieqiLang['obook']['obook_setgood_success']);
?>