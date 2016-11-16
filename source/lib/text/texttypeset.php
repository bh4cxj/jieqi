<?php
/**
 * 文章排版类
 *
 * 文章排版类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: texttypeset.php 205 2008-11-25 06:10:54Z juny $
 */

class TextTypeset extends JieqiObject
{
	var $freplace=array();  //搜索替换字符
	var $treplace=array();  //替换成
	var $delmoreblank=true; //删除连续空格
	var $delchars=array(); //需要删除的字符串
	var $errstartchars=array(); //不能最为段首支付
	var $fmore=array();  //连续字符需要替换
	var $tmore=array();  //连续字符替换成
	function TextTypeset()
	{
		$this->freplace=array(',', '.', '·', '．', ';', '!', '?', ':', '(', ')');
		$this->treplace=array('，', '。', '。', '。', '；', '！', '？', '：', '（', '）');
		$this->delmoreblank=true;
		$this->delchars=array(' ', '　', "\r");
		$this->errstartchars=array('。', '？', '！', '」', '”', '）');
		$this->fmore=array('.', '。', '-');
		$this->tmore=array('……', '……', '——');
	}
	
	//排版
	function doTypeset(&$str)
	{
		//$str=str_replace($this->freplace, $this->treplace, $str);
		$ret='';
		$tmpstr='';
		$tmpstr1='';
		$repeatnum=0; //重复次数
		$start=true;  //文章开始标志
		$linestart=true;  //行开始标志
		$sectionstart=true;  //段开始标志
		$strlen=strlen($str);
		for($i = 0; $i < $strlen; $i++) {
			$tmpstr = $str[$i];
			//判断中英文，取字符
			if (ord($str[$i]) > 0x80 && $i+1<$strlen) {
				$tmpstr .= $str[++$i];
			}
			//需要删除的字符
			if(in_array($tmpstr, $this->delchars)) continue;
			//遇到回车设置分段标志
			if($tmpstr=="\n"){
				$sectionstart=true;
				continue;
			}
			//不允许作为段首的字符
			if($sectionstart && in_array($tmpstr, $this->errstartchars)) $sectionstart=false;
			
			//某些重复字符处理
			$tmpvar=$repeatnum;
			if(in_array($tmpstr, $this->fmore)){
				if($tmpstr==$tmpstr1){
					$repeatnum++;
				}else{
					$tmpstr1=$tmpstr;
					$repeatnum=1;
				}
				continue;
			}
			if($tmpvar>0 && $tmpvar==$repeatnum){
				if($repeatnum==1){
					$ret.=$tmpstr1;
				}else{
					$key=array_search($tmpstr1, $this->fmore);
					if($key) $ret.=$this->tmore[$key];
				}
				$tmpstr1='';
				$repeatnum=0;
			}
			//段首处理
			if($sectionstart){
				if(!$start) $ret.="\r\n\r\n";
				else $start=false;
				$ret.='    ';
				$sectionstart=false;
			}
			$ret.=$tmpstr;
		}
		//最后一个可能缓存的字符
		if($repeatnum==1){
			$ret.=$tmpstr1;
		}elseif($repeatnum>1){
			$key=array_search($tmpstr1, $this->fmore);
			if($key) $$ret.=$this->tmore[$key];
		}
		return $ret;
	}
}
?>