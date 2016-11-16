<?php 
/**
 * 数据表类(jieqi_obook_ocontent - 电子书章节内容)
 *
 * 数据表类(jieqi_obook_ocontent - 电子书章节内容)
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    obook
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: ocontent.php 300 2008-12-26 04:36:06Z juny $
 */

jieqi_includedb();

class JieqiOcontent extends JieqiObjectData
{
    //构建函数
    function JieqiOcontent()
    {
        $this->JieqiObjectData();
        $this->initVar('ocontentid', JIEQI_TYPE_INT, 0, '内容序号', false, 11);
        $this->initVar('ochapterid', JIEQI_TYPE_INT, 0, '章节序号', false, 11);
        $this->initVar('ocontent', JIEQI_TYPE_TXTAREA, '', '章节内容', true, NULL);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//内容句柄
class JieqiOcontentHandler extends JieqiObjectHandler
{
	
	function JieqiOcontentHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='ocontent';
	    $this->autoid='ocontentid';	
	    $this->dbname='obook_ocontent';
	}
}

?>