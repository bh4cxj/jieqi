<?php
/**
 * 注册全局变量流
 *
 * 把变量像文件一样读写
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: gdbmp.php 317 2009-01-06 09:03:33Z juny $
 */

/*
$vname = 'data';
if(function_exists('stream_wrapper_register') && stream_wrapper_register("var", "JieqiVarStream")){
	$file = "var://vname";
	echo file_get_contents('var://vname');
}else{
}	echo 'Failed to register protocol';
*/

class JieqiVarStream{
	var $position;
	var $varname;

	function stream_open($path, $mode, $options, &$opened_path){
		$url = parse_url($path);
		$this->varname = $url["host"];
		$this->position = 0;
		return true;
	}

	function stream_close(){

	}

	function stream_read($count){
		$ret = substr($GLOBALS[$this->varname], $this->position, $count);
		$this->position += strlen($ret);
		return $ret;
	}

	function stream_write($data){
		$left = substr($GLOBALS[$this->varname], 0, $this->position);
		$right = substr($GLOBALS[$this->varname], $this->position + strlen($data));
		$GLOBALS[$this->varname] = $left . $data . $right;
		$this->position += strlen($data);
		return strlen($data);
	}

	function stream_tell(){
		return $this->position;
	}

	function stream_eof(){
		return $this->position >= strlen($GLOBALS[$this->varname]);
	}

	function stream_stat(){
		return array('size' => strlen($GLOBALS[$this->varname]));
	}

	function stream_flush(){
		return true;
	}

	function stream_seek($offset, $whence){
		switch($whence) {
			case SEEK_SET:
				if ($offset < strlen($GLOBALS[$this->varname]) && $offset >= 0) {
					$this->position = $offset;
					return true;
				} else {
					return false;
				}
				break;

			case SEEK_CUR:
				if ($offset >= 0) {
					$this->position += $offset;
					return true;
				} else {
					return false;
				}
				break;

			case SEEK_END:
				if (strlen($GLOBALS[$this->varname]) + $offset >= 0) {
					$this->position = strlen($GLOBALS[$this->varname]) + $offset;
					return true;
				} else {
					return false;
				}
				break;

			default:
				return false;
		}
	}
}

?>