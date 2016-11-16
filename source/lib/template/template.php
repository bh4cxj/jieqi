<?php
/**
 * 模板引擎
 *
 * 模板引擎
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: template.php 332 2009-02-23 09:15:08Z juny $
 */

if (!defined('TEMPLATE_DIR')) {
	define('TEMPLATE_DIR', dirname(__FILE__) . '/');
}
$jieqiTset = array(); //初始化模板相关设置

/**
 * 字符串截取函数
 * 
 * @param      string      $text 输入的字符串
 * @param      int         $length 截取的长度
 * @param      string      $trimmarker 截取后末尾加上
 * @param      int         $html 字符串是否html代码
 * @access     public
 * @return     string
 */
function truncate($str, $length=10, $trimmarker='', $html=1){
	$start=0;
	if($html && (strpos($str, '<') !== false || strpos($str, '&') !== false)){
		$tmpstr = ''; //返回的字符串
		$tmplen = 0; //返回字符串长度
		$cutlen = $length - strlen($trimmarker); //要截取的字符串
		$len = strlen($str); //原字符串长度
		$i=0; //原字符串指针
		$s=0; //源字符串开始部分不截取的指针
		$htmltag = ''; //html标记临时存储
		$htmlflag = 1; //html标记类型 1 - <>, 2 - &nbsp;
		while($i<$len && $tmplen<$cutlen){
			$add = 1;
			if($str[$i] == '<')	$htmlflag = 1;
			elseif($str[$i] == '&') $htmlflag = 2;
			if($htmlflag > 0){
				$htmltag .= $str[$i];
				if(($htmlflag == 1 && $str[$i] == '>') || ($htmlflag == 2 && $str[$i] == ';')){
					if($s >= $start) $tmpstr .= $htmltag;
					$htmlflag = 0;
					$htmltag = '';
				}
			}else{
				if (ord($str[$i]) > 0x80) {
					if($s >= $start){
						$tmpstr .= $str[$i].$str[$i+1];
						$tmplen += 2;
					}
					$add = 2;
				}elseif($s >= $start){
					$tmpstr .= $str[$i];
					$tmplen ++;
				}
				$s+=$add;
			}
			$i+=$add;
		}
		if($i<$len) $tmpstr.= $trimmarker;
		return $tmpstr;
	}else{
		return jieqi_substr($str, $start, $length, $trimmarker);
	}
}

/**
 * 四则运算函数
 * 
 * @param      string      $str 输入的字符串
 * @param      string      $opt 操作符
 * @param      mixed       $val 操作的值
 * @param      int         $front 操作的值在后面还是前面
 * @access     public
 * @return     string
 */
function arithmetic($str, $opt='', $val=0, $front=0){
	$optary=array('+', '-', '*', '/', '%');
	if(is_numeric($str) && is_numeric($val) && in_array($opt, $optary)){
		if(!$front) eval('$ret = $str '.$opt.' $val;');
		else eval('$ret = $val '.$opt.' $str;');
		return $ret;
	}else{
		return $str;
	}
}

/**
 * 取得子目录
 * 
 * @param      int         $id 数字ID
 * @access     public
 * @return     string
 */
function subdirectory($id){
	return jieqi_getsubdir($id);
}

/**
 * 变量不存在或者为空使用默认值
 * 
 * @param      string      $str 输入的变量
 * @param      string      $val 替换的值
 * @access     public
 * @return     string
 */
function defaultval($str,$val){
	if (!isset($str) || empty($str) || (is_array($str) && count($str) == 0)) $str = $val;
	return $str;
}

/**
 * 模板类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiTpl{
	var $template_dir    =  'templates';  //模板默认目录
	var $compile_dir     =  'compiled';   //编译文件目录
	var $compile_check   =  true;  //检查是否需要重新编译
	var $force_compile   =  false;  //每次强制编译
	var $caching         =  0;  //cache模式 0-无cache 1－使用默认的cache_lifetime
	var $cache_dir       =  'cache';  //cache文件保存目录
	var $cache_lifetime  =  3600;  //cache生存时间（秒） 0＝每次都重新生成cache -1=cache永不过期
	var $cache_overtime  =  0;  //超时时间，这里可以设置一个int型的时间，缓存的时间大于这个时间就认为缓存失效，默认0表示不检查这个设置
	var $left_delimiter  =  '{\?';  //模板左标记
	var $right_delimiter =  '\?}';  //模板右标记
	var $left_comments  =  '{\*';  //注释左标记
	var $right_comments =  '\*}';  //注释右标记
	var $compile_id      = NULL;  //设置编译ID，同一模板可以设置不同id，这对处理多语言很有效
	//********************************************************
	//配置部分完成，以下内部变量
	var $_tpl_vars       = array();  //模板变量
	var $_tmp_vars       = array(); //编译后模板里面的临时变量
	var $_file_perms     = 0777;  //默认文件读写权限
	var $_dir_perms      = 0777;  //默认目录权限
	var $_compile_prefix = '.php'; //编译文件后缀
	var $_include_prefix = '.inc.php'; //编译包含文件后缀

	/**
	 * 构造函数
	 * 
	 * @param      void       
	 * @access     private
	 * @return     void
	 */
	function JieqiTpl(){
		global $jieqiModules;
		$this->template_dir = JIEQI_ROOT_PATH;
		$this->cache_dir = JIEQI_CACHE_PATH;
		$this->compile_dir = JIEQI_COMPILED_PATH;
		if (JIEQI_USE_CACHE) $this->caching = 1;
		else $this->caching = 0;
		$this->cache_lifetime = JIEQI_CACHE_LIFETIME;
		$this->assign(array('jieqi_url' => JIEQI_URL, 'jieqi_rootpath' => JIEQI_ROOT_PATH, 'jieqi_charset' => JIEQI_CHAR_SET, 'jieqi_version' => JIEQI_VERSION, 'jieqi_main_url' => JIEQI_MAIN_URL, 'jieqi_user_url' => JIEQI_USER_URL, 'jieqi_local_url' => JIEQI_LOCAL_URL, 'jieqi_theme' => JIEQI_THEME_NAME, 'jieqi_themeurl' => JIEQI_URL.'/themes/'.JIEQI_THEME_NAME.'/',  'jieqi_sitename' => JIEQI_SITE_NAME, 'jieqi_email' => JIEQI_CONTACT_EMAIL, 'meta_keywords' => JIEQI_META_KEYWORDS, 'meta_description' => JIEQI_META_DESCRIPTION, 'meta_copyright' => JIEQI_META_COPYRIGHT, 'meta_author' => 'http://www.jieqi.com (jieqi cms)', 'jieqi_host'=>JIEQI_LOCAL_HOST, 'jieqi_time'=>JIEQI_NOW_TIME, 'egoldname'=>JIEQI_EGOLD_NAME, 'fun'=>NULL));  //默认参数
		if(defined('JIEQI_SILVER_USAGE') && JIEQI_SILVER_USAGE==1) $this->assign('jieqi_silverusage', 1);
		else $this->assign('jieqi_silverusage', 0);
		if(!empty($_REQUEST['ajax_request'])) $this->assign('ajax_request', 1);
		else $this->assign('ajax_request', 0);
		$this->assign_by_ref('jieqi_modules', $jieqiModules);
	}

	/**
	 * 创建一个实例
	 * 
	 * @param      void
	 * @access     public
	 * @return     object
	 */
	function &getInstance(){
		static $instance;
		if (!isset($instance)) $instance = new JieqiTpl();
		return $instance;
	}
	
	/**
	 * 获得cache模式 0 不缓存 1 自动缓存 2 本次不缓存但是保存缓存
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function getCaching(){
		return $this->caching;
	}

	/**
	 * 设置cache模式 0 不缓存 1 自动缓存 2 本次不缓存但是保存缓存
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function setCaching($num=0){
		$this->caching = (int)$num;
	}
	
	/**
	 * 获得cache生存期，单位是秒
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function getCacheTime(){
		return $this->cache_lifetime;
	}
		

	/**
	 * 设置cache生存期，单位是秒
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function setCacheTime($num=0){
		$this->cache_lifetime = (int)$num;
	}
	
	/**
	 * 获得cache超时时间
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function getOverTime(){
		return $this->cache_overtime;
	}
		

	/**
	 * 设置cache超时时间
	 * 
	 * @param      int         $num
	 * @access     public
	 * @return     void
	 */
	function setOverTime($num=0){
		$this->cache_overtime = (int)$num;
	}

	/**
	 * 模板变量赋值(可以是单个变量或数组)
	 * 
	 * @param      string      $tpl_var 变量名
	 * @param      mixed       $value 变量值
	 * @access     public
	 * @return     void
	 */
	function assign($tpl_var, $value = NULL){
		if (is_array($tpl_var)){
			foreach ($tpl_var as $key => $val) {
				if ($key != '') {
					$this->_tpl_vars[$key] = $val;
				}
			}
		} else {
			if ($tpl_var != '')
			$this->_tpl_vars[$tpl_var] = $value;
		}
	}

	/**
	 * 引用赋值
	 * 
	 * @param      string      $tpl_var 变量名
	 * @param      mixed       $value 变量值
	 * @access     public
	 * @return     void
	 */
	function assign_by_ref($tpl_var, &$value){
		if ($tpl_var != '')
		$this->_tpl_vars[$tpl_var] = &$value;
	}

	/**
	 * 清除某个变量
	 * 
	 * @param      string      $tpl_var 变量名
	 * @access     public
	 * @return     void
	 */
	function clear_assign($tpl_var){
		if (is_array($tpl_var)){
			foreach ($tpl_var as $curr_var){
				unset($this->_tpl_vars[$curr_var]);
			}
		}else{
			unset($this->_tpl_vars[$tpl_var]);
		}
	}

	/**
	 * 清除所有赋值
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function clear_all_assign(){
		$this->_tpl_vars = array();
	}

	/**
	 * 获得所有赋值
	 * 
	 * @param      void
	 * @access     public
	 * @return     void
	 */
	function get_all_assign(){
		return $this->_tpl_vars;
	}

	/**
	 * 设置所有赋值
	 * 
	 * @param      array      $vars
	 * @access     public
	 * @return     void
	 */
	function set_all_assign($vars){
		$this->_tpl_vars = $vars;
	}

	/**
	 * 清除模板的cache
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     public
	 * @return     void
	 */
	function clear_cache($tpl_file = NULL, $cache_id = NULL, $compile_id = NULL){
		global $jieqiCache;
		if (!isset($compile_id)) $compile_id = $this->compile_id;
		if (!isset($tpl_file)) $compile_id = NULL;
		$_auto_id = $this->_get_auto_id($cache_id, $compile_id);
		$_tname = $this->_get_auto_filename($this->cache_dir, $tpl_file, $_auto_id);
		$jieqiCache->delete($_tname);
	}

	/**
	 * 清除所有cache
	 * 
	 * @access     public
	 * @return     void
	 */
	function clear_all_cache(){
		global $jieqiCache;
		$jieqiCache->clear();
	}

	/**
	 * 判断某一模板是否有cache
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @param      int         $cache_time 缓存时间
	 * @param      bool        $return_value 是否返回缓存数据
	 * @access     public
	 * @return     mixed
	 */
	function is_cached($tpl_file, $cache_id = NULL, $compile_id = NULL, $cache_time = NULL, $over_time = NULL, $return_value = false){
		global $jieqiCache;
		//if (!$this->caching) return false;
		
		if(!JIEQI_USE_CACHE) return false;
		if ($this->force_compile) return false;

		if (!isset($compile_id)) $compile_id = $this->compile_id;
		$_auto_id = $this->_get_auto_id($cache_id, $compile_id);
		$_cache_file = $this->_get_auto_filename($this->cache_dir, $tpl_file, $_auto_id);
		
		if(is_null($cache_time)) $cache_time = $this->cache_lifetime;
		if(is_null($over_time)) $over_time = $this->cache_overtime;
		if(empty($over_time)) $over_time = filemtime($tpl_file);
		
		if(!$return_value){
			return $jieqiCache->iscached($_cache_file, $cache_time, $over_time);
		}else{
			return $jieqiCache->get($_cache_file, $cache_time, $over_time);
		}
	}
	
	/**
	 * 获得缓存键名（文件名）
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     public
	 * @return     string
	 */
	function get_cachekey($tpl_file, $cache_id = NULL, $compile_id = NULL){
		return $this->_get_auto_filename($this->cache_dir, $tpl_file, $this->_get_auto_id($cache_id, $compile_id));
	}

	/**
	 * 获得缓存的时间
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     public
	 * @return     int
	 */
	function get_cachedtime($tpl_file, $cache_id = NULL, $compile_id = NULL){
		global $jieqiCache;
		$cachefile = $this->_get_auto_filename($this->cache_dir, $tpl_file, $this->_get_auto_id($cache_id, $compile_id));
		return $jieqiCache->cachedtime($cachefile);
	}
	
	/**
	 * 刷新缓存时间
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     public
	 * @return     int
	 */
	function update_cachedtime($tpl_file, $cache_id = NULL, $compile_id = NULL){
		global $jieqiCache;
		$cachefile = $this->_get_auto_filename($this->cache_dir, $tpl_file, $this->_get_auto_id($cache_id, $compile_id));
		return $jieqiCache->uptime($cachefile);
	}


	/**
	 * 清除某一模板的编译文件，如果不指定则清除所有文件
	 * 
	 * @param      string      $tpl_file 模板名
	 * @param      string      $compile_id
	 * @access     public
	 * @return     string
	 */
	function clear_compiled_tpl($tpl_file = NULL, $compile_id = NULL){
		if (!isset($compile_id)) $compile_id = $this->compile_id;
		$_tname = $this->_get_auto_filename($this->compile_dir, $tpl_file, $compile_id);
		@unlink($_tname.'.php');
		@unlink($_tname.'.inc.php');
	}

	/**
	 * 模板是否存在
	 * 
	 * @param      string      $tpl_file 模板名
	 * @access     public
	 * @return     bool
	 */
	function template_exists($tpl_file){
		return is_file($tpl_file);
	}

	/**
	 * 取得模板变量
	 * 
	 * @param      string      $name 变量名
	 * @access     public
	 * @return     array
	 */
	function &get_template_vars($name=NULL){
		if(!isset($name)) {
			return $this->_tpl_vars;
		}
		if(isset($this->_tpl_vars[$name])) {
			return $this->_tpl_vars[$name];
		}
	}

	/**
	 * 获得编译文件的配置文件名
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $compile_id
	 * @access     public
	 * @return     mixed
	 */
	function get_compiled_inc($resource_name, $compile_id = NULL){
		$resource_dir=dirname($resource_name);
		if(empty($resource_dir) || $resource_dir=='.') $resource_name=$this->template_dir.'/'.$resource_name;
		if (!isset($compile_id)) $compile_id = $this->compile_id;
		$_template_compile_path = $this->_get_compile_path($resource_name);
		if ($this->_is_compiled($resource_name, $_template_compile_path)
		|| $this->_compile_resource($resource_name, $_template_compile_path)){
			$incfile = $_template_compile_path . $this->_include_prefix;
			if(is_file($incfile)) return $incfile;
			else return false;
		}
	}

	/**
	 * 包含编译文件的配置文件
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $compile_id
	 * @param      bool        $retfname 是否返回文件名，默认不返回直接include
	 * @access     public
	 * @return     mixed
	 */
	function include_compiled_inc($resource_name, $compile_id = NULL){
		$incfile = $this->get_compiled_inc($resource_name, $compile_id);
		if(!empty($incfile)) include_once($incfile);
	}

	/**
	 * 输出处理后的页面
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     public
	 * @return     string
	 */
	function display($resource_name, $cache_id = NULL, $compile_id = NULL, $cache_time = NULL, $over_time = NULL){
		$this->fetch($resource_name, $cache_id, $compile_id, $cache_time, $over_time, true);
	}

	/**
	 * 显示或返回处理结果
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @param      bool        $display 是否直接显示
	 * @access     public
	 * @return     string
	 */
	function fetch($resource_name, $cache_id = NULL, $compile_id = NULL, $cache_time = NULL, $over_time = NULL, $display = false){
		global $jieqiCache;
		$resource_dir=dirname($resource_name);
		if(empty($resource_dir) || $resource_dir=='.') $resource_name=$this->template_dir.'/'.$resource_name;
		if (!isset($compile_id)) $compile_id = $this->compile_id;
		$_template_compile_path = $this->_get_compile_path($resource_name);
		if(is_null($cache_time)) $cache_time = $this->cache_lifetime;
		if(is_null($over_time)) $over_time = $this->cache_overtime;
		
		//如果有cache则直接调用cache
		if ($this->caching == 1) {
			$_template_results=$this->is_cached($resource_name, $cache_id, $compile_id, $cache_time, $over_time, true);
			if (false !== $_template_results) {
				$this->include_compiled_inc($resource_name, $compile_id);
				if ($display) {
					echo $_template_results;
					return true;
				} else {
					return $_template_results;
				}
			} else {
				if ($display) {
					header("Last-Modified: ".date('D, d M Y H:i:s', JIEQI_NOW_TIME).' GMT');
				}
			}
		}
		ob_start();
		if ($this->_is_compiled($resource_name, $_template_compile_path)
		|| $this->_compile_resource($resource_name, $_template_compile_path)){
			include($_template_compile_path . $this->_compile_prefix);
		}
		$_template_results = ob_get_contents();
		ob_end_clean();
		//保存cache
		if ($this->caching) {
			$_auto_id = $this->_get_auto_id($cache_id, $compile_id);
			$_cache_file = $this->_get_auto_filename($this->cache_dir, $resource_name, $_auto_id);
			$jieqiCache->set($_cache_file, $_template_results, $cache_time, $over_time);
		}
		if ($display) {
			if (isset($_template_results)) echo $_template_results;
			return true;
		} else {
			if (isset($_template_results)) return $_template_results;
		}
	}

	/**
	 * 获得模板格式字符串编译后的代码或者执行结果
	 * 
	 * @param      string      $str 模板字符串
	 * @param      bool        $retcode 返回编译后的代码还是编译后执行结果，默认返回后者
	 * @access     private
	 * @return     string
	 */
	function parse_string($str, $retcode = false){
		include_once(TEMPLATE_DIR . 'compiler.php');
		$template_compiler =& JieqiCompiler::getInstance();
		$template_compiler->_init_template_vars($this);
		$compiled_content = $template_compiler->_compile_file($str, false);
		if($retcode){
			return $compiled_content;
		}else{
			ob_start();
			eval($compiled_content);
			$results = ob_get_contents();
			ob_end_clean();
			return $results;
		}
	}

	/**
	 * 是否需要编译
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $compile_path 编译路径
	 * @access     private
	 * @return     bool
	 */
	function _is_compiled($resource_name, $compile_path){
		$compile_path .= $this->_compile_prefix;
		if (!$this->force_compile && file_exists($compile_path)) {
			if (!$this->compile_check)  return true;
			else{
				if (!is_file($resource_name)) return false;
				if (filemtime($resource_name) <= filemtime($compile_path)) return true;
				else return false;
			}
		}else{
			return false;
		}
	}

	/**
	 * 写编译文件
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $compile_path 编译路径
	 * @access     private
	 * @return     bool
	 */
	function _compile_resource($resource_name, $compile_path){
		if (!is_file($resource_name)) {
			echo 'Template file ('.str_replace(JIEQI_ROOT_PATH, '', $resource_name).') is not exists!';
			return false;
		}
		$_resource_timestamp = filemtime($resource_name);
		$this->_compile_source($resource_name, $_compiled_content, $_compiled_include);

		$_compile_file = $compile_path.$this->_compile_prefix;
		if(jieqi_checkdir(dirname($_compile_file), true)){
			$ret = jieqi_writefile($_compile_file, $_compiled_content);
			if($ret && $_resource_timestamp) @touch($_compile_file, $_resource_timestamp);
		}

		if(strlen($_compiled_include) > 0){
			$_compile_infile = $compile_path.$this->_include_prefix;
			if(jieqi_checkdir(dirname($_compile_infile), true)){
				$ret1 = jieqi_writefile($_compile_infile, $_compiled_include);
				if($ret1 && $_resource_timestamp) @touch($_compile_infile, $_resource_timestamp);
			}
		}else{
			$this->_unlink($compile_path.$this->_include_prefix);
		}
		if($ret && $_resource_timestamp) @clearstatcache();
		return $ret;
	}

	/**
	 * 编译模板
	 * 
	 * @param      string      $resource_name 模板名
	 * @param      string      $compiled_content 编译后的内容
	 * @param      string      $compiled_include 编译需要include的文件
	 * @access     private
	 * @return     bool
	 */
	function _compile_source($resource_name, &$compiled_content, &$compiled_include){
		include_once(TEMPLATE_DIR . 'compiler.php');
		$template_compiler =& JieqiCompiler::getInstance();
		$template_compiler->_init_template_vars($this);
		$compiled_content = '<?php'."\r\n".$template_compiler->_compile_file($resource_name)."\r\n".'?>';
		$compiled_include = strlen($template_compiler->tplinc) == '' ? '' : '<?php'."\r\n".$template_compiler->tplinc."\r\n".'?>';

		return true;
	}

	/**
	 * 取得编译路径
	 * 
	 * @param      string      $resource_name 模板名
	 * @access     private
	 * @return     string
	 */
	function _get_compile_path($resource_name){
		return $this->_get_auto_filename($this->compile_dir, $resource_name,
		$this->compile_id);
	}

	/**
	 * 取得cache或编译的文件名称
	 * 
	 * @param      string      $auto_base
	 * @param      string      $auto_source
	 * @param      string      $auto_id
	 * @access     private
	 * @return     string
	 */
	function _get_auto_filename($auto_base, $auto_source = NULL, $auto_id = NULL){
		//$auto_source = trim($auto_source);
		$_filename = basename($auto_source);
		$_dir = dirname($auto_source);
		$_return = str_replace(JIEQI_ROOT_PATH, $auto_base, $_dir);
		if($_return == $_dir){
			$_dir = trim(str_replace(array('\\',':'), array('/', ''), $_dir));
			if($dir[0] != '/') $_return=$auto_base.'/'.$_dir;
			else $_return=$auto_base.$_dir;
		}
		if(isset($auto_id) && strlen($auto_id) > 0) {
			$_return .= '/'.$_filename;
			if(is_numeric($auto_id)) $_return .= jieqi_getsubdir(intval($auto_id)).'/'.$auto_id;
			elseif(preg_match('/^\w+$/', $auto_id)) $_return .= '/'.str_replace(array('/', '.', '|'), array('-', '+', '/'), $auto_id);
			else $_return .= '/'.md5($auto_id);
			$_return .= strrchr($_filename,".");
		}else{
			$_return .= '/'.$_filename;
		}
		return $_return;
	}
	
	/**
	 * 自动获取id
	 * 
	 * @param      string      $cache_id
	 * @param      string      $compile_id
	 * @access     private
	 * @return     bool
	 */
	function _get_auto_id($cache_id=NULL, $compile_id=NULL){
		if(isset($cache_id)) return (isset($compile_id)) ? $cache_id . '|' . $compile_id  : $cache_id;
		elseif(isset($compile_id)) return $compile_id;
		else return NULL;
	}

	/**
	 * 删除过期文件
	 * 
	 * @param      string      $resource
	 * @param      int         $exp_time
	 * @access     private
	 * @return     bool
	 */
	function _unlink($resource, $exp_time = NULL){
		if(isset($exp_time)) {
			if(JIEQI_NOW_TIME - @filemtime($resource) >= $exp_time) {
				return @unlink($resource);
			}
		} else {
			return @unlink($resource);
		}
	}

	/**
	 * 包含一个模板
	 * 
	 * @param      array       $params
	 * @access     private
	 * @return     void
	 */
	function _template_include($params){
		$this->_tpl_vars = array_merge($this->_tpl_vars, $params['template_include_vars']);
		$params['template_include_tpl_file']=trim($params['template_include_tpl_file']);
		if($params['template_include_tpl_file'][0] != '/' && $params['template_include_tpl_file'][1] != ':') $params['template_include_tpl_file']=$this->template_dir.'/'.$params['template_include_tpl_file'];

		$_template_compile_path = $this->_get_compile_path($params['template_include_tpl_file']);

		if ($this->_is_compiled($params['template_include_tpl_file'], $_template_compile_path)
		|| $this->_compile_resource($params['template_include_tpl_file'], $_template_compile_path)){
			include($_template_compile_path . $this->_compile_prefix);
		}
	}
}
?>