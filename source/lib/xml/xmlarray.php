<?php
/**
 * XML和数组转换类
 *
 * 把php数组转换成xml，以及读取xml转换回数组
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: xmlarray.php 319 2009-01-09 05:59:13Z juny $
 */

/*
include('xmlarray.php');
$example_array=array('one'=>'111','two'=>array('subone'=>'s1',subtwo=>'s2'),'three'=>'333');
$xmlarray= new XMLArray();
$string=$xmlarray->array2xml($example_array);
echo $string;
$array=$xmlarray->xml2array($string);
print_r($array);
*/

class XMLArray {
	var $text;
	var $arrays, $keys, $node_flag, $depth, $xml_parser;
	// XML 编码方式
	var $encoding = 'ISO-8859-1';
	var $entities = array ( "&" => "&amp;", "<" => "&lt;", ">" => "&gt;",
	"'" => "&apos;", '"' => "&quot;" );

	/*Converts an array to an xml string*/
	function array2xml($array) {
		//global $text;
		$this->text="<?xml version=\"1.0\" encoding=\"".$this->encoding."\"?>\n<array>\n";
		$this->text.= $this->array_transform($array);
		$this->text .="</array>";
		return $this->text;
	}

	function array_transform($array){
		//global $array_text;
		foreach($array as $key => $value){
			if(!is_array($value)){
				if(preg_match('/(&|<|>)/is', $value)) $value = '<![CDATA['.$value.']]>';
				$this->text .= "<key name=\"".$key."\">".$value."</key>\n";
			} else {
				$this->text.="<key name=\"".$key."\">\n";
				$this->array_transform($value);
				$this->text.="</key>\n";
			}
		}
		return $array_text;
	}
	
	function array_transform1($array){
		//global $array_text;
		foreach($array as $key => $value){
			if(!is_array($value)){
				if(preg_match('/(&|<|>)/is', $value)) $value = '<![CDATA['.$value.']]>';
				$this->text .= "<$key>$value</$key>\n";
			} else {
				$this->text.="<$key>\n";
				$this->array_transform($value);
				$this->text.="</$key>\n";
			}
		}
		return $array_text;
	}
	/*Transform an XML string to associative array "XML Parser Functions"*/
	function xml2array($xml){
		$this->depth=-1;
		$this->xml_parser = xml_parser_create($this->encoding);
		xml_set_object($this->xml_parser, $this);
		xml_parser_set_option($this->xml_parser, XML_OPTION_CASE_FOLDING, 0);//Don't put tags uppercase
		xml_set_element_handler($this->xml_parser, "startElement", "endElement");
		xml_set_character_data_handler($this->xml_parser, "characterData");
		xml_parse($this->xml_parser, $xml, true);
		xml_parser_free($this->xml_parser);
		return $this->arrays[0]['array'];

	}
	function startElement($parser, $name, $attrs){
		$key = isset($attrs['name']) ? $attrs['name'] : $name;
		$this->keys[]=$key; //We add a key
		$this->node_flag=1;
		$this->depth++;
	}
	function characterData($parser,$data){
		if($this->node_flag == 1){
			$key=end($this->keys);
			$this->arrays[$this->depth][$key]=$data;
			$this->node_flag=0; //So that we don't add as an array, but as an element
		}
	}
	function endElement($parser, $name){
		$key=array_pop($this->keys);
		//If $node_flag==1 we add as an array, if not, as an element
		if($this->node_flag > 0){
			$this->arrays[$this->depth][$key]=$this->arrays[$this->depth+1];
			unset($this->arrays[$this->depth+1]);
		}
		$this->node_flag = 2;
		$this->depth--;
	}

}

?>