<?php
/**
 * 通用预处理文件
 *
 * 定义系统函数、变量，程序预处理
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: global.php 332 2009-02-23 09:15:08Z juny $
 */

$tmpvar = explode(' ', microtime());
define('JIEQI_START_TIME', $tmpvar[1] + $tmpvar[0]);
if(defined('JIEQI_PHP_CLI')) exit('error defined JIEQI_PHP_CLI');
if((!empty($_SERVER['SCRIPT_FILENAME']) && $_SERVER['SCRIPT_FILENAME'] == $_SERVER['argv'][0]) || (empty($_SERVER['SCRIPT_FILENAME']) && !empty($_SERVER['argv'][0]))) define('JIEQI_PHP_CLI', 1);
else define('JIEQI_PHP_CLI', 0);
if(defined('JIEQI_SCRIPT_FILENAME')) exit('error defined JIEQI_SCRIPT_FILENAME');
$tmpvar = (!empty($_SERVER['PATH_TRANSLATED']) && substr($_SERVER['PATH_TRANSLATED'],-4)=='.php') ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
define('JIEQI_SCRIPT_FILENAME', str_replace(array('\\\\','\\'),'/',$tmpvar));
if(!defined('JIEQI_SITE_ID')) define('JIEQI_SITE_ID', 0); //网站序号，0表示主站，大于0的表示分站
//包含系统全局参数
include_once('configs/define.php');
if(defined('JIEQI_LOCAL_HOST')) exit('error defined JIEQI_LOCAL_HOST');
if($_SERVER['HTTP_HOST'] == '' && JIEQI_URL != '') define('JIEQI_LOCAL_HOST', str_replace(array('http://', 'https://'), '', JIEQI_URL));
else define('JIEQI_LOCAL_HOST', $_SERVER['HTTP_HOST']);
$_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES);
define("JIEQI_NOW_TIME", time());  //系统时间
define("JIEQI_VERSION","1.60");  //系统版本
//Free(F), Popular(O), Standard(S), Professional(P), Enterprise(E), Deluxe(D), Custom(C)
//在认证文件里面授权
//define('JIEQI_VERSION_TYPE', 'Standard'); //版本类型
define('JIEQI_GLOBAL_INCLUDE',true);  //包含global标记
if(!defined('JIEQI_ROOT_PATH')) @define('JIEQI_ROOT_PATH', str_replace(array('\\\\','\\'),'/',dirname(__FILE__)));  //物理根路径

if(!defined('JIEQI_COOKIE_DOMAIN')) define('JIEQI_COOKIE_DOMAIN', strval(@ini_get('session.cookie_domain')));
elseif(JIEQI_COOKIE_DOMAIN != '') @ini_set('session.cookie_domain', JIEQI_COOKIE_DOMAIN);
define('JIEQI_SYSTEM_CHARSET', 'gbk'); //系统代码语言
//本机完整地址
if(JIEQI_URL == '') define('JIEQI_LOCAL_URL', 'http://'.$_SERVER['HTTP_HOST']);
else define('JIEQI_LOCAL_URL', JIEQI_URL);
//主服务器地址
if(!defined('JIEQI_MAIN_SERVER') || JIEQI_MAIN_SERVER == '') define('JIEQI_MAIN_URL', JIEQI_LOCAL_URL);
else define('JIEQI_MAIN_URL', JIEQI_MAIN_SERVER);
//用户入口地址
if(!defined('JIEQI_USER_ENTRY') || JIEQI_USER_ENTRY == '') define('JIEQI_USER_URL', JIEQI_LOCAL_URL);
else define('JIEQI_USER_URL', JIEQI_USER_ENTRY);

//错误模式
define('JIEQI_ERROR_RETURN', 1);  //只返回
define('JIEQI_ERROR_PRINT', 2);  //打印错误并继续
define('JIEQI_ERROR_DIE', 4);  //显示错误并停止

//默认用户组
define('JIEQI_GROUP_USER', 3);  //登录用户
define('JIEQI_GROUP_ADMIN', 2);  //系统管理员
define('JIEQI_GROUP_GUEST', 1);  //游客

// 区块位置
define("JIEQI_SIDEBLOCK_CUSTOM",-1);  //自定义
define("JIEQI_SIDEBLOCK_LEFT",0);  //左
define("JIEQI_SIDEBLOCK_RIGHT",1);  //右
define("JIEQI_CENTERBLOCK_LEFT",2);  //中左
define("JIEQI_CENTERBLOCK_RIGHT",3);  //中右
define("JIEQI_CENTERBLOCK_TOP",4);  //中上
define("JIEQI_CENTERBLOCK_MIDDLE",5);  //中中
define("JIEQI_CENTERBLOCK_BOTTOM",6);  //中下
define("JIEQI_TOPBLOCK_ALL",7);  //顶部
define("JIEQI_BOTTOMBLOCK_ALL",8);  //底部

//数据显示类型
define('JIEQI_TYPE_TXTBOX', 1);  //单行文本
define('JIEQI_TYPE_TXTAREA', 2);  //多行文本
define('JIEQI_TYPE_INT', 3);  //整数
define('JIEQI_TYPE_NUM', 4);  //数字
define('JIEQI_TYPE_PASSWORD', 5);  //密码
define('JIEQI_TYPE_HIDDEN', 6);  //隐藏域
define('JIEQI_TYPE_SELECT', 7);  //下拉单选
define('JIEQI_TYPE_MULSELECT', 8);  //下拉多选
define('JIEQI_TYPE_RADIO', 9);  //单选
define('JIEQI_TYPE_CHECKBOX', 10);  //多选
define('JIEQI_TYPE_LABEL', 11);  //文字文本
define('JIEQI_TYPE_FILE', 12);  //文件上传
define('JIEQI_TYPE_DATE', 13);  //日期
define('JIEQI_TYPE_UBB', 14);  //ubb代码
define('JIEQI_TYPE_HTML', 15);  //html代码
define('JIEQI_TYPE_CODE', 16);  //程序代码
define('JIEQI_TYPE_SCRIPT', 17);  //网页脚本javascript/vbscript
define('JIEQI_TYPE_OTHER', 20);  //其他

//打开窗口方式
define('JIEQI_TARGET_SELF', 'self'); //自身窗口
define('JIEQI_TARGET_NEW', 'blank'); //新开窗口
define('JIEQI_TARGET_TOP', 'top'); //弹出小窗口

//内容格式
define('JIEQI_CONTENT_TXT', 0); //文本
define('JIEQI_CONTENT_HTML', 1); //html
define('JIEQI_CONTENT_JS', 2); //js文件
define('JIEQI_CONTENT_MIX', 3); //html和script混合
define('JIEQI_CONTENT_PHP', 4); //php

//图片格式
$jieqi_image_type=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
//文件后缀名统一定义
$jieqi_file_postfix=array('txt'=>'.txt', 'html'=>'.html', 'htm'=>'.htm', 'xml'=>'.xml', 'php'=>'.php', 'js'=>'.js', 'css'=>'.css', 'zip'=>'.zip', 'jar'=>'.jar', 'jad'=>'.jad', 'umd'=>'.umd', 'opf'=>'.opf');
//编码类型
$jieqi_charset_type=array('gb'=>'gbk', 'gbk'=>'gbk', 'gb2312'=>'gbk', 'big5'=>'big5', 'utf8'=>'utf-8', 'utf-8'=>'utf-8');

//******************************************************
//预处理
//******************************************************

//php5的时区问题
//if(function_exists('date_default_timezone_set')) @date_default_timezone_set('PRC');

//外部引入资料不自动加反斜线溢出字元
@set_magic_quotes_runtime(0);
//错误显示模式
if(JIEQI_ERROR_MODE == 0){
	@ini_set('display_errors', 0);
	@error_reporting(0);
}elseif(JIEQI_ERROR_MODE == 1){
	@ini_set('display_errors', 1);
	@error_reporting(E_ALL & ~E_NOTICE);
}elseif(JIEQI_ERROR_MODE == 2){
	@ini_set('display_errors', 1);
	@error_reporting(E_ALL);
}

//显示版权信息
if(isset($_GET['show_jieqi_version']) && $_GET['show_jieqi_version'] == 1){
	echo '<html><head><meta http-equiv="Content-Type" content="text/html; charset='.JIEQI_SYSTEM_CHARSET.'" /><title>Version Info</title></head><body>Site name: '.JIEQI_SITE_NAME.'<br />URL: '.JIEQI_URL.'<br />Version: JIEQI CMS V'.JIEQI_VERSION.'<br />Powered by <a href="http://www.jieqi.com">JIEQI CMS</a><br /><br />License key:<br />'.JIEQI_LICENSE_KEY.'</body></html>';
	exit;
}
//检查访问许可(后台管理可以不检查)
//0-校验  1-授权域名 2-授权模块
if(defined('JIEQI_MODULE_VTYPE')) exit('error defined JIEQI_MODULE_VTYPE');
$jieqi_license_ary=jieqi_funtoarray('base64_decode', explode('@', JIEQI_LICENSE_KEY));
if(!empty($jieqi_license_ary[1]) && !empty($jieqi_license_ary[2]))	$jieqi_license_modules=jieqi_strtosary($jieqi_license_ary[2], '=', '|');
else $jieqi_license_modules=array();
$matchs=array();
if(empty($jieqi_license_modules) || (JIEQI_LOCAL_HOST == '' && JIEQI_PHP_CLI == 1 && ALLOW_PHP_CLI == 1) || preg_match('/^'.preg_quote(str_replace(array('\\\\','\\'),'/',JIEQI_ROOT_PATH), '/').'\/(admin|install|logout\.php)/is', JIEQI_SCRIPT_FILENAME) || preg_match('/^(http:\/\/|https:\/\/)?[^\/\?]*(localhost|127.0.0.1)/i', JIEQI_LOCAL_HOST, $matchs)){
	//不检查授权
}else{
	//检查域名授权
	$site_is_licensed=false;
	if (!empty($jieqi_license_ary[1]) && preg_match('/^(http:\/\/|https:\/\/)?[^\/\?]*('.$jieqi_license_ary[1].')/i', JIEQI_LOCAL_HOST, $matchs)){
		$jieqi_license_domain = $jieqi_license_ary[1];
		$tmpvar=md5($jieqi_license_ary[1].$jieqi_license_ary[2].'jnyzn090211');
		if($tmpvar[0]==$jieqi_license_ary[0][0] && $tmpvar[9]==$jieqi_license_ary[0][9] && $tmpvar[2]==$jieqi_license_ary[0][2] && $tmpvar[11]==$jieqi_license_ary[0][11]) $site_is_licensed=true;
	}
	//域名没有授权
	if(!$site_is_licensed){
		header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
		if(defined('JIEQI_IS_OPEN') && JIEQI_IS_OPEN == 0) echo JIEQI_CLOSE_INFO;
		else echo 'License check error!<br />Domain: '.JIEQI_LOCAL_HOST.'<br />Module: '.JIEQI_MODULE_NAME.'<br /><br />Powered by <a href="http://www.jieqi.com" target="_blank">JIEQI CMS</a>';
		exit;
	}
}
//设置系统和模块版本
if(isset($jieqi_license_modules[JIEQI_MODULE_NAME]) && isset($jieqi_license_modules['system'])){
	@define('JIEQI_VERSION_TYPE', $jieqi_license_modules['system']); //系统版本类型
	@define('JIEQI_MODULE_VTYPE', $jieqi_license_modules[JIEQI_MODULE_NAME]); //模块版本
}else{
	@define('JIEQI_VERSION_TYPE', 'ok');
	@define('JIEQI_MODULE_VTYPE', 'ok');
}

//是否启用PATH_INFO
if (isset($_SERVER['PATH_INFO']) && defined('JIEQI_PATH_INFO') && JIEQI_PATH_INFO > 0) {
	$tmpary = explode('/', str_replace(array("'", '"', '.htm', '.html'), '', substr($_SERVER['PATH_INFO'], 1)));
	$tmpcot = count($tmpary);
	for($i = 0; $i < $tmpcot; $i += 2){
		if(isset($tmpary[$i + 1]) && !is_numeric($tmpary[$i])){
			$_GET[$tmpary[$i]] = $tmpary[$i + 1];
			$_REQUEST[$tmpary[$i]] = $tmpary[$i + 1];
		}
	}
}

//设置模块路径
$jieqiModules = array();
include_once('configs/modules.php');
//本模块未开放
if(isset($jieqiModules[JIEQI_MODULE_NAME]['publish']) && $jieqiModules[JIEQI_MODULE_NAME]['publish']==0){
	header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
	echo 'This function is not valid!';
	jieqi_freeresource();
	exit;
}

foreach($jieqiModules as $k=>$v){
	if(strtolower(substr($k,0,3)) == 'wap'){
		$wapmod = true;
		$dirmod = substr($k,3);
	}else{
		$wapmod = false;
		$dirmod = $k;
	}
	if($v['dir']=='') $jieqiModules[$k]['dir'] = ($wapmod == true) ? (($k == 'wap') ? '/wap' : '/wap/'.$dirmod) : (($k == 'system') ? '' : '/modules/'.$dirmod);
	if($v['path']=='') $jieqiModules[$k]['path'] = JIEQI_ROOT_PATH.$jieqiModules[$k]['dir'];
	if($v['url']=='') $jieqiModules[$k]['url'] = JIEQI_LOCAL_URL.$jieqiModules[$k]['dir'];
	if($v['theme']=='') $jieqiModules[$k]['theme'] = JIEQI_THEME_SET;
	if(defined('JIEQI_MODULE_NAME') && JIEQI_MODULE_NAME == $k){
		if(!empty($jieqiModules[$k]['theme'])) @define('JIEQI_THEME_NAME', $jieqiModules[$k]['theme']);
	}
}
if(!defined('JIEQI_THEME_NAME')) define('JIEQI_THEME_NAME', JIEQI_THEME_SET);
if(isset($jieqiModules['wap']['path'])) define('JIEQI_WAP_PATH', $jieqiModules['wap']['path']);
else define('JIEQI_WAP_PATH', JIEQI_ROOT_PATH.'/wap');
if(isset($jieqiModules['wap']['url'])) define('JIEQI_WAP_URL', $jieqiModules['wap']['url']);
else define('JIEQI_WAP_URL', JIEQI_LOCAL_URL.'/wap');

//是否需要编码转换(免费版不支持)
if(defined('JIEQI_CHARSET_CONVERT') && JIEQI_CHARSET_CONVERT == 1 && JIEQI_VERSION_TYPE != '' && JIEQI_VERSION_TYPE != 'Free'){
	if(isset($_GET['charset'])) $_GET['charset']=strtolower($_GET['charset']);
	if(isset($_GET['charset']) && isset($jieqi_charset_type[$_GET['charset']])) @define('JIEQI_CHAR_SET', $jieqi_charset_type[$_GET['charset']]);
	elseif(isset($_COOKIE['jieqiUserCharset']) && isset($jieqi_charset_type[$_COOKIE['jieqiUserCharset']])) @define('JIEQI_CHAR_SET', $jieqi_charset_type[$_COOKIE['jieqiUserCharset']]);
	else @define('JIEQI_CHAR_SET', JIEQI_SYSTEM_CHARSET);
	if ((!isset($_COOKIE['jieqiUserCharset']) && JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET) || (isset($_COOKIE['jieqiUserCharset']) && $_COOKIE['jieqiUserCharset'] != JIEQI_CHAR_SET)) setcookie("jieqiUserCharset",JIEQI_CHAR_SET,time()+2592000, '/', JIEQI_COOKIE_DOMAIN, 0);
}else{
	@define('JIEQI_CHAR_SET', JIEQI_SYSTEM_CHARSET);
}
//允许cache的情况下使用cache(不是系统编码时候也能用cache)
//if(JIEQI_ENABLE_CACHE && JIEQI_CHAR_SET == JIEQI_SYSTEM_CHARSET) define('JIEQI_USE_CACHE', true);
if(JIEQI_ENABLE_CACHE) define('JIEQI_USE_CACHE', true);
else define('JIEQI_USE_CACHE', false);

//设置缓存路径
if(!defined('JIEQI_CACHE_DIR') || JIEQI_CACHE_DIR=='' || strtolower(substr(trim(JIEQI_CACHE_DIR), 0, 12)) == 'memcached://') $tmpvar = JIEQI_ROOT_PATH.'/cache';
elseif(strpos(JIEQI_CACHE_DIR, '/')===false && strpos(JIEQI_CACHE_DIR, '\\')===false) $tmpvar = JIEQI_ROOT_PATH.'/'.JIEQI_CACHE_DIR;
else $tmpvar = JIEQI_CACHE_DIR;
//if(JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET) $tmpvar.='/c_'.JIEQI_CHAR_SET;
if(!is_dir($tmpvar)) jieqi_createdir($tmpvar);
define('JIEQI_CACHE_PATH',$tmpvar);

if(!defined('JIEQI_COMPILED_DIR') || JIEQI_COMPILED_DIR=='') define('JIEQI_COMPILED_PATH', JIEQI_ROOT_PATH.'/compiled');
elseif(strpos(JIEQI_COMPILED_DIR, '/')===false && strpos(JIEQI_COMPILED_DIR, '\\')===false) define('JIEQI_COMPILED_PATH', JIEQI_ROOT_PATH.'/'.JIEQI_COMPILED_DIR);
else define('JIEQI_COMPILED_PATH',JIEQI_COMPILED_DIR);

//******************************************************
//页面预处理
//******************************************************

if(isset($_COOKIE[session_name()]) && strlen($_COOKIE[session_name()]) < 16) unset($_COOKIE[session_name()]);
//是否启用页面压缩输出(ob_gzhandler 和 zlib.output_compression 不能同时使用)
if(JIEQI_USE_GZIP && !(boolean)@ini_get('zlib.output_compression')) @ob_start("ob_gzhandler");
//启动session，已经有sessionid的直接调用session，没有的要登录服务器才能创建新的session
//if (!empty($_COOKIE[session_name()]) || (defined('JIEQI_NEED_SESSION') && JIEQI_LOCAL_URL == JIEQI_USER_URL)) {
if (!empty($_COOKIE[session_name()]) || defined('JIEQI_NEED_SESSION')) {
	if (JIEQI_SESSION_EXPRIE > 0) @ini_set('session.gc_maxlifetime', JIEQI_SESSION_EXPRIE);
	@session_cache_limiter('private, must-revalidate');
	//session的数据库保存模式
	if(JIEQI_SESSION_STORAGE=='db'){
		include_once(JIEQI_ROOT_PATH.'/include/session.php');
		$sess_handler =& JieqiSessionHandler::getInstance('JieqiSessionHandler');
		//设置session处理函数
		@session_set_save_handler(array(&$sess_handler, 'open'), array(&$sess_handler, 'close'), array(&$sess_handler, 'read'), array(&$sess_handler, 'write'), array(&$sess_handler, 'destroy'), array(&$sess_handler, 'gc'));
	}else{
		if(JIEQI_SESSION_SAVEPATH != '' && is_dir(JIEQI_SESSION_SAVEPATH)) session_save_path(JIEQI_SESSION_SAVEPATH);
	}
	//设置sessionid
	if(!empty($_COOKIE[session_name()])) session_id($_COOKIE[session_name()]);
	@session_start();
	//用于多服务器的情况，当一台服务器已经登陆，另一台自动登陆
	if (!empty($_COOKIE[session_name()]) && !empty($_COOKIE['jieqiUserInfo']) && count($_SESSION)==0){
		include_once(JIEQI_ROOT_PATH.'/class/online.php');
		$online_handler =& JieqiOnlineHandler::getInstance('JieqiOnlineHandler');
		$criteria=new CriteriaCompo(new Criteria('sid', $_COOKIE[session_name()], '='));
		$result = $online_handler->queryObjects($criteria);
		$srow = $online_handler->getRow($result);
		if(!empty($srow['uid'])){
			include_once(JIEQI_ROOT_PATH.'/class/users.php');
			$users_handler =& JieqiUsersHandler::getInstance('JieqiUsersHandler');
			$jieqiUsers=$users_handler->get($srow['uid']);
			if(is_object($jieqiUsers)){
				jieqi_setusersession($jieqiUsers);
			}
		}
	}
}

//去掉反溢出斜杠
$magic_quotes_gpc = get_magic_quotes_gpc();
$register_globals = @ini_get('register_globals');
if ($magic_quotes_gpc){
	$_GET= jieqi_funtoarray('stripslashes',$_GET);
	$_POST= jieqi_funtoarray('stripslashes',$_POST);
	$_COOKIE= jieqi_funtoarray('stripslashes',$_COOKIE);
}

//网页内容和提交变量转化
$charsetary=array('gb2312'=>'gb', 'gbk'=>'gb', 'gb'=>'gb', 'big5'=>'big5', 'utf-8'=>'utf8', 'utf8'=>'utf8');
if(JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET || (!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8')){
	include_once(JIEQI_ROOT_PATH.'/include/changecode.php');
}
if(!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8'){
	$charset_convert_ajax='jieqi_'.$charsetary['utf8'].'2'.$charsetary[JIEQI_CHAR_SET];
	//$_GET=& jieqi_funtoarray($charset_convert_ajax,$_GET);
	$_POST=& jieqi_funtoarray($charset_convert_ajax,$_POST);
}
$charset_convert_out='';
if(JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
	$charset_convert_out='jieqi_'.$charsetary[JIEQI_SYSTEM_CHARSET].'2'.$charsetary[JIEQI_CHAR_SET];
	if(!defined('JIEQI_NOCONVERT_CHAR')) @ob_start($charset_convert_out);
	$charset_convert_in='jieqi_'.$charsetary[JIEQI_CHAR_SET].'2'.$charsetary[JIEQI_SYSTEM_CHARSET];
	$_GET=& jieqi_funtoarray($charset_convert_in,$_GET);
	$_POST=& jieqi_funtoarray($charset_convert_in,$_POST);
	$_COOKIE=& jieqi_funtoarray($charset_convert_in,$_COOKIE);
}

//处理REQUEST的转换
if($magic_quotes_gpc || JIEQI_SYSTEM_CHARSET != JIEQI_CHAR_SET || (!empty($_REQUEST['ajax_request']) && $charsetary[JIEQI_CHAR_SET] != 'utf8'))	$_REQUEST=array_merge($_REQUEST, $_GET, $_POST, $_COOKIE);

//最大页数限制
if(defined('JIEQI_MAX_PAGES') && JIEQI_MAX_PAGES > 0 && is_numeric($_REQUEST['page']) && $_REQUEST['page'] > JIEQI_MAX_PAGES) $_REQUEST['page'] = intval(JIEQI_MAX_PAGES);

//******************************************************
//用户预处理
//******************************************************
$jieqiUsersStatus = JIEQI_GROUP_GUEST;
$jieqiUsersGroup = JIEQI_GROUP_GUEST;
$jieqiCache =& jieqi_initcache(); //初始化缓存实例
//自动登录情况
if(empty($_SESSION['jieqiUserId'])){
	if(!empty($_REQUEST['jieqi_username']) && !empty($_REQUEST['jieqi_userpassword'])){
		//提交登录情况
		session_start();
		include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
		$urllogin=jieqi_logincheck($_REQUEST['jieqi_username'], $_REQUEST['jieqi_userpassword'], '', false, false, false);
		if($urllogin == 0) $_SESSION['jieqiAdminLogin']=1;
		//}elseif(!empty($_COOKIE['jieqiUserInfo']) && JIEQI_LOCAL_URL == JIEQI_USER_URL){
	}elseif(!empty($_COOKIE['jieqiUserInfo'])){
		//使用COOKIE登录
		$jieqi_user_info=jieqi_strtosary($_COOKIE['jieqiUserInfo']);
		if(!empty($jieqi_user_info['jieqiUserName']) && !empty($jieqi_user_info['jieqiUserPassword'])){
			session_start();
			include_once(JIEQI_ROOT_PATH.'/include/checklogin.php');
			jieqi_logincheck($jieqi_user_info['jieqiUserName'], $jieqi_user_info['jieqiUserPassword'], '', true, true, false);
		}
	}
}

if(!empty($_SESSION['jieqiUserGroup'])){
	$jieqiUsersGroup = $_SESSION['jieqiUserGroup'];
	switch($_SESSION['jieqiUserGroup']){
		case JIEQI_GROUP_GUEST:
			$jieqiUsersStatus = JIEQI_GROUP_GUEST;
			break;
		case JIEQI_GROUP_ADMIN:
			$jieqiUsersStatus = JIEQI_GROUP_ADMIN;
			define('JIEQI_IS_ADMIN', 1);
			break;
		default:
			$jieqiUsersStatus = JIEQI_GROUP_USER;
			break;
	}
}
//网站是否开放
if(defined('JIEQI_IS_OPEN') && JIEQI_IS_OPEN == 0 && !defined('JIEQI_ADMIN_LOGIN') && $jieqiUsersStatus != JIEQI_GROUP_ADMIN){
	header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
	echo JIEQI_CLOSE_INFO;
	jieqi_freeresource();
	exit;
}elseif(defined('JIEQI_IS_OPEN') && JIEQI_IS_OPEN == 2 && !defined('JIEQI_ADMIN_LOGIN') && $jieqiUsersStatus != JIEQI_GROUP_ADMIN && count($_POST)>0){
	//禁止发表
	header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
	echo LANG_DENY_POST;
	jieqi_freeresource();
	exit;
}

//是否允许代理访问
if(defined('JIEQI_PROXY_DENIED') && JIEQI_PROXY_DENIED != 1){
	if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION']) {
		header('Content-type:text/html;charset='.JIEQI_SYSTEM_CHARSET);
		echo LANG_DENY_PROXY;
		jieqi_freeresource();
		exit;
	}
}

//DEMO帐号管理界面不准保存数据
/*
if((strstr($_SERVER['PHP_SELF'], '/admin') || basename($_SERVER['PHP_SELF'])=='useredit.php') && !empty($_SESSION['jieqiUserName']) && $_SESSION['jieqiUserName']=='demo'){
if((!empty($_POST) || !empty($_REQUEST['action'])) && basename($_SERVER['PHP_SELF'])!='login.php') jieqi_printfail('对不起，测试帐号不允许保存或修改管理数据！');
}
*/

//是否载入用户自定义程序段(仅限于php文件)
if(defined('JIEQI_CUSTOM_INCLUDE') && JIEQI_CUSTOM_INCLUDE == 1){
	$tmpstr = $_SERVER['PHP_SELF'] ? basename($_SERVER['PHP_SELF']) : basename($_SERVER['SCRIPT_NAME']);
	if( preg_match('/\.php$/i', $tmpstr)){
		$tmpstr = @realpath(substr($tmpstr, 0, -4)).'.inc.php';
		if(is_file($tmpstr) && preg_match('/\.inc\.php$/i', $tmpstr)) include_once($tmpstr);
	}
}

//******************************************************
//公共函数
//******************************************************

//  ------------------------------------------------------------------------
//  系统相关
//  ------------------------------------------------------------------------
/**
 * 页面跳转
 * 
 * @param      string      $url 跳转的url地址
 * @param      string      $title 提示的标题
 * @param      string      $content 提示的内容
 * @access     public
 * @return     void
 */
function jieqi_jumppage($url, $title, $content){
	if(empty($_REQUEST['ajax_request'])){
		if(JIEQI_VERSION_TYPE != '' && JIEQI_VERSION_TYPE != 'Free'){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$url=jieqi_htmlstr($url);
			$title=jieqi_htmlstr($title);
			$jieqiTpl =& JieqiTpl::getInstance();
			$jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/', 'jieqi_themecss'=> JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/style.css',  'pagetitle' => $title, 'title' => $title, 'content' => $content, 'url' => $url));
			$jieqiTpl->setCaching(0);
			$jieqiTpl->display(JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/jumppage.html');
		}else{
			echo '<html><head><meta http-equiv="content-type" content="text/html; charset='.JIEQI_CHAR_SET.'" /><meta http-equiv="refresh" content="3; url='.$url.'">
<title>'.jieqi_htmlstr($title).'</title><link rel="stylesheet" type="text/css" media="all" href="'.JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/style.css" /></head><body><div id="msgboard" style="position:absolute; left:210px; top:150px; width:350px; height:100px; z-index:1;"><table class="grid" width="100%" border="0" cellspacing="1" cellpadding="6" ><caption>'.jieqi_htmlstr($title).'</caption><tr><td class="even"><br />'.$content.'<br /><br />如不能自动跳转，<a href="'.$url.'">点击这里直接进入！</a><br /><br />程序设计：<a href="http://www.jieqi.com" target="_blank">杰奇网络</a><br /><br /></td></tr></table></div></body></html>';
		}
	}else{
		header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
		header("Cache-Control:no-cache");
		return $url;
	}
	jieqi_freeresource();
	exit;
}

/**
 * 返回提示消息框的html代码
 * 
 * @param      string      $title 提示的标题
 * @param      string      $content 提示的内容
 * @access     public
 * @return     string       返回html代码
 */
function jieqi_msgbox($title, $content){
	if(empty($_REQUEST['ajax_request'])){
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$title=jieqi_htmlstr($title);
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign(array('title' => $title, 'content' => $content));
		$jieqiTpl->setCaching(0);
		return $jieqiTpl->fetch(JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/msgbox.html');
	}else{
		header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
		header("Cache-Control:no-cache");
		return $content;
	}
}

/**
 * 显示提示信息（完整的html页面）
 * 
 * @param      string      $title 提示的标题
 * @param      string      $content 提示的内容
 * @access     public
 * @return     void
 */
function jieqi_msgwin($title, $content){
	if(defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
	if(empty($_REQUEST['ajax_request'])){
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$title=jieqi_htmlstr($title);
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/', 'jieqi_themecss'=> JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/style.css', 'title' => $title, 'content' => $content, 'jieqi_sitename' => JIEQI_SITE_NAME));
		$jieqiTpl->setCaching(0);
		$jieqiTpl->display(JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/msgwin.html');
	}else{
		header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
		header("Cache-Control:no-cache");
		echo $content;
	}
	jieqi_freeresource();
	exit;
}

/**
 * 显示错误信息（完整页面）
 * 
 * @param      string      $errorinfo 错误信息的html代码
 * @access     public
 * @return     void
 */
function jieqi_printfail($errorinfo){
	if(defined('JIEQI_NOCONVERT_CHAR') && !empty($GLOBALS['charset_convert_out'])) @ob_start($GLOBALS['charset_convert_out']);
	$debuginfo='';
	if(defined('JIEQI_DEBUG_MODE') && JIEQI_DEBUG_MODE > 0){
		$trace = debug_backtrace();
		$debuginfo='FILE: '.jieqi_htmlstr($trace[0]['file']).' LINE:'.jieqi_htmlstr($trace[0]['line']);
	}
	if(empty($_REQUEST['ajax_request'])){
		include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
		$jieqiTpl =& JieqiTpl::getInstance();
		$jieqiTpl->assign(array('jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_themeurl' => JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/', 'jieqi_themecss'=> JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/style.css', 'errorinfo' => $errorinfo, 'debuginfo' => $debuginfo, 'jieqi_sitename' => JIEQI_SITE_NAME));
		$jieqiTpl->setCaching(0);
		$jieqiTpl->display(JIEQI_ROOT_PATH.'/themes/'.JIEQI_THEME_NAME.'/msgerr.html');
	}else{
		header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
		header("Cache-Control:no-cache");
		echo $errorinfo;
	}
	jieqi_freeresource();
	exit;

}

/**
 * 取得用户ip地址
 * 
 * @param      void
 * @access     public
 * @return     string      当前访问者的ip
 */
function jieqi_userip(){
	if(isset($_SERVER['HTTP_CLIENT_IP'])) $ip=$_SERVER['HTTP_CLIENT_IP'];
	elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
	else  $ip=$_SERVER['REMOTE_ADDR'];
	$ip=trim($ip);
	if(!is_numeric(str_replace('.','',$ip))) $ip='';
	return $ip;
}

/**
 * 根据id取得文件保存的子目录
 * 
 * @param      int         $id
 * @access     public
 * @return     string       返回子目录
 */
function jieqi_getsubdir($id, $divnum = 1000){
	return '/'.floor(intval($id) / $divnum);
}

/**
 * 根据记录id获得访问该记录的url地址
 * 
 * 本函数是调用另外一个函数来处理url地址，前面两个参数是必要的，后面参数是具体处理函数里面使用
 * 
 * @param      string       $module 函数所属模块名
 * @param      string       $target 处理对象，用来组成函数名的 'jieqi_url_'.$module.'_'.$target
 * @access     public
 * @return     string       返回url字符串
 */
function jieqi_geturl($module, $target){
	global $jieqiModules;
	if(!isset($jieqiModules[$module])) $module = 'system';
	$funname = 'jieqi_url_'.$module.'_'.$target;
	if(!function_exists($funname) && isset($jieqiModules[$module]['path']) && is_file($jieqiModules[$module]['path'].'/include/funurl.php')) include_once($jieqiModules[$module]['path'].'/include/funurl.php');
	if(function_exists($funname)){
		$numargs = func_num_args();
		$args = func_get_args();
		switch($numargs){
			case 0:
			case 1:
			case 2:
				return $funname();
				break;
			case 3:
				return $funname($args[2]);
				break;
			case 4:
				return $funname($args[2], $args[3]);
				break;
			case 5:
				return $funname($args[2], $args[3], $args[4]);
				break;
			case 6:
			default:
				return $funname($args[2], $args[3], $args[4], $args[5]);
				break;
		}
	}else{
		return false;
	}
}

/**
 * 取得上传文件目录路径
 * 
 * @param      string      $path 保存上传文件的目录名或者完整的路径名称
 * @param      string      $module 模块名称，留空表示当前模块
 * @param      string      $systempath 系统根路径，留空则自动获取
 * @access     public
 * @return     string      
 */
function jieqi_uploadpath($path, $module='', $systempath=''){
	if(strpos($path, '/')===false && strpos($path, '\\')===false){
		if($module=='' && defined('JIEQI_MODULE_NAME')) $module=JIEQI_MODULE_NAME;
		if($systempath=='') $systempath=JIEQI_ROOT_PATH;
		if($path=='') return $systempath.'/files/'.$module;
		else return $systempath.'/files/'.$module.'/'.$path;
	}else{
		return $path;
	}
}

/**
 * 取得上传文件目录URL
 * 
 * @param      string      $path 保存上传文件的目录名或者完整的路径名称
 * @param      string      $url 自定义的url地址
 * @param      string      $module 模块名称，留空表示当前模块
 * @param      string      $systempath 系统根路径，留空则自动获取
 * @access     public
 * @return     string      
 */
function jieqi_uploadurl($path, $url='', $module='', $systemurl=''){
	if(!empty($url)){
		return $url;
	}else{
		if($module=='' && defined('JIEQI_MODULE_NAME')) $module=JIEQI_MODULE_NAME;
		if($systemurl=='') $systemurl=JIEQI_URL;
		elseif(strpos($systemurl,'/modules') > 0) $systemurl=substr($systemurl,0,strpos($systemurl,'/modules'));
		if($path=='') return $systemurl.'/files/'.$module;
		else return $systemurl.'/files/'.$module.'/'.$path;
	}
}

/**
 * 检查用户权限
 * 
 * @param      array       $powerset 需要的权限数组
 * @param      int         $ustatus 用户状态
 * @param      int         $ugroup 用户组
 * @param      bool        $isreturn 是否返回检查结果，默认不返回，表示检查不通过直接提示
 * @param      bool        $isadmin 是否检查后台权限，默认否
 * @access     public
 * @return     bool      
 */
function jieqi_checkpower($powerset=array(), $ustatus='0', $ugroup='0', $isreturn=false, $isadmin=false){
	if(empty($_POST)){
		$local_domain_url=(empty($_SERVER['HTTP_HOST'])) ? '' : 'http://'.$_SERVER['HTTP_HOST'];
		$jumpurl=$local_domain_url.jieqi_addurlvars(array());
	}elseif(!empty($_SERVER['HTTP_REFERER'])){
		$jumpurl=$_SERVER['HTTP_REFERER'];
	}else{
		$jumpurl=JIEQI_MAIN_URL;
	}

	if((!isset($_SESSION['jieqiAdminLogin']) || $_SESSION['jieqiAdminLogin'] != 1) && !empty($_COOKIE['jieqiOnlineInfo'])){
		$jieqi_online_info = jieqi_strtosary($_COOKIE['jieqiOnlineInfo']);
		if($jieqi_online_info['jieqiAdminLogin'] == 1) $_SESSION['jieqiAdminLogin'] = 1;
	}

	if($ustatus==JIEQI_GROUP_ADMIN){
		if($isadmin && empty($_SESSION['jieqiAdminLogin'])){
			if($isreturn){
				return false;
			}else{
				header('Location: '.JIEQI_LOCAL_URL.'/admin/login.php?jumpurl='.urlencode($jumpurl));
				exit;
			}
		}else{
			return true;
		}
	}else{
		if(is_array($powerset['groups']) && (in_array($ugroup, $powerset['groups'], false) || in_array('0',$powerset['groups'],false))){
			if($isadmin && empty($_SESSION['jieqiAdminLogin'])){
				if($isreturn){
					return false;
				}else{
					header('Location: '.JIEQI_LOCAL_URL.'/admin/login.php?jumpurl='.urlencode($jumpurl));
					exit;
				}
			}else{
				return true;
			}
		}else{
			if($isreturn){
				return false;
			}else{
				if($ugroup==JIEQI_GROUP_GUEST){
					if($isadmin){
						header('Location: '.JIEQI_USER_URL.'/admin/login.php?jumpurl='.urlencode($jumpurl));
					}else{
						header('Location: '.JIEQI_USER_URL.'/login.php?jumpurl='.urlencode($jumpurl));
					}
					exit;
				}else{
					jieqi_printfail(LANG_NO_PERMISSION);
				}
			}
		}
	}
}


/**
 * 检查用户是否已登陆
 * 
 * @param      bool        $isreturn 是否返回检查结果，默认不返回，表示检查不通过直接提示
 * @param      bool        $isadmin 是否检查后台权限，默认否
 * @access     public
 * @return     bool        已登录返回true，未登录返回false
 */
function jieqi_checklogin($isreturn=false, $isadmin=false){
	global $jieqiUsersGroup;
	if($jieqiUsersGroup==JIEQI_GROUP_GUEST)	$ret=false;
	else $ret=true;
	if($isreturn) return $ret;
	elseif(!$ret){
		if(empty($_REQUEST['ajax_request'])){
			if(empty($_POST)){
				$local_domain_url=(empty($_SERVER['HTTP_HOST'])) ? '' : 'http://'.$_SERVER['HTTP_HOST'];
				$jumpurl=$local_domain_url.jieqi_addurlvars(array());
			}elseif(!empty($_SERVER['HTTP_REFERER'])){
				$jumpurl=$_SERVER['HTTP_REFERER'];
			}else{
				$jumpurl=JIEQI_MAIN_URL;
			}
			if($isadmin) header('Location: '.JIEQI_USER_URL.'/admin/login.php?jumpurl='.urlencode($jumpurl));
			else header('Location: '.JIEQI_USER_URL.'/login.php?jumpurl='.urlencode($jumpurl));
		}else{
			header('Content-Type:text/html; charset='.JIEQI_CHAR_SET);
			header("Cache-Control:no-cache");
			echo LANG_NEED_LOGIN;
		}
		exit;
	}
}

/**
 * 保存用户SESSION
 * 
 * @param      object      $user 用户表记录对象
 * @access     public
 * @return     void
 */
function jieqi_setusersession($user){
	global $jieqiHonors;
	global $jieqiModules;
	$_SESSION = array();
	$_SESSION['jieqiUserId'] = $user->getVar('uid', 'n');
	$_SESSION['jieqiUserUname'] = $user->getVar('uname', 'n');
	$_SESSION['jieqiUserName'] = (strlen($user->getVar('name', 'n')) > 0) ? $user->getVar('name', 'n') : $user->getVar('uname', 'n');
	$_SESSION['jieqiUserGroup'] = $user->getVar('groupid', 'n');
	$_SESSION['jieqiUserEmail'] = $user->getVar('email', 'n');
	$_SESSION['jieqiUserAvatar'] = $user->getVar('avatar', 'n');
	$_SESSION['jieqiUserScore'] = $user->getVar('score', 'n');
	$_SESSION['jieqiUserExperience'] = $user->getVar('experience', 'n');
	$_SESSION['jieqiUserVip'] = $user->getVar('isvip', 'n');
	$_SESSION['jieqiUserEgold'] = ($user->getVar('egold', 'n')>0 || $user->getVar('esilver', 'n')>0) ? 1 : 0;
	jieqi_getconfigs('system', 'honors'); //头衔
	$honorid=intval(jieqi_gethonorid($user->getVar('score'), $jieqiHonors));
	$_SESSION['jieqiUserHonorid'] = $honorid;
	$_SESSION['jieqiUserHonor'] = isset($jieqiHonors[$honorid]['name'][intval($user->getVar('workid', 'n'))]) ? $jieqiHonors[$honorid]['name'][intval($user->getVar('workid', 'n'))] : $jieqiHonors[$honorid]['caption'];
	if(!empty($jieqiModules['badge']['publish'])){
		$_SESSION['jieqiUserBadges'] = $user->getVar('badges', 'n'); //徽章
	}
	$_SESSION['jieqiUserSet'] = unserialize($user->getVar('setting','n'));
}

/**
 * 增加当前url参数
 * 
 * @param      array      $varary 需要增加的变量数组
 * @param      bool       $addget 是否自动增加GET参数，默认是
 * @param      bool       $addpost 是否自动增加POST参数，默认否
 * @param      array      $filter 过滤哪些参数名
 * @access     public
 * @return     string
 */
function jieqi_addurlvars($varary, $addget=true, $addpost=false, $filter=''){
	if(!empty($_SERVER['PHP_SELF'])) $ret=$_SERVER['PHP_SELF'];
	elseif(!empty($_SERVER['SCRIPT_NAME']) && substr($_SERVER['SCRIPT_NAME'],-4)=='.php') $ret=$_SERVER['SCRIPT_NAME'];
	else $ret='';
	$start=0;
	if(!is_array($filter)) $filter=array();
	if($addget){
		foreach($_GET as $k=>$v){
			if (!array_key_exists($k, $varary) && !in_array($k, $filter) && is_string($v)){
				$ret.=($start++ > 0) ? '&'.$k.'='.urlencode($v) : '?'.$k.'='.urlencode($v);
			}
		}
	}
	if($addpost){
		foreach($_POST as $k=>$v){
			if (!array_key_exists($k, $varary) && !in_array($k, $filter) && is_string($v)){
				$ret.=($start++ > 0) ? '&'.$k.'='.urlencode($v) : '?'.$k.'='.urlencode($v);
			}
		}
	}
	if(is_array($varary)){
		foreach($varary as $k=>$v){
			$ret.=($start++ > 0) ? '&'.$k.'='.$v : '?'.$k.'='.$v;
		}
	}
	return $ret;
}

/**
 * 包含数据库类
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_includedb(){
	if(!defined('JIEQI_DBCLASS_INCLUDE')){
		include_once(JIEQI_ROOT_PATH.'/lib/database/database.php');
		define('JIEQI_DBCLASS_INCLUDE', true);
	}
}

/**
 * 关闭数据库连接
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_closedb($db = NULL){
	if(defined('JIEQI_DB_CONNECTED') && !defined('JIEQI_DB_NOTCLOSE') && JIEQI_DB_PCONNECT == false) JieqiDatabase::close($db);
}

/**
 * 关闭ftp连接
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_closeftp($ftp = NULL){
	if(defined('JIEQI_FTP_CONNECTED') && !defined('JIEQI_FTP_NOTCLOSE')) JieqiFTP::close($ftp);
}

/**
 * 初始化缓存，包含缓存类并返回缓存实例
 * 
 * @param      object      $jieqiCache
 * @access     public
 * @return     bool
*/
function &jieqi_initcache(){
	if(strtolower(substr(trim(JIEQI_CACHE_DIR), 0, 12)) != 'memcached://'){
		$jieqiCache =& JieqiCache::getInstance('file');
	}else{
		$params = @parse_url(trim(JIEQI_CACHE_DIR));
		$jieqiCache =& JieqiCache::getInstance('memcached', array('host'=>strval($params['host']), 'port'=>intval($params['port'])));
	}

	return $jieqiCache;
}

/**
 * 关闭memcached连接
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_closecache($cache = NULL){
	if(defined('JIEQI_CACHE_CONNECTED') && !defined('JIEQI_CACHE_NOTCLOSE')) JieqiCache::close($cache);
}

/**
 * 释放占用的资源（如数据库链接，ftp链接，其他远程服务等）
 * 
 * @param      void
 * @access     public
 * @return     void
 */
function jieqi_freeresource(){
	jieqi_closedb();
	jieqi_closeftp();
	jieqi_closecache();
}

/**
 * 载入语言包
 * 
 * @param      string     $fname 语言包文件名，不带后缀
 * @param      string     $module 模块名，默认是 'system'
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_loadlang($fname, $module='system'){
	global $jieqiLang;
	global $jieqiModules;
	if(empty($jieqiLang[$module][$fname])){
		if($module=='system' || $module=='') $file=JIEQI_ROOT_PATH.'/lang/lang_'.$fname.'.php';
		else $file=$jieqiModules[$module]['path'].'/lang/lang_'.$fname.'.php';
		$file = @realpath($file);
		if (is_file($file) && preg_match('/\.php$/i', $file)){
			include_once($file);
			return true;
		}else return false;
	}
}

/**
 * 根据积分获得用户头衔ID
 * 
 * @param      int        $userscore 用户积分
 * @param      array      $jieqiHonors 头衔设置数组
 * @access     public
 * @return     int
 */
function jieqi_gethonorid($userscore=0, $jieqiHonors=array()){
	if(is_array($jieqiHonors)){
		foreach($jieqiHonors as $k=>$v){
			if($userscore >= $v['minscore'] && $userscore < $v['maxscore']) return $k;
		}
	}
	return false;
}

//  ------------------------------------------------------------------------
//  字符串处理
//  ------------------------------------------------------------------------

/**
 * 把字符串转换为htm格式
 * 
 * @param      string     $str 输入的字符串
 * @param      int        $quote_style 转换标志，默认ENT_QUOTES表示转换单引号
 * @access     public
 * @return     string
 */
function jieqi_htmlstr($str, $quote_style=ENT_QUOTES){
	$str = htmlspecialchars($str, $quote_style);
	$str = nl2br($str);
	$str = str_replace("  ", "&nbsp;&nbsp;", $str);
	$str = preg_replace("/&amp;#(\d+);/isU", "&#\\1;", $str);
	return $str;
}

/**
 * 字符串截取函数，适应中文
 * 
 * @param      string     $str  原始字符串
 * @param      int        $start  开始位置
 * @param      int        $length  截取长度
 * @param      string     $trimmarker  附加字符串
 * @access     public
 * @return     string
 */
function jieqi_substr($str, $start, $length, $trimmarker = '...'){
	$strlen = $start + $length - strlen($trimmarker);
	$len=strlen($str);
	if($strlen > $len) $strlen=$len;
	$tmpstr = "";
	for($i = 0;$i < $strlen;$i++) {
		if (ord($str[$i]) > 0x80) {
			if($i >= $start) $tmpstr .= $str[$i].$str[$i+1];
			$i++;
		} else if ($i >= $start) $tmpstr .= $str[$i];
	}
	if($strlen<$len) $tmpstr.= $trimmarker;
	return $tmpstr;
}

/**
 * 将字符串的函数应用到整个数组,即把数组里面所有字符串用某个函数处理一遍
 * 
 * @param      string     $funname  函数名
 * @param      array      $ary 字符串数组
 * @access     public
 * @return     array
 */
function jieqi_funtoarray($funname, $ary){
	if (is_array($ary)){
		foreach($ary as $k=>$v){
			if(is_string($v)){
				$ary[$k] = $funname($v);
			}elseif(is_array($v)){
				$ary[$k] = jieqi_funtoarray($funname, $v);
			}
		}
	}else{
		$ary = $funname($ary);
	}
	return $ary;
}

/**
 * 数据表加前缀
 * 
 * @param      string     $tbname  数据表名
 * @param      bool       $fullname 是否已经是完整的表名，默认否
 * @access     public
 * @return     string
 */
function jieqi_dbprefix($tbname, $fullname=false){
	if (JIEQI_DB_PREFIX=='' || $fullname) return $tbname;
	else return JIEQI_DB_PREFIX.'_'.$tbname;
}

/**
 * 使用反斜线引用字符串
 * 
 * @param      string     $str 输入的字符串
 * @param      string     $filter 不加反斜线的字符
 * @access     public
 * @return     string
 */
function jieqi_setslashes($str, $filter=''){
	if($filter == '"') return str_replace(array('\\', '\''), array('\\\\', '\\\''), $str);
	elseif($filter == '\'') return str_replace(array('\\', '"'), array('\\\\', '\\"'), $str);
	else return addslashes($str);
}


/**
 * 准备生成sql入库的数据反斜线处理
 * 
 * @param      string     $str 输入的字符串
 * @param      bool       $use_slashes 是否已经加过反斜线，默认否
 * @access     public
 * @return     string
 */
function jieqi_dbslashes($str, $use_slashes=false){
	if($use_slashes) return $str;
	else{
		//为了解决 许功盖 问题
		if(JIEQI_SYSTEM_CHARSET == 'big5' && JIEQI_DB_CHARSET != 'big5'){
			$str=strval($str);
			$l=strlen($str);
			$ret='';
			for($i=0; $i<$l; $i++){
				$o=ord($str[$i]);
				if($o > 0x80) {
					$ret.=$str[$i].$str[$i+1];
					$i++;
				}elseif($o == 0 || $o == 34 || $o == 39 || $o == 92){
					$ret.=chr(92).$str[$i];
				}else{
					$ret.=$str[$i];
				}
			}
			return $ret;
		}else{
			return addslashes($str);
		}
	}
}

/**
 * 把字符串数组转换成一个字符串
 * 
 * @param      array      $ary 字符串数组
 * @param      string     $equal 变量名称和值之间的分隔符
 * @param      string     $split 两个变量之间的分隔符
 * @access     public
 * @return     string
 */
function jieqi_sarytostr($ary, $equal='=', $split=','){
	$ret='';
	foreach($ary as $k=>$v){
		if(!empty($ret)) $ret.=$split;
		$ret.=$k.$equal.$v;
	}
	return $ret;
}

/**
 * 把一个字符串还原成字符串数组
 * 
 * @param      string     $str 输入的字符串
 * @param      string     $equal 变量名称和值之间的分隔符
 * @param      string     $split 两个变量之间的分隔符
 * @access     public
 * @return     array
 */
function jieqi_strtosary($str, $equal='=', $split=','){
	$ret=array();
	$tmpary=explode($split, $str);
	foreach($tmpary as $v){
		$idx=strpos($v, $equal);
		if($idx>0) $ret[substr($v,0,$idx)]=substr($v,$idx+1);
	}
	return $ret;
}

//  ------------------------------------------------------------------------
//  文件处理
//  ------------------------------------------------------------------------

/**
 * 读取一个文件内容
 * 
 * @param      string     $file_name 文件名
 * @access     public
 * @return     string      返回文件内容
 */
function jieqi_readfile($file_name){
	if (function_exists("file_get_contents")) {
		return file_get_contents($file_name);
	}else{
		$filenum = @fopen($file_name, "rb");
		@flock($filenum, LOCK_SH);
		$file_data = @fread($filenum, @filesize($file_name));
		@flock($filenum, LOCK_UN);
		@fclose($filenum);
		return $file_data;
	}
}

/**
 * 把内容写到一个文件
 * 
 * @param      string     $file_name 文件名
 * @param      string     $data 内容
 * @param      string     $method 写的模式，默认 "wb" 是指二进制方式写
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_writefile($file_name, &$data, $method = "wb"){
	$filenum = @fopen($file_name, $method);
	if(!$filenum) return false;
	@flock($filenum, LOCK_EX);
	$ret = @fwrite($filenum, $data);
	@flock($filenum, LOCK_UN);
	@fclose($filenum);
	@chmod($file_name, 0777);
	return $ret;
}

/**
 * 删除文件
 * 
 * @param      string     $file_name 文件名
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_delfile($file_name){
	$file_name = trim($file_name);
	$matches = array();
	if(!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $file_name, $matches)){
		return @unlink($file_name);
	}else{
		include_once(JIEQI_ROOT_PATH.'/lib/ftp/ftp.php');
		$ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
		$matches[6]=intval(trim($matches[6]));
		$ftpport = ($matches[6] > 0) ? $matches[6] : 21;
		$ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
		if(!$ftp) return false;
		$matches[7] = trim($matches[7]);
		return $ftp->ftp_delete($matches[7]);
	}
}

/**
 * 删除目录
 * 
 * @param      string     $dirname 目录名
 * @param      bool       $flag true表示删除目录本身（默认），false表示清空目录里面内容
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_delfolder($dirname, $flag = true){
	$dirname = trim($dirname);
	$matches = array();
	if(!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $dirname, $matches)){
		$handle = @opendir($dirname);
		while (($file = @readdir($handle)) !== false) {
			if($file != '.' && $file != '..'){
				if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)){
					jieqi_delfolder($dirname . DIRECTORY_SEPARATOR . $file, true);
				}else{
					@unlink($dirname . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		@closedir($handle);
		if ($flag) @rmdir($dirname);
		return true;
	}else{
		include_once(JIEQI_ROOT_PATH.'/lib/ftp/ftp.php');
		$ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
		$matches[6]=intval(trim($matches[6]));
		$ftpport = ($matches[6] > 0) ? $matches[6] : 21;
		$ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
		if(!$ftp) return false;
		$matches[7] = trim($matches[7]);
		return $ftp->ftp_delfolder($matches[7], $flag);
	}
}

/**
 * 建立目录
 * 
 * @param      string     $dirname 目录名
 * @param      int        $mode 建立后的目录权限
 * @param      bool       $recursive 是否支持多级目录建立，默认否
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_createdir($dirname, $mode=0777, $recursive = false){
	if (!$recursive) {
		$ret=@mkdir($dirname, $mode);
		if($ret) @chmod($dirname, $mode);
		return $ret;
	}
	if(is_dir($dirname)){
		return true;
	}elseif(jieqi_createdir(dirname($dirname), $mode, true)){
		$ret=@mkdir($dirname, $mode);
		if($ret) @chmod($dirname, $mode);
		return $ret;
	}else{
		return false;
	}
}

/**
 * 检查目录是否存在，不存在尝试自动建立
 * 
 * @param      string     $dirname 目录名
 * @param      bool       $autocreate 目录不存在是否尝试自动建立，默认否
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_checkdir($dirname, $autocreate=false){
	if(is_dir($dirname)){
		return true;
	}else{
		if(empty($autocreate)) return false;
		else return jieqi_createdir($dirname, 0777, true);
	}
}

/**
 * 发送下载文件信息
 * 
 * @param      string     $filename 文件名
 * @param      string     $contenttype 文件mime类型
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_downfile($filename, $contenttype='application/octet-stream'){
	if(file_exists($filename)){
		header("Content-type: ".$contenttype);
		header("Accept-Ranges: bytes");
		header("Content-Disposition: attachment; filename=".basename($filename));
		echo jieqi_readfile($filename);
		return true;
	}else{
		return false;
	}
}

/**
 * 拷贝或者移动文件
 * 
 * @param      string     $from_file 原始文件名
 * @param      string     $to_file 拷贝到文件名，支持ftp模式，如 ftp://user:password@host/dir/file.txt
 * @param      int        $mode 保存后的文件权限
 * @param      bool       $move 是否移动文件，默认false表示拷贝，true表示移动
 * @access     public
 * @return     bool       成功返回true，失败返回false
 */
function jieqi_copyfile($from_file, $to_file, $mode = 0777, $move = false){
	$from_file = trim($from_file);
	if(!is_file($from_file)) return false;
	$to_file = trim($to_file);
	$matches = array();
	if(!preg_match('/^(ftps?):\/\/([^:\/]+):([^:\/]*)@([0-9a-z\-\.]+)(:(\d+))?([0-9a-z_\-\/\.]*)/is', $to_file, $matches)){
		jieqi_checkdir(dirname($to_file), true);
		if(is_file($to_file)) @unlink($to_file);
		if($move) $ret = rename($from_file, $to_file);
		else $ret = copy($from_file, $to_file);
		if($ret && $mode) @chmod($to_file, $mode);
		return $ret;
	}else{
		include_once(JIEQI_ROOT_PATH.'/lib/ftp/ftp.php');
		$ftpssl = (strtolower($matches[1]) == 'ftps') ? 1 : 0;
		$matches[6]=intval(trim($matches[6]));
		$ftpport = ($matches[6] > 0) ? $matches[6] : 21;
		$ftp =& JieqiFTP::getInstance($matches[4], $matches[2], $matches[3], '.', $ftpport, 0, $ftpssl);
		if(!$ftp) return false;
		$matches[7] = trim($matches[7]);
		if(!$ftp->ftp_chdir(dirname($matches[7]))){
			if(substr($matches[7],0,1) == '/') $matches[7] = substr($matches[7],1);
			$pathary = explode('/', dirname($matches[7]));
			foreach ($pathary as $v){
				$v=trim($v);
				if(strlen($v) > 0){
					if($ftp->ftp_mkdir($v) !== false && $mode) $ftp->ftp_chmod($mode, $v);
					$ftp->ftp_chdir($v);
				}
			}
		}
		$ret = $ftp->ftp_put(basename($matches[7]), $from_file);
		if($ret && $mode) $ftp->ftp_chmod($mode, basename($matches[7]));
		//$ftp->ftp_close();
		if($move) @unlink($from_file);
		return $ret;
	}
}

/**
 * 将一个变量转换成可保存在设置文件的字符串
 * 
 * @param      string     $varname 变量名
 * @param      mixed      $vars 要保存的变量
 * @access     public
 * @return     string
 */
function jieqi_extractvars($varname, &$vars){
	$extract_array_str='';
	if (is_array($vars)) {
		foreach($vars as $key=>$val) {
			if (is_array($val)) {
				$extract_array_str .= jieqi_extractvars($varname."['".jieqi_setslashes($key, '"')."']", $vars[$key]);
			}else {
				$extract_array_str .= '$' . $varname . "['" . jieqi_setslashes($key, '"') . "'] = '" . jieqi_setslashes($val, '"') . "';\n";
			}
		}
	}else{
		$extract_array_str .= '$' .$varname . " = '" . jieqi_setslashes($vars, '"') . "';\n";
	}
	return  $extract_array_str;
}


/**
 * 保存配置文件（通常是把一个数组存成php文件）
 * 
 * @param      string     $fname 文件名，不带后缀
 * @param      string     $varname 变量名
 * @param      mixed      $vars 要保存的变量
 * @param      string     $module 模块名，默认'system'
 * @access     public
 * @return     bool
 */
function jieqi_setconfigs($fname='', $varname, &$vars, $module='system'){
	global $jieqiModules;
	if(strlen($fname) > 0 && strlen($varname) > 0){
		$dir = JIEQI_ROOT_PATH.'/configs';
		if(!file_exists($dir)) @mkdir($dir, 0777);
		@chmod($dir, 0777);
		if($module != 'system' && isset($jieqiModules[$module])){
			$dir.='/'.$module;
			if(!file_exists($dir)) @mkdir($dir, 0777);
			@chmod($dir, 0777);
		}
		$dir.='/'.$fname.'.php';
		if(file_exists($dir)) @chmod($dir, 0777);
		$varstring="<?php\n".jieqi_extractvars($varname, $vars)."\n?>";
		return jieqi_writefile($dir, $varstring);
	}
	return false;
}

/**
 * 保存缓存变量
 * 
 * @param      string     $fname 文件名，不带后缀
 * @param      string     $varname 变量名
 * @param      mixed      $vars 要保存的变量
 * @param      string     $module 模块名，默认'system'
 * @param      int        $cacheid 缓存id
 * @access     public
 * @return     bool
 */
function jieqi_setcachevars($fname='', $varname, &$vars, $module='system', $cacheid = 0){
	global $jieqiModules;
	global $jieqiCache;
	if(empty($fname) || empty($varname)) return false;
	$cachefile = JIEQI_CACHE_PATH.'/cachevars';
	if(isset($jieqiModules[$module])) $cachefile .= '/'.$module;
	if(empty($cacheid)){
		$cachefile .= '/'.$fname.'.php';
	}else{
		$cacheid = intval($cacheid);
		$cachefile .= '/'.$fname.jieqi_getsubdir($cacheid).'/'.$cacheid.'.php';
	}
	if(is_a($jieqiCache, 'JieqiCacheMemcached')){
		return $jieqiCache->set($cachefile, $vars);
	}else{
		$varstring="<?php\n".jieqi_extractvars($varname, $vars)."\n?>";
		return $jieqiCache->set($cachefile, $varstring);
	}
}

/**
 * 从配置文件获得变量(一般是配置参数，放在configs目录下)
 * 
 * @param      string     $module 模块名
 * @param      string     $fname 文件名，不带后缀
 * @param      string     $vname 提取的变量名
 * @access     public
 * @return     bool
 */
function jieqi_getconfigs($module, $fname, $vname=''){
	if($vname !== false){
		if($vname=='') $vname='jieqi'.ucfirst($fname);
		global ${$vname};
	}
	//区块的参数只包含一次
	if($vname == 'jieqiBlocks' && isset($jieqiBlocks)){
		return true;
	}else{
		if($module=='system' || $module=='') $file=JIEQI_ROOT_PATH.'/configs/'.$fname.'.php';
		else $file=JIEQI_ROOT_PATH.'/configs/'.$module.'/'.$fname.'.php';
		$file = @realpath($file);
		if (preg_match('/\.php$/i', $file)){
			include_once($file);
			return true;
		}else return false;
	}
}

/**
 * 获取缓存变量
 * 
 * @param      string     $module 模块名
 * @param      string     $fname 文件名，不带后缀
 * @param      string     $vname 提取的变量名
 * @param      int        $cacheid 缓存id
 * @access     public
 * @return     bool
 */
function jieqi_getcachevars($module, $fname, $vname='', $cacheid = 0){
	global $jieqiModules;
	global $jieqiCache;
	if(empty($module) || empty($fname)) return false;
	if($vname !== false){
		if($vname=='') $vname='jieqi'.ucfirst($fname);
		global ${$vname};
	}
	$cachefile = JIEQI_CACHE_PATH.'/cachevars';
	if(isset($jieqiModules[$module])) $cachefile .= '/'.$module;
	if(empty($cacheid)){
		$cachefile .= '/'.$fname.'.php';
	}else{
		$cacheid = intval($cacheid);
		$cachefile .= '/'.$fname.jieqi_getsubdir($cacheid).'/'.$cacheid.'.php';
	}
	if(is_a($jieqiCache, 'JieqiCacheMemcached')){
		${$vname} = $jieqiCache->get($cachefile);
	}else{
		$cachefile = @realpath($cachefile);
		if(is_file($cachefile) && preg_match('/\.php$/i', $cachefile)) include_once($cachefile);
	}
}

//******************************************************
//基类
//******************************************************

/**
 * 通用的对象基类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiObject{
	//保存所有变量
	var $vars = array();
	//错误数组
	var $errors = array();

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function JieqiObject(){

	}

	/**
	 * 创建一个实例
	 * 
	 * @param      string     $classname 类名
	 * @param      array      $valarray 初始化参数
	 * @access     public
	 * @return     object
	 */
	function &getInstance($classname, $valarray=''){
		static $instance;
		if (!isset($instance)) {
			if (class_exists($classname)) {
				if ($valarray==''){
					$instance = new $classname();
				}else{
					if (is_array($valarray)){
						$instance = new $classname(implode(', ',$valarray));
					}else{
						$instance = new $classname($valarray);
					}
				}
			} else {
				return false;
			}
		}
		return $instance;
	}

	/**
	 * 取得变量值
	 * 
	 * @param      string     $key 变量名
	 * @param      string     $format 输出的格式化方式 's'-html显示格式, 'e'-html编辑格式, 'q'-数据库查询格式，'n'-不进行格式处理
	 * @access     public
	 * @return     mixed
	 */
	function getVar($key, $format = 's'){
		if (isset($this->vars[$key])) {
			if(is_string($this->vars[$key])){
				switch (strtolower($format)) {
					case 's':
						return jieqi_htmlstr($this->vars[$key]);
					case 'e':
						return htmlspecialchars($this->vars[$key],ENT_QUOTES);
					case 'q':
						return jieqi_dbslashes($this->vars[$key]);
					case 'n':
					default:
						return $this->vars[$key];
				}
			}else return $this->vars[$key];
		}else{
			return false;
		}
	}

	/**
	 * 取得所有变量
	 * 
	 * @param      void
	 * @access     public
	 * @return     mixed
	 */
	function getVars(){
		return $this->vars;
	}

	/**
	 * 设置一个变量
	 * 
	 * @param      string     $key 变量名
	 * @param      mixed      $value 变量值
	 * @access     public
	 * @return     void
	 */
	function setVar($key, $value){
		$this->vars[$key] = $value;
	}

	/**
	 * 批量设置变量
	 * 
	 * @param      array      $var_arr 变量数组，键是变量名，值是变量值
	 * @access     public
	 * @return     void
	 */
	function setVars($var_arr){
		foreach ($var_arr as $key => $value){
			$this->setVar($key, $value);
		}
	}

	/**
	 * 取消所有变量设置
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function clearVars(){
		$this->vars=array();
	}

	/**
	 * 产生一个错误
	 * 
	 * @param      string     $message 错误信息
	 * @param      int        $mode 错误类型
	 * @access     public
	 * @return     void
	 */
	function raiseError($message='unknown error!', $mode=JIEQI_ERROR_DIE){
		switch ($mode) {
			case JIEQI_ERROR_DIE:
				jieqi_printfail($message);
				//$this->errors[$mode][] = $message;
				break;
			case JIEQI_ERROR_RETURN:
			case JIEQI_ERROR_PRINT:
				$this->errors[$mode][] = $message;
				break;
			default:
				$this->errors[JIEQI_ERROR_RETURN][] = $message;
				break;
		}
	}

	/**
	 * 检查是否有错误
	 * 
	 * @param      int        $mode 错误类型
	 * @access     public
	 * @return     int         返回0表示没错误，大于0表示有错误
	 */
	function isError($mode=0){
		if (empty($mode)) return count($this->errors);
		elseif(isset($this->errors[$mode])) return count($this->errors[$mode]);
		else return 0;
	}

	/**
	 * 获得错误信息
	 * 
	 * @param      int        $mode 错误类型
	 * @access     public
	 * @return     array      错误信息数组
	 */
	function getErrors($mode=''){
		if (empty($mode)) return $this->errors;
		return $this->errors[$mode];
	}

	/**
	 * 清理错误信息
	 * 
	 * @param      int        $mode 错误类型
	 * @access     public
	 * @return     void
	 */
	function clearErrors($mode=''){
		if (empty($mode)) $this->errors = array();
		else $this->errors[$mode] = array();
	}
}

//******************************************************
//区块类
//******************************************************
/**
 * 区块类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiBlock extends JieqiObject{
	var $blockvars = array(); //区块输入参数
	var $module = '';  //区块所属模块
	var $template = ''; //区块模板文件名（注意默认的模板名要和程序文件名一致，一个区块程序名是 block_file.php，模板名是 block_file.html）
	var $cachetime = JIEQI_CACHE_LIFETIME; //缓存时间设置 -1 表示不缓存 0 表示默认系统缓存时间 >0 表示自定义缓存时间

	/**
	 * 构造函数，初始参数赋值
	 * 
	 * @param      array
	 * @access     private
	 * @return     void
	 */
	function JieqiBlock(&$vars){
		global $jieqiModules;
		global $jieqiTpl;
		$this->blockvars=$vars;
		if(empty($this->module)) $this->module = (empty($this->blockvars['module'])) ? 'system' : $this->blockvars['module'];
		if(empty($this->blockvars['template'])) $this->blockvars['template'] = $this->template;
		if(!empty($this->blockvars['template'])){
			$this->blockvars['tlpfile'] = $jieqiModules[$this->module]['path'].'/templates/blocks/'.$this->blockvars['template'];
		}else $this->blockvars['tlpfile'] = '';
		if($this->cachetime == 0) $this->cachetime = JIEQI_CACHE_LIFETIME;
		if(empty($this->blockvars['cachetime'])) $this->blockvars['cachetime'] = $this->cachetime;
		if(empty($this->blockvars['overtime'])) $this->blockvars['overtime'] = 0;
		if(empty($this->blockvars['cacheid'])) $this->blockvars['cacheid'] = NULL;
		if(empty($this->blockvars['compileid'])) $this->blockvars['compileid'] = NULL;

		if(!empty($this->blockvars['template'])) $this->template = $this->blockvars['template'];
		if(!is_object($jieqiTpl) && !empty($this->blockvars['tlpfile'])){
			include_once(JIEQI_ROOT_PATH.'/lib/template/template.php');
			$jieqiTpl =& JieqiTpl::getInstance();
		}
	}

	/**
	 * 获得区块标题
	 * 
	 * @param      void
	 * @access     private
	 * @return     string
	 */
	function getTitle(){
		return isset($this->blockvars['title']) ? $this->blockvars['title'] : '';
	}

	/**
	 * 获得区块内容
	 * 
	 * @param      void
	 * @access     private
	 * @return     string
	 */
	function getContent(){
		global $jieqiTpl;
		if(JIEQI_USE_CACHE && !empty($this->blockvars['tlpfile']) && $this->blockvars['cachetime'] > 0 && $jieqiTpl->is_cached($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'])){
			$jieqiTpl->setCaching(1);
			return $jieqiTpl->fetch($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'], false);
		}else{
			return $this->updateContent(true);
		}
	}

	/**
	 * 更新区块缓存
	 * 
	 * @param      bool        $isreturn 是否返回内容
	 * @access     private
	 * @return     string
	 */
	function updateContent($isreturn=false){
		global $jieqiTpl;
		$this->setContent();
		if(!empty($this->blockvars['tlpfile'])){
			if(JIEQI_USE_CACHE && $this->blockvars['cachetime'] > 0){
				$jieqiTpl->setCaching(2);
				//$jieqiTpl->setCacheTime($this->blockvars['cachetime']);
				//$jieqiTpl->setOverTime($this->blockvars['overtime']);
			}else{
				$jieqiTpl->setCaching(0);
			}
			$tmpvar=$jieqiTpl->fetch($this->blockvars['tlpfile'], $this->blockvars['cacheid'], $this->blockvars['compileid'], $this->blockvars['cachetime'], $this->blockvars['overtime'], false);
			if($isreturn) return $tmpvar;
		}
	}

	/**
	 * 赋值区块内容
	 * 
	 * @param      void
	 * @access     private
	 * @return     void
	 */
	function setContent($isreturn=false){
	}

}

//******************************************************
//缓存类
//******************************************************

/**
 * 缓存类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiCache extends JieqiObject{
	/**
	 * 返回静态实例变量的引用
	 * 
	 * @param      void       
	 * @access     private
	 * @return     array
	 */
	function &retInstance(){
		static $instance = array();
		return $instance;
	}

	/**
	 * 关闭所有连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function close($cache = NULL) {
		if(is_object($cache)){
			$cache->close();
		}else{
			$instance =& JieqiCache::retInstance();
			if(!empty($instance)){
				foreach($instance as $cache){
					$cache->close();
				}
			}
		}
	}

	//创建一个实例
	function &getInstance($type = false, $options = array()){
		if(in_array(strtolower($type), array('file', 'memcached'))) $type = strtolower($type);
		else $type = 'file';
		if(JIEQI_VERSION_TYPE == '' || JIEQI_VERSION_TYPE == 'Free') $type = 'file';
		$class = 'JieqiCache'.ucfirst($type);
		$instance =& JieqiCache::retInstance();
		$inskey = md5($class.'::'.serialize($options));
		if (!isset($instance[$inskey])) {
			$instance[$inskey] = new $class($options);
			if($type != 'file' && $instance[$inskey] === false) $instance[$inskey] = new JieqiCacheFile($options);
		}
		if(!defined('JIEQI_CACHE_CONNECTED')) @define('JIEQI_CACHE_CONNECTED',true);
		return $instance[$inskey];
	}
}

/**
 * 文件缓存类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiCacheFile extends JieqiCache{

	/**
	 * 构建函数
	 * 
	 * @access     public
	 * @return     bool
	 */
	function JieqiCacheFile(){
		return true;
	}

	/**
	 * 关闭所有连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function close($cache = NULL) {
		return true;
	}

	/**
	 * 是否已经缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @param      int         $ttl 缓存时间
	 * @access     public
	 * @return     boolean
	 */
	function iscached($name, $ttl = 0, $over = 0){
		if(empty($ttl) && empty($over)){
			return is_file($name);
		}else{
			$ftime = @filemtime($name);
			if(!$ftime) return false;
			if(($ttl > 0 && JIEQI_NOW_TIME - $ftime > $ttl) || ($over > 0 && $over > $ftime)){
				jieqi_delfile($name);
				return false;
			}else{
				return true;
			}
		}
	}

	/**
	 * 返回缓存的时间
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     boolean
	 */
	function cachedtime($name){
		if(file_exists($name)) return filemtime($name);
		else return 0;
	}

	/**
	 * 更新缓存时间
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     boolean
	 */
	function uptime($name){
		@touch($name, time());
		@clearstatcache();
	}

	/**
	 * 获得缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @param      int         $ttl 缓存时间
	 * @access     public
	 * @return     string
	 */
	function get($name, $ttl = 0, $over = 0){
		if(empty($ttl) && empty($over)){
			return jieqi_readfile($name);
		}else{
			$ftime = @filemtime($name);
			if(!$ftime) return false;
			if(($ttl > 0 && JIEQI_NOW_TIME - $ftime > $ttl) || ($over > 0 && $over > $ftime)){
				jieqi_delfile($name);
				return false;
			}else{
				return jieqi_readfile($name);
			}
		}
	}

	/**
	 * 设置缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @param      string      $value 缓存的内容
	 * @param      int         $ttl 缓存时间
	 * @access     public
	 * @return     bool
	 */
	function set($name, $value, $ttl = 0, $over = 0){
		if(jieqi_checkdir(dirname($name), true)) return jieqi_writefile($name, $value);
		else return false;
	}

	/**
	 * 删除缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     bool
	 */
	function delete($name){
		return jieqi_delfile($name);
	}

	/**
	 * 清理缓存
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function clear($path=''){
		if(!empty($path) && is_dir($path)) jieqi_delfolder($path);
	}
}

/**
 * memcached缓存类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiCacheMemcached extends JieqiCache{

	var $_connected; //是否已经连接
	var $_mc; //memcached对象
	var $_md5key = true; //键值是否md5后保存
	var $_keyext = '.mt'; //保存键值时候，附加一个后缀作为新的键保存时间

	/**
	 * 构建函数，连接memcached服务
	 * 
	 * @param      array      $options 参数数组
	 * @access     public
	 * @return     bool
	 */
	function JieqiCacheMemcached($options){
		if(!class_exists('Memcache')) exit('Memcache class not exists');
		if(!isset($options['host'])) $options['host'] = '127.0.0.1';
		if(!isset($options['port'])) $options['port'] = 11211;
		if(!isset($options['timeout'])) $options['timeout'] = false;
		if(!isset($options['persistent'])) $options['persistent'] = false;

		$func = $options['persistent'] ? 'pconnect' : 'connect';
		$this->_mc  = &new Memcache;
		$this->_connected = ($options['timeout'] === false) ? @$this->_mc->$func($options['host'], $options['port']) : @$this->_mc->func($options['host'], $options['port'], $options['timeout']);
		if(!$this->_connected && JIEQI_ERROR_MODE > 0) echo 'Could not connect to memcache and try to use file cache now!<br />';
		return $this->_connected;
	}

	/**
	 * 关闭所有连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function close($cache = NULL) {
		if(is_object($this->_mc)) return $this->_mc->close();
		else return true;
	}

	/**
	 * 是否已经缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @param      int         $ttl 缓存时间
	 * @access     public
	 * @return     boolean
	 */
	function iscached($name, $ttl = 0, $over = 0){
		return ($this->get($name, $ttl, $over) === false) ? false : true;
	}

	/**
	 * 返回缓存的时间
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     boolean
	 */
	function cachedtime($name){
		if($this->_md5key) $name = md5($name);
		return intval($this->_mc->get($name.$this->_keyext));
	}

	/**
	 * 更新缓存时间
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     boolean
	 */
	function uptime($name){
		if($this->_md5key) $name = md5($name);
		return $this->_mc->set($name.$this->_keyext, time(), 0, 0);
	}

	/**
	 * 获得缓存
	 * 
	 * @param      string      $name 缓存的键名
	 * @param      int         $ttl 缓存时间
	 * @access     public
	 * @return     string
	 */
	function get($name, $ttl = 0, $over = 0){
		$key = ($this->_md5key == true) ? md5($name) : $name;
		$ret = $this->_mc->get($key);
		if($ret === false || (empty($ttl) && empty($over))) return $ret;
		else{
			$ctime = $this->cachedtime($name);
			if(($ttl > 0 && JIEQI_NOW_TIME - $ctime > $ttl) || ($over > 0 && $over > $ctime)){
				$this->delete($name);
				return false;
			}else{
				return $ret;
			}
		}
	}

	/**
	 * 设置缓存
	 * 
	 * @param      string      $name 缓存的键名
	 * @param      string      $value 缓存的内容
	 * @access     public
	 * @return     bool
	 */
	function set($name, $value, $ttl=0, $over = 0){
		if($ttl > 2592000) $ttl = 0;
		if($this->_md5key) $name = md5($name);
		if($over > JIEQI_NOW_TIME && $over - JIEQI_NOW_TIME < $ttl) $ttl = $over - JIEQI_NOW_TIME;
		return ($this->_mc->set($name.$this->_keyext, time(), 0, $ttl) && $this->_mc->set($name, $value, 0, $ttl));
	}

	/**
	 * 删除缓存
	 * 
	 * @param      string      $name 缓存的键名（这里是文件名）
	 * @access     public
	 * @return     bool
	 */
	function delete($name){
		if($this->_md5key) $name = md5($name);
		return ($this->_mc->delete($name.$this->_keyext) && $this->_mc->delete($name));
	}

	/**
	 * 清理缓存
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function clear(){
		return $this->_mc->flush();
	}
}

?>