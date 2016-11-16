<?php 
/**
 * 论坛主题列表通用区块
 *
 * 论坛主题列表通用区块
 * 
 * 调用模板：/modules/forum/templates/blocks/block_topiclist.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_topiclist.php 329 2009-02-07 01:21:38Z juny $
 */

class BlockForumTopiclist extends JieqiBlock
{
	var $module = 'forum';
	var $template = 'block_topiclist.html';
	
	var $blockvars=array();
	var $exevars=array('field'=>'replytime', 'listnum'=>10, 'forumid'=>'0', 'asc'=>0);  //执行配置

	function BlockForumTopiclist(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('replytime', 'topictime', 'topicviews', 'topicreplies'))) $this->exevars['field']=$varary[0];
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}

			if($arynum>2){
				$varary[2]=trim($varary[2]);
				$tmpvar=str_replace('|', '', $varary[2]);
				if(is_numeric($tmpvar)) $this->exevars['forumid']=$varary[2];
			}

			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(in_array($varary[3], array('0', '1'))) $this->exevars['asc']=$varary[3];
			}
		}
		
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);

	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
		include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumtopics.php');
		$topics_handler=JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');

		$criteria = new CriteriaCompo();
		if(!empty($this->exevars['forumid'])){
			$eclassary=explode('|', $this->exevars['forumid']);
			foreach($eclassary as $v){
				if(is_numeric($v)) $criteria->add(new Criteria('forumid', intval($v), '='), 'OR');
			}
		}
		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$topics_handler->queryObjects($criteria);
		$topicrows=array();
		$k=0;
		while($topic = $topics_handler->getObject()){
			$topicrows[$k] = jieqi_topic_vars($topic);
			$k++;
		}
		$jieqiTpl->assign_by_ref('topicrows', $topicrows);
		if(is_numeric($this->exevars['forumid']) && intval($this->exevars['f
		orumid'])>0) $jieqiTpl->assign('url_more', $GLOBALS['jieqiModules']['forum']['url'].'/topiclist.php?fid='.intval($this->exevars['forumid']));
		else $jieqiTpl->assign('url_more', $GLOBALS['jieqiModules']['forum']['url'].'/index.php');
	}
}

?>