<?php 
/**
 * 用户组列表区块
 *
 * 用户组列表区块
 * 
 * 调用模板：/templates/blocks/block_grouplist.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: block_grouplist.php 331 2009-02-13 09:07:14Z juny $
 */

class BlockSystemGrouplist extends JieqiBlock
{
	var $module='system';
	var $template='block_grouplist.html';

	/**
	 * 赋值区块内容
	 * 
	 * @param      void
	 * @access     private
	 * @return     void
	 */
    function setContent(){
		global $jieqiSort;
		global $jieqiTpl;
		global $jieqiGroups;
		
		$grouprows=array();
		$i=0;
		foreach($jieqiGroups as $k=>$v){
			if($k != JIEQI_GROUP_GUEST){
				$grouprows[$i]['groupid']=$k;
		        $grouprows[$i]['groupname']=$v;
			    $i++;
			}
		}
		$jieqiTpl->assign_by_ref('grouprows', $grouprows);
	}
	
}

?>