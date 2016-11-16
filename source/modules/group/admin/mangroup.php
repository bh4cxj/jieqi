<?php
$tmpvar = empty($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
preg_match('/\/modules\/([^\/]+)\//is', str_replace(array('\\\\','\\'),'/',$tmpvar), $matches);
if(!empty($matches[1]) ) {
	define('JIEQI_MODULE_NAME',$matches[1] );
}
else{
	exit('error modules name!');
}
require_once('../../../global.php');
include_once JIEQI_ROOT_PATH.'/admin/header.php';
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower[JIEQI_MODULE_NAME]['group'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);
jieqi_loadlang('mangroup',JIEQI_MODULE_NAME);
	if(!defined('JIEQI_USE_BADGE')){
	   if($jieqiModules['badge']['publish']){
		  define('JIEQI_USE_BADGE',1);
	   }else{
		  define('JIEQI_USE_BADGE',0);
	   }
	}	
	$jieqi_use_badge = JIEQI_USE_BADGE;
	$jieqiTpl->assign('jieqi_use_badge',JIEQI_USE_BADGE);

require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
//初始化页数
if (empty($_REQUEST['page']) || !is_numeric($_REQUEST['page'])) $_REQUEST['page']=1;

if($_REQUEST['docreate']){
    jieqi_loadlang('setbasic',JIEQI_MODULE_NAME);
	jieqi_loadlang('create',JIEQI_MODULE_NAME);
	$gid = intval($_REQUEST['gid'] );
	if(empty($_REQUEST['id']) && empty($_REQUEST['username'])) jieqi_printfail(LANG_NO_USER);
	include_once(JIEQI_ROOT_PATH.'/class/users.php');
	$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
	if($_REQUEST['id']){
		$userobj=$users_handler->get(intval($_REQUEST['id']));
	}else{
		$_REQUEST['username']=trim($_REQUEST['username']);
		$userobj=$users_handler->getByname($_REQUEST['username']);
	}
	if($userobj){
		$uid = $userobj->getVar('uid');
		$uname = $userobj->getVar('uname');
		$name = $userobj->getVar('name');
	}else{
		jieqi_printfail(LANG_NO_USER);
	}	
	//更换圈主
    if($_REQUEST['guid']!=intval($uid)){
	    jieqi_includedb();
	    include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/member.php');
		$member_handler = JieqimemberHandler::getInstance('JieqimemberHandler');
		//设置新的圈主
		$criteria = new CriteriaCompo(new Criteria('gid',$gid));
		$criteria->add(new Criteria('uid',$uid));
		$count=$member_handler->getCount($criteria);
		if(!$count) jieqi_printfail(sprintf($jieqiLang['g']['no_member'],$uname));
		$member_handler->updatefields(array('creater'=>1,'membergid'=>1,'mswitch'=>1),$criteria);
		//修改原先管理员
		//unset($criteria);
		$criteria = new CriteriaCompo(new Criteria('gid',$gid));
		$criteria->add(new Criteria('uid',$_REQUEST['guid']));
		$member_handler->updatefields(array('creater'=>0,'membergid'=>3),$criteria);
	}
	
	//必用数据句柄初始化
	require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/group.php');
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');

	//$criteria = new Criteria('gid',$gid);
	$updatefields['guid'] = $uid;
	$updatefields['guname'] = $uname;
	$updatefields['gowner_name'] = $name;
	$updatefields['gcatid'] = intval($_REQUEST['gcatid']);
	$updatefields['gbrief'] = jieqi_setslashes($_REQUEST['gbrief']);
	$updatefields['gaudit'] = intval($_REQUEST['gaudit']);
	include_once("../include/functions.php");
	update_ginfo($updatefields,$gid);
	jieqi_jumppage("mangroup.php?g=$gid&set=basic",LANG_DO_SUCCESS,$jieqiLang['g']['set_sucess'] );
}

switch($_REQUEST['set'] ){
   case basic:
        $content_man = set_basic();
		$jieqiTpl->setCaching(0);
		$jieqiTpl->assign('jieqi_contents',$content_man);
   break;
   default:
		$jieqiTpl->assign('page', $_REQUEST['page']);
		$criteria = new CriteriaCompo();
		$count = $group_handler->getCount($criteria);
		$criteria->setLimit(20);
		$criteria->setStart(($_REQUEST['page']-1) * 20);
		$k = 0;
		$group_handler->queryObjects($criteria);
		while($v = $group_handler->getObject() ){
			$grouprows[$k]['gname'] = $v->getVar('gname',n);
			$grouprows[$k]['gid'] = $v->getVar('gid',n);
			$grouprows[$k]['guname'] = $v->getVar('guname',n);
			$grouprows[$k]['gtopics'] = $v->getVar('topicnum',n);
			$k++;
		}
		$jieqiTpl->assign('grouprows',$grouprows);
		$jieqiTpl->setCaching(0);
		//分页标记
		$jieqiTpl->assign('page',$_REQUEST['page']);
		include_once(JIEQI_ROOT_PATH.'/lib/html/page.php');
		$jumppage = new JieqiPage($count,20,$_REQUEST['page']);
		$jieqiTpl->assign('url_jumppage',$jumppage->whole_bar());
		$jieqiTpl->assign('jieqi_contents', $jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/group/templates/admin/mangroup.html') );      
   break;
}
function set_basic(){
	jieqi_getconfigs('group', 'manblock', 'jieqiBlocks');
	$gid = intval($_REQUEST['g']);
	global $jieqiTpl;
	global $group;
	$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
	$group = $group_handler->get($gid);	
	define('GTHEME',$group->getVar('gtheme') );
	$jieqiTpl->assign('gname',$group->getVar('gname') );
	
	// province.js href
	$jieqiTpl->assign("provincejs_href",JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/include/province.js');

	//group cats
	include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/gcats.php');
	$gcatsoption = '';
	foreach($gcats as $key=>$value){
		if($key == $group->getVar('gcatid') ){
			$selected = 'selected';
		}
		$gcatsoption .= "<option value=$key $selected>$value</option>";
		unset($selected);
	}

	$jieqiTpl->assign('gcatsoption',$gcatsoption);
	$jieqiTpl->assign('guid',$group->getVar('guid')); 
	$jieqiTpl->assign('guname',$group->getVar('guname'));
	//$jieqiTpl->assign('province',$group->getVar('gprovince') );
	//$jieqiTpl->assign('city',$group->getVar('gcity') );
	//$jieqiTpl->assign('province_code',$group->getVar('gprovince') );
	//$jieqiTpl->assign('city_code',$group->getVar('gcity') );
	if($group->getVar('gaudit')==1 ){
		$jieqiTpl->assign('checked1','checked');
	}else{
		$jieqiTpl->assign('checked0','checked');
	}

	$jieqiTpl->assign('gbrief',stripcslashes($group->getVar('gbrief','n')) );	
	$jieqiTpl->assign('gid',$gid );
	$jieqiTpl->assign('setbasic_href','./mangroup.php?g='.$gid);

	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/group/templates/admin/set_basic.html');
}
include_once JIEQI_ROOT_PATH.'/admin/footer.php';
?>