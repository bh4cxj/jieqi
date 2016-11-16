<?php 
/**
 * 我的转载文章列表
 *
 * 我的转载文章列表
 * 
 * 调用模板：/modules/article/templates/blocks/block_transarticles.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_transarticles.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleTransarticles extends JieqiBlock
{
    var $module = 'article';
	var $template = 'block_transarticles.html';
	var $cachetime = -1;
	
	function BlockArticleTransarticles(){
		$this->JieqiBlock($vars);
		$this->blockvars['cacheid'] = intval($_SESSION['jieqiUserId']);
	}
    
	
	function setContent(){
		global $jieqiTpl;
		global $jieqiConfigs;

		if(empty($_SESSION['jieqiUserId'])){
			return false;
		}else{
			jieqi_getconfigs('article', 'configs');
		    $article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['staticurl'];
		    $article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $GLOBALS['jieqiModules']['article']['url'] : $jieqiConfigs['article']['dynamicurl'];
		    $jieqiTpl->assign('article_static_url',$article_static_url);
		    $jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		
			include_once($GLOBALS['jieqiModules']['article']['path'].'/class/article.php');
			$article_handler =& JieqiArticleHandler::getInstance('JieqiArticleHandler');
			$criteria = new CriteriaCompo(new Criteria('posterid', $_SESSION['jieqiUserId'], '='));
			$criteria->add(new Criteria('authorid', $_SESSION['jieqiUserId'], '!='), 'AND');
			$article_handler->queryObjects($criteria);
			$articlerows=array();
			$i=0;
			while($v = $article_handler->getObject()){
				$articlerows[$i]['articleid']=$v->getVar('articleid');
				$articlerows[$i]['articlename']=$v->getVar('articlename');
				$i++;
			}
			$jieqiTpl->assign_by_ref('articlerows',$articlerows);		
		}
	}
}