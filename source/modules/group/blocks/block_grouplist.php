<?php
/**
 * 通用的用户排行区块
 *
 * 通用的用户排行区块
 * 
 * 调用模板：/modules/group/templates/blocks/block_grouplist.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
class Blockgrouplist extends JieqiBlock
{
	var $module = 'group';
	var $exevars=array('field'=>'gid', 'listnum'=>10,'asc'=>0);  //执行配置
	var $template='block_grouplist.html';
	var $cachetime = -1;
	function Blockgrouplist(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('gid', 'topicnum', 'gmembers', 'gtime'))) $this->exevars['field']=$varary[0];
			}
			
			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(is_numeric($varary[1]) && $varary[1]>0) $this->exevars['listnum']=intval($varary[1]);
			}
			
			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(in_array($varary[2], array('0', '1'))) $this->exevars['asc']=$varary[2];
			}
		}
	}

	function setContent($isreturn=false){
		global $jieqiTpl,$gcats;
		include_once(JIEQI_ROOT_PATH.'/configs/group/gcats.php');
		require_once(JIEQI_ROOT_PATH.'/modules/group/class/group.php');
		$group_handler = JieqigroupHandler::getInstance('JieqigroupHandler');
		$criteria = new CriteriaCompo();
		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$group_handler->queryObjects($criteria);	
		$grouprows=array();
		$k=0;
		while($v = $group_handler->getObject()){
			$grouprows[$k]['gid']=$v->getVar('gid');
			$grouprows[$k]['gname'] = $v->getVar('gname');
			$grouprows[$k]['topicnum'] = $v->getVar('topicnum');
			$grouprows[$k]['gmembers'] = $v->getVar('gmembers');
			$grouprows[$k]['guname'] = $v->getVar('guname');
			$grouprows[$k]['catname'] = $gcats[$v->getVar('gcatid')];
			$k++;
		}

		$jieqiTpl->assign_by_ref('grouprows', $grouprows);
		$jieqiTpl->assign('sort', $this->exevars['field']);

		$jieqiTpl->assign('url_more', JIEQI_URL.'/modules/group/index.php?sort='.$this->exevars['field']);
		$jieqiTpl->setCaching(0);

	}
}

?>