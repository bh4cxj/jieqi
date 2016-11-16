<?php
//  ------------------------------------------------------------------------
//  JIEQI CMS
//  Copyright (c) jieqi.com All rights reserved.
//  http://www.jieqi.com/
//  ------------------------------------------------------------------------
define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
switch ($_REQUEST['action'])
{
	case 'del':
		if($_REQUEST['key2']=='')
		{
			unset($jieqiSort[JIEQI_MODULE_NAME][$_REQUEST['key1']]);
		}
		else
		{
			unset($jieqiSort[JIEQI_MODULE_NAME][$_REQUEST['key1']]['types'][$_REQUEST['key2']]);
		}
		jieqi_setconfigs('sort','jieqiSort',$jieqiSort,JIEQI_MODULE_NAME);
		Header("Location: ?");
		break;
	case 'add';
	if($_REQUEST['key1']=='')
	{
		$k=count($jieqiSort[JIEQI_MODULE_NAME]);
		$k++;
		$jieqiSort[JIEQI_MODULE_NAME][$k]['layer'] = '0';
		$jieqiSort[JIEQI_MODULE_NAME][$k]['caption'] = $_REQUEST['name'];
		$jieqiSort[JIEQI_MODULE_NAME][$k]['shortname'] = $_REQUEST['name'];
		$jieqiSort[JIEQI_MODULE_NAME][$k]['description'] = '';
		$jieqiSort[JIEQI_MODULE_NAME][$k]['imgurl'] = '';
		$jieqiSort[JIEQI_MODULE_NAME][$k]['publish'] = '1';
	}
	else
	{
		$jieqiSort[JIEQI_MODULE_NAME][$_REQUEST['key1']]['types'][]=$_REQUEST['name'];
	}
	jieqi_setconfigs('sort','jieqiSort',$jieqiSort,JIEQI_MODULE_NAME);
	Header("Location: ?"); 
	break;
}
include_once(JIEQI_ROOT_PATH.'/admin/header.php');
$jieqiTpl->assign('sort',$jieqiSort['quiz']);
$jieqiTset['jieqi_contents_template'] = $jieqiModules['quiz']['path'].'/templates/admin/quiz_type.html';
include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
?>