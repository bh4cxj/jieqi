<?php
/**
 * Òþ²Ø×Ö¶Î
 *
 * Òþ²Ø×Ö¶Î
 * 
 * µ÷ÓÃÄ£°å£ºÎÞ
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formhidden.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormHidden extends JieqiFormElement {


	var $_value;


	function JieqiFormHidden($name, $value){
		$this->setName($name);
		$this->setHidden();
		$this->_value = $value;
		$this->setCaption("");
	}


	function getValue(){
		return $this->_value;
	}


	function render(){
		return "<input type=\"hidden\" name=\"".$this->getName()."\" id=\"".$this->getName()."\" value=\"".$this->getValue()."\" />";
	}
}
?>