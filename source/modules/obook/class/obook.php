<?php 
/**
 * 数据表类(jieqi_obook_obook - 电子书信息)
 *
 * 数据表类(jieqi_obook_obook - 电子书信息)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: obook.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();

class JieqiObook extends JieqiObjectData
{
    //构建函数
    function JieqiObook()
    {
        $this->JieqiObjectData();
        $this->initVar('obookid', JIEQI_TYPE_INT, 0, '序号', false, 11);
        $this->initVar('siteid', JIEQI_TYPE_INT, 0, '网站序号', false, 6);
        $this->initVar('postdate', JIEQI_TYPE_INT, 0, '加入日期', false, 11);
        $this->initVar('lastupdate', JIEQI_TYPE_INT, 0, '更新日期', false, 11);
        $this->initVar('obookname', JIEQI_TYPE_TXTBOX, '', '电子书名', true, 100);
        $this->initVar('keywords', JIEQI_TYPE_TXTBOX, '', '关键字', false, 250);
        $this->initVar('articleid', JIEQI_TYPE_INT, 0, '相关文章序号', false, 11);
        $this->initVar('initial', JIEQI_TYPE_TXTBOX, '', '书名首字母', false, 1);
        $this->initVar('sortid', JIEQI_TYPE_INT, 0, '分类序号', false, 6);
        $this->initVar('intro', JIEQI_TYPE_TXTAREA, '', '内容简介', false, NULL);
        $this->initVar('notice', JIEQI_TYPE_TXTAREA, '', '本书公告', false, NULL);
        $this->initVar('setting', JIEQI_TYPE_TXTAREA, '', '文章参数', false, NULL);
        $this->initVar('lastvolumeid', JIEQI_TYPE_INT, 0, '最新分卷序号', false, 11);
        $this->initVar('lastvolume', JIEQI_TYPE_TXTBOX, '', '最新分卷', false, 255);
        $this->initVar('lastchapterid', JIEQI_TYPE_INT, 0, '最新章节序号', false, 11);
        $this->initVar('lastchapter', JIEQI_TYPE_TXTBOX, '', '最新章节', false, 255);
        $this->initVar('chapters', JIEQI_TYPE_INT, 0, '章节数', false, 6);
        $this->initVar('authorid', JIEQI_TYPE_INT, 0, '作者序号', false, 11);
        $this->initVar('author', JIEQI_TYPE_TXTBOX, '', '作者', false, 50);
        $this->initVar('aintro', JIEQI_TYPE_TXTAREA, '', '作者简介', false, NULL);
        $this->initVar('agentid', JIEQI_TYPE_INT, 0, '所有人序号', false, 11);
        $this->initVar('agent', JIEQI_TYPE_TXTBOX, '', '所有人', false, 50);
        $this->initVar('posterid', JIEQI_TYPE_INT, 0, '发表者序号', false, 11);
        $this->initVar('poster', JIEQI_TYPE_TXTBOX, '', '发表者', false, 50);
        $this->initVar('publishid', JIEQI_TYPE_INT, 0, '出版者序号', false, 11);
        $this->initVar('tbookinfo', JIEQI_TYPE_TXTAREA, '', '实体书信息', false, NULL);
        $this->initVar('toptime', JIEQI_TYPE_INT, 0, '置顶时间', false, 11);
        $this->initVar('goodnum', JIEQI_TYPE_INT, 0, '收藏量', false, 11);
        $this->initVar('badnum', JIEQI_TYPE_INT, 0, '投诉量', false, 11);
        $this->initVar('fullflag', JIEQI_TYPE_INT, 0, '书籍发全标志', false, 1);
        $this->initVar('imgflag', JIEQI_TYPE_INT, 0, '图片标志', false, 1);
        $this->initVar('saleprice', JIEQI_TYPE_INT, 0, '销售价格', false, 11);
        $this->initVar('vipprice', JIEQI_TYPE_INT, 0, '优惠价格', false, 11);
        $this->initVar('sumegold', JIEQI_TYPE_INT, 0, '金币总销售额', false, 11);
        $this->initVar('sumesilver', JIEQI_TYPE_INT, 0, '银币总销售额', false, 11);
        $this->initVar('normalsale', JIEQI_TYPE_INT, 0, '普通价格销售量', false, 11);
        $this->initVar('vipsale', JIEQI_TYPE_INT, 0, 'VIP价格销售量', false, 11);
        $this->initVar('freesale', JIEQI_TYPE_INT, 0, '免费阅读销售量', false, 11);
        $this->initVar('bespsale', JIEQI_TYPE_INT, 0, '包月阅读销售量', false, 11);
        $this->initVar('totalsale', JIEQI_TYPE_INT, 0, '合计销售量', false, 11);
        $this->initVar('daysale', JIEQI_TYPE_INT, 0, '本日销售量', false, 11);
        $this->initVar('weeksale', JIEQI_TYPE_INT, 0, '本周销售量', false, 11);
        $this->initVar('monthsale', JIEQI_TYPE_INT, 0, '本月销售量', false, 11);
        $this->initVar('allsale', JIEQI_TYPE_INT, 0, '总销售量', false, 11);
        $this->initVar('lastsale', JIEQI_TYPE_INT, 0, '最后销售时间', false, 11);
        $this->initVar('canvip', JIEQI_TYPE_INT, 0, '是否允许VIP阅读', false, 1);
        $this->initVar('canfree', JIEQI_TYPE_INT, 0, '是否允许免费阅读', false, 1);
        $this->initVar('canbesp', JIEQI_TYPE_INT, 0, '是否允许包月阅读', false, 1);
        $this->initVar('hasebook', JIEQI_TYPE_INT, 0, '是否有电子书', false, 1);
        $this->initVar('hastbook', JIEQI_TYPE_INT, 0, '是否有实体书', false, 1);
        $this->initVar('state', JIEQI_TYPE_INT, 0, '状态', false, 1);
        $this->initVar('flag', JIEQI_TYPE_INT, 0, '标志', false, 1);
        $this->initVar('display', JIEQI_TYPE_INT, 0, '是否显示', false, 1);
    }
    
    function getSalestatus($display=''){
    	global $jieqiLang;
    	jieqi_loadlang('obook', 'obook');
    	if($display=='') $display=$this->getVar('display', 'n');
    	switch($display){
    		case 1:
    		return $jieqiLang['obook']['obook_status_noauth'];
    		case 2:
    		return $jieqiLang['obook']['obook_status_unsale'];
    		case 0:
    		default:
    		return $jieqiLang['obook']['obook_status_sale'];
    	}
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiObookHandler extends JieqiObjectHandler
{
	
	function JieqiObookHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='obook';
	    $this->autoid='obookid';	
	    $this->dbname='obook_obook';
	}
}

?>