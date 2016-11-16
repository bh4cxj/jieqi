<?php
/**
 * 当前采集规则列表
 *
 * 当前采集规则列表
 * 
 * 调用模板：/modules/article/templates/admin/collectset.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectset.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite');
$updateconfig=false;
if(isset($_REQUEST['action']) && $_REQUEST['action']=='del' && !empty($_REQUEST['config'])){
	foreach($jieqiCollectsite as $k=>$v){
		if($v['config']==$_REQUEST['config']){
			unset($jieqiCollectsite[$k]);
			$updateconfig=true;
			break;
		}
	}
}

include_once(JIEQI_ROOT_PATH.'/admin/header.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
$jieqiTpl->assign('article_static_url',$article_static_url);
$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

$jieqiTpl->assign_by_ref('siterows', $jieqiCollectsite);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['article']['path'].'/templates/admin/collectset.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
if($updateconfig){
	jieqi_setconfigs('collectsite', 'jieqiCollectsite', $jieqiCollectsite, JIEQI_MODULE_NAME);
	if(file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php')) jieqi_delfile(JIEQI_ROOT_PATH.'/configs/article/site_'.$_REQUEST['config'].'.php');
}
?>