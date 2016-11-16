<?php
/**
 * FTP操作类
 *
 * 定义FTP相关功能
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ftp.php 318 2009-01-09 04:58:56Z juny $
 */

if(JIEQI_VERSION_TYPE == '' || JIEQI_VERSION_TYPE == 'Free') exit('Your version type is '.JIEQI_VERSION_TYPE.', ftp function is is not supported!'); //免费版不支持

/**
 * FTP类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiFTP extends JieqiObject{
	var $_host;  //服务器
	var $_port = 21;  //端口
	var $_user;  //用户名
	var $_pass;  //密码
	var $_path = '.';  //默认路径
	var $_ssl = 0;  //是否使用SSL连接
	var $_timeout = 0;  //连接超时
	var $_pasv = 1;  //是否被动模式
	var $connid; //连接字符串


	/**
	 * 去掉换行符
	 * 
	 * @param      string      $str
	 * @access     public
	 * @return     string
	 */
	function wipespecial($str) {
		return str_replace(array("\n", "\r"), '', $str);
	}

	/**
	 * 构造函数，设置ftp基本参数
	 * 
	 * @param      string      $ftphost ftp服务器地址
	 * @param      string      $ftpuser 用户名
	 * @param      string      $ftppass 密码
	 * @param      string      $ftppath 默认路径
	 * @param      int         $ftpport 端口号
	 * @access     private
	 * @return     void
	 */
	function JieqiFTP($ftphost = '', $ftpuser = '', $ftppass = '', $ftppath = '.', $ftpport = 21, $timeout = 0, $ftpssl = 0, $ftppasv = 1){
		$this->_host = $this->wipespecial($ftphost);
		$this->_user = $ftpuser;
		$this->_pass = $ftppass;
		$this->_port = intval($ftpport);
		$this->_timeout = intval($timeout);
		$this->_ssl = intval($ftpssl);
		$this->_pasv = intval($ftppasv);
		$this->_path = $ftppath;
	}
	
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
	 * 关闭所有ftp连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function close($ftp = NULL) {
		if(is_object($ftp)){
			$ftp->ftp_close();
		}else{
			$instance =& JieqiFTP::retInstance();
			if(!empty($instance)){
				foreach($instance as $ftp){
					$ftp->ftp_close();
				}
			}
		}
	}
	
	/**
	 * 创建一个实例，如果已经存在则直接返回
	 * 
	 * @param      string      $ftphost ftp服务器地址
	 * @param      string      $ftpuser 用户名
	 * @param      string      $ftppass 密码
	 * @param      string      $ftppath 默认路径
	 * @param      int         $ftpport 端口号
	 * @access     private
	 * @return     void
	 */
	function &getInstance($ftphost = '', $ftpuser = '', $ftppass = '', $ftppath = '.', $ftpport = 21, $timeout = 0, $ftpssl = 0, $ftppasv = 1){
		$instance =& JieqiFTP::retInstance();
		$inskey = md5($ftphost.','.$ftpuser.','.$ftppass.','.$ftppath.','.$ftpport.','.$timeout.','.$ftpssl.','.$ftppasv);
		if (!isset($instance[$inskey])) {
			$instance[$inskey] = new JieqiFTP($ftphost, $ftpuser, $ftppass, $ftppath, $ftpport, $timeout, $ftpssl, $ftppasv);
			$fid = $instance[$inskey]->ftp_connect();
			if(!$fid) return false;
		}
		return $instance[$inskey];
	}

	/**
	 * ftp链接
	 * 
	 * @param      void
	 * @access     public
	 * @return     int
	 */
	function ftp_connect() {
		//@set_time_limit(0);
		$func = $this->_ssl && function_exists('ftp_ssl_connect') ? 'ftp_ssl_connect' : 'ftp_connect';
		if($func == 'ftp_connect' && !function_exists('ftp_connect')) {
			$this->raiseError('FTP not supported', JIEQI_ERROR_RETURN);
			return -4; //不支持ftp函数
		}
		if($this->connid = @$func($this->_host, $this->_port, 20)) {
			if($this->_timeout && function_exists('ftp_set_option')) {
				@ftp_set_option($this->connid, FTP_TIMEOUT_SEC, $this->_timeout);
			}
			if($this->ftp_login($this->_user, $this->_pass)) {
				if($this->_pasv) {
					$this->ftp_pasv(TRUE);
				}
				if($this->ftp_chdir($this->_path)) {
					if(!defined('JIEQI_FTP_CONNECTED')) @define('JIEQI_FTP_CONNECTED',true);
					return 1;
				} else {
					$this->ftp_close();
					$this->raiseError('Chdir '.$this->_path,' error', JIEQI_ERROR_RETURN);
					return -3; //设置目录失败
				}
			} else {
				$this->ftp_close();
				$this->raiseError('FTP login failure', JIEQI_ERROR_RETURN);
				return -2; //登录失败
			}
		} else {
			$this->raiseError('Couldn\'t connect to '.$this->_host.':'.$this->_port, JIEQI_ERROR_RETURN);
			return -2; //连接失败
		}
	}

	/**
	 * 新建目录
	 * 
	 * @param      string      $directory
	 * @access     public
	 * @return     bool
	 */
	function ftp_mkdir($directory) {
		$directory = $this->wipespecial($directory);
		return @ftp_mkdir($this->connid, $directory);
	}

	/**
	 * 删除目录
	 * 
	 * @param      string      $directory
	 * @access     public
	 * @return     bool
	 */
	function ftp_rmdir($directory) {
		$directory = $this->wipespecial($directory);
		return @ftp_rmdir($this->connid, $directory);
	}

	/**
	 * 上传文件
	 * 
	 * @param      string      $remote_file 远程文件名
	 * @param      string      $local_file 本地文件名
	 * @param      int         $mode 传输方式
	 * @param      int         $startpos 开始位置
	 * @access     public
	 * @return     bool
	 */
	function ftp_put($remote_file, $local_file, $mode = FTP_BINARY, $startpos = 0 ) {
		$remote_file = $this->wipespecial($remote_file);
		$local_file = $this->wipespecial($local_file);
		$mode = intval($mode);
		$startpos = intval($startpos);
		return @ftp_put($this->connid, $remote_file, $local_file, $mode, $startpos);
	}

	/**
	 * 取得ftp服务器上文件大小
	 * 
	 * @param      string      $remote_file
	 * @access     public
	 * @return     int
	 */
	function ftp_size($remote_file) {
		$remote_file = $this->wipespecial($remote_file);
		return @ftp_size($this->connid, $remote_file);
	}

	/**
	 * 关闭ftp连接
	 * 
	 * @param      void
	 * @access     public
	 * @return     bool
	 */
	function ftp_close() {
		return @ftp_close($this->connid);
	}

	/**
	 * 删除文件
	 * 
	 * @param      string      $path
	 * @access     public
	 * @return     bool
	 */
	function ftp_delete($path) {
		$path = $this->wipespecial($path);
		return @ftp_delete($this->connid, $path);
	}

	/**
	 * 下载文件
	 * 
	 * @param      string      $local_file 本地文件名
	 * @param      string      $remote_file 远程文件名
	 * @param      int         $mode 传输方式
	 * @param      int         $resumepos 开始位置
	 * @access     public
	 * @return     bool
	 */
	function ftp_get($local_file, $remote_file, $mode = FTP_BINARY, $resumepos = 0) {
		$remote_file = $this->wipespecial($remote_file);
		$local_file = $this->wipespecial($local_file);
		$mode = intval($mode);
		$resumepos = intval($resumepos);
		return @ftp_get($this->connid, $local_file, $remote_file, $mode, $resumepos);
	}

	/**
	 * ftp登录
	 * 
	 * @param      string      $username 用户名
	 * @param      string      $password 密码
	 * @access     public
	 * @return     bool
	 */
	function ftp_login($username, $password) {
		$username = $this->wipespecial($username);
		$password = str_replace(array("\n", "\r"), array('', ''), $password);
		return @ftp_login($this->connid, $username, $password);
	}

	/**
	 * 主动还是被动模式
	 * 
	 * @param      int         $pasv
	 * @access     public
	 * @return     bool
	 */
	function ftp_pasv($pasv) {
		$pasv = intval($pasv);
		return @ftp_pasv($this->connid, $pasv);
	}

	/**
	 * 改变路径
	 * 
	 * @param      string     $directory
	 * @access     public
	 * @return     bool
	 */
	function ftp_chdir($directory) {
		$directory = $this->wipespecial($directory);
		return @ftp_chdir($this->connid, $directory);
	}

	/**
	 * 向服务器发送 SITE 命令
	 * 
	 * @param      string     $cmd
	 * @access     public
	 * @return     bool
	 */
	function ftp_site($cmd) {
		$cmd = $this->wipespecial($cmd);
		return @ftp_site($this->connid, $cmd);
	}

	/**
	 * 改变文件权限
	 * 
	 * @param      int        $mode 访问权限
	 * @param      string     $filename 文件名
	 * @access     public
	 * @return     bool
	 */
	function ftp_chmod($mode, $filename) {
		$mode = intval($mode);
		$filename = $this->wipespecial($filename);
		if(function_exists('ftp_chmod')) {
			return @ftp_chmod($this->connid, $mode, $filename);
		} else {
			return $this->ftp_site($this->connid, 'CHMOD '.$mode.' '.$filename);
		}
	}
	
	/**
	 * 文件重命名
	 * 
	 * @param      string     $oldfile 原文件名
	 * @param      string     $newfile 新文件名
	 * @access     public
	 * @return     bool
	 */
	function ftp_rename($oldfile, $newfile) {
		return @ftp_rename($this->connid, $oldfile, $newfile);
	}

	/**
	 * 获得当前路径
	 * 
	 * @param      void
	 * @access     public
	 * @return     string
	 */
	function ftp_pwd() {
		return @ftp_pwd($this->connid);
	}
	
	/**
	 * 返回给定目录的文件列表
	 * 
	 * @param      string      $path
	 * @access     public
	 * @return     array
	 */
	function ftp_nlist($path) {
		$path = $this->wipespecial($path);
		return @ftp_nlist($this->connid, $path);
	}

	/**
	 * 删除FTP文件夹及里面文件
	 * 
	 * @param      string      $path
	 * @param      bool       $flag true表示删除目录本身（默认），false表示清空目录里面内容
	 * @access     public
	 * @return     bool
	 */
	function ftp_delfolder($path, $flag = true)	{
		$path = $this->wipespecial($path);
		if($flag) $ret  = $this->ftp_rmdir($path) || $this->ftp_delete($path);
		else $ret = false;
		if (!$ret){
			$files = $this->ftp_nlist($path);
			foreach ($files as $values){
				$values = basename($values);        //有的FTP服务器ftp_nlist()返回的是路径+文件名形式的数组
				if(!$this->ftp_delete($path .'/'. $values)){
					$this->ftp_delfolder($path .'/'. $values, true);
				}
			}
			if($flag) return $this->ftp_rmdir($path);
			else return true;
		}else{
			return $ret;
		}
	}

	/**
	 * 根据给定路径字符串，循环创建目录(当前目录下创建)
	 * 
	 * @param      string      $path
	 * @access     public
	 * @return     bool
	 */
	function ftp_mkdirs($path)
	{
		$path = $this->wipespecial($path);
		$path_arr = explode('/',$path);        // 取目录数组
		$path_div  = count($path_arr);         // 取层数

		foreach($path_arr as $val)             // 创建目录
		{
			if($this->ftp_chdir($val) == FALSE)
			{
				$tmp = $this->ftp_mkdir($val);
				if($tmp == FALSE)
				{
					$this->raiseError('FTP mkdir failure', JIEQI_ERROR_RETURN);
					exit;
				}
				$this->ftp_chdir($val);
			}
		}
		for($i=1;$i<=$path_div;$i++)           // 回退到根(创建时的目录)
		{
			@ftp_cdup($this->connid);
		}
	}
	
	/**
	 * 目录拷贝(待测试)
	 * 
	 * @param      string      $srcfolder 原始目录
	 * @param      string      $dstfolder 目标目录
	 * @access     public
	 * @return     bool
	 */
	function ftp_xcopy($srcfolder, $dstfolder)
	{
		//do sth
		$srcfolder = $this->wipespecial($srcfolder);
		$dstfolder = $this->wipespecial($dstfolder);
		$srcfiles = $this->ftp_nlist($srcfolder);
		$this->ftp_mkdirs($dstfolder);
		foreach ($srcfiles as $srcfile)
		{
			$srcfile = basename($srcfile);        //有的FTP服务器ftp_nlist()返回的是路径+文件名形式的数组
			if(!$this->ftp_rename($srcfolder.'/'.$srcfile, $dstfolder.'/'.$srcfile))
			{
				$this->ftp_mkdir($dstfolder.'/'.$srcfile);
				$this->ftp_xcopy($srcfolder.'/'.$srcfile, $dstfolder.'/'.$srcfile);
			}
		}
	}
}
?>