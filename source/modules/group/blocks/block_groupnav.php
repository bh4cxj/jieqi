<?php
/**
 * 圈子简介区块
 *
 * 圈子简介区块
 * 
 * 调用模板：/modules/group/templates/blocks/block_nav.html
 * 
 * @category   jieqicms
 * @package    group
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */


class BlockGroupNav extends JieqiBlock
{
	var $module = 'group';
	var $template = 'block_nav.html';
	var $cachetime = -1;
	function BlockGroupNav(&$vars){
	    $this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				$this->exevars['field']=$varary[0];
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
		global $jieqiTpl;
		global $gid;
		$jieqiTpl->assign('index_href',JIEQI_URL);
		$jieqiTpl->assign('intro_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/intro.php?g='.$gid);
		$jieqiTpl->assign('party_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/parties.php?g='.$gid);
		$jieqiTpl->assign('topic_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/topiclist.php?g='.$gid);
		$jieqiTpl->assign('poll_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/poll.php?g='.$gid);
		$jieqiTpl->assign('member_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/member.php?g='.$gid);
		$jieqiTpl->assign('album_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/album.php?g='.$gid);
	}	

}

?>