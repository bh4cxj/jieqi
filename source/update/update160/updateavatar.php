<?php
@set_time_limit(0);
header('Content-type:text/html;charset=gb2312');
if(function_exists('gd_info')) $usegd=true;
else $usegd=false;
if(!$usegd){
	echo '本程序检查所有头像图片，并裁剪成统一大小。<br>图片裁剪需要GD库支持，当前系统未安装GD库，请安装后再执行本程序！';
	exit;
}
if($_GET['confirm'] != 1){
	echo '<font color="red">注意：</font><br>本程序检查所有头像图片，并裁剪成统一大小。<br>图片裁剪需要GD库支持(已支持)<br><br><a href="'.basename($_SERVER['PHP_SELF']).'?confirm=1">点击这里开始更新头像图片</a><br><br>';
	exit;
}
echo '                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          ';
echo '开始转换头像数据，请耐心等待...<br>';
ob_flush();
flush();
require_once('../../global.php');
include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
jieqi_getconfigs('system', 'configs');
if(empty($jieqiConfigs['system']['avatardir'])) $jieqiConfigs['system']['avatardir']='avatar';
$dirname = jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
update_avatar($dirname);
echo '<br><font color="red">恭喜您，全部数据转换完成！</font>';
function  update_avatar($dirname){
	$handle = opendir($dirname);
	while (($file = @readdir($handle)) !== false) {
		if($file != '.' && $file != '..' && $file != '.svn'){
			if (is_dir($dirname . '/' . $file)){
				update_avatar($dirname . '/' . $file);
			}elseif(preg_match('/^\d+\.(jpg|jpeg|gif|bmp)$/is', $file)){
				$id = intval($file);
				if(!is_file($dirname.'/'.$id.'s.jpg') || !is_file($dirname.'/'.$id.'i.jpg')){
					$filename = $dirname . '/' . $file;
					list($width, $height, $type, $attr) = getimagesize($filename);
					$neww = $width;
					$newh = $height;
					if($neww >= $newh && $newh > 120){
						$neww = floor(($neww * 120) / $newh);
						$newh = 120;
					}
					if($newh >= $neww && $neww > 120){
						$newh = floor(($newh * 120) / $neww);
						$neww = 120;
					}

					$imgresize = new ImageResize();
					$imgresize->load($filename);
					jieqi_delfile($filename);
					$imgresize->resize($neww, $newh);
					$imgresize->cut(120, 120, 0, 0);
					$imgresize->save($dirname.'/'.$id.'.jpg', false);
					$imgresize->resize(48, 48);
					$imgresize->save($dirname.'/'.$id.'s.jpg', false);
					$imgresize->resize(16, 16);
					$imgresize->save($dirname.'/'.$id.'i.jpg', true);
					unset($imgresize);
					echo '. ';
					ob_flush();
					flush();
				}
			}
		}
	}
	@closedir($handle);
	return true;
}
?>