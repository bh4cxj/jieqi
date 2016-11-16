<?php
/**
 * 文章列表区块
 *
 * 文章列表
 * 
 * 调用模板：/modules/space/templates/blocks/block_spacelist.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//文章分类
//通用的用户排行区块
class BlockSpaceList extends JieqiBlock
{
	var $module = 'space';
	var $template='block_spacelist.html';
	var $exevars=array('field'=>'uid', 'listnum'=>10,'asc'=>0);  //执行配置
	function BlockSpaceList(&$vars){
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
        $this->blockvars['cacheid'] = $this->template.'|'.$this->blockvars['bid'].'|'.$this->exevars['field'].'|'.$this->exevars['listnum'].'|'.$this->exevars['asc'];
	}

	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiModules;
		require_once($jieqiModules['space']['path'].'/class/space.php');
		$space_handler = JieqiSpaceHandler::getInstance('JieqiSpaceHandler');
		$criteria=new CriteriaCompo();
		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		if($this->blockvars['bid'] == 5){
			$criteria->add(new Criteria('sp_commend',1) );
		}
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$space_handler->queryObjects($criteria);
		$spaces=array();
		$k=0;
		while($v = $space_handler->getObject()){
			$spaces[$k]['uid']=$v->getVar('uid');
			$spaces[$k]['space_title']=jieqi_substr($v->getVar('title'),0,16);
			$spaces[$k]['visit_num'] = $v->getVar('visit_num');
			$k++;
		}
		if($this->blockvars['bid'] == 3){
			$sort = 'spacelistvisit.php';
		}elseif($this->blockvars['bid'] == 4){
			$sort = 'spacelistup.php';
		}elseif($this->blockvars['bid'] == 5){
			$sort = 'spacelistcom.php';
		}elseif($this->blockvars['bid'] == 10){
			$sort = 'spacelistvisit.php';
		}
		$jieqiTpl->assign('url_more',$jieqiModules['space']['url'].'/'.$sort);
		$jieqiTpl->assign_by_ref('spaces', $spaces);
		$jieqiTpl->fetch($GLOBALS['jieqiModules']['space']['path'].'/templates/blocks/block_newblog.html');
	}
}

?>