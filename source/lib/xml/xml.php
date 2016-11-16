<?php
/**
 * XML读写类
 *
 * XML读写类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: xml.php 312 2008-12-29 05:30:54Z juny $
 */

define('XML_TYPE_NODE', 1); //节点
define('XML_TYPE_TEXT', 3);  //文本
define('XML_TYPE_CDATA', 4);  //数据块


// 文本内容
class XMLText {
	var $nodeValue;
	var $nodeType;
	var $entities = array ( "&" => "&amp;", "<" => "&lt;", ">" => "&gt;",
	"'" => "&apos;", '"' => "&quot;" );
	function XMLText() {
		$this->nodeValue = NULL;
		$this->nodeType = XML_TYPE_TEXT;
	}
}

// XML 节点
class XMLNode extends XMLText {
	var $attributes; //属性
	var $childNodes;  //子节点数组
	var $firstChild;  //第一个子节点
	var $lastChild;  //最后一个子节点
	var $previousSibling;  //上一个同级节点
	var $nextSibling;  //下一个同级节点
	var $nodeName;  //节点名
	var $parentNode;  //父节点
	var $nodeType;  //节点类型

	function XMLNode() {
		$this->attributes = NULL;
		$this->childNodes = NULL;
		$this->firstChild = NULL;
		$this->lastChild = NULL;
		$this->previousSibling = NULL;
		$this->nextSibling = NULL;
		$this->nodeName = NULL;
		$this->parentNode = NULL;
		$this->nodeType = XML_TYPE_NODE;

	}

	// 查找子节点（私有函数）
	function _xml_get_children($vals, &$i) {
		$children = array();

		// CData section before children
		if (isset($vals[$i]['value'])) {
			$tmp = new XMLText();
			$tmp->nodeValue = $vals[$i]['value'];
			$tmp->nodeType = XML_TYPE_CDATA;
			$children[] = $tmp;
		}

		// Browse children
		$lastelm = '';
		$nChildren = count($vals);
		while (++$i < $nChildren) {
			switch ($vals[$i]['type']) {

				case 'cdata':
					if ($lastelm != 'cdata') {

						// New CData section
						$tmp = new XMLText();
						$tmp->nodeValue = $vals[$i]['value'];
						$tmp->nodeType = XML_TYPE_CDATA;
						$children[] = $tmp;

					} else {

						// Continuing last CData section
						$children[count($children)-1]->nodeValue .= $vals[$i]['value'];

					}
					break;

				case 'complete':
					$tmp = new XMLNode();
					$tmp->nodeName = $vals[$i]['tag'];
					$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:NULL;
					if (isset($vals[$i]['value'])) {
						$tmp->appendChild(XMLNode::createTextNode($vals[$i]['value']));
					}
					$tmp->parentNode = $this;
					$children[] = $tmp;
					break;

				case 'open':
					$tmp = new XMLNode();
					$tmp->nodeName = $vals[$i]['tag'];
					$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:NULL;
					$tmp->parentNode = $this;
					$tmp->childNodes = $tmp->_xml_get_children($vals, $i);
					$children[] = $tmp;
					break;

				case 'close':
					$nThisChildren = count($children);
					if ($nThisChildren > 1) {
						for ($j = $nThisChildren-2; $j >= 0; $j--)
						$children[$j]->nextSibling =& $children[$j+1];
						for ($j = 1; $j < $nThisChildren; $j++)
						$children[$j]->previousSibling =& $children[$j-1];
					}
					$this->firstChild =& $children[0];
					$this->lastChild =& $children[($nThisChildren-1) % $nThisChildren];
					return $children;
					break;

			}

			$lastelm = $vals[$i]['type'];

		}

	}

	// 公用函数
	// 增加子节点
	function appendChild(&$child) {

		$child->parentNode =& $this;
		$this->childNodes[] =& $child;

		// 子节点的前一个
		if ($child->nodeType == XML_TYPE_NODE) {
			$child->previousSibling =& $this->lastChild;
		}

		// 原来子节点的后一个
		if ($this->nodeType == XML_TYPE_NODE) {
			if (!is_null($this->lastChild)) {
				$this->lastChild->nextSibling =& $child;
			}

			$this->firstChild =& $this->childNodes[0];
			$this->lastChild =& $child;
		}
	}

	// 新建节点元素
	function createElement($name) {
		$tmp = new XMLNode();
		$tmp->nodeName = $name;
		return $tmp;
	}

	// 新建文本节点
	function createTextNode($value) {
		$tmp = new XMLText();
		$tmp->nodeValue = trim($value);
		return $tmp;
	}

	// 是否有子节点
	function hasChildNodes() {
		return !is_null($this->childNodes);
	}

	// 插入节点
	function insertBefore(&$child, $refChild = NULL) {
		// Not implemented yet...
	}

	// 删除节点
	function removeChild() {
		// Not implemented yet...
	}

	// 整个xml转换成字符串
	function toString() {

		$tagOpen = "<";
		$tagClose = ">";
		$tagBreak = "\n";

		// Set xml-decleration and doc-type if this is the root-element
		$retVal = "";
		if (is_null($this->parentNode)) {
			if(!empty($this->xmlDecl)) $retVal .= $this->xmlDecl.$tagBreak;
			if(!empty($this->docTypeDecl)) $retVal .= $this->docTypeDecl.$tagBreak;
		}

		// If this element has attributes, gather them
		$sAttr = "";
		if (isset($this->attributes)) {
			foreach ($this->attributes as $key=>$val)
			$sAttr .= " $key=\"".strtr($val, $this->entities)."\"";
		}

		if (isset($this->nodeName)) {
			if ($this->hasChildNodes()) {
				$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose;
				if($this->firstChild->nodeType != XML_TYPE_TEXT && $this->firstChild->nodeType != XML_TYPE_CDATA) $retVal .= $tagBreak;
			} elseif (isset($this->firstChild->nodeValue)) {
				$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose.strtr($this->firstChild->nodeValue,$this->entities).$tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
			} else {
				$retVal .= $tagOpen.$this->nodeName.$sAttr." /".$tagClose.$tagBreak;
			}
		}

		if ($this->hasChildNodes()) {
			foreach ($this->childNodes as $child) {
				switch ($child->nodeType) {
					case XML_TYPE_NODE: // node
					default:
						$retVal .= $child->toString();
						break;
					case XML_TYPE_TEXT: // text
					$retVal .= strtr($child->nodeValue,$this->entities);
					break;
					case XML_TYPE_CDATA: // CData
					$retVal .= "<![CDATA[".$child->nodeValue."]]>";
					break;
				}
			}
		}

		if ($this->hasChildNodes() && isset($this->nodeName)) {
			$retVal .= $tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
		}

		return $retVal;

	} // End toString
}


// XML类
class XML extends XMLNode {

	// 公共属性

	// 错误信息
	var $status;
	var $error;

	// XML 本本
	var $version;

	// XML 编码方式
	var $encoding;

	// 文档类型
	var $contentType;

	// 文档申明.
	var $docTypeDecl;

	// XML 申明
	var $xmlDecl;


	function XML($url = '') {
		parent::XMLNode();
		$this->status = 0;
		$this->error = '';
		$this->version = '1.0';
		$this->encoding = 'ISO-8859-1';
		$this->contentType = 'text/xml';
		$this->docTypeDecl = '';
		$this->xmlDecl = '';

		// 载入xml文档
		$this->load($url);

	}

	// 公用方法

	// 载入xml文档
	function load($url) {
		if (empty($url)) return false;
		$this->parseXML(@file_get_contents($url));
	}

	// 解析XML
	function parseXML($source) {
		// Clear any content that this object might have
		// Call: $this->removeNode()

		// Get xml declration from document and set in object
		if (preg_match("/<?xml\ (.*?)\?>/i", $source, $matches)) {
			$this->xmlDecl = "<?xml ".$matches[1]."?>";

			// Get version
			if (preg_match("/version=\"(.*?)\"/i", $matches[1], $versionInfo)) {
				$this->version = $versionInfo[1];
			}

			// Get encoding
			if (preg_match("/encoding=\"(.*?)\"/i", $matches[1], $encodingInfo)) {
				$this->encoding = $encodingInfo[1];
			}

		}

		// Get document type decleration from document and set in object
		if (preg_match("/<!doctype\ (.*?)>/i", $source, $matches)) {
			$this->docTypeDecl = "<!DOCTYPE ".$matches[1].">";
		}

		// Strip white space between tags - not _in_ tags
		$source = preg_replace("/>\s+</i", "><", $source);

		// Parse the xml document to an array structure
		//$parser = xml_parser_create($this->encoding);
		$parser = xml_parser_create('ISO-8859-1');


		//$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		$ret=xml_parse_into_struct($parser, $source, $vals);
		xml_parser_free($parser);

		// parse the structure and create this object...
		if (!empty($vals)) {
			$root = XMLNode::createElement($vals[0]['tag']);
			$root->attributes = isset($vals[0]['attributes'])?$vals[0]['attributes']:NULL;
			$root->childNodes = $root->_xml_get_children($vals, $i = 0);
			$this->appendChild($root);
		}
		return $ret;

	}

}

?>