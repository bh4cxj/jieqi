<?php 
/**
 * 购买虚拟币界面
 *
 * 购买虚拟币界面
 * 
 * 调用模板：/modules/pay/templates/buyegold.html
 * 
 * @category   jieqicms
 * @package    pay
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: buyegold.php 234 2008-11-28 01:53:06Z juny $
 */

define('JIEQI_MODULE_NAME', 'pay');
require_once('../../global.php');
//jieqi_getconfigs('pay', 'payblocks', 'jieqiBlocks');
include_once(JIEQI_ROOT_PATH.'/header.php');
$jieqiTpl->assign('egoldname', JIEQI_EGOLD_NAME);
if (JIEQI_USE_CACHE) $jieqiTpl->setCaching(0);
if(!empty($_REQUEST['t'])) $tmpfile=$_REQUEST['t'];
else $tmpfile='buyegold';
$tmpfile=$jieqiModules['pay']['path'].'/templates/'.$tmpfile.'.html';
$jieqiTpl->setCaching(0);
if(is_file($tmpfile)) $jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch($tmpfile));
else $jieqiTpl->assign('jieqi_contents','');
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>