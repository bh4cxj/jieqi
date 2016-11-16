<?php
define('JIEQI_MODULE_NAME', 'quiz');
$jieqiSort='';
if(!defined('JIEQI_GLOBAL_INCLUDE')) include_once('../../global.php');

jieqi_loadlang('ask', JIEQI_MODULE_NAME);

jieqi_getconfigs('quiz', 'sort', 'jieqiSort');

header('Content-Type:   text/html;   charset=gbk');

$key=(int)$_REQUEST['key'];

if($key==0)exit;
echo '<select class="select" name="select" >';
foreach($jieqiSort['quiz'][$key]['types'] as $type)
{
	echo '<option value="'.$type.'">'.$type.'</option>';
}
echo '</select>';
?>