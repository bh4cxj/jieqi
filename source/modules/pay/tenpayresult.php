<?php 
/**
 * 腾讯财付通-处理结果显示
 *
 * 腾讯财付通-处理结果显示 (https://www.tenpay.com)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tenpayresult.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'tenpay');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
//jieqi_getconfigs(JIEQI_MODULE_NAME, JIEQI_PAY_TYPE, 'jieqiPayset');

jieqi_msgwin(LANG_DO_SUCCESS,sprintf($jieqiLang['pay']['buy_egold_success'], $_REQUEST['buyname'], JIEQI_EGOLD_NAME, $_REQUEST['egold']));
?>