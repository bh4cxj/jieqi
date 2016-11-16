<?php 
/**
 * 退出登录
 *
 * 已登录用户退出处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: logout.php 142 2008-11-20 04:09:57Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
define('JIEQI_ADMIN_LOGIN', 1);
require_once('global.php');
jieqi_loadlang('users', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/include/dologout.php');
jieqi_dologout();
if (empty($_REQUEST['jumpurl'])) $_REQUEST['jumpurl']=empty($_REQUEST['forward']) ? JIEQI_URL.'/' : $_REQUEST['forward'];
include_once(JIEQI_ROOT_PATH.'/include/funuser.php');
jieqi_logoutdo($_REQUEST['jumpurl']);
?>