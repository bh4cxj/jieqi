<?php 
// $Id: adminmenu.php 163 2008-11-21 06:49:52Z lee $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
/**
菜单数组：0、菜单深度 1、显示名称 2、链接地址 3、使用权限(0,游客;1,用户;2,管理员) 4、是否新开窗口(0,不新开;1,新开;2,新开小窗口) 5、是否显示（0,隐藏；1,显示）
*/
$jieqiAdminmenu['info'][0] = array('layer' => '0', 'caption' => '参数设置', 'command'=>JIEQI_URL.'/admin/configs.php?mod=info&define=0', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['info'][1] = array('layer' => '0', 'caption' => '权限设置', 'command'=>JIEQI_URL.'/admin/power.php?mod=info', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['info'][2] = array('layer' => '0', 'caption' => '分类栏目管理', 'command'=>JIEQI_URL.'/modules/info/admin/magcolumn.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['info'][3] = array('layer' => '0', 'caption' => '模型管理', 'command'=>JIEQI_URL.'/modules/info/admin/addmx.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['info'][4] = array('layer' => '0', 'caption' => '信息管理', 'command'=>JIEQI_URL.'/modules/info/admin/magmsg.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['info'][5] = array('layer' => '0', 'caption' => '附件管理', 'command'=>JIEQI_URL.'/modules/info/admin/magupload.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

?>