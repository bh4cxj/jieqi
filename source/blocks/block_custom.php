<?php
/**
 * 用户自定义区块
 *
 * 用户自己输入html代码生成的区块
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_custom.php 301 2008-12-26 04:36:17Z juny $
 */

class BlockSystemCustom extends JieqiBlock
{
	var $module='system';

	/**
	 * 更新区块缓存
	 * 
	 * @param      void
	 * @access     private
	 * @return     string
	 */
	function updateContent($isreturn=false){
		$ret='';
		include_once(JIEQI_ROOT_PATH.'/class/blocks.php');
		$blocks_handler =& JieqiBlocksHandler::getInstance('JieqiBlocksHandler');
		if(!empty($this->blockvars['bid'])){
			$block=$blocks_handler->get($this->blockvars['bid']);
			if(is_object($block)){
				switch($block->getVar('contenttype')){
					case JIEQI_CONTENT_TXT:
						$ret=$block->getVar('content','s');
						break;
					case JIEQI_CONTENT_HTML:
						$ret=$block->getVar('content','n');
						break;
					case JIEQI_CONTENT_JS:
						$ret='<script language="javascript" type="text/javascript">'.$block->getVar('content','n').'</script>';
						break;
					case JIEQI_CONTENT_MIX:
						$ret=$block->getVar('content','n');
						break;
					case JIEQI_CONTENT_PHP:
						break;
				}
				$blocks_handler->saveContent($block->getVar('bid'), $block->getVar('modname'), $block->getVar('contenttype'), $ret);
			}else{
				$ret='block not exists! (id:'.$this->blockvars['bid'].')';
			}
		}elseif(!empty($this->blockvars['filename']) && preg_match('/^\w+$/', $this->blockvars['filename'])){
			$blockpath = ($this->blockvars['module'] == 'system') ? JIEQI_ROOT_PATH : $GLOBALS['jieqiModules'][$this->blockvars['module']]['path'];
			$blockpath .= '/templates/blocks/'.$this->blockvars['filename'].'.html';
			$ret=jieqi_readfile($blockpath);
			$blocks_handler->saveContent($this->blockvars['filename'], $this->blockvars['module'], JIEQI_CONTENT_HTML, $ret);
		}else{
			$ret='empty block id!';
		}
		if($isreturn) return $ret;
	}
}

?>