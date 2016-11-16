<?php 
/**
 * 数据库优化
 *
 * 优化和修复数据表
 * 
 * 调用模板：/templates/admin/dboptimize.html
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: dboptimize.php 326 2009-02-04 00:26:22Z juny $
 */

define('JIEQI_MODULE_NAME', 'system');
require_once('../global.php');
jieqi_checklogin();
jieqi_loadlang('database', JIEQI_MODULE_NAME);
if($jieqiUsersStatus != JIEQI_GROUP_ADMIN) jieqi_printfail(LANG_NEED_ADMIN);
@set_time_limit(3600);
@session_write_close();
jieqi_includedb();
$db_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
if($_POST['action']=='optimize' || $_POST['action']=='repair'){
	if(empty($_POST['checkid'])) jieqi_printfail($jieqiLang['system']['need_select_table']);
	$sql="SHOW TABLE STATUS LIKE '".JIEQI_DB_PREFIX."%'";
	$res=$db_query->execute($sql);
	$alltables=array();
	while($row=$db_query->getRow($res)){
		$alltables[]=$row['Name'];
	}
	$doaction='';
	foreach ($_POST['checkid'] as $v){
		if(in_array($v, $alltables)){
			if($_POST['action']=='optimize'){
				$db_query->execute("OPTIMIZE TABLE ".$v);
				$doaction = $jieqiLang['system']['optimize_table_action'];
				echo "<br>OPTIMIZE TABLE ".$v;
			}else{
				$db_query->execute("REPAIR TABLE ".$v);
				$doaction = $jieqiLang['system']['repair_table_action'];
				echo "<br>REPAIR TABLE ".$v;
			}
		}
	}
	if(!empty($doaction)) jieqi_jumppage(JIEQI_URL.'/admin/dboptimize.php', LANG_DO_SUCCESS, sprintf($jieqiLang['system']['optrep_table_success'], $doaction));
	else jieqi_printfail(sprintf($jieqiLang['system']['optrep_table_success'], $doaction));
}else{
	$sql="SHOW TABLE STATUS LIKE '".JIEQI_DB_PREFIX."%'";
	$res=$db_query->execute($sql);
	$tablerows=array();
	$k=0;
	$totaltable=0; //表数
	$totalsize=0;  //数据长度
	$totalrows=0; //记录数
	$totalindex=0; //索引程度
	$totalfree=0; //碎片长度
	while($row=$db_query->getRow($res)){
		$tablerows[$k]=jieqi_funtoarray('jieqi_htmlstr', $row);
		$tablerows[$k]['checkbox']='<input type="checkbox" id="checkid[]" name="checkid[]" value="'.jieqi_htmlstr($row['Name']).'">';
		$totaltable++;
		$totalrows+=$row['Rows'];
		$totalsize+=$row['Data_length'];
		$totalindex+=$row['Index_length'];
		$totalfree+=$row['Data_free'];
		$k++;
	}
	$jieqiTpl->assign('checkall', '<input type="checkbox" id="checkall" name="checkall" value="checkall" onclick="javascript: for (var i=0;i<this.form.elements.length;i++){ if (this.form.elements[i].type == \'checkbox\' && this.form.elements[i].name != \'checkkall\') this.form.elements[i].checked = form.checkall.checked; }">');
	$jieqiTpl->assign_by_ref('tablerows', $tablerows);
	if($totalsize)
	$jieqiTpl->assign('totaltable', $totaltable);
	$jieqiTpl->assign('totalrows', $totalrows);
	if($totalsize > 1048576) $totalsize = sprintf('%0.1fM', $totalsize / 1048576);
	elseif($totalsize > 1024) $totalsize = sprintf('%0.1fK', $totalsize / 1024);
	$jieqiTpl->assign('totalsize', $totalsize);
	if($totalindex > 1048576) $totalindex = sprintf('%0.1fM', $totalindex / 1048576);
	elseif($totalindex > 1024) $totalindex = sprintf('%0.1fK', $totalindex / 1024);
	$jieqiTpl->assign('totalindex', $totalindex);
	if($totalfree > 1048576) $totalfree = sprintf('%0.1fM', $totalfree / 1048576);
	elseif($totalfree > 1024) $totalfree = sprintf('%0.1fK', $totalfree / 1024);
	$jieqiTpl->assign('totalfree', $totalfree);
	if($_REQUEST['option'] != 'repair') $_REQUEST['option']='optimize';
	$jieqiTpl->assign('option', $_REQUEST['option']);
	$jieqiTpl->setCaching(0);
	$jieqiTset['jieqi_contents_template'] = JIEQI_ROOT_PATH.'/templates/admin/dboptimize.html';
}
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>