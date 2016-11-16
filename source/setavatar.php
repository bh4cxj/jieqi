<?php 
/**
 * 设置头像
 *
 * 上传用户头像图片，支持缩放和裁剪（GD库）
 * 
 * 调用模板：/templates/setavatar.html;/templates/cutavatar.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: setavatar.php 326 2009-02-04 00:26:22Z juny $
 */

//avatar 0-无头像 1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp'
define('JIEQI_MODULE_NAME', 'system');
require_once('global.php');
jieqi_checklogin();
jieqi_loadlang('users', JIEQI_MODULE_NAME);
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
$jieqiUsers = $users_handler->get($_SESSION['jieqiUserId']);
if(!$jieqiUsers) jieqi_printfail(LANG_NO_USER);
jieqi_getconfigs('system', 'configs');

$jieqiConfigs['system']['avatardt'] = '.jpg';
$jieqiConfigs['system']['avatardw'] = '120';
$jieqiConfigs['system']['avatardh'] = '120';
$jieqiConfigs['system']['avatarsw'] = '48';
$jieqiConfigs['system']['avatarsh'] = '48';
$jieqiConfigs['system']['avatariw'] = '16';
$jieqiConfigs['system']['avatarih'] = '16';

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';
switch ( $_REQUEST['action'] ) {
	case 'cutsave':
		$old_avatar=$jieqiUsers->getVar('avatar','n');
		$basedir=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system').jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
		$newfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').$jieqiConfigs['system']['avatardt'];
		$smallfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'s'.$jieqiConfigs['system']['avatardt'];
		$iconfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'i'.$jieqiConfigs['system']['avatardt'];
		//$oldfile=$basedir.'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'];
		$uptmp = (strlen(ini_get('upload_tmp_dir')) > 0) ? ini_get('upload_tmp_dir') : ((strlen($_ENV['TEMP']) > 0) ? $_ENV['TEMP'] : ((strlen($_ENV['TMP']) > 0) ? $_ENV['TMP'] : ((strtolower(substr(PHP_OS, 0, 3)) == 'win') ? 'C:/WINDOWS/TEMP' : '/tmp')));
		$oldfile=$uptmp.'/'.$_SESSION['jieqiUserId'].'_tmp'.$jieqiConfigs['system']['avatardt'];

		if(is_file($oldfile)){
			if($old_avatar > 0 && isset($jieqi_image_type[$old_avatar])){
				$old_imagefile=$basedir.'/'.$jieqiUsers->getVar('uid','n').$jieqi_image_type[$old_avatar];
				jieqi_delfile($old_imagefile);
			}
			$posary=explode(',', $_REQUEST['cut_pos']);
			foreach($posary as $k=>$v) $posary[$k]=intval($v);
			include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
			$imgresize = new ImageResize();
			$imgresize->load($oldfile);
			if($posary[2]>0 && $posary[3]>0) $imgresize->resize($posary[2], $posary[3]);
			$imgresize->cut($jieqiConfigs['system']['avatardw'], $jieqiConfigs['system']['avatardh'], intval($posary[0]), intval($posary[1]));
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save);
			jieqi_copyfile($tmp_save, $newfile, 0777, true);
			
			$imgresize->resize($jieqiConfigs['system']['avatarsw'], $jieqiConfigs['system']['avatarsh']);
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].'s'.$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save);
			jieqi_copyfile($tmp_save, $smallfile, 0777, true);
			
			$imgresize->resize($jieqiConfigs['system']['avatariw'], $jieqiConfigs['system']['avatarih']);
			$tmp_save = $uptmp.'/'.$_SESSION['jieqiUserId'].'i'.$jieqiConfigs['system']['avatardt'];
			$imgresize->save($tmp_save, true);
			jieqi_copyfile($tmp_save, $iconfile, 0777, true);
			
			jieqi_delfile($oldfile);

			$image_type=0;
			$image_postfix=$jieqiConfigs['system']['avatardt'];
			foreach($jieqi_image_type as $k=>$v){
				if($image_postfix == $v){
					$image_type=$k;
					break;
				}
			}
			$old_avatar=$jieqiUsers->getVar('avatar','n');
			$jieqiUsers->unsetNew();
			$jieqiUsers->setVar('avatar',$image_type);
			if (!$users_handler->insert($jieqiUsers)) jieqi_printfail($jieqiLang['system']['avatar_set_failure']);
			else jieqi_jumppage(JIEQI_URL.'/setavatar.php', LANG_DO_SUCCESS, $jieqiLang['system']['avatar_set_success']);

		}else{
			jieqi_printfail($jieqiLang['system']['avatar_set_failure']);
		}
		break;
	case 'cutavatar':
		jieqi_getconfigs('system', 'userblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');
		//$base_avatar = jieqi_uploadurl($jieqiConfigs['system']['avatardir'], $jieqiConfigs['system']['avatarurl'], 'system').jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
		//$url_avatar=$base_avatar.'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'].'?'.JIEQI_NOW_TIME;
		//$jieqiTpl->assign('base_avatar', $base_avatar);
		//$jieqiTpl->assign('url_avatar', $url_avatar);

		$jieqiTpl->assign('url_avatar', JIEQI_URL.'/tmpavatar.php?time='.JIEQI_NOW_TIME);
		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/cutavatar.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
	case 'upload':
		//上传头像
		$errtext='';
		if (empty($_FILES['avatarimage']['name'])) $errtext .= $jieqiLang['system']['need_avatar_image'].'<br />';

		//检查格式及大小
		$image_postfix='';
		if (!empty($_FILES['avatarimage']['name'])){
			if($_FILES['avatarimage']['error'] > 0) $errtext = $jieqiLang['system']['avatar_upload_failure'];
			else{
				$image_postfix = strrchr(trim(strtolower($_FILES['avatarimage']['name'])),".");
				if(eregi("\.(gif|jpg|jpeg|png|bmp)$",$_FILES['avatarimage']['name'])){
					$typeary=explode(' ',trim($jieqiConfigs['system']['avatartype']));
					foreach($typeary as $k=>$v){
						if(substr($v,0,1) != '.') $typeary[$k]='.'.$typeary[$k];
					}
					if(!in_array($image_postfix, $typeary)) $errtext .= sprintf($jieqiLang['system']['avatar_type_error'], $jieqiConfigs['system']['avatartype']).'<br />';

					if($_FILES['avatarimage']['size'] > (intval($jieqiConfigs['system']['avatarsize']) * 1024)) $errtext .=sprintf($jieqiLang['system']['avatar_filesize_toolarge'], intval($jieqiConfigs['system']['avatarsize'])).'<br />';

				}else{
					$errtext .= sprintf($jieqiLang['system']['avatar_not_image'], $_FILES['avatarimage']['name']).'<br />';
				}
				if(!empty($errtext)) jieqi_delfile($_FILES['avatarimage']['tmp_name']);
			}
		}else{
			$errtext = $jieqiLang['system']['avatar_need_upload'];
		}
		//更新头像
		if(empty($errtext)) {
			if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']){
				//上传后需要裁剪
				//保存临时图片
				if (!empty($_FILES['avatarimage']['name'])){
					//$imagefile=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
					//if (!file_exists($retdir)) jieqi_createdir($imagefile);
					//$imagefile.=jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
					//if (!file_exists($retdir)) jieqi_createdir($imagefile);

					$tmpfile = dirname($_FILES['avatarimage']['tmp_name']).'/tmp_'.$_FILES['avatarimage']['name'];

					//$tmpfile=$imagefile.'/tmp_'.$_FILES['avatarimage']['name'];
					@move_uploaded_file($_FILES['avatarimage']['tmp_name'], $tmpfile);
					//默认转换成jpg
					$imagefile=dirname($_FILES['avatarimage']['tmp_name']).'/'.$jieqiUsers->getVar('uid','n').'_tmp'.$jieqiConfigs['system']['avatardt'];
					include_once(JIEQI_ROOT_PATH.'/lib/image/imageresize.php');
					$imgresize = new ImageResize();
					$imgresize->load($tmpfile);
					$imgresize->save($imagefile, true, substr(strrchr(trim(strtolower($imagefile)),"."), 1));
					@chmod($imagefile, 0777);
					jieqi_delfile($tmpfile);
				}
				header('Location: '.JIEQI_URL.'/setavatar.php?action=cutavatar');
			}else{
				//直接保存
				$image_type=0;
				foreach($jieqi_image_type as $k=>$v){
					if($image_postfix == $v){
						$image_type=$k;
						break;
					}
				}
				$old_avatar=$jieqiUsers->getVar('avatar','n');
				$jieqiUsers->unsetNew();
				$jieqiUsers->setVar('avatar',$image_type);
				if (!$users_handler->insert($jieqiUsers)) jieqi_printfail($jieqiLang['system']['avatar_set_failure']);
				else {
					//<!--jieqi insert license check-->
					//保存图片
					if (!empty($_FILES['avatarimage']['name'])){
						$imagefile=jieqi_uploadpath($jieqiConfigs['system']['avatardir'], 'system');
						if (!file_exists($retdir)) jieqi_createdir($imagefile);
						$imagefile.=jieqi_getsubdir($jieqiUsers->getVar('uid','n'));
						if (!file_exists($retdir)) jieqi_createdir($imagefile);
						if($old_avatar > 0 && isset($jieqi_image_type[$old_avatar])){
							$old_imagefile=$imagefile.'/'.$jieqiUsers->getVar('uid','n').$jieqi_image_type[$old_avatar];
							if(is_file($old_imagefile)) jieqi_delfile($old_imagefile);
						}
						$imagefile.='/'.$jieqiUsers->getVar('uid','n').$image_postfix;
						jieqi_copyfile($_FILES['avatarimage']['tmp_name'], $imagefile, 0777, true);
					}
					jieqi_jumppage(JIEQI_URL.'/setavatar.php', LANG_DO_SUCCESS, $jieqiLang['system']['avatar_set_success']);
				}
			}
		} else {
			jieqi_printfail($errtext);
		}
		break;
	case 'show':
	default:
		//显示头像状态，包含区块参数(定制区块)
		jieqi_getconfigs('system', 'userblocks', 'jieqiBlocks');
		include_once(JIEQI_ROOT_PATH.'/header.php');

		$avatartype=intval($jieqiUsers->getVar('avatar','n'));
		$avatarimg='';
		if(isset($jieqi_image_type[$avatartype])){
			$urls = jieqi_geturl('system', 'avatar', $jieqiUsers->getVar('uid','n'), 'a', $avatartype);
			if(is_array($urls)){
				$jieqiTpl->assign('base_avatar', $urls['d']);
				$jieqiTpl->assign('url_avatar', $urls['l']);
				$jieqiTpl->assign('url_avatars', $urls['s']);
				$jieqiTpl->assign('url_avatari', $urls['i']);
			}
		}
		$jieqiTpl->assign('avatartype', $avatartype);
		$jieqiTpl->assign('need_imagetype', $jieqiConfigs['system']['avatartype']);
		$jieqiTpl->assign('max_imagesize', $jieqiConfigs['system']['avatarsize']);

		$jieqiTpl->assign('avatartype', $avatartype);
		if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']) $jieqiTpl->assign('avatarcut', 1);
		else $jieqiTpl->assign('avatarcut', 0);

		$jieqiTpl->setCaching(0);
		$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/setavatar.html';
		include_once(JIEQI_ROOT_PATH.'/footer.php');
		break;
}

?>