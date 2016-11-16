<?php
/**
 * 圈子列表
 *
 * 圈子列表
 * 
 * 调用模板：$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/group/templates/admin/admincreate.html')
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 * @version 1.0
 */

$tmpvar = empty($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
preg_match('/\/modules\/([^\/]+)\//is', str_replace(array('\\\\','\\'),'/',$tmpvar), $matches);
if(!empty($matches[1]) ) {
	define('JIEQI_MODULE_NAME',$matches[1] );
}
else{
	exit('error modules name!');
}
//@define('JIEQI_DEBUG_MODE','1');
require_once('../../../global.php');

include_once JIEQI_ROOT_PATH.'/admin/header.php';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower[JIEQI_MODULE_NAME]['group'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('create',JIEQI_MODULE_NAME);
//add new group
if($_REQUEST['gname']){
	if(empty($_REQUEST['id']) && empty($_REQUEST['username'])) jieqi_printfail(LANG_NO_USER);
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	if(!empty($_REQUEST['id'])){
		$userobj=$users_handler->get($_REQUEST['id']);
	}else{
		//$_REQUEST['username']=strtolower(trim($_REQUEST['username']));
		$_REQUEST['username']=trim($_REQUEST['username']);
		$userobj=$users_handler->getByname($_REQUEST['username']);
	}
	if(is_object($userobj)){
		$uid = $userobj->getVar('uid');
		$uname = $userobj->getVar('uname');
		$name = $userobj->getVar('name');
	}else{
		jieqi_printfail(LANG_NO_USER);
	}

	//必用数据句柄初始化
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
	$criteria = new Criteria(gname,trim($_REQUEST['gname']),'=' );
	$group_handler->queryObjects($criteria);
	$v = $group_handler->getObject();
	if($v){
		jieqi_printfail( sprintf($jieqiLang['g']['be_already_registed'],$_REQUEST['gname']) );
	}
	$newgroup = $group_handler->create();
	$newgroup->setVar('gname',$_REQUEST['gname']);
	$newgroup->setVar('gcatid',intval($_REQUEST['gcatid']) );
	$newgroup->setVar('gprovince',$_REQUEST['province'] );
	$newgroup->setVar('gcity',$_REQUEST['city']);
	$newgroup->setVar('gbrief',$_REQUEST['gbrief'] );
	$newgroup->setVar('gaudit',intval($_REQUEST['gaudit']) );
	$newgroup->setVar('gmembers',1);
	$newgroup->setVar('guid',$uid);
	$newgroup->setVar('guname',$uname);
	$newgroup->setVar('gowner_name',$name);
	$newgroup->setVar('gtime',JIEQI_NOW_TIME );
	if($group_handler->insert($newgroup) ){
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
		$gid = $newgroup->getVar('gid');
		updateginfo($gid);
		//加入第一个会员
		include(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME."/class/member.php");
		$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
		$newmember = $member_handler->create();
		$newmember->setVar('uid',$uid);
		$newmember->setVar('uname',$uname );
		$newmember->setVar('name',$name);
		$newmember->setVar('gid',$gid);
		$newmember->setVar('creater',1);
		$newmember->setVar('membergid',1);
		$newmember->setVar('mtime',JIEQI_NOW_TIME );
		$newmember->setVar('mswitch',1);
		$member_handler->insert($newmember);

		//创建默认相册
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');
		$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');
		$defaultalbum = $album_handler->create();
		$defaultalbum->setVar('gid',$gid);
		$defaultalbum->setVar('albumorder',0);
		$defaultalbum->setVar('albumname',$jieqiLang['g']['default_album']);
		$defaultalbum->setVar('defaultflag',1);
		$album_handler->insert($defaultalbum);

		//相册分类文件
		$criteria = new CriteriaCompo(new Criteria('gid',$gid) );
		$criteria->setSort('albumorder');
		$criteria->setOrder('asc');
		$album_handler->queryObjects($criteria);
		$albums = array();
		while($v = $album_handler->getObject() ){
			$albums[$v->getVar('albumid')] = $v->getVar('albumname');
		}
		$string = "<?php  \n \$albumscat = ".var_export($albums,true)."; \n  ?>";
		$groupUserfile['dir'] = jieqi_uploadpath('userdir',JIEQI_MODULE_NAME);
		$groupUserfile['dir'].= jieqi_getsubdir($gid).'/';
		$groupUserfile['dir'].= $gid.'/';
		jieqi_writefile($groupUserfile['dir'].'/albumscat.php',$string);
		if($jieqiModules['badge']['publish']){
		jieqi_jumppage($jieqiModules['badge']['url'].'/admin/badgenew.php?btypeid=2010&linkid='.$gid.'&caption='.$_REQUEST['gname'],LANG_DO_SUCCESS,$jieqiLang['g']['create_group_success']);
		} else{
		 jieqi_jumppage($jieqiModules['group']['url'].'/admin/mangroup.php',LANG_DO_SUCCESS,$jieqiLang['g']['create_group_success']);
		}
	}else{
		jieqi_printfail($jieqiLang['g']['create_group_failure']);
	}
}

//group cats

include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/gcats.php');
$gcatsarr = array();
foreach($gcats as $key=>$value){
	$gcatsarr[]= array('id'=>$key,'name'=>$value);
}
$jieqiTpl->assign('gcatsarr',$gcatsarr);

$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/admincreate.html') );
include_once JIEQI_ROOT_PATH.'/admin/footer.php';
?>