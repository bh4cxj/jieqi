<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------

/**
 *  网站首页
 */

//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');


jieqi_loadlang('upface',JIEQI_MODULE_NAME);

include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');


//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');

//query user whether a manager or not
//.....
//检查权限
include_once("include/functions.php");
setpower($gid);

if(!$admingid){
	jieqi_printfail($jieqiLang['g']['create_group_no']);
}
//up group face
if($_REQUEST['doup']){
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	if($_FILES['gpic']['name']){
		$pic = $_FILES['gpic'];
                if(checkPic($pic,$prosize=209400) !== 'ok'){
		    echo checkPic($pic,209400);
		    exit;
		}	
		
		if(image_resize($pic['tmp_name'],$groupUserfile['picdir'].'face.jpg') ){
			jieqi_jumppage('./regsuc.php?g='.$gid,LANG_DO_SUCCESS,$jieqiLang['g']['create_group_success']);
		}
	}
}
$jieqiTpl->setCaching(0);
		if(file_exists($groupUserfile['picdir'].'face.jpg')){
		   $picurl = $groupUserfile['picurl'].'face.jpg';
		}else {
		   $picurl = $groupUserfile['defaultpic'];
		};
$jieqiTpl->assign('imgsrc',$picurl);
$jieqiTpl->assign('jumppage','./regsuc.php?g='.$gid);
$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/upface.html') );

include_once(JIEQI_ROOT_PATH.'/footer.php');
?>