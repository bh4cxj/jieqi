<?php 
/**
 * 显示分卷阅读
 *
 * 显示分卷阅读
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: showvolume.php 228 2008-11-27 06:44:31Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../global.php');
if(empty($_REQUEST['aid']) || empty($_REQUEST['vid'])) jieqi_printfail(LANG_ERROR_PARAMETER);
include_once($jieqiModules['article']['path'].'/class/package.php');
$package=new JieqiPackage($_REQUEST['aid']);
if($package->loadOPF()){
	$package->showVolume($_REQUEST['vid']);
}else{
	jieqi_loadlang('article', JIEQI_MODULE_NAME);
	jieqi_printfail($jieqiLang['article']['article_not_exists']);
}

?>