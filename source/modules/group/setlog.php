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
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
jieqi_loadlang('setlog',JIEQI_MODULE_NAME);
jieqi_loadlang('setlog',JIEQI_MODULE_NAME);
//包含区块参数
jieqi_getconfigs('group', 'createblocks','jieqiBlocks');


//query user is whether a manager or not
//.....


//up group face
jieqi_checklogin();
$gid = intval($_REQUEST['g']);
include_once("./include/functions.php");
setpower($gid);

if( $allowmanbasic!=1){
   jieqi_printfail($jieqiLang['g']['have_no_power']);
}



if($_REQUEST['doup']){
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/include/functions.php');
	if($_FILES['gpic']['name']){
		$pic = $_FILES['gpic'];
           if(checkPic($pic,$prosize=2094000) !== 'ok'){
		    jieqi_printfail( checkPic($pic,209400) );
		    exit;
		}

		if(image_resize($pic['tmp_name'],$groupUserfile['picdir'].'face.jpg') ){
			jieqi_jumppage("man.php?g=$gid&set=log",LANG_DO_SUCCESS,$jieqiLang['g']['set_success']);
		}
	}
}

?>