<?php
/**
 * 文本框（单行）
 *
 * 文本框（单行）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formtext.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormText extends JieqiFormElement {

	var $_size;

	var $_maxlength;

	var $_value;

	function JieqiFormText($caption, $name, $size, $maxlength, $value=""){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_size = intval($size);
		$this->_maxlength = intval($maxlength);
		$this->_value = $value;
	}

	function getSize(){
		return $this->_size;
	}

	function getMaxlength(){
		return $this->_maxlength;
	}

	function getValue(){
		return $this->_value;
	}

	function render(){
		return "<input type=\"text\" class=\"text\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" size=\"".$this->getSize()."\" maxlength=\"".$this->getMaxlength()."\" value=\"".$this->getValue()."\"".$this->getExtra()." />";
	}
}
?>