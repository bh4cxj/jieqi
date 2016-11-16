<?php
/**
 * 数据表类(jieqi_article_attachs - 文章附件信息表)
 *
 * 数据表类(jieqi_article_attachs - 文章附件信息表)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: articleattachs.php 230 2008-11-27 08:46:07Z juny $
 */

jieqi_includedb();
//论坛附件
class JieqiArticleattachs extends JieqiObjectData
{
    //构建函数
    function JieqiArticleattachs()
    {       
        $this->JieqiObjectData();
        $this->initVar('attachid', JIEQI_TYPE_INT, 0, '附件序号', false, 11);
        $this->initVar('articleid', JIEQI_TYPE_INT, 0, '文章序号', false, 11);
		$this->initVar('chapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
		$this->initVar('name', JIEQI_TYPE_TXTBOX, '', '附件名称', true, 80);
        $this->initVar('class', JIEQI_TYPE_TXTBOX, '', '附件类型', true, 30);
		$this->initVar('postfix', JIEQI_TYPE_TXTBOX, '', '附件后缀', true, 30);
		$this->initVar('size', JIEQI_TYPE_INT, 0, '文件大小', false, 11);
		$this->initVar('hits', JIEQI_TYPE_INT, 0, '点击数', false, 11);
		$this->initVar('needexp', JIEQI_TYPE_INT, 0, '需要经验值', false, 11);
		$this->initVar('uptime', JIEQI_TYPE_INT, 0, '上传时间', false, 11);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiArticleattachsHandler extends JieqiObjectHandler
{
	function JieqiArticleattachsHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='articleattachs';
	    $this->autoid='attachid';	
	    $this->dbname='article_attachs';
	}
}
?>