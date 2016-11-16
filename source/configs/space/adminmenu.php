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

$jieqiAdminmenu['space'][0] = array('layer' => '0', 'caption' => '参数设置', 'command'=>JIEQI_URL.'/admin/configs.php?mod=space', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
//$jieqiAdminmenu['space'][1] = array('layer' => '0', 'caption' => '设置TAG', 'command'=>JIEQI_URL.'/admin/articletag.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['space'][2] = array('layer' => '0', 'caption' => '所有空间', 'command'=>$GLOBALS['jieqiModules']['space']['url'].'/admin/allspace.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['space'][3] = array('layer' => '0', 'caption' => '所有博文', 'command'=>$GLOBALS['jieqiModules']['space']['url'].'/admin/allblog.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
?>