<?php
/**
 * 浏览器客户端信息获取
 *
 * 浏览器客户端信息获取
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: visitorinfo.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * 浏览器客户端信息获取类
 * 
 * @category   jieqicms
 * @package    system
 */
class VisitorInfo
{

	/**
	 * 取得IP
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getIp()
	{
		if(isset($_SERVER['HTTP_CLIENT_IP'])) $ip=$_SERVER['HTTP_CLIENT_IP'];
		elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		else  $ip=$_SERVER['REMOTE_ADDR'];
		$ip=trim($ip);
		if(!is_numeric(str_replace('.','',$ip))) $ip='0.0.0.0';
		return $ip;
	}

	/**
	 * 取得IP所在的地理位置
	 * 
	 * @param      string      $ip
	 * @access     public
	 * @return     string
	 */
	function getIpLocation ( $ip = '' )
	{
		include_once(JIEQI_ROOT_PATH.'/include/ip2location.php');

		if (empty($ip)) $ip = VisitorInfo::getIp();
		
		if($ip=='0.0.0.0') return 'unknow';

		$wry = new Ip2Location ;

		$wry->qqwry ( $ip );

		$returnVal = $wry->Country.$wry->Local ;
		return $returnVal;
	}

	/**
	 * 取得当前访问的uri
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getUrl(){
		$server = substr(getenv('SERVER_SOFTWARE'), 0, 3);

		if($server == 'Apa')
		{
			$wookie = $server;
			$url    = getenv('REQUEST_URI');
		}
		else if($server == 'Mic' || $server == 'Aby')
		{
			$protocol = (getenv('HTTPS') == 'off') ? ('http://') : ('https://');
			$query    = (getenv('QUERY_STRING'))   ? ('?'.getenv('QUERY_STRING')) : ('');
			$url      = $protocol.getenv('SERVER_NAME').getenv('SCRIPT_NAME').$query;
		}
		else if($server == 'Aby')
		{
			$protocol = (getenv('HTTPS') == 'on') ? ('https://') : ('http://');
			$query    = (getenv('QUERY_STRING'))  ? ('?'.getenv('QUERY_STRING')) : ('');
			$url      = $protocol.getenv('SERVER_NAME').getenv('SCRIPT_NAME').$query;
		}
		else
		{
			$url = getenv('REQUEST_URI');
		}

		return $url;
	}

	/**
	 * 取得浏览器名称
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getBrowser()
	{
		global $_SERVER;
		$Agent = $_SERVER['HTTP_USER_AGENT'];
		$browser = $browserver = '';
		$Browsers = array('Lynx', 'MOSAIC', 'AOL', 'Opera', 'JAVA', 'MacWeb', 'WebExplorer', 'OmniWeb');
		for($i = 0; $i <= 7; $i ++){
			if(strpos($Agent, $Browsers[$i])){
				$browser = $Browsers[$i];
			}
		}
		if(ereg('Mozilla', $Agent))
		{

			if(ereg('MSIE', $Agent)){
				preg_match('/MSIE (.*);/U',$Agent,$args);
				$browserver = $args[1];
				$browser = 'Internet Explorer';
			}
			else if(ereg('Opera', $Agent)) {
				$temp = explode(')', $Agent);
				$browserver = $temp[1];
				$temp = explode(' ', $browserver);
				$browserver = $temp[2];
				$browser = 'Opera';
			}
			else {
				$temp = explode('/', $Agent);
				$browserver = $temp[1];
				$temp = explode(' ', $browserver);
				$browserver = $temp[0];
				$browser = 'Netscape Navigator';
			}
		}
		//$browserver = preg_replace('/([d.]+)/','\1',$browserver);
		if($browser != ''){
			$browseinfo = $browser.' '.$browserver;
		} else {
			$browseinfo = false;
		}
		return $browseinfo;
	}

	/**
	 * 取得操作系统名称
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getOS ()
	{
		global $_SERVER;
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$os = false;
		if (eregi('win', $agent) && strpos($agent, '95')){
			$os = 'Windows 95';
		}
		else if (eregi('win 9x', $agent) && strpos($agent, '4.90')){
			$os = 'Windows ME';
		}
		else if (eregi('win', $agent) && ereg('98', $agent)){
			$os = 'Windows 98';
		}
		else if (eregi('win', $agent) && eregi('nt 5.1', $agent)){
			$os = 'Windows XP';
		}
		else if (eregi('win', $agent) && eregi('nt 5', $agent)){
			$os = 'Windows 2000';
		}
		else if (eregi('win', $agent) && eregi('nt', $agent)){
			$os = 'Windows NT';
		}
		else if (eregi('win', $agent) && ereg('32', $agent)){
			$os = 'Windows 32';
		}
		else if (eregi('linux', $agent)){
			$os = 'Linux';
		}
		else if (eregi('unix', $agent)){
			$os = 'Unix';
		}
		else if (eregi('sun', $agent) && eregi('os', $agent)){
			$os = 'SunOS';
		}
		else if (eregi('ibm', $agent) && eregi('os', $agent)){
			$os = 'IBM OS/2';
		}
		else if (eregi('Mac', $agent) && eregi('PC', $agent)){
			$os = 'Macintosh';
		}
		else if (eregi('PowerPC', $agent)){
			$os = 'PowerPC';
		}
		else if (eregi('AIX', $agent)){
			$os = 'AIX';
		}
		else if (eregi('HPUX', $agent)){
			$os = 'HPUX';
		}
		else if (eregi('NetBSD', $agent)){
			$os = 'NetBSD';
		}
		else if (eregi('BSD', $agent)){
			$os = 'BSD';
		}
		else if (ereg('OSF1', $agent)){
			$os = 'OSF1';
		}
		else if (ereg('IRIX', $agent)){
			$os = 'IRIX';
		}
		else if (eregi('FreeBSD', $agent)){
			$os = 'FreeBSD';
		}
		else if (eregi('teleport', $agent)){
			$os = 'teleport';
		}
		else if (eregi('flashget', $agent)){
			$os = 'flashget';
		}
		else if (eregi('webzip', $agent)){
			$os = 'webzip';
		}
		else if (eregi('offline', $agent)){
			$os = 'offline';
		}
		else {
			$os = 'Unknown';
		}
		return $os;
	}

	/**
	 * 取得链接到此的页的地址（上一页）
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function getFromUrl()
	{
		if (!empty($_REQUEST['fromurl']))$fromUrl = $_REQUEST['fromurl'] ;
		else if ($_SERVER['HTTP_REFERER']!="")$fromUrl = $_SERVER['HTTP_REFERER'] ;
		else $fromUrl = $_SERVER['REQUEST_URI'];
		return $fromUrl ;
	}

}

?>