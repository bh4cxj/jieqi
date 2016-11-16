<?php
/**
 * 个人空间首页
 *
 * 个人空间首页
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
define('JIEQI_MODULE_NAME','space');
require_once('../../global.php');
if(empty($_REQUEST['uid'])) {
	jieqi_checklogin();
	$_REQUEST['uid'] = $_SESSION['jieqiUserId'];
}
include_once($jieqiModules['space']['path'].'/spaceheader.php');
space_get_blocks('space');
include_once($jieqiModules['space']['path'].'/spacefooter.php');

?>