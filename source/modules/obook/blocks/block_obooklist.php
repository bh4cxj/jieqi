<?php 
/**
 * 电子书列表区块
 *
 * 根据参数不同可显示不同的排序方式
 * 
 * 调用模板：/modules/obook/templates/blocks/block_obooklist.html
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_obooklist.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockObookObooklist extends JieqiBlock
{
	var $module = 'obook';
	var $template = 'block_obooklist.html';
	
	var $exevars=array('field'=>'lastupdate', 'listnum'=>15, 'sortid'=>'0', 'publishid'=>'', 'isfull'=>0, 'asc'=>0);  //执行配置

	//listnum 显示行数
	//sortid 0表示所有类别，可以是多个类别 '1|2|3'
	//publishid 留空表示所有出版商, 可以是多个类别 '1|2|3'
	//isfull 0 表示所有, 1表示全本，2连载
	//asc  0表示从大往小排，1表示从小往大

	function BlockObookObooklist(&$vars){
		global $jieqiObookuplog;
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('allsale', 'monthsale', 'weeksale', 'daysale', 'postdate', 'toptime', 'goodnum', 'size', 'lastupdate'))) $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				$tmpvar=str_replace('|', '', $varary[2]);
				if(is_numeric($tmpvar)) $this->exevars['sortid']=$varary[2];
			}

			if($arynum>3){
				$varary[3]=trim($varary[3]);
				$tmpvar=str_replace('|', '', $varary[3]);
				if(is_numeric($tmpvar)) $this->exevars['publishid']=$varary[3];
			}

			if($arynum>4){
				$varary[4]=trim($varary[4]);
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['isfull']=$varary[4];
			}
			if($arynum>5){
				$varary[5]=trim($varary[5]);
				if(in_array($varary[5], array('0', '1'))) $this->exevars['asc']=$varary[5];
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);
		if($this->exevars['field']=='lastupdate' || $this->exevars['field']=='postdate'){
			jieqi_getcachevars('obook', 'obookuplog');
			if(!is_array($jieqiObookuplog)) $jieqiObookuplog=array('obookuptime'=>0, 'chapteruptime'=>0);
			$this->blockvars['overtime'] = $jieqiObookuplog['obookuptime'] > $jieqiObookuplog['chapteruptime'] ? $jieqiObookuplog['obookuptime'] : $jieqiObookuplog['chapteruptime'];
		}
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		global $jieqiSort;
		
		include_once($GLOBALS['jieqiModules']['obook']['path'].'/class/obook.php');
		jieqi_getconfigs('obook', 'configs');
		$obook_static_url = (empty($jieqiConfigs['obook']['staticurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['staticurl'];
		$obook_dynamic_url = (empty($jieqiConfigs['obook']['dynamicurl'])) ? $GLOBALS['jieqiModules']['obook']['url'] : $jieqiConfigs['obook']['dynamicurl'];
		$jieqiTpl->assign('obook_static_url',$obook_static_url);
		$jieqiTpl->assign('obook_dynamic_url',$obook_dynamic_url);

		$tmpvar=explode('-',date('Y-m-d',JIEQI_NOW_TIME));
		$daystart=mktime(0,0,0,(int)$tmpvar[1],(int)$tmpvar[2],(int)$tmpvar[0]);
		$monthstart=mktime(0,0,0,(int)$tmpvar[1],1,(int)$tmpvar[0]);
		$tmpvar=date('w',JIEQI_NOW_TIME);
		if($tmpvar==0) $tmpvar=7; //星期天是0，国人习惯作为作为一星期的最后一天
		$weekstart=$daystart;
		if($tmpvar>1) $weekstart-=($tmpvar-1) * 86400;

		$obook_handler =& JieqiObookHandler::getInstance('JieqiObookHandler');
		$sql='SELECT * FROM '.jieqi_dbprefix('obook_obook').' WHERE display=0 AND size>0';
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
		if(isset($this->exevars['publishid']) && $this->exevars['publishid'] !== ''){
			$publishstr='';
			$publishnum=0;
			$publishary=explode('|', $this->exevars['publishid']);
			foreach($publishary as $v){
				if(is_numeric($v)){
					if(!empty($publishstr)) $publishstr.=' OR ';
					$publishstr.='publishid='.intval($v);
					$publishnum++;
				}
			}
			if($publishnum==1) $sql.=' AND '.$publishstr;
			elseif($publishnum>1) $sql.=' AND ('.$publishstr.')';
		}

		if($this->exevars['isfull']==1) $sql.=' AND fullflag=1';
		elseif($this->exevars['isfull']==2) $sql.=' AND fullflag=0';
		switch($this->exevars['field']){
			case 'monthsale';
			$sql.=' AND lastsale>='.$monthstart;
			break;
			case 'weeksale';
			$sql.=' AND lastsale>='.$weekstart;
			break;
			case 'daysale';
			$sql.=' AND lastsale>='.$daystart;
			break;
		}

		$sql.=' ORDER BY '.$this->exevars['field'];
		if($this->exevars['asc']==1) $sql.=' ASC';
		else  $sql.=' DESC';
		$sql.=' LIMIT 0, '.$this->exevars['listnum'];
		$res=$obook_handler->db->query($sql);
		jieqi_getconfigs('obook', 'sort');
		$obookrows=array();
		$k=0;
		while($v = $obook_handler->getObject($res)){
			$obookrows[$k]['order']=$k+1;
			$obookrows[$k]['obookid']=$v->getVar('obookid');  //文章序号
			$obookrows[$k]['obookname']=$v->getVar('obookname');  //文章名称
			$obookrows[$k]['articleid']=$v->getVar('articleid');  //文章序号
			if($jieqiConfigs['obook']['fakeinfo']==1){
				$obookrows[$k]['obooksubdir']=jieqi_getsubdir($v->getVar('obookid'));  //子目录
				if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'info';
				else $tmpvar='/files/obook/info';
				$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.$tmpvar.$obookrows[$k]['obooksubdir'].'/'.$v->getVar('obookid').$jieqiConfigs['obook']['fakefile'];  //子目录
			}else{
				$obookrows[$k]['obooksubdir']='';
				$obookrows[$k]['url_obookinfo']=$obook_dynamic_url.'/obookinfo.php?id='.$v->getVar('obookid');  //子目录
			}
			if($v->getVar('lastchapter')==''){
				$obookrows[$k]['lastchapterid']=0;  //章节序号
				$obookrows[$k]['lastchapter']='';  //章节名称
				$obookrows[$k]['url_lastchapter']='';  //章节地址
			}else{
				$obookrows[$k]['lastchapterid']=$v->getVar('lastchapterid');
				$obookrows[$k]['lastchapter']=$v->getVar('lastchapter');
				$obookrows[$k]['url_lastchapter']=$obook_static_url.'/reader.php?aid='.$v->getVar('obookid').'&cid='.$v->getVar('lastchapterid');
			}
			//公众章节
			if($obookrows[$k]['articleid'] > 0){
				if($jieqiConfigs['article']['makehtml']==0 || JIEQI_CHAR_SET != JIEQI_SYSTEM_CHARSET){
					$obookrows[$k]['url_read'] = $article_static_url.'/reader.php?aid='.$obookrows[$k]['articleid'];
				}else{
					$obookrows[$k]['url_read'] = jieqi_uploadurl($jieqiConfigs['article']['htmldir'], $jieqiConfigs['article']['htmlurl'], 'article', $article_static_url).jieqi_getsubdir($obookrows[$k]['articleid']).'/'.$obookrows[$k]['articleid'].'/index'.$jieqiConfigs['article']['htmlfile'];
				}
			}
			$obookrows[$k]['lastvolume']=$v->getVar('lastvolumeid');  //分卷序号
			$obookrows[$k]['lastvolume']=$v->getVar('lastvolume');  //分卷名称

			$obookrows[$k]['authorid']=$v->getVar('authorid');  //作者
			$obookrows[$k]['author']=$v->getVar('author');
			$obookrows[$k]['posterid']=$v->getVar('posterid');  //发表者
			$obookrows[$k]['poster']=$v->getVar('poster');
			$obookrows[$k]['agentid']=$v->getVar('agentid');  //代理者
			$obookrows[$k]['agent']=$v->getVar('agent');

			$obookrows[$k]['sortid']=$v->getVar('sortid');  //类别序号
			$obookrows[$k]['sort']=$jieqiSort['obook'][$v->getVar('sortid')]['shortname'];  //类别

			$obookrows[$k]['size']=$v->getVar('size');
			$obookrows[$k]['size_k']=ceil($v->getVar('size')/1024);
			$obookrows[$k]['size_c']=ceil($v->getVar('size')/2);
			$obookrows[$k]['daysale']=$v->getVar('daysale');
			$obookrows[$k]['weeksale']=$v->getVar('weeksale');
			$obookrows[$k]['monthsale']=$v->getVar('monthsale');
			$obookrows[$k]['sumegold']=$v->getVar('sumegold');
			$obookrows[$k]['sumesilver']=$v->getVar('sumesilver');
			$obookrows[$k]['sumemoney']=$obookrows[$k]['sumegold']+$obookrows[$k]['sumesilver'];
			$obookrows[$k]['payprice']=$v->getVar('payprice');
			$obookrows[$k]['allsale']=$v->getVar('allsale');
			$obookrows[$k]['lastupdate']=date('y-m-d',$v->getVar('lastupdate')); //最后更新日期
			$obookrows[$k]['update']=date('m-d',$v->getVar('lastupdate')); //最后更新日期
			$obookrows[$k]['display']=$v->getVar('display');
			$obookrows[$k]['url_image']=jieqi_uploadurl($jieqiConfigs['obook']['imagedir'], $jieqiConfigs['obook']['imageurl'], 'obook', $obook_static_url).jieqi_getsubdir($v->getVar('obookid')).'/'.$v->getVar('obookid').'/'.$v->getVar('obookid').'s'.$jieqiConfigs['obook']['imagetype'];
			$k++;
		}
		$jieqiTpl->assign_by_ref('obookrows', $obookrows);
		$toptype=$this->exevars['field'];
		if($jieqiConfigs['obook']['faketoplist']==1){
			if(!empty($jieqiConfigs['obook']['fakeprefix'])) $tmpvar='/'.$jieqiConfigs['obook']['fakeprefix'].'top'.$toptype;
			else $tmpvar='/files/obook/top'.$toptype;
			$jieqiTpl->assign('url_more', $obook_dynamic_url.$tmpvar.'/0/1'.$jieqiConfigs['obook']['fakefile']);
		}else{
			$jieqiTpl->assign('url_more', $obook_dynamic_url.'/obooklist.php?sort='.$toptype);
		}
	}
}
?>