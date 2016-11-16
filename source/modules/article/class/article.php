<?php 
/**
 * 数据表类(jieqi_article_article - 文章信息表)
 *
 * 数据表类(jieqi_article_article - 文章信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: article.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();

class JieqiArticle extends JieqiObjectData
{
	//构建函数
	function JieqiArticle()
	{
		$this->JieqiObjectData();
		$this->initVar('articleid', JIEQI_TYPE_INT, 0, '序号', false, 11);
		$this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
		$this->initVar('postdate', JIEQI_TYPE_INT, 0, '发表日期', false, 11);
		$this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '最后更新', false, 11);
		$this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章标题', true, 250);
		$this->initVar('keywords', JIEQI_TYPE_TXTBOX, '', '关键字', false, 250);
		$this->initVar('initial', JIEQI_TYPE_TXTBOX, '', '标题首字母', false, 1);
		$this->initVar('authorid', JIEQI_TYPE_INT, 0, '作者序号', false, 11);
		$this->initVar('author', JIEQI_TYPE_TXTBOX, '', '作者', false, 30);
		$this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
		$this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 30);
		$this->initVar('agentid', JIEQI_TYPE_INT, 0, '代理人序号', false, 11);
		$this->initVar('agent', JIEQI_TYPE_TXTBOX, '', '代理人', false, 30);
		$this->initVar('sortid', JIEQI_TYPE_INT, 0, '类别序号', false, 3);
		$this->initVar('typeid', JIEQI_TYPE_INT, 0, '情节序号', false, 3);
		$this->initVar('intro', JIEQI_TYPE_TXTAREA, '', '内容简介', false, NULL);
		$this->initVar('notice', JIEQI_TYPE_TXTAREA, '', '本书公告', false, NULL);
		$this->initVar('setting', JIEQI_TYPE_TXTAREA, '', '文章参数', false, NULL);
		$this->initVar('lastvolumeid', JIEQI_TYPE_INT, 0, '末卷序号', false, 11);
		$this->initVar('lastvolume', JIEQI_TYPE_TXTBOX, '', '末卷', false, 250);
		$this->initVar('lastchapterid', JIEQI_TYPE_INT, 0, '最新章节序号', false, 11);
		$this->initVar('lastchapter', JIEQI_TYPE_TXTBOX, '', '最新章节', false, 255);
		$this->initVar('chapters', JIEQI_TYPE_INT, 0, '章节数', false, 6);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '字节数', false, 11);
		$this->initVar('lastvisit', JIEQI_TYPE_INT, 0, '最后访问', false, 11);
		$this->initVar('dayvisit', JIEQI_TYPE_INT, 0, '日访问', false, 11);
		$this->initVar('weekvisit', JIEQI_TYPE_INT, 0, '周访问', false, 11);
		$this->initVar('monthvisit', JIEQI_TYPE_INT, 0, '月访问', false, 11);
		$this->initVar('allvisit', JIEQI_TYPE_INT, 0, '总访问', false, 11);
		$this->initVar('lastvote', JIEQI_TYPE_INT, 0, '最后推荐', false, 11);
		$this->initVar('dayvote', JIEQI_TYPE_INT, 0, '日推荐', false, 11);
		$this->initVar('weekvote', JIEQI_TYPE_INT, 0, '周推荐', false, 11);
		$this->initVar('monthvote', JIEQI_TYPE_INT, 0, '月推荐', false, 11);
		$this->initVar('allvote', JIEQI_TYPE_INT, 0, '总推荐', false, 11);
		$this->initVar('goodnum', JIEQI_TYPE_INT, 0, '收藏数', false, 11);
		$this->initVar('badnum', JIEQI_TYPE_INT, 0, '投诉数', false, 11);
		$this->initVar('toptime', JIEQI_TYPE_INT, 0, '置顶时间', false, 11);
		$this->initVar('saleprice', JIEQI_TYPE_INT, 0, '销售价格', false, 11);
		$this->initVar('salenum', JIEQI_TYPE_INT, 0, '销售量', false, 11);
		$this->initVar('totalcost', JIEQI_TYPE_INT, 0, '总销售额', false, 11);
		$this->initVar('articletype', JIEQI_TYPE_INT, 0, '文章类型', false, 1);
		$this->initVar('permission', JIEQI_TYPE_INT, 0, '授权类型', false, 1);
		$this->initVar('firstflag', JIEQI_TYPE_INT, 0, '首发标志', false, 1);
		$this->initVar('fullflag', JIEQI_TYPE_INT, 0, '完整标志', false, 1);
		$this->initVar('imgflag', JIEQI_TYPE_INT, 0, '图片标志', false, 1);
		$this->initVar('power', JIEQI_TYPE_INT, 0, '访问级别', false, 1);
		$this->initVar('display', JIEQI_TYPE_INT, 0, '显示', false, 1);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiArticleHandler extends JieqiObjectHandler
{

	function JieqiArticleHandler($db='')
	{
		$this->JieqiObjectHandler($db);
		$this->basename='article';
		$this->autoid='articleid';
		$this->dbname='article_article';
	}

	function getCoverInfo($imgflag){
		global $jieqiConfigs;
		if(!isset($jieqiConfigs['article'])) jieqi_getconfigs('article', 'configs');
		$ret=array('stype'=>'', 'ltype'=>'');
		if(($imgflag & 1)>0) $ret['stype']=$jieqiConfigs['article']['imagetype'];
		if(($imgflag & 2)>0) $ret['ltype']=$jieqiConfigs['article']['imagetype'];

		$imgtype=$imgflag >> 2;
		if($imgtype > 0){
			$imgtary=array(1=>'.gif', 2=>'.jpg', 3=>'.jpeg', 4=>'.png', 5=>'.bmp');
			$tmpvar = round($imgtype & 7);
			if(isset($imgtary[$tmpvar])) $ret['stype']=$imgtary[$tmpvar];
			$tmpvar = round($imgtype >> 3);
			if(isset($imgtary[$tmpvar])) $ret['ltype']=$imgtary[$tmpvar];
		}
		return $ret;
	}
}

?>