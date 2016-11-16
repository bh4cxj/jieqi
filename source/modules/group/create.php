<?php
/**
 * 创建工会
 *
 * 创建工会
 * 
 * 调用模板：/modules/group/templates/create.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_checklogin();

jieqi_loadlang('create',JIEQI_MODULE_NAME);

//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');
//包含页头页尾
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign("provincejs_href",JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/include/province.js');
//检查权限
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//检查创建圈子权限
$creategrouppower = jieqi_checkpower($jieqiPower['group']['creategroup'],$jieqiUsersStatus,$jieqiUsersGroup, true);
if(!$creategrouppower) jieqi_printfail($jieqiLang['g']['create_group_nopower']);
//add new group 
if($_REQUEST['gname']){
	$nowtime = time();
	//必用数据句柄初始化
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');

	$criteria = new Criteria(gname,trim($_REQUEST['gname']),'=' );
	$group_handler->queryObjects($criteria);
	$v = $group_handler->getObject();
	if($v ){
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
	$newgroup->setVar('guid',$_SESSION['jieqiUserId']);
	$newgroup->setVar('guname',$_SESSION['jieqiUserName']);
	$newgroup->setVar('gtime',$nowtime );
	if($group_handler->insert($newgroup) ){
		include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
		$gid = $newgroup->getVar('gid');
		updateginfo($gid);
		//加入第一个会员
		include(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME."/class/member.php");
		$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
		$newmember = $member_handler->create();
		$newmember->setVar('uid',$_SESSION['jieqiUserId']);
		$newmember->setVar('uname',$_SESSION['jieqiUserName'] );
		$newmember->setVar('gid',$gid);
		$newmember->setVar('creater',1);
		$newmember->setVar('membergid',1);
		$newmember->setVar('mtime',$nowtime );
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

		jieqi_jumppage('./upface.php?g='.$newgroup->getVar('gid'),LANG_DO_SUCCESS,$jieqiLang['g']['create_group_success']);
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

$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/create.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');

?>