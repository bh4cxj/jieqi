<?php
/**
 * 书评列表区块
 *
 * 默认显示最新书评
 * 
 * 调用模板：/modules/article/templates/blocks/block_newreview.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_reviewslist.php 332 2009-02-23 09:15:08Z juny $
 */

class BlockArticleReviewslist extends JieqiBlock
{
	var $module = 'article';
	var $template = 'block_viewslist.html';

	var $exevars=array('listnum'=>10, 'istop'=>'0', 'isgood'=>0, 'width'=>64);  //执行配置
	//listnum 显示行数
	//istop 0表示所有，1置顶，2非置顶
	//isgood 0 表示所有, 1精华，2表示转载

	function BlockArticleReviewslist(&$vars){
		$this->JieqiBlock($vars);
		if(!empty($this->blockvars['vars'])){
			$varary=explode(',', trim($this->blockvars['vars']));
			$arynum=count($varary);
			if($arynum>0){
				$varary[0]=trim($varary[0]);
				if(is_numeric($varary[0])) $this->exevars['listnum']=intval($varary[0]);
			}

			if($arynum>1){
				$varary[1]=trim($varary[1]);
				if(in_array($varary[1], array('0', '1', '2'))) $this->exevars['istop']=$varary[1];
			}
			
			if($arynum>2){
				$varary[2]=trim($varary[2]);
				if(in_array($varary[2], array('0', '1', '2'))) $this->exevars['isgood']=$varary[2];
			}
			
			if($arynum>3){
				$varary[3]=trim($varary[3]);
				if(is_numeric($varary[3])) $this->exevars['width']=intval($varary[3]);
			}
		}
		$this->blockvars['cacheid']=md5(serialize($this->exevars).'|'.$this->blockvars['template']);
	}


	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;

		jieqi_getconfigs('article', 'configs');
		$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);

		jieqi_includedb();
		$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
		$criteria = new CriteriaCompo();
		$criteria->setFields("r.*,a.articlename");
		$criteria->setTables(jieqi_dbprefix('article_reviews')." AS r LEFT JOIN ".jieqi_dbprefix('article_article')." AS a ON r.ownerid=a.articleid");

		if($this->exevars['istop'] == 1) $criteria->add(new Criteria('r.istop', '1'));
		elseif($this->exevars['istop'] == 2) $criteria->add(new Criteria('r.istop', '0'));
		if($this->exevars['isgood'] == 1) $criteria->add(new Criteria('r.isgood', '1'));
		elseif($this->exevars['isgood'] == 2) $criteria->add(new Criteria('r.isgood', '0'));
		$criteria->setSort('r.topicid');
		$criteria->setOrder('DESC');
		$criteria->setLimit($this->exevars['listnum']);
		$criteria->setStart(0);
		$query->queryObjects($criteria);
		$reviewrows=array();
		$i=0;
		while($v = $query->getObject()){		
			$reviewrows[$i]['reviewtitle']=jieqi_htmlstr(str_replace(array("\r", "\n"), array('', ' '), $v->getVar('title','n')));
			$reviewrows[$i]['ownerid']=$v->getVar('ownerid');
			$reviewrows[$i]['articlename']=$v->getVar('articlename');
			$reviewrows[$i]['topicid']=$v->getVar('topicid');
			$reviewrows[$i]['url_review']=$article_dynamic_url.'/reviews.php?aid='.$v->getVar('ownerid');
			$reviewrows[$i]['poster']=$v->getVar('poster');
			$reviewrows[$i]['posterid']=$v->getVar('posterid');
			$reviewrows[$i]['postdate']=date('m-d H:i',$v->getVar('posttime'));
			$reviewrows[$i]['posttime']=$v->getVar('posttime');
			$reviewrows[$i]['replytime']=$v->getVar('replytime');
			$reviewrows[$i]['views']=$v->getVar('views');
			$reviewrows[$i]['replies']=$v->getVar('replies');
			$reviewrows[$i]['islock']=$v->getVar('islock');
			$reviewrows[$i]['istop']=$v->getVar('istop');
			$reviewrows[$i]['isgood']=$v->getVar('isgood');
			$reviewrows[$i]['topictype']=$v->getVar('topictype');			
			$reviewrows[$i]['url_articleinfo']=jieqi_geturl('article', 'article', $v->getVar('ownerid'), 'info');
			$reviewrows[$i]['url_articleindex']=jieqi_geturl('article', 'article', $v->getVar('ownerid'), 'index');
			$reviewrows[$i]['url_articleread'] = $reviewrows[$i]['url_articleindex'];
			$i++;
		}
		$jieqiTpl->assign_by_ref('reviewrows', $reviewrows);
		$jieqiTpl->assign('url_more',$article_dynamic_url.'/reviewslist.php');
	}
}

?>