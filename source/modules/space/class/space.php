<?php
// $Id: forumcat.php 2004-03-04 $
//  ------------------------------------------------------------------------ 
//                                ½ÜÆæÍøÂç                                     
//                    Copyright (c) 2004 jieqi.com                         
//                       <http://www.jieqi.com/>                           
//  ------------------------------------------------------------------------
//  Éè¼Æ£ºÐì·ç(juny)
//  ÓÊÏä: 377653@qq.com
//  ------------------------------------------------------------------------
jieqi_includedb();

class Jieqispace extends JieqiObjectData
{
    //¹¹½¨º¯Êý
    function JieqiSpace()
    {
        $this->JieqiObjectData();
        $this->initVar('uid', JIEQI_TYPE_INT, 0, 'ÐòºÅ', false, 6);
    }
}

//------------------------------------------------------------------------
//------------------------------------------------------------------------

//ÄÚÈÝ¾ä±ú
class JieqiSpaceHandler extends JieqiObjectHandler
{
	function JieqiSpaceHandler($db='')
	{
	    $this->JieqiObjectHandler($db);
	    $this->basename='space';
	    $this->autoid='uid';	
	    $this->dbname='space_space';
	}
}
?>
