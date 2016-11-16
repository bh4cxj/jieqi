<?php 
/**
 * 获得url路径相关函数
 *
 * 获得url路径相关函数
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: funuser.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * 用户信息相关url
 * 
 * @param      int         $id 用户id
 * @param      string      $type 页面类型 'info' - 个人信息页, 'space' - 个人空间页(默认)
 * @access     public
 * @return     string
 */
function jieqi_url_system_user($id, $type=''){
	global $jieqiModules;
	switch($type){
		case 'info':
			return JIEQI_USER_URL.'/userinfo.php?id='.$id;
			break;
		case 'page':
			return JIEQI_USER_URL.'/userpage.php?uid='.$id;
			break;
		case 'space':
		default:
			return !empty($jieqiModules['space']['publish']) ? $jieqiModules['space']['url'].'/space.php?uid='.$id : JIEQI_USER_URL.'/userpage.php?uid='.$id;
			break;
	}
}

/**
 * 返回用户头像图片url
 * 
 * @param      int         $uid 用户id
 * @param      int         $size 返回类型 'd'=>图片目录， 'l'=>大图(默认)， 's'=>小图, 'i'=>图标, 'a'=>返回前面几个合并的数组
 * @param      int         $type 图片类型 -1 系统自动判断，0 无头像 1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp'
 * @param      bool        $retdft 无头像是否返回默认头像地址，true-是（默认），false-否
 * @access     public
 * @return     mixed
 */
function jieqi_url_system_avatar($uid, $size = 'l', $type = -1, $retdft = true){
	global $jieqiConfigs;
	global $jieqi_image_type;
	if(!isset($jieqiConfigs['system'])) jieqi_getconfigs('system', 'configs');
	if(empty($jieqi_image_type)) $jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
	$base_avatar = '';
	if($uid == 0 || $type == 0 || ($type > 0 && !isset($jieqi_image_type[$type]))){
		if($retdft){
			$base_avatar = JIEQI_USER_URL.'/images';
			$type = 2;
			$uid = 'noavatar';
		}else{
			return false;
		}
	}elseif($type < 0){
		return JIEQI_USER_URL.'/avatar.php?uid='.$uid.'&size='.$size;
		//如果有启用裁剪功能，统一头像图片 .jpg，否则没有赋值头像类型就用程序输出
		//if(function_exists('gd_info') && $jieqiConfigs['system']['avatarcut']) $type = 2;
		//else return JIEQI_USER_URL.'/avatar.php?uid='.$uid.'&size='.$size;
	}
	$prefix = $jieqi_image_type[$type];
	if(empty($base_avatar)) $base_avatar = jieqi_uploadurl($jieqiConfigs['system']['avatardir'], $jieqiConfigs['system']['avatarurl'], 'system').jieqi_getsubdir($uid);
	switch($size){
		case 'd':
			return $base_avatar;
			break;
		case 'l':
			return $base_avatar.'/'.$uid.$prefix;
			break;
		case 's':
			return $base_avatar.'/'.$uid.'s'.$prefix;
			break;
		case 'i':
			return $base_avatar.'/'.$uid.'i'.$prefix;
			break;
		case 'a':
		default:
			return array('l'=>$base_avatar.'/'.$uid.$prefix, 's'=>$base_avatar.'/'.$uid.'s'.$prefix, 'i'=>$base_avatar.'/'.$uid.'i'.$prefix, 'd'=>$base_avatar);
			break;
	}

}


/**
 * 返回PATH_INFO伪静态URL
 * 
 * @param      string      $url 默认的动态url
 * @param      string      $prefix 伪静态地址后缀，如 .html，默认为空
 * @access     public
 * @return     string
 */
function jieqi_url_system_pathinfo($url, $prefix=''){
	if(!in_array($prefix, array('.html', '.htm'))) $prefix='';
	$pos=strpos($url, '?');
	if($pos > 0){
		$parmary = explode('&', substr($url, $pos+1));
		$pstr='';
		foreach($parmary as $v){
			$tmpary = explode('=', $v);
			if(isset($tmpary[1])) $pstr.='/'.$tmpary[0].'/'.$tmpary[1];
		}
		return substr($url, 0, $pos).$pstr.$prefix;
	}else{
		return $url;
	}
}

?>