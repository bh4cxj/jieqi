<?php 
/**
 * 用户文章列表区块
 *
 * 显示某个用户的原创文章
 * 
 * 调用模板：/modules/article/templates/blocks/block_uarticles.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_uarticles.php 332 2009-02-23 09:15:08Z juny $
 */

class BlockArticleUarticles extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_uarticles.html';

	var $exevars=array('field'=>'lastupdate', 'listnum'=>10, 'asc'=>0, 'uid'=>'uid', 'isfull'=>0);
	//uid: 'self' = 自己, 'uid' = $_REQUEST['uid'], 0 = 所有人, >0 = 某个人
	//isfull : 0 = 不限 1 = 符合 2 = 不符合


	function BlockArticleUarticles(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(in_array($varary[0], array('articleid', 'postdate', 'lastupdate', 'allvisit', 'monthvisit', 'weekvisit', 'dayvisit', 'allvote', 'monthvote', 'weekvote', 'dayvote', 'size', 'goodnum', 'badnum'))) $this->exevars['field']=$varary[0];
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
				if(in_array($varary[4], array('0', '1', '2'))) $this->exevars['isfull']=$varary[4];
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
		global $jieqiConfigs;
		global $jieqiSort;
		
		jieqi_getconfigs('article', 'configs');
		jieqi_getconfigs('article', 'sort');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		$this->blockvars['cacheid']=0;
		if(strval($this->exevars['uid']) != '0'){
			if($this->exevars['uid'] == 'self'){
				$this->blockvars['cacheid']=$_SESSION['jieqiUserId'];
			}elseif(is_numeric($this->exevars['uid'])){
				$this->blockvars['cacheid']=intval($this->exevars['uid']);
			}else{
				$this->blockvars['cacheid']=intval($_REQUEST[$this->exevars['uid']]);
			}
		}
		include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
		//载入相关处理函数
		include_once($GLOBALS['jieqiModules']['article']['path'].'/include/funarticle.php');
		$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
		$criteria=new CriteriaCompo();
		$criteria->add(new Criteria('authorid', $this->blockvars['cacheid']));
		
		if($this->exevars['isfull']==1) $criteria->add(new Criteria('isfull', 1));
		elseif($this->exevars['isfull']==2) $criteria->add(new Criteria('isfull', 0));
		$criteria->setSort($this->exevars['field']);
		if($this->exevars['asc']==1) $criteria->setOrder('ASC');
		else  $criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$article_handler->queryObjects($criteria);
		$articlerows=array();
		$k=0;
		while($v = $article_handler->getObject()){
			$articlerows[$k] = jieqi_article_vars($v);
			$articlerows[$k]['order']=$k+1;
				
			if($this->exevars['field']=='goodnew') $articlerows[$k]['visitnum']=$v->getVar('allvisit');
			else $articlerows[$k]['visitnum']=$v->getVar($this->exevars['field']);
			if($this->exevars['field']=='size') $articlerows[$k]['visitnum']=ceil($articlerows[$k]['visitnum']/1024).'K';
			elseif($this->exevars['field']=='lastupdate' || $this->exevars['field']=='postdate' || $this->exevars['field']=='toptime') $articlerows[$k]['visitnum']=date('m-d', $articlerows[$k]['visitnum']);
			$k++;
		}
		$jieqiTpl->assign_by_ref('articlerows',$articlerows);
		$jieqiTpl->assign('ownerid', $this->blockvars['cacheid']);
	}

}