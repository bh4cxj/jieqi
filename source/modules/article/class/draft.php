<?php 
/**
 * 数据表类(jieqi_article_draft - 草稿信息表)
 *
 * 数据表类(jieqi_article_draft - 草稿信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: draft.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
//用户类
class JieqiDraft extends JieqiObjectData
{
	//构建函数
	function JieqiDraft()
	{
		$this->JieqiObjectData();
		$this->initVar('draftid', JIEQI_TYPE_INT, 0, '序号', false, 11);
		$this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', true, 11);
		$this->initVar('articlename', JIEQI_TYPE_TXTBOX, '', '文章名称', false, 250);
		$this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 30);
		$this->initVar('postdate', JIEQI_TYPE_INT, 0, '发表日期', false, 11);
		$this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '最后更新', false, 11);
		$this->initVar('draftname', JIEQI_TYPE_TXTBOX, '', '章节标题', true, 250);
		$this->initVar('content', JIEQI_TYPE_TXTAREA, '', '章节内容', true, NULL);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '字节数', false, 11);
		$this->initVar('note', JIEQI_TYPE_TXTAREA, '', '备注', false, NULL);
		$this->initVar('drafttype', JIEQI_TYPE_INT, 0, '类型', false, 1);
	}
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiDraftHandler extends JieqiObjectHandler
{
	function JieqiDraftHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='draft';
	    $this->autoid='draftid';	
	    $this->dbname='article_draft';
	}
}

?>