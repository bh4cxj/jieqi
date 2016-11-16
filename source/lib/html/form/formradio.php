<?php
/**
 * 单选按钮组
 *
 * 单选按钮组
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formradio.php 301 2008-12-26 04:36:17Z juny $
 */

class JieqiFormRadio extends JieqiFormElement {


	var $_options = array();


	var $_value;


	function JieqiFormRadio($caption, $name, $value = NULL){
		$this->setCaption($caption);
		$this->setName($name);
		if (isset($value)) {
			$this->_value = $value;
		}
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
		$ret = "";
		foreach ( $this->getOptions() as $value => $name ) {
			$ret .= "<input type=\"radio\" class=\"radio\" name=\"".$this->getName()."\" value=\"".$value."\"";
			$selected = $this->getValue();
			if ( isset($selected) && ($value == $selected) ) {
				$ret .= " checked=\"checked\"";
			}
			$ret .= $this->getExtra()." />".$name."\n";
		}
		return $ret;
	}
}
?>