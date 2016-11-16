<?php 
/**
 * 本模块首页
 *
 * 默认显示文章分类列表，也可以自定义成区块组成的首页，如：
 * 
 * define('JIEQI_MODULE_NAME', 'article');  //定义本页面所属区块
 * 
 * require_once('../../global.php');  //包含公共文件
 * 
 * jieqi_getconfigs(JIEQI_MODULE_NAME, 'blocks'); //包含区块参数
 * 
 * include_once(JIEQI_ROOT_PATH.'/header.php'); //包含页头
 * 
 * $jieqiTpl->assign('jieqi_contents','');  //内容位置不赋值，全部用区块
 * 
 * include_once(JIEQI_ROOT_PATH.'/footer.php'); //包含页尾
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: index.php 228 2008-11-27 06:44:31Z juny $
 */

include_once('articlelist.php');
?>