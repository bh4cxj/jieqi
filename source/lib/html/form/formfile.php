<?php
/**
 * 上传文件框
 *
 * 上传文件框
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formfile.php 201 2008-11-25 06:09:43Z juny $
 */

class JieqiFormFile extends JieqiFormElement {

	var $_size;

	function JieqiFormFile($caption, $name, $size){
		$this->setCaption($caption);
		$this->setName($name);
		$this->_size = intval($size);;
	}

	function getSize(){
		return $this->_size;
	}

	function render(){
		return "<input type=\"file\" class=\"text\" size=\"".$this->getSize()."\" name=\"".$this->getName()."\" id=\"".$this->getName()."\"".$this->getExtra()." />";
	}
}
?>