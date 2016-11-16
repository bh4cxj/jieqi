<?php
/**
 * 调用远程页面内容作为一个区块
 *
 * 调用远程页面内容作为一个区块
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_Fileget.php 187 2008-11-24 09:30:03Z juny $
 */

class BlockSystemFileget extends JieqiBlock{
	var $module='system';
	var $template = '';
	var $cachetime=JIEQI_CACHE_LIFETIME;
	var $exevars=array('fname'=>'', 'cachetime'=>0, 'charset'=>'');
	//区块配置，fname，调用的页面url，如 http://www.domain.com/ad/ad1.php，调用本站的页面可以写成 ad/ad1.php
	////缓存时间设置 -1 表示不缓存 0 表示默认系统缓存时间 >0 表示自定义缓存时间
	//charset 调用的页面内容编码，留空表示和系统编码一样

	function BlockSystemFileget(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(!empty($varary[0])){
					if(preg_match('/^https?:\/\//is', $varary[0])){
						$this->exevars['fname'] = $varary[0];
					}else{
						$this->exevars['fname'] = (substr($varary[0],0,1) == '/') ? JIEQI_URL.$varary[0] : JIEQI_URL.'/'.$varary[0];
					}
				}
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1])){
					$varary[1] = intval($varary[1]);
					if($varary[1] > 0) $this->blockvars['cachetime'] = $varary[1];
					elseif($varary[1] < 0) $this->blockvars['cachetime'] = 0;
				}

			}
			if($arynum>2){
				$varary[2]=strtolower(trim($varary[2]));
				if(in_array($varary[2], array('gbk', 'gb2312', 'big5', 'utf-8'))) $this->exevars['charset']=$varary[2];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);
	}

	function getContent(){
		global $jieqiTpl;
		global $jieqiCache;
		$cachefile=JIEQI_CACHE_PATH.'/templates/blocks/block_fileget/'.$this->blockvars['cacheid'].'.html';
		$usecache=false;
		if(JIEQI_USE_CACHE && $this->blockvars['cachetime'] > 0){
			$ret = $jieqiCache->get($cachefile, $this->blockvars['cachetime']);
			if($ret !== false){
				return $ret;
			}
		}
		return $this->updateContent(true);
	}

	function updateContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiCache;
		if(empty($this->exevars['fname'])) return '';
		$bcontent = '';
		if(defined('PHP_VERSION') && PHP_VERSION >= '5.0.0'){
			$context=array('http' => array ('header'=> 'User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; .NET CLR 1.1.4322)'));
			$xcontext = stream_context_create($context);
			$bcontent = file_get_contents($this->exevars['fname'],false,$xcontext);
		}else{
			$bcontent = file_get_contents($this->exevars['fname']);
		}
		if(!empty($this->exevars['charset']) && $charsetary[$this->exevars['charset']] != $charsetary[JIEQI_SYSTEM_CHARSET]){
			$charset_convert_block='jieqi_'.$charsetary[$this->exevars['charset']].'2'.$charsetary[JIEQI_SYSTEM_CHARSET];
			if(function_exists($charset_convert_block)) $bcontent = $charset_convert_block($bcontent);
		}

		if(!empty($this->blockvars['tlpfile'])){
			$jieqiTpl->assign('block_main_content', $bcontent);
			$jieqiTpl->setCaching(0);
			$bcontent=$jieqiTpl->fetch($this->blockvars['tlpfile']);
		}
		if (JIEQI_USE_CACHE && $this->blockvars['cachetime'] > 0) {
			$jieqiCache->set(JIEQI_CACHE_PATH.'/templates/blocks/block_fileget/'.$this->blockvars['cacheid'].'.html', $bcontent, $this->blockvars['cachetime']);
		}
		if($isreturn) return $bcontent;
	}
}

?>