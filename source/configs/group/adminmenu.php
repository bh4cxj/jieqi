<?php
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '基本设置', 'command'=>JIEQI_URL.'/admin/configs.php?mod=group', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '圈子分类', 'command'=>JIEQI_URL.'/modules/group/admin/gcatset.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '成员组设置', 'command'=>JIEQI_URL.'/modules/group/admin/membergroup.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '管理组设置', 'command'=>JIEQI_URL.'/modules/group/admin/admingroup.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '权限设置', 'command'=>JIEQI_URL.'/admin/power.php?mod=group', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '添加圈子', 'command'=>JIEQI_URL.'/modules/group/admin/admincreate.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
$jieqiAdminmenu['group'][] = array('layer' => '0', 'caption' => '管理圈子', 'command'=>JIEQI_URL.'/modules/group/admin/mangroup.php', 'power' => JIEQI_GROUP_GUEST, 'target' => JIEQI_TARGET_SELF, 'publish' => '1');
?>
