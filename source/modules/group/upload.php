<?php
/**
 * 上传圈子照片
 *
 * 上传圈子照片
 * 
 * 调用模板：/modules/group/templates/upload.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//定义本页面所属区块
define('JIEQI_MODULE_NAME', 'group');
require_once('../../global.php');
jieqi_loadlang('upload',JIEQI_MODULE_NAME);
jieqi_getconfigs('group', 'topicblock', 'jieqiBlocks');
jieqi_getconfigs('group', 'configs');
include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/header.php');
include_once(JIEQI_ROOT_PATH."/modules/".JIEQI_MODULE_NAME.'/include/functions.php');
$form = array();
//准备显示变量
$form['maximagesize'] = ceil($jieqiConfigs['group']['maximagesize']/1024);
$form['kmaximagesize'] = $jieqiConfigs['group']['maximagesize'];
$form['albumid'] = intval($_REQUEST['albumid']);
$form['allowmanalbum'] = $allowmanalbum;
$form['posterid'] = $_SESSION['jieqiUserId'];
$form['poster'] = $_SESSION['jieqiUserName'];
//add new post 
if ($form['albumid'] && (isset($_FILES["Filedata"]) || is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] == 0) && $_REQUEST['action']=='upload' && JIEQI_LOCAL_URL=='http://'.$_SERVER['HTTP_HOST']) {

	//获得上传文件目录
	$photodir =  $groupUserfile['albumdir'];
	$photodir .= date('Ymd',time());
	if (!file_exists($photodir) ) {
		jieqi_createdir($photodir);
	}
	//获得上传文件目录结束
    //附件入库
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/photo.php');	
	$photo_handler = JieqiphotoHandler::getInstance('JieqiphotoHandler');
	$newphoto = $photo_handler->create();	
	$newphoto->setVar('albumid',$form['albumid']);
	$newphoto->setVar('gid',$gid);
	$newphoto->setVar('size',$_FILES["Filedata"]['size']);
	//$newphoto->setVar('class',$photosinfo[$key]['fclass']);
	$tmpary=explode('.', $_FILES["Filedata"]['name']);
	$tmpint=count($tmpary)-1;
	$tmpary[$tmpint]=strtolower(trim($tmpary[$tmpint]));
	
	$newphoto->setVar('postfix',$tmpary[$tmpint]);
	$newphoto->setVar('intro',$_FILES["Filedata"]['name']);
	$newphoto->setVar('uptime',time());
	$newphoto->setVar('posterid',$_REQUEST['posterid']);
	$newphoto->setVar('poster',$_REQUEST['poster']);
	$photo_handler->insert($newphoto);
	//附件入库结束
	
	//保存图片至指定目录
	$photosinfo[$key]['photoid'] = $newphoto->getVar('photoid');
	$attach_save_path = $photodir."/{$photosinfo[$key][photoid]}.{$tmpary[$tmpint]}";
	$newfile = $photodir."/re_{$photosinfo[$key][photoid]}.{$tmpary[$tmpint]}";
	move_uploaded_file($_FILES['Filedata']['tmp_name'],$attach_save_path);
	@chmod('0777',$attach_save_path);
	//if( !image_resize($attach_save_path,$newfile) ) {
	//	unlink($attach_save_path);	
	//} 
	
	//图片缩略图
	if(is_file($attach_save_path)){
	    include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
    	$imgresize = new ImageResize();
    	$imgresize->load($attach_save_path);
    	$imgresize->resize(150, 120); //缩放
    	//$newfile = $photodir."/re_{$photosinfo[$key][photoid]}.{$tmpary[$tmpint]}";
    	$imgresize->save($newfile, true);
	}	
	
	//判断是否加水印
	$make_image_water = false;
	if(function_exists('gd_info') && $jieqiConfigs['group']['attachwater'] > 0){
		if(strpos($jieqiConfigs['group']['attachwimage'], '/')===false && strpos($jieqiConfigs['group']['attachwimage'], '\\')===false) $water_image_file = $jieqiModules['group']['path'].'/templates/images/'.$jieqiConfigs['group']['attachwimage'];
		else $water_image_file = $jieqiConfigs['group']['attachwimage'];
		if(is_file($water_image_file)){
			$make_image_water = true;
			include_once(JIEQI_ROOT_PATH.'/lib/image/imagewater.php');
		}
	}
	//图片加水印
	if($make_image_water && eregi("\.(gif|jpg|jpeg|png)$",$attach_save_path)){
		$img = new ImageWater();
		$img->save_image_file = $attach_save_path;
		$img->codepage = JIEQI_SYSTEM_CHARSET;
		$img->wm_image_pos = $jieqiConfigs['group']['attachwater'];
		$img->wm_image_name = $water_image_file;
		$img->wm_image_transition  = $jieqiConfigs['group']['attachwtrans'];
		$img->jpeg_quality = $jieqiConfigs['group']['attachwquality'];
		$img->create($attach_save_path);
		unset($img);
	}
	@chmod($attach_save_path, 0777);	
	//相册更新
	$criteria = new CriteriaCompo('albumid',$form['albumid']);
	$lastphotoid = $newphoto->getVar('photoid');
	$lastuptime  = $newphoto->getVar('uptime');
	$lastpostfix = $newphoto->getVar('postfix');
	include_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/album.php');	
	$album_handler = JieqialbumHandler::getInstance('JieqialbumHandler');
	$criteria = new Criteria('albumid',$form['albumid']);
	$album_handler->updatefields("nums = nums+1,lastphotoid='$lastphotoid',lastuptime='$lastuptime',lastpostfix='$lastpostfix' ",$criteria);
	include_once("./include/functions.php");
	update_ginfo("gpics=gpics+$photonum",$gid);
	exit('uploadSuccess');
}
//权限检查
setpower($gid);
if($allowpostpic!=1) {
	jieqi_printfail($jieqiLang['g']['no_right_upload_pic']);
	exit();
}
include_once($groupUserfile['albumscat']);
$i=0;
foreach($albumscat as $albumid=>$albumname ){
	$image_cats[$i]= array('id'=>$albumid,'name'=>$albumname);
	$i++;
}
$jieqiTpl->assign('image_cats',$image_cats);

$jieqiTpl->assign('form',$form);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['group']['path'].'/templates/upload.html';			
require_once($jieqiModules['group']['path'].'/groupfooter.php');
?>