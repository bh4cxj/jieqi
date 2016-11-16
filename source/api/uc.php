<?php

define('IN_DISCUZ', TRUE);

define('UC_CLIENT_VERSION', '1.5.0'); //note UCenter 版本标识
define('UC_CLIENT_RELEASE', '20081212');

define('API_DELETEUSER', 0);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 0);		//note 用户改名 API 接口开关
define('API_GETTAG', 0);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 0);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 0);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 0);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 0);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 0);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 0);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 0);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 0);	//note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('JIEQI_NEED_SESSION', 1);
require_once(dirname(dirname(__FILE__)).'/global.php');
include_once JIEQI_ROOT_PATH.'/uc_client/config.inc.php';
jieqi_includedb();
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
include_once(JIEQI_ROOT_PATH.'/class/users.php');
$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');

define('DISCUZ_ROOT', substr(dirname(__FILE__), 0, -3));

//通过常量 IN_UC 来判断接口文件是通过远程 HTTP 方式访问还是直接包含方式调用
if(!defined('IN_UC')) {
	//使用http访问方式
	@error_reporting(0);
	@set_magic_quotes_runtime(0);

	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}

	$timestamp = time();
	if(empty($get)) {
		exit('Invalid Request');
	} elseif($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	$action = $get['action'];

	require_once JIEQI_ROOT_PATH.'/uc_client/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}

}else{

}

class uc_note {
	var $query;
	var $userhandler;
	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once JIEQI_ROOT_PATH.'/uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->query =& $GLOBALS['query'];
		$this->userhandler =& $GLOBALS['users_handler'];
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function _checkids($ids, $separator = ','){
		$idary = explode($separator, $ids);
		if(!is_array($idary)) $idary = array();
		$ids ='';
		foreach ($idary as $id){
			$id = trim($id);
			if(is_numeric($id)){
				if($ids != '') $ids .= $separator;
				$ids .= intval($id);
			}
		}
		return $ids;
	}

	function deleteuser($get, $post) {
		if(!API_DELETEUSER) return API_RETURN_FORBIDDEN;
		$uids = $this->_checkids($get['ids']);
		if(strlen($uids) == 0) return API_RETURN_FAILED;

		$sql = "DELETE FROM ".jieqi_dbprefix('system_users')." WHERE uid IN (".$uids.")";
		$ret = $this->query->execute($sql);
		if($ret === false) return API_RETURN_FAILED;
		else return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		if(!API_RENAMEUSER) return API_RETURN_FORBIDDEN;
		$uid = intval($get['uid']);
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if($uid <= 0) return API_RETURN_FAILED;

		$sql = "UPDATE ".jieqi_dbprefix('system_users')." SET uname = '".jieqi_dbslashes($usernamenew)."' WHERE uname = '".jieqi_dbslashes($usernameold)."'";
		$ret = $this->query->execute($sql);
		if($ret === false) return API_RETURN_FAILED;
		else return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		//if(!API_GETTAG) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function synlogin($get, $post) {
		if(!API_SYNLOGIN) return API_RETURN_FORBIDDEN;

		$uid = intval($get['uid']);
		$username = $get['username'];

		$jieqiUsers = $this->userhandler->getByname($username);
		if(!is_object($jieqiUsers)){
			//用户不存在自动注册
			include_once(JIEQI_ROOT_PATH.'/uc_client/client.php');
			if($data = uc_get_user($username)) {
				list($uid, $username, $email) = $data;
				//检查email是否重复
				if($this->userhandler->getCount(new Criteria('email', $email, '=')) > 0){
					return API_RETURN_FAILED;
				}else{
					//注册新用户
					include_once(JIEQI_ROOT_PATH.'/lib/text/textfunction.php');
					global $jieqiConfigs;
					jieqi_getconfigs('system', 'configs');
					$jieqiUsers = $this->userhandler->create();
					$jieqiUsers->setVar('siteid', JIEQI_SITE_ID);
					$jieqiUsers->setVar('uname', $username);
					$jieqiUsers->setVar('name', $username);
					$jieqiUsers->setVar('pass', '');
					$jieqiUsers->setVar('groupid', JIEQI_GROUP_USER);
					$jieqiUsers->setVar('regdate', JIEQI_NOW_TIME);
					$jieqiUsers->setVar('initial', jieqi_getinitial($username));
					$jieqiUsers->setVar('sex', 0);
					$jieqiUsers->setVar('email', $email);
					$jieqiUsers->setVar('lastlogin', JIEQI_NOW_TIME);
					$jieqiUsers->setVar('experience', $jieqiConfigs['system']['scoreregister']);
					$jieqiUsers->setVar('score', $jieqiConfigs['system']['scoreregister']);
					$jieqiUsers->setVar('setting', '');
					if(!$this->userhandler->insert($jieqiUsers)) return API_RETURN_FAILED;
				}
			}else{
				return API_RETURN_FAILED;
			}
		}
		if(is_object($jieqiUsers)){
			//header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
			include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
			jieqi_loginprocess($jieqiUsers);
			return API_RETURN_SUCCEED;
		}else{
			return API_RETURN_FAILED;
		}
	}

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) return API_RETURN_FORBIDDEN;

		//header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		include_once(JIEQI_ROOT_PATH.'/include/dologout.php');
		jieqi_dologout();
		return API_RETURN_SUCCEED;
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) return API_RETURN_FORBIDDEN;

		$username = $get['username'];
		$password = $get['password'];

		$encpass=$this->userhandler->encryptPass($password);

		$sql = "UPDATE ".jieqi_dbprefix('system_users')." SET pass = '".jieqi_dbslashes($encpass)."' WHERE uname = '".jieqi_dbslashes($username)."'";
		$ret = $this->query->execute($sql);
		if($ret === false) return API_RETURN_FAILED;
		else return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		//if(!API_UPDATEBADWORDS) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function updatehosts($get, $post) {
		//if(!API_UPDATEHOSTS) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function updateapps($get, $post) {
		//if(!API_UPDATEAPPS) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function updateclient($get, $post) {
		//if(!API_UPDATECLIENT) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function updatecredit($get, $post) {
		//if(!API_UPDATECREDIT) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function getcredit($get, $post) {
		//if(!API_GETCREDIT) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function getcreditsettings($get, $post) {
		//if(!API_GETCREDITSETTINGS) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}

	function updatecreditsettings($get, $post) {
		//if(!API_UPDATECREDITSETTINGS) return API_RETURN_FORBIDDEN;
		return API_RETURN_FORBIDDEN;
	}
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}
?>