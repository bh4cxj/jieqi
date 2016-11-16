<?php 
/**
 * 数据表类(jieqi_article_chapter - 章节表)
 *
 * 数据表类(jieqi_article_chapter - 章节表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: chapter.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
//用户类
class JieqiChapter extends JieqiObjectData
{
	//构建函数
	function JieqiChapter()
	{
		$this->JieqiObjectData();
		$this->initVar('chapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
		$this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
		$this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', true, 11);
		$this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章名称', false, 250);
		$this->initVar('volumeid', JIEQI_TYPE_INT, 0, '卷序号', true, 11);
		$this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 30);
		$this->initVar('postdate', JIEQI_TYPE_INT, 0, '发表日期', false, 11);
		$this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '最后更新', false, 11);
		$this->initVar('chaptername', JIEQI_TYPE_TXTBOX, '', '章节标题', true, 250);
		$this->initVar('chapterorder', JIEQI_TYPE_INT, 0, '章节排序', false, 6);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '字节数', false, 11);
		$this->initVar('saleprice', JIEQI_TYPE_INT, 0, '销售价格', false, 11);
        $this->initVar('salenum', JIEQI_TYPE_INT, 0, '销售量', false, 11);
        $this->initVar('totalcost', JIEQI_TYPE_INT, 0, '总销售额', false, 11);
        $this->initVar('attachment', JIEQI_TYPE_TXTAREA, '', '附件', false, NULL);
        $this->initVar('isvip', JIEQI_TYPE_INT, 0, '是否出电子书', false, 1);
		$this->initVar('chaptertype', JIEQI_TYPE_INT, 0, '章节类型', false, 1);
		$this->initVar('power', JIEQI_TYPE_INT, 0, '访问级别', false, 1);
		$this->initVar('display', JIEQI_TYPE_INT, 0, '显示', false, 1);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiChapterHandler extends JieqiObjectHandler
{
	function JieqiChapterHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='chapter';
	    $this->autoid='chapterid';	
	    $this->dbname='article_chapter';
	}
}

?>