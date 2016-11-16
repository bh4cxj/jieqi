<?php
/**
 * 文章列表区块
 *
 * 可以根据参数不同显示，最新文章排行榜等
 * 
 * 调用模板：/modules/article/templates/blocks/block_articlelist.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_articlelist.php 332 2009-02-23 09:15:08Z juny $
 */

class BlockArticleArticlelist extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_articlelist.html';
	
	var $exevars=array('field'=>'allvisit', 'listnum'=>15, 'sortid'=>'0', 'isauthor'=>0, 'isfull'=>0, 'asc'=>0, 'permission'=>'', 'firstflag'=>'', 'power'=>'');  //执行配置
	
	//listnum 显示行数
	//sortid 0表示所有类别，可以是多个类别 '1|2|3'
	//isauthor 0 表示所有, 1表示原创，2表示转载
	//isfull 0 表示所有, 1表示全本，2连载
	//asc  0表示从大往小排，1表示从小往大
	//permission 授权等级 空表示不检查 0-3 授权等级,可以多个等级 1|2|3
	//firstflag 是否首发 空表示不检查 0他站首发 1本站首发
	//power 状态标记 空表示不检查 0-普通 1-签约，2-vip，3-签约+vip
	function BlockArticleArticlelist(&$vars){
		global $jieqiArticleuplog;
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if($varary[0]=='mouthvisit') $varary[0]='monthvisit';
				elseif($varary[0]=='mouthvote') $varary[0]='monthvote';
				if(in_array($varary[0], array('allvisit', 'monthvisit', 'weekvisit', 'dayvisit', 'allvote', 'monthvote', 'weekvote', 'dayvote', 'postdate', 'toptime', 'goodnum', 'size', 'lastupdate', 'goodnew'))) $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				$tmpvar=str_replace('|', '', $varary[2]);
				if(is_numeric($tmpvar)) $this->exevars['sortid']=$varary[2];
				elseif(isset($_REQUEST[$tmpvar]) && is_numeric($_REQUEST[$tmpvar])) $this->exevars['sortid']=$_REQUEST[$tmpvar];
			}
			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(in_array($varary[3], array('0', '1', '2'))) $this->exevars['isauthor']=$varary[3];
			}
			if($arynum>4){
				$varary[4]=trim($varary[4]);
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['isfull']=$varary[4];
			}
			if($arynum>5){
				$varary[5]=trim($varary[5]);
				if(in_array($varary[5], array('0', '1'))) $this->exevars['asc']=$varary[5];
			}
			
			if($arynum>6){
				$varary[6]=trim($varary[6]);
				//if(in_array($varary[6], array('0', '1', '2', '3'))) $this->exevars['permission']=$varary[6];
				$tmpvar=str_replace('|', '', $varary[6]);
				if(is_numeric($tmpvar)) $this->exevars['permission']=$varary[6];
			}
			
			if($arynum>7){
				$varary[7]=trim($varary[7]);
				if(in_array($varary[7], array('0', '1'))) $this->exevars['firstflag']=$varary[7];
			}
			
			if($arynum>8){
				$varary[8]=trim($varary[8]);
				if(in_array($varary[8], array('0', '1', '2', '3'))) $this->exevars['power']=$varary[8];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);	
		if($this->exevars['field']=='lastupdate' || $this->exevars['field']=='postdate'){
			jieqi_getcachevars('article', 'articleuplog');
			if(!is_array($jieqiArticleuplog)) $jieqiArticleuplog=array('articleuptime'=>0, 'chapteruptime'=>0);
			$this->blockvars['overtime'] = $jieqiArticleuplog['articleuptime'] > $jieqiArticleuplog['chapteruptime'] ? intval($jieqiArticleuplog['articleuptime']) : intval($jieqiArticleuplog['chapteruptime']);
		}
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $jieqiSort;
		
		include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
		//载入相关处理函数
		include_once($GLOBALS['jieqiModules']['article']['path'].'/include/funarticle.php');
		jieqi_getconfigs('article', 'configs');
		jieqi_getconfigs('article', 'sort');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
		$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
		$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
		$tmpvar=date('w',JIEQI_NOW_TIME);
		if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
		$weekstart=$daystart;
		if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;

		$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
		$sql='SELECT * FROM '.jieqi_dbprefix('article_article').' WHERE display=0 AND size>0';
		if(!empty($this->exevars['sortid'])){
			$sortstr='';
			$sortnum=0;
			$sortary=explode('|', $this->exevars['sortid']);
			foreach($sortary as $v){
				if(is_numeric($v)){
					if(!empty($sortstr)) $sortstr.=' OR ';
					$sortstr.='sortid='.intval($v);
					$sortnum++;
				}
			}
			if($sortnum==1) $sql.=' AND '.$sortstr;
			elseif($sortnum>1) $sql.=' AND ('.$sortstr.')';
		}
		if($this->exevars['isauthor']==1) $sql.=' AND authorid>0';
		elseif($this->exevars['isauthor']==2) $sql.=' AND authorid=0';
		if($this->exevars['isfull']==1) $sql.=' AND fullflag=1';
		elseif($this->exevars['isfull']==2) $sql.=' AND fullflag=0';
		//授权许可
		if(strlen($this->exevars['permission'])>0){
			$perstr='';
			$pernum=0;
			$perary=explode('|', $this->exevars['permission']);
			foreach($perary as $v){
				if(is_numeric($v)){
					if(!empty($perstr)) $perstr.=' OR ';
					$perstr.='permission='.intval($v);
					$pernum++;
				}
			}
			if($pernum==1) $sql.=' AND '.$perstr;
			elseif($pernum>1) $sql.=' AND ('.$perstr.')';
		}
		//是否首发
		if(strlen($this->exevars['firstflag'])>0){
			$sql.=' AND firstflag='.intval($this->exevars['firstflag']);
		}
		//状态标志
		if(strlen($this->exevars['power'])>0){
			$sql.=' AND power='.intval($this->exevars['power']);
		}
		
		switch($this->exevars['field']){
			case 'monthvisit':
			$sql.=' AND lastvisit>='.$monthstart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'monthvote':
			$sql.=' AND lastvote>='.$monthstart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'weekvisit':
			$sql.=' AND lastvisit>='.$weekstart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'weekvote':
			$sql.=' AND lastvote>='.$weekstart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'dayvisit':
			$sql.=' AND lastvisit>='.$daystart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'dayvote':
			$sql.=' AND lastvote>='.$daystart;
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
			case 'goodnew':
			$sql.=' AND postdate>='.(JIEQI_NOW_TIME - (3600*24*30));
			$sql.=' ORDER BY allvisit + allvote * 10 + goodnum * 20';
			break;
			default:
			$sql.=' ORDER BY '.$this->exevars['field'];
			break;
		}
		if($this->exevars['asc']==1) $sql.=' ASC';
		else  $sql.=' DESC';
		$sql.=' LIMIT 0, '.$this->exevars['listnum'];
		$res=$article_handler->db->query($sql);
		$articlerows=array();
		$k=0;
		while($v = $article_handler->getObject($res)){
			$articlerows[$k] = jieqi_article_vars($v);
			$articlerows[$k]['order']=$k+1;
			if($this->exevars['field']=='goodnew') $articlerows[$k]['visitnum']=$v->getVar('allvisit');
			else $articlerows[$k]['visitnum']=$v->getVar($this->exevars['field']);
			if($this->exevars['field']=='size') $articlerows[$k]['visitnum']=ceil($articlerows[$k]['visitnum']/1024).'K';
			elseif($this->exevars['field']=='lastupdate' || $this->exevars['field']=='postdate' || $this->exevars['field']=='toptime') $articlerows[$k]['visitnum']=date('m-d', $articlerows[$k]['visitnum']);
			$k++;
		}
		$jieqiTpl->assign_by_ref('articlerows', $articlerows);
		$topsort=$this->exevars['field'];
		if($topsort=='lastupdate'){
			if($this->exevars['isauthor']==1) $topsort='authorupdate';
			elseif($this->exevars['isauthor']==2) $topsort='masterupdate';
		}
		if($jieqiConfigs['article']['faketoplist'] > 0){
			if(!empty($jieqiConfigs['article']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['article']['fakeprefix'].'top'.$topsort;
			else $tmpvar='/files/article/top'.$topsort;
			$jieqiTpl->assign('url_more', JIEQI_URL.$tmpvar.'/0/1'.$jieqiConfigs['article']['fakefile']);
		}else{
			$jieqiTpl->assign('url_more', $article_dynamic_url.'/toplist.php?sort='.$topsort);
		}
	}
}

?>