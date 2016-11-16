<?php 
/**
 * 明天数码-充值界面
 *
 * 明天数码-充值界面
 * 
 * 调用模板：/modules/pay/templates/tomorrowpay.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tomorrowpay.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'tomorrow');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs('pay', 'payblocks', 'jieqiBlocks');
jieqi_getconfigs('system', 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
if(!empty($_SESSION['jieqiUserId'])) $jieqiTpl->assign('islogin', 1);
else $jieqiTpl->assign('islogin', 0);
$jieqiTpl->assign('url_confirm', $jieqiModules['pay']['url'].'/tomorrowconfirm.php');
$local_domain_url=(empty($_SERVER['HTTP_HOST'])) ? '' : 'http://'.$_SERVER['HTTP_HOST'];
$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/login.php?do=submit&jumpurl='.urlencode($local_domain_url.'/modules/pay/tomorrowpay.php'));
$jieqiTpl->assign('url_register', JIEQI_USER_URL.'/register.php');
$jieqiTpl->assign('url_getpass', JIEQI_USER_URL.'/getpass.php');
if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
else $jieqiTpl->assign('show_checkcode', 0);

$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
if (JIEQI_USE_CACHE) $jieqiTpl->setCaching(0);
$tmpfile=$jieqiModules['pay']['path'].'/templates/tomorrowpay.html';
$jieqiTpl->setCaching(0);
if(is_file($tmpfile)) $jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch($tmpfile));
else $jieqiTpl->assign('jieqi_contents','');
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>