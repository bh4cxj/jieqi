<?php
/**
 * 编码转换
 *
 * gb2312、big5、utf8 以及拼音之前的转换
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: changecode.php 243 2008-11-28 02:59:57Z juny $
 */

/**
 * gb2312转换成big5
 * 
 * @param      string      $text 输入字符串
 * @param      bool        $addslashes 是否加反斜杠处理，默认否
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_gb2big5($text, $addslashes=false)
{
	$chgcode = new ChangeCode("GB2312", "BIG5", $text);
	if (isset($chgcode)){
		$chgcode->addslashes = $addslashes;
		return($chgcode->ConvertIT());
	}else{
		return $text;
	}
}

/**
 * big5转换成gb2312
 * 
 * @param      string      $text 输入字符串
 * @param      bool        $addslashes 是否加反斜杠处理，默认否
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_big52gb($text, $addslashes=false)
{
	$chgcode = new ChangeCode("BIG5", "GB2312", $text);
	if (isset($chgcode)){
		$chgcode->addslashes = $addslashes;
		return($chgcode->ConvertIT());
	}else{
		return $text;
	}
}

/**
 * gb2312转换成拼音
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_gb2py($text)
{
	$chgcode = new ChangeCode("GB2312", "PinYin", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * big5转换成拼音
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_big52py($text)
{
	$chgcode = new ChangeCode("BIG5", "PinYin", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * gb2312转换成unicode
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_gb2unicode($text)
{
	$chgcode = new ChangeCode("GB2312", "UNICODE", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * big5转换成unicode
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_big52unicode($text)
{
	$chgcode = new ChangeCode("BIG5", "UNICODE", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * gb2312转换成utf-8
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_gb2utf8($text)
{
	if($text == '') return '';
	if (function_exists('iconv')){
		$ret = iconv('GBK', 'UTF-8//IGNORE', $text);
		if($ret) return $ret;
	}
	$chgcode = new ChangeCode("GB2312", "UTF8", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * big5转换成utf-8
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_big52utf8($text)
{
	if($text == '') return '';
	if (function_exists('iconv')){
		$ret = iconv('BIG5', 'UTF-8//IGNORE', $text);
		if($ret) return $ret;
	}
	$chgcode = new ChangeCode("BIG5", "UTF8", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * utf-8转换成gb2312
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_utf82gb($text)
{
	if($text == '') return '';
	if (function_exists('iconv')){
		$ret = iconv('UTF-8', 'GBK//IGNORE', $text);
		if(strlen($ret) > 0 && strlen($ret) >= floor(strlen($text) / 2)) return $ret;
	}
	$chgcode = new ChangeCode("UTF8", "GB2312", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * utf-8转换成big5
 * 
 * @param      string      $text 输入字符串
 * @access     public
 * @return     string      转换后的字符串
 */
function jieqi_utf82big5($text)
{
	if($text == '') return '';
	if (function_exists('iconv')){
		$ret = iconv('UTF-8', 'BIG5//IGNORE', $text);
		if(strlen($ret) > 0 && strlen($ret) >= floor(strlen($text) / 2)) return $ret;
	}
	$chgcode = new ChangeCode("UTF8", "BIG5", $text);
	if (isset($chgcode)) return($chgcode->ConvertIT());
	else return $text;
}

/**
 * 编码转换类
 * 
 * @category   jieqicms
 * @package    system
 */
class ChangeCode extends JieqiObject
{

	// 存放简体中文与拼音对照表
	var $pinyin_table = array();

	// 存放 GB <-> UNICODE 对照表的内容
	var $unicode_table = array();

	// 访问中文繁简互换表的文件指针
	var $ctf;

	// 等待转换的字符串

	var $SourceText = "";

	var $codetable_dir ; //  存放各种语言互换表的目录
	
	var $addslashes = false; //转换编码是否加入魔术转换

	// Chinese 的运行配置

	var $config  =  array(
	'SourceLang'            => '',                    //  字符的原编码
	'TargetLang'            => '',                    //  转换后的编码
	'GBtoBIG5_table'        => 'gb-big5.table',       //  简体中文转换为繁体中文的对照表
	'BIG5toGB_table'        => 'big5-gb.table',       //  繁体中文转换为简体中文的对照表
	'GBtoPinYin_table'      => 'gb-pinyin.table',     //  简体中文转换为拼音的对照表
	'GBtoUnicode_table'     => 'gb-unicode.table',    //  简体中文转换为UNICODE的对照表
	'BIG5toUnicode_table'   => 'big5-unicode.table'   //  繁体中文转换为UNICODE的对照表
	);

	//析构函数
	function ChangeCode( $SourceLang , $TargetLang , $SourceString='')
	{
		$this->codetable_dir =  dirname(__FILE__) . "/";

		if ($SourceLang != '') {
			$this->config['SourceLang'] = $SourceLang;
		}

		if ($TargetLang != '') {
			$this->config['TargetLang'] = $TargetLang;
		}

		if ($SourceString != '') {
			$this->SourceText = $SourceString;
		}

		$this->OpenTable();
	}


	// 将 16 进制转换为 2 进制字符
	function _hex2bin( $hexdata )
	{
		$bindata='';
		for ( $i=0; $i<strlen($hexdata); $i+=2 )
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));

		return $bindata;
	}

	// 打开对照表
	function OpenTable()
	{
		// 假如原编码为简体中文的话
		if ($this->config['SourceLang']=="GB2312") {

			// 假如转换目标编码为繁体中文的话
			if ($this->config['TargetLang'] == "BIG5") {
				$this->ctf = fopen($this->codetable_dir.$this->config['GBtoBIG5_table'], "r");
				if (is_null($this->ctf)) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
			}

			// 假如转换目标编码为拼音的话
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->codetable_dir.$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$i = 0;
				for ($i=0; $i<count($tmp); $i++) {
					$tmp1 = explode("	", $tmp[$i]);
					$this->pinyin_table[$i]=array($tmp1[0],$tmp1[1]);
				}
			}

			// 假如转换目标编码为 UTF8 的话
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->codetable_dir.$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// 假如转换目标编码为 UNICODE 的话
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->codetable_dir.$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}
		}

		// 假如原编码为繁体中文的话
		if ($this->config['SourceLang']=="BIG5") {
			// 假如转换目标编码为简体中文的话
			if ($this->config['TargetLang'] == "GB2312") {
				$this->ctf = fopen($this->codetable_dir.$this->config['BIG5toGB_table'], "r");
				if (is_null($this->ctf)) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
			}
			// 假如转换目标编码为 UTF8 的话
			if ($this->config['TargetLang'] == "UTF8") {
				$tmp = @file($this->codetable_dir.$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,7,6);
			}

			// 假如转换目标编码为 UNICODE 的话
			if ($this->config['TargetLang'] == "UNICODE") {
				$tmp = @file($this->codetable_dir.$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,0,6))]=substr($value,9,4);
			}

			// 假如转换目标编码为拼音的话
			if ($this->config['TargetLang'] == "PinYin") {
				$tmp = @file($this->codetable_dir.$this->config['GBtoPinYin_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				//
				$i = 0;
				for ($i=0; $i<count($tmp); $i++) {
					$tmp1 = explode("	", $tmp[$i]);
					$this->pinyin_table[$i]=array($tmp1[0],$tmp1[1]);
				}
			}
		}

		// 假如原编码为 UTF8 的话
		if ($this->config['SourceLang']=="UTF8") {

			// 假如转换目标编码为 GB2312 的话
			if ($this->config['TargetLang'] == "GB2312") {
				$tmp = @file($this->codetable_dir.$this->config['GBtoUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}

			// 假如转换目标编码为 BIG5 的话
			if ($this->config['TargetLang'] == "BIG5") {
				$tmp = @file($this->codetable_dir.$this->config['BIG5toUnicode_table']);
				if (!$tmp) {
					$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
				}
				$this->unicode_table = array();
				while(list($key,$value)=each($tmp))
				$this->unicode_table[hexdec(substr($value,7,6))]=substr($value,0,6);
			}
		}

	} // 结束 OpenTable 函数

	/**
	* 将简体、繁体中文的 UNICODE 编码转换为 UTF8 字符
	*/
	function CHSUtoUTF8($c)
	{
		if(empty($c)) return '';  //无法解析的字符（239188159 表示中文的 ？，12288 表示中文的全角空格）
		$str="";

		if ($c < 0x80) {
			$str.=$c;
		}

		else if ($c < 0x800) {
			$str.=(0xC0 | $c>>6);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x10000) {
			$str.=(0xE0 | $c>>12);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}

		else if ($c < 0x200000) {
			$str.=(0xF0 | $c>>18);
			$str.=(0x80 | $c>>12 & 0x3F);
			$str.=(0x80 | $c>>6 & 0x3F);
			$str.=(0x80 | $c & 0x3F);
		}
		return $str;

	} // 结束 CHSUtoUTF8 函数

	/**
	* 简体、繁体中文 <-> UTF8 互相转换的函数
	*/
	function CHStoUTF8(){

		if ($this->config["SourceLang"]=="BIG5" || $this->config["SourceLang"]=="GB2312") {
			$ret="";
			while($this->SourceText != ''){

				if(ord(substr($this->SourceText,0,1))>127){

					if ($this->config["SourceLang"]=="BIG5") {
						$utf8=$this->CHSUtoUTF8(hexdec($this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))]));
					}
					if ($this->config["SourceLang"]=="GB2312") {
						$utf8=$this->CHSUtoUTF8(hexdec($this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))-0x8080]));
					}
					for($i=0;$i<strlen($utf8);$i+=3)
					$ret.=chr(substr($utf8,$i,3));

					$this->SourceText=substr($this->SourceText,2,strlen($this->SourceText));
				}

				else{
					$ret.=substr($this->SourceText,0,1);
					$this->SourceText=substr($this->SourceText,1,strlen($this->SourceText));
				}
			}
			// $this->unicode_table = array();
			$this->SourceText = "";
			return $ret;
		}

		if ($this->config["SourceLang"]=="UTF8") {
			$out = "";
			$len = strlen($this->SourceText);
			$i = 0;
			while($i < $len) {
				$c = ord( substr( $this->SourceText, $i++, 1 ) );
				switch($c >> 4)
				{
					case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
						// 0xxxxxxx
						$out .= substr( $this->SourceText, $i-1, 1 );
						break;
					case 12: case 13:
						// 110x xxxx   10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = 0x0;
						$char3 = $this->unicode_table[(($c & 0x1F) << 6) | ($char2 & 0x3F)];
						if(isset($char3)){
						if ($this->config["TargetLang"]=="GB2312")
						$out .= $this->_hex2bin( dechex(  $char3 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
						$out .= $this->_hex2bin( $char3 );
						}else{
							$out .= ' ';
						}
						break;
					case 14:
						// 1110 xxxx  10xx xxxx  10xx xxxx
						$char2 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char3 = ord( substr( $this->SourceText, $i++, 1 ) );
						$char4 = 0x0;
						$char4 = $this->unicode_table[(($c & 0x0F) << 12) | (($char2 & 0x3F) << 6) | (($char3 & 0x3F) << 0)];
						if(isset($char4)){
						if ($this->config["TargetLang"]=="GB2312")
						$out .= $this->_hex2bin( dechex ( $char4 + 0x8080 ) );

						if ($this->config["TargetLang"]=="BIG5")
						$out .= $this->_hex2bin( $char4 );
						}else{
							$out .= ' ';
						}
						break;
				}
			}

			// 返回结果
			return $out;
		}
	} // 结束 CHStoUTF8 函数

	/**
	* 简体、繁体中文转换为 UNICODE编码
	*/
	function CHStoUNICODE()
	{

		$utf="";

		while($this->SourceText != '')
		{
			if (ord(substr($this->SourceText,0,1))>127)
			{

				if ($this->config["SourceLang"]=="GB2312")
				$utf.="&#x".$this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))-0x8080].";";

				if ($this->config["SourceLang"]=="BIG5")
				$utf.="&#x".$this->unicode_table[hexdec(bin2hex(substr($this->SourceText,0,2)))].";";

				$this->SourceText=substr($this->SourceText,2,strlen($this->SourceText));
			}
			else
			{
				$utf.=substr($this->SourceText,0,1);
				$this->SourceText=substr($this->SourceText,1,strlen($this->SourceText));
			}
		}
		return $utf;
	} // 结束 CHStoUNICODE 函数

	/**
	* 简体中文 <-> 繁体中文 互相转换的函数
	*/
	function GB2312toBIG5()
	{
		// 获取等待转换的字符串的总长度
		$max=strlen($this->SourceText)-1;
		$result='';
		for($i=0;$i<$max;$i++){
			$h=ord($this->SourceText[$i]);
			if($h>=160){
				$l=ord($this->SourceText[$i+1]);
				if($h==161 && $l==64){
					$result.="  ";
				}else{
					fseek($this->ctf,($h-160)*510+($l-1)*2);
					if($this->addslashes !== true) $result.=fread($this->ctf,2);
					else $result.=addslashes(fread($this->ctf,2));
				}
				$i++;
			}else{
				$result.=$this->SourceText[$i];
			}
		}

		if($i==$max) $result.=$this->SourceText[$i];

		fclose($this->ctf);

		// 清空 $thisSourceText
		$this->SourceText = "";

		// 返回转换结果
		return $result;
	} // 结束 GB2312toBIG5 函数

	/**
	* 根据所得到的编码搜寻拼音
	*/
	function PinYinSearch($num){

		if($num>0&&$num<160){
			return chr($num);
		}

		elseif($num<-20319||$num>-10247){
			return "";
		}

		else{

			for($i=count($this->pinyin_table)-1;$i>=0;$i--){
				if($this->pinyin_table[$i][1]<=$num)
				break;
			}

			return $this->pinyin_table[$i][0];
		}
	} // 结束 PinYinSearch 函数

	/**
	* 简体、繁体中文 -> 拼音 转换
	*/
	function CHStoPinYin(){

		if ( $this->config['SourceLang']=="BIG5" ) {
			$this->ctf = fopen($this->codetable_dir.$this->config['BIG5toGB_table'], "r");
			if (is_null($this->ctf)) {
				$this->raiseError("Open code table file failure!", JIEQI_ERROR_RETURN);
			}

			$this->SourceText = $this->GB2312toBIG5();
			$this->config['TargetLang'] = "PinYin";
		}

		$ret = array();
		$ri = 0;
		for($i=0;$i<strlen($this->SourceText);$i++){

			$p=ord(substr($this->SourceText,$i,1));

			if($p>160){
				$q=ord(substr($this->SourceText,++$i,1));
				$p=$p*256+$q-65536;
			}

			$ret[$ri]=$this->PinYinSearch($p);
			$ri = $ri + 1;
		}

		// 清空 $this->SourceText
		$this->SourceText = "";

		$this->pinyin_table = array();

		// 返回转换后的结果
		return implode(" ", $ret);
	} // 结束 CHStoPinYin 函数

	/**
	* 输出转换结果
	*/
	function ConvertIT()
	{
		// 判断是否为中文繁、简转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && ($this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->GB2312toBIG5();
		}

		// 判断是否为简体中文与拼音转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="PinYin" ) {

			return $this->CHStoPinYin();
		}

		// 判断是否为简体、繁体中文与UTF8转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5" || $this->config['SourceLang']=="UTF8") && ($this->config['TargetLang']=="UTF8" || $this->config['TargetLang']=="GB2312" || $this->config['TargetLang']=="BIG5") ) {
			return $this->CHStoUTF8();
		}

		// 判断是否为简体、繁体中文与UNICODE转换
		if ( ($this->config['SourceLang']=="GB2312" || $this->config['SourceLang']=="BIG5") && $this->config['TargetLang']=="UNICODE" ) {
			return $this->CHStoUNICODE();
		}

	} // 结束 ConvertIT 函数

} // 结束类库

?>