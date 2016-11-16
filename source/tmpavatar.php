<?php
/**
 * 输出临时上传目录里面的图片
 *
 * 输出临时上传目录里面的图片
 * 
 * 无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: tmpavatar.php 320 2009-01-13 05:51:02Z juny $
 */
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
if(jieqi_checklogin(true)){
	jieqi_getconfigs('system', 'configs');
	$jieqiConfigs['system']['avatardt'] = '.jpg';
	$jieqiConfigs['system']['avatardw'] = '120';
	$jieqiConfigs['system']['avatardh'] = '120';
	$jieqiConfigs['system']['avatarsw'] = '48';
	$jieqiConfigs['system']['avatarsh'] = '48';
	$jieqiConfigs['system']['avatariw'] = '16';
	$jieqiConfigs['system']['avatarih'] = '16';

	$uptmp = (strlen(ini_get('upload_tmp_dir')) > 0) ? ini_get('upload_tmp_dir') : ((strlen($_ENV['TEMP']) > 0) ? $_ENV['TEMP'] : ((strlen($_ENV['TMP']) > 0) ? $_ENV['TMP'] : ((strtolower(substr(PHP_OS, 0, 3)) == 'win') ? 'C:/WINDOWS/TEMP' : '/tmp')));
	$file = $uptmp.'/'.$_SESSION['jieqiUserId'].'_tmp'.$jieqiConfigs['system']['avatardt'];
	if(is_file($file)){
		$prefix = substr(strrchr(trim(strtolower($file)),"."), 1);
		switch($prefix){
			case 'jpg':
			case 'jpeg':
				header("Content-type: image/jpeg");
				break;
			case 'gif':
				header("Content-type: image/gif");
				break;
			case 'png':
				header("Content-type: image/png");
				break;
			case 'bmp':
				header("Content-type: image/bmp");
				break;
			default:
				exit;
		}
		echo file_get_contents($file);
	}
}
?>