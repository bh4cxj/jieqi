<?php
/**
 * 用户会客室留言主题显示区块
 *
 * 显示某个用户的会客室留言主题
 * 
 * 调用模板：/templates/blocks/block_uptopics.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_uptopics.php 328 2009-02-06 09:24:29Z juny $
 */

class BlockSystemUptopics extends JieqiBlock
{
	var $module='system';
	var $template='block_uptopics.html';
	
	var $exevars=array('field'=>'topicid', 'listnum'=>10, 'asc'=>0, 'uid'=>'uid', 'istop'=>0, 'isgood'=>0, 'islock'=>0);
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人
	//istop isgood islock : 0 = 不限 1 = 符合 2 = 不符合

	function BlockSystemUptopics(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('topicid', 'ownerid', 'posttime', 'replytime', 'views', 'replies', 'size'))) $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(in_array($varary[2], array('0', '1'))) $this->exevars['asc']=$varary[2];
			}

			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(strlen($varary[3]) > 0) $this->exevars['uid']=$varary[3];
			}

			if($arynum>4){
				$varary[4]=trim($varary[4]);
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['istop']=$varary[4];
			}

			if($arynum>5){
				$varary[5]=trim($varary[5]);
				if(in_array($varary[5], array('0', '1', '2'))) $this->exevars['isgood']=$varary[5];
			}

			if($arynum>6){
				$varary[6]=trim($varary[6]);
				if(in_array($varary[6], array('0', '1', '2'))) $this->exevars['islock']=$varary[6];
			}
		}
		$this->blockvars['cacheid']=0;
		if(strval($this->exevars['uid']) != '0'){
			if($this->exevars['uid'] == 'self') $this->blockvars['cacheid'] = intval($_SESSION['jieqiUserId']);
			elseif(is_numeric($this->exevars['uid'])) $this->blockvars['cacheid']=intval($this->exevars['uid']);
			else $this->blockvars['cacheid']=intval($_REQUEST[$this->exevars['uid']]);
		}
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		
		include_once(JIEQI_ROOT_PATH.'/class/ptopics.php');
		$ptopics_handler =& JieqiPtopicsHandler::getInstance('JieqiPtopicsHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('ownerid', $this->blockvars['cacheid']));

		if($this->exevars['istop']==1) $criteria->add(new Criteria('istop', 1));
		elseif($this->exevars['istop']==2) $criteria->add(new Criteria('istop', 0));

		if($this->exevars['isgood']==1) $criteria->add(new Criteria('isgood', 1));
		elseif($this->exevars['isgood']==2) $criteria->add(new Criteria('isgood', 0));

		if($this->exevars['islock']==1) $criteria->add(new Criteria('islock', 1));
		elseif($this->exevars['islock']==2) $criteria->add(new Criteria('islock', 0));

		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$ptopics_handler->queryObjects($criteria);
		$ptopicrows=array();
		$k=0;
		while($v = $ptopics_handler->getObject()){
			$ptopicrows[$k]['istop']=$v->getVar('istop');
			$ptopicrows[$k]['isgood']=$v->getVar('isgood');
			$ptopicrows[$k]['islock']=$v->getVar('islock');
			$ptopicrows[$k]['topicid']=$v->getVar('topicid');
			$ptopicrows[$k]['posttime']=$v->getVar('posttime');
			$ptopicrows[$k]['replytime']=$v->getVar('replytime');
			$ptopicrows[$k]['posterid']=$v->getVar('posterid');
			$ptopicrows[$k]['poster']=$v->getVar('poster');
			$ptopicrows[$k]['title']=$v->getVar('title');
			$ptopicrows[$k]['views']=$v->getVar('views');
			$ptopicrows[$k]['replies']=$v->getVar('replies');
			$ptopicrows[$k]['size']=$v->getVar('size');
			$ptopicrows[$k]['size_c']=ceil($v->getVar('size')/2);
			$ptopicrows[$k]['ownerid']=$v->getVar('ownerid');
			$k++;
		}
		$jieqiTpl->assign_by_ref('ptopicrows', $ptopicrows);
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);

		$jieqiTpl->assign('url_more', JIEQI_URL.'/ptopics?oid='.$this->blockvars['cacheid']);

	}
}

?>