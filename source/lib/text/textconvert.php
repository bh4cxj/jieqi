<?php
/**
 * 字符串转换
 *
 * 用于显示(替换表情、特殊代码等)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: textconvert.php 331 2009-02-13 09:07:14Z juny $
 */

class TextConvert extends JieqiObject
{
	//表情
	var $smiles = array();

	function TextConvert()
	{

	}

	function getSmiles()
	{
		return $this->smiles;
	}

	function loadSmiles()
	{
		global $jieqiSmiles;
		jieqi_getconfigs('system','smiles');
		$this->smiles=& $jieqiSmiles;
	}

	/**
	* 替换表情符号为表情图片
	*/
	function smile($message)
	{
		if (count($this->smiles)==0) {
			$this->loadSmiles();
		}
		$from=array();
		$to=array();
		foreach($this->smiles as $k=>$v){
			$from[$k]=$v['code'];
			$to[$k]='<img src="'.JIEQI_URL.'/images/smiles/'.$v['url'].'" alt="'.jieqi_htmlstr($v['emotion']).'" border="0">';
		}
		$message=str_replace($from, $to, $message);
		return $message;
	}

	/**
	* 自动分析超级链接
	**/
	function makeClickable(&$text, $limitsize=0)
	{
		$patterns = array(
		"/(?<=[^\[\]=\"'\/\.<>])((https?|ftp):\/\/)([^\s\r\n\t\f<>]+(\.gif|\.jpg|\.jpeg|\.png|\.bmp))/i", 
		"/(?<=[^\[\]=\"'\/\.<>])((https?|ftp):\/\/)([^\s\r\n\t\f<>]+(\.txt|\.htm|\.html|\.css|\.js|\.zip|\.rar|\.gz|\.tar))/i", 
		"/(?<=[^\[\]=\"'\/\.<>a-z0-9-])([a-z]+?):\/\/([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", 
		"/(?<=[^\[\]=\"'\/\.<>])www\.([a-z0-9\-]+)\.([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", 
		"/(?<=[^\[\]=\"'\/\.<>]a-z0-9-)ftp\.([a-z0-9\-]+)\.([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i", 
		"/(?<=[^\[\]=\"'\/\.<>])([a-z0-9\-_\.]{3,})@([a-z0-9\/\-_+=.~!%@?#%&;:$\\│]+)/i"
		);
		if(!empty($limitsize)){
			$replacements = array(
			"<div class=\"divimage\"><img src=\"\\1\\3\" border=\"0\" onload=\"if(this.width>".$limitsize.") {this.resized=true; this.width=".$limitsize.";}\" onmouseover=\"if(this.resized) this.style.cursor='hand';\" onclick=\"if(this.resized) window.open('\\1\\3');\"></div>", 
			"<a href=\"\\1\\3\" target=\"_blank\">\\1\\3</a>", 
			"<a href=\"\\1://\\3\" target=\"_blank\">\\1://\\3</a>", 
			"<a href=\"http://www.\\1.\\2\" target=\"_blank\">www.\\1.\\2</a>", 
			"<a href=\"ftp://ftp.\\1.\\2\" target=\"_blank\">ftp.\\1.\\2</a>", 
			"<a href=\"mailto:\\1@\\2\">\\1@\\2</a>"
			);
		}else{
			$replacements = array(
			"<div class=\"divimage\"><img src=\"\\1\\3\" border=\"0\" class=\"imagecontent\"></div>", 
			"<a href=\"\\1\\3\" target=\"_blank\">\\1\\3</a>", 
			"<a href=\"\\1://\\3\" target=\"_blank\">\\1://\\3</a>", 
			"<a href=\"http://www.\\1.\\2\" target=\"_blank\">www.\\1.\\2</a>", 
			"<a href=\"ftp://ftp.\\1.\\2\" target=\"_blank\">ftp.\\1.\\2</a>", 
			"<a href=\"mailto:\\1@\\2\">\\1@\\2</a>"
			);
		}
		return preg_replace($patterns, $replacements, $text);
	}

	/**
	* 把 JieqiCodes 替换成 HTML 标记
	**/
	function jieqiCodeDecode(&$text, $allowimage = 1, $limitsize=0)
	{
		$patterns = array();
		$replacements = array();
		$patterns[] = "/\[code](.*)\[\/code\]/esU";
		$replacements[] = "'<div class=\"jieqiCode\"><code><pre>'.stripslashes(wordwrap('\\1', 100)).'</pre></code></div>'";
		$patterns[] = "/\[url=(['\"]?)(http[s]?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)(ftp?:\/\/[^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[url=(['\"]?)([^\"'<>]*)\\1](.*)\[\/url\]/sU";
		$replacements[] = '<a href="http://\\2" target="_blank">\\3</a>';
		$patterns[] = "/\[color=(['\"]?)([a-zA-Z0-9]*)\\1](.*)\[\/color\]/sU";
		$replacements[] = '<span style="color: #\\2;">\\3</span>';
		$patterns[] = "/\[size=(['\"]?)([a-z0-9-]*)\\1](.*)\[\/size\]/sU";
		$replacements[] = '<span style="font-size: \\2px;">\\3</span>';
		$patterns[] = "/\[font=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/font\]/sU";
		$replacements[] = '<span style="font-family: \\2;">\\3</span>';
		$patterns[] = "/\[align=(['\"]?)([^;<>\*\(\)\"']*)\\1](.*)\[\/align\]/sU";
		$replacements[] = '<p align="\\2">\\3</p>';
		$patterns[] = "/\[email]([^;<>\*\(\)\"']*)\[\/email\]/sU";
		$replacements[] = '<a href="mailto:\\1">\\1</a>';
		$patterns[] = "/\[b](.*)\[\/b\]/sU";
		$replacements[] = '<b>\\1</b>';
		$patterns[] = "/\[i](.*)\[\/i\]/sU";
		$replacements[] = '<i>\\1</i>';
		$patterns[] = "/\[u](.*)\[\/u\]/sU";
		$replacements[] = '<u>\\1</u>';
		$patterns[] = "/\[d](.*)\[\/d\]/sU";
		$replacements[] = '<del>\\1</del>';
		//$patterns[] = "/\[li](.*)\[\/li\]/sU";
		//$replacements[] = '<li>\\1</li>';
		$patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		if ($allowimage != 1) {
			$replacements[] = '<a href="\\3" target="_blank">\\3</a>';
			$replacements[] = '<a href="\\1" target="_blank">\\1</a>';
			$replacements[] = '<a href="'.JIEQI_URL.'/image.php?id=\\4" target="_blank">\\4</a>';
			$replacements[] = '<a href="'.JIEQI_URL.'/image.php?id=\\2" target="_blank">\\3</a>';
		} else {
			if(!empty($limitsize)){
				$resizestr=' onload="if(this.width>'.$limitsize.') {this.resized=true; this.width='.$limitsize.';}" onmouseover="if(this.resized) this.style.cursor=\'hand\';" onclick="if(this.resized) window.open(\'\\1\\3\');"';
			}else{
				$resizestr='';
			}
			$replacements[] = '<img src="\\3" align="\\2" alt=""'.$resizestr.' />';
			$replacements[] = '<img src="\\1" alt=""'.$resizestr.' />';
			$replacements[] = '<img src="'.JIEQI_URL.'/image.php?id=\\4" align="\\2" alt="\\4"'.$resizestr.' />';
			$replacements[] = '<img src="'.JIEQI_URL.'/image.php?id=\\2" alt="\\3"'.$resizestr.' />';
		}
		$patterns[] = "/\[quote]/sU";
		$replacements[] = 'Quote:'.'<div class="jieqiQuote">';
		$patterns[] = "/\[\/quote]/sU";
		$replacements[] = '</div>';
		$patterns[] = "/\[added]/sU";
		$replacements[] = '<div class="jieqiAdded">';
		$patterns[] = "/\[\/added]/sU";
		$replacements[] = '</div>';
		$patterns[] = "/javascript:/si";
		$replacements[] = "java script:";
		$patterns[] = "/about:/si";
		$replacements[] = "about :";
		return preg_replace($patterns, $replacements, $text);
	}

	/**
	* 过滤文本用于显示
	*
	* @参数   string  $text
	* @参数   bool    $html   allow html?
	* @参数   bool    $smile allow smiles?
	* @参数   bool    $xcode  allow jieqicode?
	* @参数   bool    $image  allow inline images?
	* @参数   bool    $br     convert linebreaks?
	* @返回  string
	**/
	function displayTarea(&$text, $html = 0, $clickable = 1, $smile = 1, $xcode = 1, $image = 1, $limitsize=0)
	{
		if ($html != 1) {
			// 不允许html代码
			$text = jieqi_htmlstr($text);
		}
		if ($clickable != 0) {
			//自动分析链接
			$text = $this->makeClickable($text, 'screen.width*0.75');
		}
		if ($smile != 0) {
			// 显示表情
			$text = $this->smile($text);
		}
		if ($xcode != 0) {
			// 显示特殊代码
			if ($image != 0) {
				// 允许图片
				$text = $this->jieqiCodeDecode($text, 1, $limitsize);
			} else {
				// 不允许图片
				$text = $this->jieqiCodeDecode($text, 0, $limitsize);
			}
		}
		return $text;
	}

}
?>