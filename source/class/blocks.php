<?php
/**
 * 数据表类(jieqi_system_blocks - 区块表)
 *
 * 数据表类(jieqi_system_blocks - 区块表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: blocks.php 320 2009-01-13 05:51:02Z juny $
 */

jieqi_includedb();
//区块类
class JieqiBlocks extends JieqiObjectData
{

    //构建函数
    function JieqiBlocks()
    {
        $this->initVar('bid', JIEQI_TYPE_INT, 0, '序号', false, 8);
        $this->initVar('blockname', JIEQI_TYPE_TXTBOX, '', '区块名称', true, 50);
        $this->initVar('modname', JIEQI_TYPE_TXTBOX, '', '模块名称', true, 50);
        $this->initVar('filename', JIEQI_TYPE_TXTBOX, '', '文件名称', false, 50);
        $this->initVar('classname', JIEQI_TYPE_TXTBOX, '', '类名称', true, 50);
        $this->initVar('side', JIEQI_TYPE_INT, 0, '区块位置', false, 3);
        $this->initVar('title', JIEQI_TYPE_TXTAREA, '', '区块标题', false, NULL);
        $this->initVar('description', JIEQI_TYPE_TXTAREA, '', '区块描述', false, NULL);
        $this->initVar('content', JIEQI_TYPE_TXTAREA, '', '区块内容', false, NULL);
        $this->initVar('vars', JIEQI_TYPE_TXTBOX, '', '区块参数', false, 255);
        $this->initVar('template', JIEQI_TYPE_TXTBOX, '', '模板文件名称', false, 50);
        $this->initVar('cachetime', JIEQI_TYPE_INT, 0, '缓存时间', false, 11);
        $this->initVar('contenttype', JIEQI_TYPE_INT, 0, '内容类型', false, 3);
        $this->initVar('weight', JIEQI_TYPE_INT, 0, '排列顺序', false, 8);
        $this->initVar('showstatus', JIEQI_TYPE_INT, 0, '显示状态', false, 1);
        $this->initVar('custom', JIEQI_TYPE_INT, 0, '是否自定义区块', false, 1);
        $this->initVar('canedit', JIEQI_TYPE_INT, 0, '可否编辑', false, 1);
        $this->initVar('publish', JIEQI_TYPE_INT, 0, '是否激活', false, 1);
        $this->initVar('hasvars', JIEQI_TYPE_INT, 0, '是否支持参数', false, 1);
    }
	
}


//------------------------------------------------------------------------
//------------------------------------------------------------------------

//区块句柄
class JieqiBlocksHandler extends JieqiObjectHandler
{
	var $sideary = array();  //位置类型
	var $contentary = array();
	function JieqiBlocksHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='blocks';
	    $this->autoid='bid';	
	    $this->dbname='system_blocks';
	    $this->sideary=array(JIEQI_SIDEBLOCK_LEFT=>'左边', JIEQI_SIDEBLOCK_RIGHT=>'右边', JIEQI_CENTERBLOCK_LEFT=>'中左', JIEQI_CENTERBLOCK_RIGHT=>'中右', JIEQI_CENTERBLOCK_TOP=>'中上', JIEQI_CENTERBLOCK_MIDDLE=>'中中', JIEQI_CENTERBLOCK_BOTTOM=>'中下', JIEQI_TOPBLOCK_ALL=>'顶部', JIEQI_BOTTOMBLOCK_ALL=>'底部');
	    $this->contentary=array(JIEQI_CONTENT_TXT=>'纯文本', JIEQI_CONTENT_HTML=>'纯HTML', JIEQI_CONTENT_JS=>'纯JAVASCRIPT', JIEQI_CONTENT_MIX=>'HTML和SCRIPT混合', JIEQI_CONTENT_PHP=>'PHP代码');
	}
	
	function getSideary()
	{
	    return $this->sideary;
	}
	
	function getSide($side)
	{
	    if(isset($this->sideary[$side])) return $this->sideary[$side];
	    else return '隐藏';	
	}
	
	function getShowlist($type)
	{
	    $ret=array();
	    foreach($this->showary as $k=>$v){
	        if(($type & $k)>0) $ret[]=$k;	
	    }	
	    return $ret;
	}
	
	function getPublish($type)
	{
		if($type==3) return '都显示';
		elseif($type==1) return '登陆前显示';
		elseif($type==2) return '登陆后显示';
		else return '不显示';
	}
	
	function getContentary($custom=true)
	{
		return $this->contentary;
	}
	
	function getContenttype($type)
	{
		if(isset($this->contentary[$type])) return $this->contentary[$type];
		else return '未知';
	}
	
	//保存自定义区块内容
	function saveContent($bid, $modname, $contenttype, &$content)
	{
		global $jieqiCache;
		$ret=false;
		if(!empty($bid) && !empty($modname)){
			$val='';
			$fname='';
			switch($contenttype){
			    case JIEQI_CONTENT_TXT:
			    $val=jieqi_htmlstr($content);
			    $fname='.html';
			    break;	
			    case JIEQI_CONTENT_HTML:
			    $val=$content;
			    $fname='.html';
			    break;
			    case JIEQI_CONTENT_JS:
			    $val=$content;
			    $fname='.html';
			    break;
			    case JIEQI_CONTENT_MIX:
			    $val=$content;
			    $fname='.html';
			    break;
			    /*
			    //为增加安全性，程序类型自定义区块暂时不支持
			    case JIEQI_CONTENT_PHP:
			    $val=$content;
			    $fname='.php';
			    break;
			    */
			}
			if(!empty($fname)){
				$cache_file = JIEQI_CACHE_PATH;
				if(!empty($modname) && $modname != 'system') $cache_file.='/modules/'.$modname;
				if(is_numeric($bid)) $cache_file .= '/templates/blocks/block_custom'.$bid.$fname;
				else $cache_file .= '/templates/blocks/'.$bid.'.html';
				if($fname != '.php') $jieqiCache->set($cache_file, $val);
				else{
					jieqi_checkdir(dirname($cache_file), true);
					jieqi_writefile($cache_file, $val);
				}
			    $ret=true;
			}
		}
		return $ret;
	}
}

?>