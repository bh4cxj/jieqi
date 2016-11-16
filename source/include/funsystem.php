<?php
/**
 * 服务器系统相关函数
 *
 * 服务器系统相关函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funsystem.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * 获取zend optimizer 版本
 * 
 * @param      void
 * @access     public
 * @return     string
 */
function jieqi_zendoptimizerver(){
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	preg_match('/Zend(\s|&nbsp;)Optimizer(\s|&nbsp;)v([\.\d]*),/is', $phpinfo, $matches);
    if(!empty($matches[3])) return $matches[3]; 
    else return '';
}
?>