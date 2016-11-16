<?php
/**
 * 文本标记
 *
 * 文本标记
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formlabel.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormLabel extends JieqiFormElement {


	var $_value;


	function JieqiFormLabel($caption="", $value=""){
		$this->setCaption($caption);
		$this->_value = $value;
	}


	function getValue(){
		return $this->_value;
	}

	function render(){
		return $this->getValue();
	}
}
?>