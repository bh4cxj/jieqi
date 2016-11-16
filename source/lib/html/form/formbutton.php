<?php
/**
 * 按钮
 *
 * 按钮
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formbutton.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormButton extends JieqiFormElement {

	var $_value;


	var $_type;

	function JieqiFormButton($caption, $name, $value="", $type="button"){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_type = $type;
		$this->_value = $value;
	}


	function getValue(){
		return $this->_value;
	}


	function getType(){
		return $this->_type;
	}

	function render(){
		return "<input type=\"".$this->getType()."\" class=\"button\" name=\"".$this->getName()."\"  id=\"".$this->getName()."\" value=\"".$this->getValue()."\"".$this->getExtra()." />";
	}
}
?>