<?php 
// $Id: adminmenu.php 2004-2-16 $
//  ------------------------------------------------------------------------ 
//                                杰奇网络                                     
//                    Copyright (c) 2004 jieqi.com                         
//                       <http://www.jieqi.com/>                           
//  ------------------------------------------------------------------------
//  设计：徐风(juny)
//  邮箱: 377653@qq.com
//  ------------------------------------------------------------------------
/**
菜单数组：0、菜单深度 1、显示名称 2、链接地址 3、使用权限(0,游客;1,用户;2,管理员) 4、是否新开窗口(0,不新开;1,新开;2,新开小窗口) 5、是否显示（0,隐藏；1,显示）
*/

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '参数设置', 'command'=>JIEQI_URL.'/admin/configs.php?mod=cartoon', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '权限管理', 'command'=>JIEQI_URL.'/admin/power.php?mod=cartoon', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '漫画管理', 'command'=>$GLOBALS['jieqiModules']['cartoon']['url'].'/admin/cartoon.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '评论管理', 'command'=>$GLOBALS['jieqiModules']['cartoon']['url'].'/admin/review.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '伪静态页面生成', 'command'=>$GLOBALS['jieqiModules']['cartoon']['url'].'/admin/makefake.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '单篇采集', 'command'=>$GLOBALS['jieqiModules']['cartoon']['url'].'/admin/collect.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['cartoon'][] = array('layer' => '0', 'caption' => '批量采集', 'command'=>$GLOBALS['jieqiModules']['cartoon']['url'].'/admin/batchcollect.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

?>