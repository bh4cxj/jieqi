<?php
/**
 * 脚部文件
 *
 * 脚部文件，输出信息和加载风格模板
 * 
 * 调用模板：$jieqiModules['space']['path'].'/themes/'.$theme.'/theme.html';
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_page_template'] = $jieqiModules['space']['path'].'/themes/'.$theme.'/theme.html';
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>