<?php
/**
 * 常见问答
 *
 * 程序使用的常见问答和使用技巧
 * 
 * 调用模板：/templates/admin/faq.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: faq.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/faq.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

?>