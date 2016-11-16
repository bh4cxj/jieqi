<?php 
/**
 * 用户登录区块
 *
 * 用户登录区块类(静态－文本)
 * 
 * 调用模板：/modules/space/templates/blocks/block_login.html
 * 
 * @category   jieqicms
 * @package    space
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: tab $
 */
//文章分类

class BlockGroupLogin extends JieqiBlock
{
	var $module = 'space';
	var $template = 'block_login.html';
	
	function setContent($isreturn=false){
		global $jieqiTpl;
		global $jieqiConfigs;
		if(!isset($jieqiConfigs['system']['checkcodelogin'])) jieqi_getconfigs('system', 'configs');
		if(!empty($jieqiConfigs['system']['checkcodelogin'])) $jieqiTpl->assign('show_checkcode', 1);
	    else $jieqiTpl->assign('show_checkcode', 0);
	    if(empty($jieqiConfigs['system']['usegd'])){
	    	$jieqiTpl->assign('usegd', 0);
	    	$jieqiTpl->assign('url_checkcode', JIEQI_URL.'/include/checkcodephp.php');
	    }else{
	    	$jieqiTpl->assign('usegd', 1);
	    	$jieqiTpl->assign('url_checkcode', JIEQI_URL.'/include/checkcodegd.php');
	    }
	    
	    if (JIEQI_USE_CACHE) {
			$jieqiTpl->setCaching(2);
		}else{
			$jieqiTpl->setCaching(0);
		}
	}
}

?>