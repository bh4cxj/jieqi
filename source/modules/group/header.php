<?php
/**
 * 圈子系统的公共处理文件
 *
 * 圈子系统的公共处理文件
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */

include_once("../../header.php");
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
$group = $group_handler->get($gid);
if(!is_Object($group) ){
	include_once('./lang/lang_header.php');
	jieqi_printfail($jieqiLang['g']['this_group_not_exist']);
}
define('GTHEME',$group->getVar('gtheme') );

if(!defined('JIEQI_USE_BADGE')){
   if($jieqiModules['badge']['publish']){
      define('JIEQI_USE_BADGE',1);
   }else{
      define('JIEQI_USE_BADGE',0);
   }
}
$jieqi_use_badge = JIEQI_USE_BADGE;
$jieqiTpl->assign('jieqi_use_badge',JIEQI_USE_BADGE);
$groupUserfile['dir'] = jieqi_uploadpath('userdir',JIEQI_MODULE_NAME);
$groupUserfile['dir'].= jieqi_getsubdir($gid).'/';
$groupUserfile['dir'].= $gid.'/';
$groupUserfile['albumdir']= $groupUserfile['dir'].'album/';
$groupUserfile['albumurl']= JIEQI_URL.'/files/'.JIEQI_MODULE_NAME.'/userdir'.jieqi_getsubdir($gid).'/'.$gid.'/'.'album/';
$groupUserfile['defaultalbum'] = JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/pic/empty.jpg';
$groupUserfile['attachdir']= $groupUserfile['dir'].'attach/';
$groupUserfile['attachurl']= JIEQI_URL.'/files/'.JIEQI_MODULE_NAME.'/userdir'.jieqi_getsubdir($gid).'/'.$gid.'/'.'attach/';
$groupUserfile['picdir']= $groupUserfile['dir'].'pic/';
$groupUserfile['picurl']= JIEQI_URL.'/files/'.JIEQI_MODULE_NAME.'/userdir'.jieqi_getsubdir($gid).'/'.$gid.'/'.'pic/';
$groupUserfile['defaultpic'] = JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/pic/face.gif';
$groupUserfile['info']= $groupUserfile['dir'].'info.php';
$groupUserfile['albumscat']= $groupUserfile['dir'].'albumscat.php';

include_once($groupUserfile['info']);

$jieqiTpl->assign('gid',$gid);
$jieqiTpl->assign('gname',$group->getVar('gname') );
//$jieqiTpl->assign('gdeclare',$group->getVar('gdeclare') );

//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//检查创建圈子权限
$creategrouppower = jieqi_checkpower($jieqiPower['group']['creategroup'],$jieqiUsersStatus,$jieqiUsersGroup, true);
if($creategrouppower){
   define('JIEQI_CREATE_GROUP',1);
}else{
   define('JIEQI_CREATE_GROUP',0);
}
$jieqiTpl->assign('jieqi_create_group',JIEQI_CREATE_GROUP);
?>