<?php 
/**
 * 我的文章列表
 *
 * 显示一个用户的原创文章列表
 * 
 * 调用模板：/modules/article/templates/blocks/block_myarticles.html
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_myarticles.php 300 2008-12-26 04:36:06Z juny $
 */

class BlockArticleMyarticles extends JieqiBlock
{
    var $module = 'article';
	var $template = 'block_myarticles.html';
	var $cachetime = -1;
    
	function BlockArticleMyarticles(){
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
			$criteria = new CriteriaCompo(new Criteria('authorid', $_SESSION['jieqiUserId']));
			$article_handler->queryObjects($criteria);
			$articles=array();
			$i=0;
			while($v = $article_handler->getObject()){
				$articles[$i]['url']=$article_static_url.'/articlemanage.php?id='.$v->getVar('articleid');
				$articles[$i]['caption']=$v->getVar('articlename');
				$articles[$i]['goodnum']=$v->getVar('goodnum');
				$articles[$i]['lastupdate']=$v->getVar('lastupdate');
				$articles[$i]['id']=$v->getVar('articleid');
				$i++;
			}
			$jieqiTpl->assign_by_ref('articles',$articles);		
		}
	}

}