<?php
/**
 * 在线编辑（多行）
 *
 * 在线编辑（多行）
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formhtmleditor.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormHtmlEditor extends JieqiFormElement {

	var $_width;

	var $_height;

	var $_value;


	function JieqiFormHtmlEditor($caption, $name, $value="", $width=600, $height=400){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_width = intval($width);
		$this->_height = intval($height);
		$this->_value = $value;
	}


	function getWidth(){
		return $this->_width;
	}


	function getHeight(){
		return $this->_height;
	}

	function getValue(){
		return $this->_value;
	}


	function render(){
		include_once(JIEQI_ROOT_PATH.'/lib/html/form/fckeditor/fckeditor.php');
		$editor = new FCKeditor;
		$editor->Value = $this->getValue();
        return $editor->ReturnFCKeditor($this->getName(), $this->getWidth(), $this->getHeight()) ;
	}
}
?>