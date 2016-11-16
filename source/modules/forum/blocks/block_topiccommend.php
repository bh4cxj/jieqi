<?php 
/**
 * 论坛主题推荐区块
 *
 * 论坛主题推荐区块
 * 
 * 调用模板：/modules/forum/templates/blocks/block_topiclist.html
 * 
 * @category   jieqicms
 * @package    forum
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_topiclist.php 253 2008-11-28 03:21:13Z juny $
 */

class BlockForumTopiccommend extends JieqiBlock
{
	var $module = 'forum';
	var $template = 'block_topiccommend.html';

	var $exevars=array();  //执行配置

	function BlockForumTopiccommend(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$tmpary=explode('|', trim($this->blockvars['vars']));
			foreach($tmpary as $v){
				$v=trim($v);
				if(is_numeric($v)) $this->exevars[]=intval($v);
			}
			$this->exevars=array_unique($this->exevars);
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);
	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		$topicrows=array();
		if(count($this->exevars) > 0){
			include_once(JIEQI_ROOT_PATH.'/include/funpost.php');
			include_once($GLOBALS['jieqiModules']['forum']['path'].'/class/forumtopics.php');
			$topics_handler=JieqiForumtopicsHandler::getInstance('JieqiForumtopicsHandler');

			$criteria=new CriteriaCompo();
			$criteria->add(new Criteria('uid', '(0,'.implode(',', $this->exevars).')', 'IN'));
			$criteria->setLimit(100);
			$criteria->setStart(0);

			$topics_handler->queryObjects($criteria);
			$k=0;
			while($topic = $topics_handler->getObject()){
				$topicrows[$k] = jieqi_topic_vars($topic);
				$k++;
			}
		}
		$jieqiTpl->assign_by_ref('topicrows', $topicrows);
		if(is_numeric($this->exevars['forumid']) && intval($this->exevars['f
		orumid'])>0) $jieqiTpl->assign('url_more', $GLOBALS['jieqiModules']['forum']['url'].'/topiclist.php?fid='.intval($this->exevars['forumid']));
		else $jieqiTpl->assign('url_more', $GLOBALS['jieqiModules']['forum']['url'].'/index.php');
	}
}

?>