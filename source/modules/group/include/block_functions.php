<?php


function block_managers(){
	global $gid;
	global $jieqiTpl;
	global $group;
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.GTHEME.'/block_managers.html');
}




?>