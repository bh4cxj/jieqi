<?php 
// $Id: adminmenu.php 2004-2-16 $
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
/**
菜单数组：0、菜单深度 1、显示名称 2、链接地址 3、使用权限(0,游客;1,用户;2,管理员) 4、是否新开窗口(0,不新开;1,新开;2,新开小窗口) 5、是否显示（0,隐藏；1,显示）
*/

$jieqiAdminmenu['quiz'][] = array('layer' => '0', 'caption' => '参数设置', 'command'=>JIEQI_URL.'/admin/configs.php?mod=quiz', 'power' => JIEQI_GROUP_ADMIN, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['quiz'][] = array('layer' => '0', 'caption' => '权限设置', 'command'=>JIEQI_URL.'/admin/power.php?mod=quiz', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['quiz'][] = array('layer' => '0', 'caption' => '类别管理', 'command'=>JIEQI_URL.'/modules/quiz/admin/quiz_type.php', 'power' => JIEQI_GROUP_ADMIN, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');

$jieqiAdminmenu['quiz'][] = array('layer' => '0', 'caption' => '问答管理', 'command'=>JIEQI_URL.'/modules/quiz/admin/quiz_list.php', 'power' => JIEQI_GROUP_ADMIN, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');