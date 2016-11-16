<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

/**
 *  网站首页
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');

//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');
//包含页头页尾
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_loadlang('upface',JIEQI_MODULE_NAME);
//query user whether a manager or not
//.....
//检查权限
include_once("include/functions.php");
setpower($gid);

if(!$admingid){
	jieqi_printfail($jieqiLang['g']['create_group_no']);
}
//up group face
$gadr = JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/?g='.$gid;
$jieqiTpl->assign('gadr',"<a href=$gadr>$gadr</a>");
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/regsuc.html') );

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>