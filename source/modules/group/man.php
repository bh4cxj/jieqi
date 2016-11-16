<?php
/**
 * 圈子参数设置
 *
 * 圈子参数设置（基本参娄、LOG、模板）
 * 
 * 调用模板：动态模板[set_badge.html,set_basic.html,set_log.html]
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('topic',JIEQI_MODULE_NAME);
$gid = intval($_REQUEST['g']);
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_getconfigs('group', 'manblock', 'jieqiBlocks');
jieqi_checklogin();
$jieqiTpl->setCaching(0);
$gid = intval($_REQUEST['g']);
//包含区块参数
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');

//center
switch($_REQUEST['set'] ){
	case basic:
	    $content_man = set_basic();
	    break;
	case badge:
	    if(!JIEQI_USE_BADGE){
		  jieqi_loadlang('header',JIEQI_MODULE_NAME);
		  exit($jieqiLang['g']['this_group_errot']);
		}
	    $content_man = set_badge();
	    break;		
	case log:
	    $content_man = set_log();
	    break;
	case tpl:
	    $content_man = set_tpl();
	    break;
	case managers:
	    break;
	case disband:
	    break;
	default:
	    $content_man = set_basic();
	    break;
}

function set_basic(){
	global $gid;
	global $jieqiTpl,$jieqiModules;
	global $group;
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
	$jieqiTpl->assign('province',$group->getVar('gprovince') );
	$jieqiTpl->assign('city',$group->getVar('gcity') );
	$jieqiTpl->assign('province_code',$group->getVar('gprovince') );
	$jieqiTpl->assign('city_code',$group->getVar('gcity') );
	if($group->getVar('gaudit')==1 ){
		$jieqiTpl->assign('checked1','checked');
	}else{
		$jieqiTpl->assign('checked0','checked');
	}

	$jieqiTpl->assign('gbrief',stripcslashes($group->getVar('gbrief','n')) );	
	$jieqiTpl->assign('gid',$gid );
	$jieqiTpl->assign('setbasic_href','./setbasic.php?g='.$gid);

	return	$jieqiTpl->fetch($jieqiModules['group']['path'].'/templates/set_basic.html');
}

function set_badge(){
	//载入语言及配置
    jieqi_loadlang('badge', 'badge');
    jieqi_getconfigs('badge', 'configs');
	jieqi_getconfigs('badge', 'type');
	global $jieqiConfigs,$jieqiLang,$jieqiTpl,$jieqiModules,$gid;
	
	include_once($jieqiModules['badge']['path'].'/class/badge.php');
	$badge_handler =& JieqiBadgeHandler::getInstance('JieqiBadgeHandler');
	$criteria=new CriteriaCompo(new Criteria('btypeid', '2010'));
	$criteria->add(new Criteria('linkid', $gid));
	$badge_handler->queryObjects($criteria);
	$badge=$badge_handler->getObject();
	if(is_object($badge)){
	    $jieqiTpl->assign('action','edit');
		$badge_name=$badge->getVar('caption','n');
		$badge_maxnum=$badge->getVar('maxnum','n');
		$badge_linkid=$badge->getVar('linkid','n');
		$jieqiTpl->assign('badge_name',$badge_name);
		$jieqiTpl->assign('badge_maxnum',$badge_maxnum);
		$jieqiTpl->assign('badge_linkid',$badge_linkid);
	}else{
	    $jieqiTpl->assign('badge_linkid',$gid);
		$jieqiTpl->assign('action','insert');
	}
	if($_REQUEST['linkid']) {
	   $errtext='';
	   if (strlen($_POST['caption'])==0) $errtext .= $jieqiLang['badge']['need_badge_caption'].'<br />';
		//徽章图片，检查格式及大小
		$image_postfix='';
		if (!empty($_FILES['badgeimage']['name'])){
			if($jieqiType['badge']['2010']['sysflag'] == 1) $allow_imagetype=$jieqiConfigs['badge']['sysimgtype'];
			else $allow_imagetype=$jieqiConfigs['badge']['imagetype'];
			$image_postfix = strrchr(trim(strtolower($_FILES['badgeimage']['name'])),".");
			if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['badgeimage']['name'])){
				$typeary=explode(' ',trim($allow_imagetype));
				foreach($typeary as $k=>$v){
					if(substr($v,0,1) != '.') $typeary[$k]='.'.$typeary[$k];
				}
				if(!in_array($image_postfix, $typeary)) $errtext .= sprintf($jieqiLang['badge']['image_type_error'], $allow_imagetype).'<br />';

				if($_FILES['badgeimage']['size'] > (intval($jieqiConfigs['badge']['maximagesize']) * 1024)) $errtext .=sprintf($jieqiLang['badge']['upload_filesize_toolarge'], intval($jieqiConfigs['badge']['maximagesize'])).'<br />';

			}else{
				$errtext .= sprintf($jieqiLang['badge']['badgeimage_not_image'], $_FILES['badgeimage']['name']).'<br />';
			}
			if(!empty($errtext)) jieqi_delfile($_FILES['badgeimage']['tmp_name']);
		}	
		if(empty($errtext)) {
		    include_once($jieqiModules['badge']['path'].'/include/badgefunction.php');
				if($jieqiType['badge']['2010']['sysflag'] != 1){
				  switch($_REQUEST['action']) {
					  case 'edit':
					        $old_imagetype=$badge->getVar('imagetype', 'n');
							$badge->setVar('caption', $_POST['caption']);
							if(isset($_POST['maxnum'])) $_POST['maxnum']=intval($_POST['maxnum']);
							else $_POST['maxnum']=0;
							//$badge->setVar('maxnum', $_POST['maxnum']);
							$badge->setVar('uptime', JIEQI_NOW_TIME);
							if (!empty($_FILES['badgeimage']['name'])){
								$imagetype=0;
								if(!empty($image_postfix)){
									$jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
									foreach($jieqi_image_type as $k=>$v){
										if($v == $image_postfix){
											$imagetype=$k;
											break;
										}
									}
								}
								$badge->setVar('imagetype', $imagetype);
							}
					   break;
					   case 'insert':
							$badge = $badge_handler->create();
							$badge->setVar('btypeid', '2010');
							$badge->setVar('caption', $_POST['caption']);
							$badge->setVar('description', $_POST['description']);
							$badge->setVar('linkid', $_POST['linkid']);
							if(isset($_POST['maxnum'])) $_POST['maxnum']=intval($_POST['maxnum']);
							else $_POST['maxnum']=$jieqiConfigs['badge']['defaultmaxnum'];
							$badge->setVar('maxnum', $_POST['maxnum']);
							$badge->setVar('usenum', 0);
							$imagetype=0;
							if(!empty($image_postfix)){
								$jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
								foreach($jieqi_image_type as $k=>$v){
									if($v == $image_postfix){
										$imagetype=$k;
										break;
									}
								}
							}
							$badge->setVar('imagetype', $imagetype);
							$badge->setVar('uptime', JIEQI_NOW_TIME);					       
					   break;
				   }
				   if (!$badge_handler->insert($badge)) jieqi_printfail($jieqiLang['badge']['badge_edit_failure']);
				}
			if (!empty($_FILES['badgeimage']['name'])){
				//删除原来图片
				//$old_imagepath=getbadgepath($badge->getVar('btypeid', 'n'), $badge->getVar('linkid', 'n'), $old_imagetype);
				//if(is_file($old_imagepath)) jieqi_delfile($old_imagepath);
				//保存图片
				$imagefile=jieqi_uploadpath($jieqiConfigs['badge']['imagedir'], 'badge');
				if (!file_exists($retdir)) jieqi_createdir($imagefile);
				$imagefile.='/'.$_REQUEST['btypeid'];
				if (!file_exists($retdir)) jieqi_createdir($imagefile);
				$imagefile.=jieqi_getsubdir($_POST['linkid']);
				if (!file_exists($retdir)) jieqi_createdir($imagefile);
				$imagefile.='/'.$_POST['linkid'].$image_postfix;
				@move_uploaded_file($_FILES['badgeimage']['tmp_name'], $imagefile);
				@chmod($imagefile, 0777);
			}
			jieqi_jumppage($jieqiModules['group']['url'].'/man.php?g='.$gid.'&set=badge', LANG_DO_SUCCESS, $jieqiLang['badge']['badge_edit_success']);		   
		}else{
			jieqi_printfail($errtext);
		}   
	}
	$jieqiTpl->setCaching(0);		
	return	$jieqiTpl->fetch($jieqiModules['group']['path'].'/templates/set_badge.html');
}

function set_log(){
	global $gid;
	global $jieqiTpl,$jieqiModules,$groupUserfile;
	global $group;
		if(file_exists($groupUserfile['picdir'].'face.jpg')){
		   $picurl = $groupUserfile['picurl'].'face.jpg';
		}else {
		   $picurl = $groupUserfile['defaultpic'];
		};
    $jieqiTpl->assign('setlog_href','./setlog.php?g='.$gid);	
	$jieqiTpl->assign('js_path',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/include/jieqidialog.js');
    $jieqiTpl->assign('imgsrc',$picurl);
	$jieqiTpl->setCaching(0);
	return	$jieqiTpl->fetch($jieqiModules['group']['path'].'/templates/set_log.html');
}

function set_tpl(){
	global $gid;
	global $jieqiTpl,$jieqiModules;
	global $group;
	global $gset;
    $jieqiTpl->assign('settpl_href','./settpl.php?g='.$gid);	
	$d = dir(JIEQI_ROOT_PATH.'/themes/');
	$modules = array();
	$k = 0;
	while($module = $d->read() ){
		if($module != '.' && $module != '..'){
			$modules[$k]['dir'] = $module;
			if(GTHEME == $module ){
				$modules[$k]['checked'] = "checked";
			}
		}
		$k++;
	}
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('modules',$modules);
	return	$jieqiTpl->fetch($jieqiModules['group']['path'].'/templates/set_tpl.html');
}

$jieqiTpl->assign('content_man',$content_man);
$content = $content_man; 
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents',$content);
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>