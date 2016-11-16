<?php
/**
 * 选择框
 *
 * 一个或多个选择框
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formcheckbox.php 301 2008-12-26 04:36:17Z juny $
 */

class JieqiFormCheckBox extends JieqiFormElement {


	var $_options = array();


	var $_value = array();


	function JieqiFormCheckBox($caption, $name, $value = NULL){
		$this->setCaption($caption);
		$this->setName($name);
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


	function getValue(){
		return $this->_value;
	}


	function addOption($value, $name=""){
		if ($name != "") {
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
		$ret = "";
		if ( count($this->getOptions()) > 1 && substr($this->getName(), -2, 2) != "[]" ) {
			$newname = $this->getName()."[]";
			$this->setName($newname);
		}
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type=\"checkbox\" class=\"checkbox\" name=\"".$this->getName()."\" value=\"".$value."\"";
			if (count($this->getValue()) > 0 && in_array($value, $this->getValue())) {
				$ret .= " checked=\"checked\"";
			}
			$ret .= $this->getExtra()." />".$name."\n";
		}
		return $ret;
	}
}
?>