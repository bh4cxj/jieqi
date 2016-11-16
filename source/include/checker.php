<?php   
/**
 * 数据校验类
 *
 * 数据校验类
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
 * 数据校验类
 * 
 * @category   jieqicms
 * @package    system
 */
class JieqiChecker extends JieqiObject{
	/**
	 * 构造函数
	 * 
	 * @param      void
	 * @access     private
	 * @return     void
	 */
	function jieqiChecker(){
	}

	/**
	 * 校验一个变量
	 * 
	 * @param      string      $val 需要校验的变量
	 * @param      string      $fun 校验函数，多个函数用"|"分割，函数参数用":"分割，如 fun1:var11:var12|fun2:var21
	 * @access     public
	 * @return     void
	 */
	function checkvar(&$val, $fun){
		$funary = explode($fun);
		foreach($funary as $v){
			$params = explode(':', $v);
			$fname = $params[0];
			unset($params[0]);
			$parnum = count($params);
			if(!empty($fname) && method_exists($this, $fname)){
				switch($parnum){
					case 1:
						$this->$fname($val, $params[0]);
						break;
					case 2:
						$this->$fname($val, $params[0], $params[1]);
						break;
					case 3:
						$this->$fname($val, $params[0], $params[1], $params[2]);
						break;
					case 4:
						$this->$fname($val, $params[0], $params[1], $params[2], $params[3]);
						break;
					default:
						$this->$fname($val);
						break;
				}
			}
		}
	}

	/**
	 * 校验一组变量
	 * 
	 * @param      array       $vals 变量数组
	 * @param      array       $funs 函数数组
	 * @access     public
	 * @return     string
	 */
	function checkvars(&$vals, $funs){
		foreach($vals as $k=>$v) $this->checkvar($vals[$k], $funs[$k]);
	}
	
	/**
	 * 校验错误信息
	 * 
	 * @param      string      $err
	 * @access     public
	 * @return     bool
	 */
	function _error($err){
		$this->raiseError($err, JIEQI_ERROR_RETURN);
		return false;
	}

	/**
	 * 必填项目
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_required(&$value){
		return (strlen($value) > 0) ? true : $this->_error('is_required');
	}

	/**
	 * 必须为数字
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_numeric(&$value){
		return (is_numeric($value)) ? true : $this->_error('is_numeric');
	}

	/**
	 * 必须为字母
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_alpha(&$value){
		return (ctype_alpha($value)) ? true : $this->_error('is_alpha');
	}

	/**
	 * 必须为字母或数字
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_alnum(&$value){
		return (ctype_alnum($value)) ? true : $this->_error('is_alnum');
	}

	/**
	 * 只能包括字母、数字和下划线
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_aldash(&$value){
		return preg_match("/^[a-z0-9_]+$/i", $value) ? true : $this->_error('is_aldash');
	}

	/**
	 * 是否email
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_email(&$value){
		return preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i",$value) ? true : $this->_error('is_email');
	}

	/**
	 * 是否url
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_url(&$value){
		return preg_match("/^https?:\/\/[a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+$/i",$value) ? true : $this->_error('is_url');
	}

	/**
	 * 是否日期，格式为 yyyy-mm-dd
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_date(&$value){
		return preg_match("/^\d{2,4}-\d{1,2}-\d{1,2}$/i",$value) ? true : $this->_error('is_date');
	}

	/**
	 * 是否时间，格式为 hh:ii:ss
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_time(&$value){
		return preg_match("/^\d{1,2}:\d{1,2}:\d{1,2}$/i",$value) ? true : $this->_error('is_time');
	}

	/**
	 * 是否日期时间，格式为 yyyy-mm-dd hh:ii:ss
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_datetime(&$value){
		return preg_match("/^\d{2,4}-\d{1,2}-\d{1,2} \d{1,2}:\d{1,2}:\d{1,2}$/i",$value) ? true : $this->_error('is_datetime');
	}

	/**
	 * 是否ip格式
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool
	 */
	function is_ip(&$value){
		return preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i",$value) ? true : $this->_error('is_ip');
	}

	/**
	 * 是否正则匹配
	 * 
	 * @param      string      $value
	 * @param      string      $match 匹配正则
	 * @access     public
	 * @return     bool
	 */
	function is_match(&$value, $match){
		return preg_match($match,$value) ? true : $this->_error('is_match');
	}

	/**
	 * 字符串最小长度
	 * 
	 * @param      string      $value
	 * @param      int         $min
	 * @access     public
	 * @return     bool
	 */
	function str_min(&$value, $min=0){
		return (strlen($value) >= intval($min)) ? true : $this->_error('str_min');
	}

	/**
	 * 字符串最大长度
	 * 
	 * @param      string      $value
	 * @param      int         $max
	 * @access     public
	 * @return     bool
	 */
	function str_max(&$value, $max=99999999){
		return (strlen($value) <= intval($max)) ? true : $this->_error('str_max');
	}

	/**
	 * 字符串长度区间
	 * 
	 * @param      string      $value
	 * @param      int         $min
	 * @param      int         $max
	 * @access     public
	 * @return     bool
	 */
	function str_between(&$value, $min=0, $max=99999999){
		return (strlen($value) >= intval($min) && strlen($value) <= intval($max)) ? true : $this->_error('str_between');
	}

	/**
	 * 数字最小值
	 * 
	 * @param      string      $value
	 * @param      int         $min
	 * @access     public
	 * @return     bool
	 */
	function num_min(&$value, $min=0){
		return ($value >= $min) ? true : $this->_error('num_min');
	}

	/**
	 * 数字最大值
	 * 
	 * @param      string      $value
	 * @param      int         $max
	 * @access     public
	 * @return     bool
	 */
	function num_max(&$value, $max=99999999){
		return ($value <= $max) ? true : $this->_error('num_max');
	}

	/**
	 * 数字大小区间
	 * 
	 * @param      string      $value
	 * @param      int         $min
	 * @param      int         $max
	 * @access     public
	 * @return     bool
	 */
	function num_between(&$value, $min=0, $max=99999999){
		return ($value >= $min && $value <= $max) ? true : $this->_error('num_between');
	}

	/**
	 * 检查变量是否不含禁用单词  
	 *
	 * @param      string      $value
	 * @param      mixed       $words 可能是字符串或者数组，字符串的话多个关键词则每个一行，支持 * 和 ? 通配符
	 * @param      bool        $retmatch 是否返回匹配的单词，默认false表示不返回
	 * @access     public
	 * @return     mixed       $retmatch=false时候返回true或者false，$retmatch=true 时候返回true或者含匹配单词的array
	 */
	function deny_words(&$value, $words, $retmatch = false){
		if(!is_array($words)) $words = explode("\n", strval($words));
		if(count($words) > 0){
			$pregstr = '';
			foreach($words as $v){
				$v = trim($v);
				if(strlen($v) > 0){
					if($pregstr != '') $pregstr.='|';
					$pregstr .= str_replace(array('\\*', '\\?'), array('.*','.?'), preg_quote($v, '/'));
				}
			}
			if($pregstr == '') return true;
			else{
				if($retmatch){
					if(preg_match_all('/'.$pregstr.'/is', $value, $matches)){
						$this->raiseError('deny_words', JIEQI_ERROR_RETURN);
						return $matches[0];
					}else return true;
				}else{
					if(preg_match('/'.$pregstr.'/is', $value)){
						$this->raiseError('deny_words', JIEQI_ERROR_RETURN);
						return false;
					}else return true;
				}
			}
		}else{
			return true;
		}
	}

	/**
	 * 替换单词
	 * 
	 * @param      string      $value
	 * @param      mixed       $words 字符串或者数组，数组的话键替换成值，字符串一行一个替换，用 abc=def 表示 abc替换成def
	 * @param      string      $hide 默认隐藏的单词替换成$hide字符串
	 * @access     public
	 * @return     string
	 */
	function replace_words(&$value, $words, $hide = '**'){
		$from = array();
		$to = array();
		if(is_array($words)){
			foreach($words as $k=>$v){
				$k = trim(strval($k));
				if(strlen($k) > 0){
					$from[] = $k;
					$to[] = trim(strval($v));
				}
			}
		}else{
			$words = explode("\n", strval($words));
			foreach($words as $word){
				$tmpary = explode('=', $word);
				$tmpary[0] = trim($tmpary[0]);
				if(strlen($tmpary[0]) > 0){
					if(!isset($tmpary[1]) || strlen(trim($tmpary[1])) == 0) $tmpary[1] = $hide;
					$from[] = $tmpary[0];
					$to[] = trim($tmpary[1]);
				}
			}
		}
		if(count($from) > 0) $value = str_replace($from, $to, $value);
		return $value;
	}

	/**
	 * 检查是否非灌水贴
	 * 
	 * @param      string      $value
	 * @param      int         $level 严格等级，越大越严格
	 * @access     public
	 * @return     bool        非灌水贴返回true，是灌水返回false
	 */
	function deny_rubbish(&$value, $level=1){
		if(empty($level)) return true;
		$ret=true;
		$len=strlen($value);

		$specialnum=0; //特殊字符数
		$tmpstr="";
		$tmpstr1="";
		$renum=0;
		for($i=0;$i<$len;$i++){
			if(ord($value[$i])>0x80){
				$tmpstr=$value[$i].$value[$i+1];
				$i++;
			}else{
				$tmpstr=$value[$i];
				$tmpasc=ord($value[$i]);
				if($tmpasc<0x41 || ($tmpasc>0x5a && $tmpasc<0x61) || $tmpasc>0x7a){
					$specialnum++;
				}
			}
			if($tmpstr==$tmpstr1){
				$renum++;
				if($renum > 4){
					$this->raiseError('deny_rubbish', JIEQI_ERROR_RETURN);
					return false;
				}
			}else{
				$renum=0;
			}
			if($tmpstr != ' ') $tmpstr1=$tmpstr;
		}
		//特殊字符多于1/2
		if($specialnum > 10 && ($specialnum * 2) > $len){
			$this->raiseError('deny_rubbish', JIEQI_ERROR_RETURN);
			return false;
		}
		return $ret;
	}


	/**
	 * 检查变量是否允许的类型
	 * 
	 * @param      string      $value
	 * @param      mixed       $types 允许的类型，可能是字符串或者数组，允许 int integer numeric float bool string array object
	 * @access     public
	 * @return     bool        允许类型返回true，禁止类型返回false
	 */
	function auth_type(&$value, $types){
		if(!is_array($types)){
			$types = array(strval($types));
		}
		if(count($types) > 0){
			$alltypes = array('int', 'integer', 'numeric', 'float', 'bool', 'string', 'array', 'object');
			foreach($types as $type){
				if(in_array($type, $alltypes) && call_user_func('is_'.$type, $value)) return true;
			}
			$this->raiseError('auth_type', JIEQI_ERROR_RETURN);
			return false;
		}else{
			return true;
		}
	}

	/**
	 * 检查发帖是否已过时间间隔
	 * 
	 * @param      int         $sec 间隔的秒数
	 * @param      string      $svar SESSION里面的记录变量名
	 * @param      string      $cvar COOKIE里面的记录变量名
	 * @access     public
	 * @return     bool 超出时间间隔返回true，在限制时间内返回false
	 */
	function interval_time($sec, $svar, $cvar='jieqiVisitTime'){
		$sec = intval($sec);
		if(empty($sec)) return true;
		if(isset($_COOKIE[$cvar])) $jieqi_vtime = jieqi_strtosary($_COOKIE[$cvar]);
		else $jieqi_vtime = array();
		if(!empty($_SESSION[$svar])) $logtime = $_SESSION[$svar];
		elseif(!empty($jieqi_vtime[$svar])) $logtime = $jieqi_vtime[$svar];
		else $logtime = 0;
		if($logtime > 0 && JIEQI_NOW_TIME - $logtime < $sec){
			$this->raiseError('interval_time', JIEQI_ERROR_RETURN);
			return false;
		}
		$_SESSION[$svar] = JIEQI_NOW_TIME;
		$jieqi_vtime[$svar] = JIEQI_NOW_TIME;
		setcookie($cvar, jieqi_sarytostr($jieqi_vtime), JIEQI_NOW_TIME + 3600, '/', JIEQI_COOKIE_DOMAIN, 0);
		return true;
	}

	/**
	 * 检查变脸是否是正确的图形校验码
	 * 
	 * @param      string      $code 用户输入的校验码
	 * @param      string      $svar session里面的校验码变量名
	 * @access     public
	 * @return     bool        正确返回true，错误返回false
	 */
	function valid_checkcode($code, $svar = 'jieqiCheckCode'){
		return (empty($_SESSION[$svar]) || $code == $_SESSION[$svar]) ? true : $this->_error('valid_checkcode');
	}

	/**
	 * 检查字符串是否合法标题
	 * 
	 * @param      string      $value
	 * @access     public
	 * @return     bool        合法返回true,不合法返回false
	 */
	function safe_title(&$value)
	{
		$len=strlen($value);
		for($i=0; $i<$len; $i++){
			$tmpvar=ord($value[$i]);
			if ($tmpvar > 0x80) {
				$i++;
			}else{
				//34-" 38-& 39-' 44-, 47-/ 59-; 60-< 62-> 92-\ 125-|
				if($tmpvar==34 || $tmpvar==38 || $tmpvar==39 || $tmpvar==44 || $tmpvar==47 || $tmpvar==59 || $tmpvar==60 || $tmpvar==62 || $tmpvar==92 || $tmpvar==124){
					$this->_error('safe_title');
					return false;
				}
			}
		}
		return true;
	}
}
?>