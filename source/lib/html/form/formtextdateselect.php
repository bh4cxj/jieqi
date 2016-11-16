<?php
/**
 * 日期选择
 *
 * 日期选择
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formtextdateselect.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormTextDateSelect extends JieqiFormText
{

	function JieqiFormTextDateSelect($caption, $name, $size = 18, $value= '')
	{
		if(is_numeric($value)){
			if($value==0) $value=date(JIEQI_DATE_FORMAT, JIEQI_NOW_TIME);
			else $value=date(JIEQI_DATE_FORMAT, $value);
		}
		$this->JieqiFormText($caption, $name, $size, 10, $value);
	}

	function render()
	{
		$ret="<input type=\"text\" class=\"text\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" size=\"".$this->getSize()."\" maxlength=\"".$this->getMaxlength()."\" value=\"".$this->getValue()."\"".$this->getExtra()." onfocus=\"showCalendar(this,event)\" onclick=\"showCalendar(this,event)\" />";
		if(!defined('JIEQI_CALENDAR_INCLUDE')){
			define('JIEQI_CALENDAR_INCLUDE', true);
			$ret="<script src=\"".JIEQI_URL."/scripts/calendar.js"."\"></script>".$ret;
		}
		return $ret;
	}
}
?>