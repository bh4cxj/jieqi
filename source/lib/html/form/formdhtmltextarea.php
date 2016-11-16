<?php
/**
 * UBB代码支持文本框
 *
 * UBB代码支持文本框
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formdhtmltextarea.php 326 2009-02-04 00:26:22Z juny $
 */

include_once(JIEQI_ROOT_PATH.'/lib/html/form/formtextarea.php');
//include_once(JIEQI_ROOT_PATH.'/lib/text/textconvert.php');

class JieqiFormDhtmlTextArea extends JieqiFormTextArea
{
	var $_hiddenText;
	function JieqiFormDhtmlTextArea($caption, $name, $value, $rows=10, $cols=50, $hiddentext="jieqiHiddenText")
	{
		$this->JieqiFormTextArea($caption, $name, $value, $rows, $cols);
		$this->_hiddenText = $hiddentext;
	}

	function render()
	{
		$ret ="<textarea class=\"textarea\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" rows=\"".$this->getRows()."\" cols=\"".$this->getCols()."\"".$this->getExtra().">".$this->getValue()."</textarea>";
		if(file_exists(JIEQI_ROOT_PATH.'/scripts/ubbeditor_'.JIEQI_CHAR_SET.'.js')) $jsfile = JIEQI_URL.'/scripts/ubbeditor_'.JIEQI_CHAR_SET.'.js';
		else $jsfile = JIEQI_URL.'/scripts/ubbeditor.js';
		$ret.='<script language="javascript">loadJs("'.$jsfile.'", function(){UBBEditor.Create("'.$this->getName().'");});</script>';
		return $ret;
	}
}
?>