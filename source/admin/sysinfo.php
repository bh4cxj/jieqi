<?php
/**
 * 系统信息及优化建议
 *
 * 检测系统配置，并提供一些优化建议
 * 
 * 调用模板：/templates/admin/sysinfo.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: sysinfo.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_checklogin();
jieqi_loadlang('sysinfo', JIEQI_MODULE_NAME);
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$sysinfos=array();
//php版本 PHP_VERSION
if(floatval(PHP_VERSION) < 4.3) $state='error';
else $state='ok';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_php_version'], 'value'=>PHP_VERSION, 'note'=>$jieqiLang['system']['snote_php_version'], 'state'=>$state);

//操作系统 PHP_OS
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_php_os'], 'value'=>PHP_OS, 'note'=>$jieqiLang['system']['snote_php_os'], 'state'=>'ok');

//硬盘空间
if(empty($_SERVER['DOCUMENT_ROOT'])) $_SERVER['DOCUMENT_ROOT'] = JIEQI_ROOT_PATH;
$tmpvar=intval(disk_free_space($_SERVER['DOCUMENT_ROOT'])/1048576);
if($tmpvar>1024){
	$tmpvar=sprintf("%0.1fG", $tmpvar/1024);
	$state='ok';
}else{
	$tmpvar.='M';
	$state='warning';
}

$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_disk_space'], 'value'=>$tmpvar, 'note'=>$jieqiLang['system']['snote_disk_space'], 'state'=>$state);

//域名 $_SERVER['SERVER_NAME']
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_server_name'], 'value'=>$_SERVER['SERVER_NAME'], 'note'=>$jieqiLang['system']['snote_server_name'], 'state'=>'ok');

//端口 getenv('SERVER_PORT')
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_server_port'], 'value'=>getenv('SERVER_PORT'), 'note'=>$jieqiLang['system']['snote_server_port'], 'state'=>'ok');

//服务器版本 $_SERVER['SERVER_SOFTWARE']
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_server_software'], 'value'=>$_SERVER['SERVER_SOFTWARE'], 'note'=>$jieqiLang['system']['snote_server_software'], 'state'=>'ok');

//服务器语种 getenv("HTTP_ACCEPT_LANGUAGE")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_accept_language'], 'value'=>getenv("HTTP_ACCEPT_LANGUAGE"), 'note'=>$jieqiLang['system']['snote_accept_language'], 'state'=>'ok');

//网站跟目录 $_SERVER['DOCUMENT_ROOT']
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_document_root'], 'value'=>$_SERVER['DOCUMENT_ROOT'], 'note'=>$jieqiLang['system']['snote_document_root'], 'state'=>'ok');

//服务器时间 date('Y-m-d H:i:s')
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_server_time'], 'value'=>date('Y-m-d H:i:s'), 'note'=>$jieqiLang['system']['snote_server_time'], 'state'=>'ok');

//zend engine版本 zend_version()
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_zend_version'], 'value'=>zend_version(), 'note'=>$jieqiLang['system']['snote_zend_version'], 'state'=>'ok');

//zend optimizer 版本
function jieqi_zendoptimizerver(){
	ob_start();
	phpinfo();
	$phpinfo=ob_get_contents();
	ob_end_clean();
	preg_match('/Zend(\s|&nbsp;)Optimizer(\s|&nbsp;)v([\.\d]*),/is', $phpinfo, $matches);
    if(!empty($matches[3])) return $matches[3]; 
    else return '';
}
if(floatval(jieqi_zendoptimizerver()) < 3) $state='error';
else $state='ok';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_zend_optimizer'], 'value'=>jieqi_zendoptimizerver(), 'note'=>$jieqiLang['system']['snote_zend_optimizer'], 'state'=>$state);

//禁止函数（多个用逗号分开） get_cfg_var("disable_functions");
$tmpvar=get_cfg_var("disable_functions");
if(empty($tmpvar)){
	$tmpvar=$jieqiLang['system']['sinfo_empty_value'];
	$state='ok';
}else{
	$state='notice';
}
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_disable_functions'], 'value'=>$tmpvar, 'note'=>$jieqiLang['system']['snote_disable_functions'], 'state'=>$state);

//自定义全局变量 get_cfg_var("register_globals")
if(get_cfg_var("register_globals")) $state='notice';
else $state='ok';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_register_globals'], 'value'=>jieqi_issupport(get_cfg_var("register_globals")), 'note'=>$jieqiLang['system']['snote_register_globals'], 'state'=>$state);

//魔术引用（不推荐）get_cfg_var('magic_quotes_gpc');
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_magic_quotes'], 'value'=>jieqi_issupport(get_cfg_var('magic_quotes_gpc')), 'note'=>$jieqiLang['system']['snote_magic_quotes'], 'state'=>'ok');

//脚本运行可占最大内存 get_cfg_var("memory_limit")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_memory_limit'], 'value'=>get_cfg_var("memory_limit"), 'note'=>$jieqiLang['system']['snote_memory_limit'], 'state'=>'ok');

//脚本上传文件大小限制 get_cfg_var("upload_max_filesize")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_upload_maxsize'], 'value'=>get_cfg_var("upload_max_filesize"), 'note'=>$jieqiLang['system']['snote_upload_maxsize'], 'state'=>'ok');

//POST方法提交限制 get_cfg_var("post_max_size")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_post_maxsize'], 'value'=>get_cfg_var("post_max_size"), 'note'=>$jieqiLang['system']['snote_post_maxsize'], 'state'=>'ok');

//脚本超时时间 get_cfg_var("max_execution_time")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_max_exetime'], 'value'=>get_cfg_var("max_execution_time"), 'note'=>$jieqiLang['system']['snote_max_exetime'], 'state'=>'ok');

//显示错误信息 get_cfg_var("display_errors")
if(get_cfg_var("display_errors")) $state='notice';
else $state='ok';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_display_errors'], 'value'=>jieqi_issupport(get_cfg_var("display_errors")), 'note'=>$jieqiLang['system']['snote_display_errors'], 'state'=>$state);

//SMTP支持 get_magic_quotes_gpc("smtp")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_smtp_support'], 'value'=>jieqi_issupport(get_magic_quotes_gpc("smtp")), 'note'=>$jieqiLang['system']['snote_smtp_support'], 'state'=>'ok');

//PHP安全模式 get_cfg_var("safe_mode")
if(get_cfg_var("safe_mode")) $state='notice';
else $state='ok';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_safe_mode'], 'value'=>jieqi_issupport(get_cfg_var("safe_mode")), 'note'=>$jieqiLang['system']['snote_safe_mode'], 'state'=>$state);

//XML语法解析库 function_exists('xml_parser_set_option');
if(function_exists('xml_parser_set_option')) $state='ok';
else $state='error';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_xml_parser'], 'value'=>jieqi_issupport(function_exists('xml_parser_set_option')), 'note'=>$jieqiLang['system']['snote_xml_parser'], 'state'=>$state);

//XML 解析函数库 get_magic_quotes_gpc("XML Support")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_xml_support'], 'value'=>jieqi_issupport(get_magic_quotes_gpc("XML Support")), 'note'=>$jieqiLang['system']['snote_xml_support'], 'state'=>'ok');

//FTP 文件传输函数库 get_magic_quotes_gpc("FTP support")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_ftp_support'], 'value'=>jieqi_issupport(get_magic_quotes_gpc("FTP support")), 'note'=>$jieqiLang['system']['snote_ftp_support'], 'state'=>'ok');

//允许使用URL打开文件(采集需要，不采集不用) get_cfg_var("allow_url_fopen")
if(get_cfg_var("allow_url_fopen")) $state='ok';
else $state='warning';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_url_fopen'], 'value'=>jieqi_issupport(get_cfg_var("allow_url_fopen")), 'note'=>$jieqiLang['system']['snote_url_fopen'], 'state'=>$state);

//动态链接库 get_cfg_var("enable_dl")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_enable_dl'], 'value'=>jieqi_issupport(get_cfg_var("enable_dl")), 'note'=>$jieqiLang['system']['snote_enable_dl'], 'state'=>'ok');

//IMAP 电子邮件系统函数库 function_exists("imap_close")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_imap_support'], 'value'=>jieqi_issupport(function_exists("imap_close")), 'note'=>$jieqiLang['system']['snote_imap_support'], 'state'=>'ok');

//历法运算函数库 function_exists("JDToGregorian")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_calendar_support'], 'value'=>jieqi_issupport(function_exists("JDToGregorian")), 'note'=>$jieqiLang['system']['snote_calendar_support'], 'state'=>'ok');

//压缩文件函数库(Zlib) function_exists("gzclose")
if(function_exists("gzclose")) $state='ok';
else $state='warning';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_zlib_support'], 'value'=>jieqi_issupport(function_exists("gzclose")), 'note'=>$jieqiLang['system']['snote_zlib_support'], 'state'=>$state);

//Session支持 function_exists("session_start")
if(function_exists("session_start")) $state='ok';
else $state='error';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_session_support'], 'value'=>jieqi_issupport(function_exists("session_start")), 'note'=>$jieqiLang['system']['snote_session_support'], 'state'=>$state);

//Socket支持 function_exists("fsockopen")
if(function_exists("fsockopen")) $state='ok';
else $state='warning';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_socket_support'], 'value'=>jieqi_issupport(function_exists("fsockopen")), 'note'=>$jieqiLang['system']['snote_socket_support'], 'state'=>$state);

//正则表达式函数库 function_exists("preg_match")
if(function_exists("preg_match")) $state='ok';
else $state='error';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_preg_support'], 'value'=>jieqi_issupport(function_exists("preg_match")), 'note'=>$jieqiLang['system']['snote_preg_support'], 'state'=>$state);


//图像函数库 function_exists("imageline")
if(function_exists("imageline")) $state='ok';
else $state='notice';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_gd_support'], 'value'=>jieqi_issupport(function_exists("gd_info")), 'note'=>$jieqiLang['system']['snote_gd_support'], 'state'=>$state);

//gd_info()
//Array ( [GD Version] => bundled (2.0.28 compatible) [FreeType Support] => 1 [FreeType Linkage] => with freetype [T1Lib Support] => 1 [GIF Read Support] => 1 [GIF Create Support] => 1 [JPG Support] => 1 [PNG Support] => 1 [WBMP Support] => 1 [XPM Support] => [XBM Support] => 1 [JIS-mapped Japanese Font Support] => ) 
if(function_exists("gd_info")){
	$tmpary=gd_info();
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_gd_version'], 'value'=>$tmpary['GD Version'], 'note'=>$jieqiLang['system']['snote_gd_version'], 'state'=>'ok');
	
	if($tmpary['FreeType Support']) $state='ok';
	else $state='warning';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_freetype_support'], 'value'=>jieqi_issupport($tmpary['FreeType Support']), 'note'=>$jieqiLang['system']['snote_freetype_support'], 'state'=>$state);
	
	if($tmpary['GIF Read Support']) $state='ok';
	else $state='notice';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_gif_read'], 'value'=>jieqi_issupport($tmpary['GIF Read Support']), 'note'=>$jieqiLang['system']['snote_gif_read'], 'state'=>$state);
	
	if($tmpary['GIF Create Support']) $state='ok';
	else $state='notice';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_gif_create'], 'value'=>jieqi_issupport($tmpary['GIF Create Support']), 'note'=>$jieqiLang['system']['snote_gif_create'], 'state'=>$state);
	
	if($tmpary['JPG Support']) $state='ok';
	else $state='notice';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_jpg_support'], 'value'=>jieqi_issupport($tmpary['JPG Support']), 'note'=>$jieqiLang['system']['snote_jpg_support'], 'state'=>$state);
	
	if($tmpary['PNG Support']) $state='ok';
	else $state='notice';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_png_support'], 'value'=>jieqi_issupport($tmpary['PNG Support']), 'note'=>$jieqiLang['system']['snote_png_support'], 'state'=>$state);
	
	if($tmpary['WBMP Support']) $state='ok';
	else $state='notice';
	$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_wbmp_support'], 'value'=>jieqi_issupport($tmpary['WBMP Support']), 'note'=>$jieqiLang['system']['snote_wbmp_support'], 'state'=>$state);
}

//Iconv编码转换 function_exists("iconv")
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_iconv_support'], 'value'=>jieqi_issupport(function_exists("iconv")), 'note'=>$jieqiLang['system']['snote_iconv_support'], 'state'=>'ok');

//MySQL 数据库 function_exists("mysql_close")
if(function_exists("mysql_close")) $state='ok';
else $state='error';
$sysinfos[]=array('name'=>$jieqiLang['system']['sinfo_mysql_support'], 'value'=>jieqi_issupport(function_exists("mysql_close")), 'note'=>$jieqiLang['system']['snote_mysql_support'], 'state'=>$state);

//mbstring function_exists("mb_eregi")
//SNMP网络管理协议 function_exists("snmpget")

//session是否在链接里面传递(建议 否) get_cfg_var('session.use_trans_sid')

//expose_php是否暴露服务器上装了php get_cfg_var('expose_php')
//getmygid() 不推荐


$jieqiTpl->assign_by_ref('sysinfos', $sysinfos);
$jieqiTpl->setCaching(0);
$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/sysinfo.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');

//是否支持
function jieqi_issupport($var){
	global $jieqiLang;
	if($var) return $jieqiLang['system']['sinfo_is_support'];
	else return $jieqiLang['system']['sinfo_not_support'];
}
?>