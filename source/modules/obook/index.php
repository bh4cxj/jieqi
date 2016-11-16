<?php 
/**
 * 本模块首页
 *
 * 默认显示电子书列表
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: index.php 231 2008-11-27 08:46:26Z juny $
 */

/*
//模块首页默认是显示分类列表，如果要改成独立的区块首页，可以用下面这种模式

define('JIEQI_MODULE_NAME', 'obook');  //定义本页面所属区块
require_once('../../global.php');  //包含公共文件
jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks'); //包含区块参数
include_once(JIEQI_ROOT_PATH.'/header.php'); //包含页头
$jieqiTpl->assign('jieqi_contents','');  //内容位置不赋值，全部用区块
include_once(JIEQI_ROOT_PATH.'/footer.php'); //包含页尾
*/

include_once('obooklist.php');
?>