<?php
define('JIEQI_MODULE_NAME','space');
require_once('../../../../global.php');
include_once($jieqiModules['space']['path'].'/spaceheader.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
//获得上传文件目录
		$attachdir = jieqi_uploadpath($jieqiConfigs['space']['attachdir'], 'space');
		if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);
		$attachdir .= '/image'.jieqi_getsubdir($_REQUEST['uid']).'/';
		if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);			
		$attachdir .= $_REQUEST['uid'];
		if (!file_exists($attachdir)) jieqi_createdir($attachdir, 0777, true);		
//获得上传文件目录结束
//echo $attachdir;
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0 || JIEQI_LOCAL_URL!='http://'.$_SERVER['HTTP_HOST']) {
		//echo "There was a problem with the upload";
		exit(0);
	} else {
			//附件入库
				include_once($jieqiModules['space']['path'].'/class/attachs.php');
				$attachs_handler =& JieqiAttachsHandler::getInstance('JieqiAttachsHandler');
					$newAttach = $attachs_handler->create();
					$newAttach->setVar('catid', $_REQUEST['catid']);
					$newAttach->setVar('uid', $_REQUEST['uid']);
					$newAttach->setVar('name', $_FILES["Filedata"]['name']);
					$newAttach->setVar('class', 'image');
					$tmpary=explode('.', $_FILES["Filedata"]['name']);
					$tmpint=count($tmpary)-1;
					$tmpary[$tmpint]=strtolower(trim($tmpary[$tmpint]));
					$newAttach->setVar('postfix', $tmpary[$tmpint]);
					$newAttach->setVar('size', $_FILES["Filedata"]['size']);
					$newAttach->setVar('filebak', $_FILES["Filedata"]['name']);
					$newAttach->setVar('url', $url);
					$newAttach->setVar('uptime', time());
					$attachs_handler->insert($newAttach);
					$attachid=$newAttach->getVar('attachid');
					$url = '/files/space/'.$jieqiConfigs['space']['attachdir'].'/image'.jieqi_getsubdir($_REQUEST['uid']).'/'.$_REQUEST['uid'].'/b_'.$attachid.'.'.$tmpary[$tmpint];
				    $attachs_handler->db->query("UPDATE ".jieqi_dbprefix('space_attachs')." SET url='".$url."' WHERE attachid=".$attachid);
	//保存图片至指定目录
	$attach_save_path = $attachdir.'/b_'.$attachid.'.'.$tmpary[$tmpint];
	@move_uploaded_file($_FILES["Filedata"]["tmp_name"], $attach_save_path);
	
	//更新相册的相关信息
	space_edit_album($attachs_handler,$_REQUEST['catid'],'../../');
	
/*$h=fopen('a.txt','w+');
$log=print_r($_FILES,true).' '.$attach_save_path;
if(file_exists($_FILES["Filedata"]["tmp_name"])) $log.='['.filesize($_FILES["Filedata"]["tmp_name"]).']';
else $log.=' no file';
fwrite($h,$log);
fclose($h);*/

	//图片缩略图
	if(is_file($attach_save_path)){
	    include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
    	$imgresize = new ImageResize();
    	$imgresize->load($attach_save_path);
    	$imgresize->resize(150, 120); //缩放
    	$newfile = $attachdir.'/'.$attachid.'.'.$tmpary[$tmpint];
    	$imgresize->save($newfile, true);
	}	
	
	//判断是否加水印
	$make_image_water = false;
	if(function_exists('gd_info') && $jieqiConfigs['space']['attachwater'] > 0){
		if(strpos($jieqiConfigs['space']['attachwimage'], '/')===false && strpos($jieqiConfigs['space']['attachwimage'], '\\')===false) $water_image_file = '../../templates/images/'.$jieqiConfigs['space']['attachwimage'];
		else $water_image_file = $jieqiConfigs['space']['attachwimage'];
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
		$img->wm_image_pos = $jieqiConfigs['space']['attachwater'];
		$img->wm_image_name = $water_image_file;
		$img->wm_image_transition  = $jieqiConfigs['space']['attachwtrans'];
		$img->jpeg_quality = $jieqiConfigs['space']['attachwquality'];
		$img->create($attach_save_path);
		unset($img);
	}
	@chmod($attach_save_path, 0777);	
		
      echo 'uploadSuccess';
	}
?>