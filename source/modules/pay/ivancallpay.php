<?php 
/**
 * 埃文手机声讯-支付界面
 *
 * 埃文手机声讯-支付界面 (http://www.ivansms.com)
 * 
 * 调用模板：/modules/pay/templates/ivancallpay.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ivancallpay.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
define('JIEQI_PAY_TYPE', 'ivancall');
require_once('../../global.php');
jieqi_loadlang('pay', JIEQI_MODULE_NAME);
jieqi_getconfigs('pay', 'payblocks', 'jieqiBlocks');
jieqi_getconfigs('system', 'configs');
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
if(!empty($_SESSION['jieqiUserId'])) $jieqiTpl->assign('islogin', 1);
else $jieqiTpl->assign('islogin', 0);
$jieqiTpl->assign('url_confirm', $jieqiModules['pay']['url'].'/ivancallconfirm.php');
$local_domain_url=(empty($_SERVER['HTTP_HOST'])) ? '' : 'http://'.$_SERVER['HTTP_HOST'];
$jieqiTpl->assign('url_login', JIEQI_USER_URL.'/login.php?do=submit&jumpurl='.urlencode($local_domain_url.'/modules/pay/ivancallpay.php'));
$jieqiTpl->assign('url_register', JIEQI_USER_URL.'/register.php');
$jieqiTpl->assign('url_getpass', JIEQI_USER_URL.'/getpass.php');
if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
else $jieqiTpl->assign('show_checkcode', 0);

$jieqiTpl->assign('url_checkcode', JIEQI_USER_URL.'/checkcode.php');
if (JIEQI_USE_CACHE) $jieqiTpl->setCaching(0);
$tmpfile=$jieqiModules['pay']['path'].'/templates/ivancallpay.html';
$jieqiTpl->setCaching(0);
if(is_file($tmpfile)) $jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch($tmpfile));
else $jieqiTpl->assign('jieqi_contents','');
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>