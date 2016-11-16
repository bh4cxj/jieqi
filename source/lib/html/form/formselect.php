<?php
/**
 * 下拉选择框
 *
 * 下拉选择框
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formselect.php 301 2008-12-26 04:36:17Z juny $
 */

class JieqiFormSelect extends JieqiFormElement {


	var $_options = array();


	var $_multiple = false;


	var $_size;


	var $_value = array();


	function JieqiFormSelect($caption, $name, $value=NULL, $size=1, $multiple=false){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_multiple = $multiple;
		$this->_size = intval($size);
		if (isset($value)) {
			if (is_array($value)) {
				foreach ($value as $v) {
					$this->_value[] = $v;
				}
			} else {
				$this->_value[] = $value;
			}
		}
	}


	function isMultiple(){
		return $this->_multiple;
	}


	function getSize(){
		return $this->_size;
	}


	function getValue(){
		return $this->_value;
	}


	function addOption($value, $name=""){
		if ( $name != "" ) {
			$this->_options[$value] = $name;
		} else {
			$this->_options[$value] = $value;
		}
	}


	function addOptionArray($options){
		if ( is_array($options) ) {
			foreach ( $options as $k=>$v ) {
				$this->addOption($k, $v);
			}
		}
	}


	function getOptions(){
		return $this->_options;
	}

	function render(){
		$ret = "<select class=\"select\"  size=\"".$this->getSize()."\"".$this->getExtra()."";
		if ($this->isMultiple() != false) {
			$ret .= " name=\"".$this->getName()."[]\" id=\"".$this->getName()."[]\" multiple=\"multiple\">\n";
		} else {
			$ret .= " name=\"".$this->getName()."\" id=\"".$this->getName()."\">\n";
		}
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<option value=\"".htmlspecialchars($value, ENT_QUOTES)."\"";
			if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
					$ret .= " selected=\"selected\"";
			}
			$ret .= ">".$name."</option>\n";
		}
		$ret .= "</select>";
		return $ret;
	}
}
?>