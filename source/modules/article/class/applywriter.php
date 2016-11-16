<?php 
/**
 * 数据表类(jieqi_article_applywriter - 作家申请记录)
 *
 * 数据表类(jieqi_article_applywriter - 作家申请记录)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: applywriter.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();
class JieqiApplywriter extends JieqiObjectData
{
    //构建函数
    function JieqiApplywriter()
    {
        $this->JieqiObjectData();
        $this->initVar('applyid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('applytime', JIEQI_TYPE_INT, 0, '申请时间', false, 11);
        $this->initVar('applyuid', JIEQI_TYPE_INT, 0, '申请人序号', true, 11);
        $this->initVar('applyname', JIEQI_TYPE_TXTBOX, '', '申请人名字', false, 30);
        $this->initVar('penname', JIEQI_TYPE_TXTBOX, '', '申请昵称', false, 30);
        $this->initVar('authtime', JIEQI_TYPE_INT, 0, '审核时间', false, 11);
        $this->initVar('authuid', JIEQI_TYPE_INT, 0, '审核人序号', false, 11);
        $this->initVar('authname', JIEQI_TYPE_TXTBOX, '', '审核人名字', false, 30);
        $this->initVar('applytitle', JIEQI_TYPE_TXTBOX, '', '申请标题', false, 250);
        $this->initVar('applytext', JIEQI_TYPE_TXTAREA, '', '申请内容', true, NULL);
        $this->initVar('applysize', JIEQI_TYPE_INT, 0, '申请样章字数', false, 11);
        $this->initVar('authnote', JIEQI_TYPE_TXTAREA, '', '斑竹备注', true, NULL);
        $this->initVar('applyflag', JIEQI_TYPE_INT, 0, '审核标志', false, 1);  
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiApplywriterHandler extends JieqiObjectHandler
{
	function JieqiApplywriterHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='applywriter';
	    $this->autoid='applyid';	
	    $this->dbname='article_applywriter';
	}
}

?>