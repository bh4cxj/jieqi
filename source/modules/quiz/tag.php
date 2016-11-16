<?php
define('JIEQI_MODULE_NAME', 'quiz');
require_once('../../global.php');

jieqi_getconfigs('quiz', 'tag_list','jieqiBlocks');

$linkurl=$jieqiConfigs['quiz']['domainname']==""?$jieqiModules['quiz']['url']:$jieqiConfigs['quiz']['domainname'];

jieqi_getconfigs('quiz', 'configs');
jieqi_getconfigs('quiz', 'blocks');

//将TAG标签的点击次数加1
jieqi_includedb();//包含数据库类
$query=JieqiQueryHandler::getInstance('JieqiQueryHandler');//实例化数据库类
$sql="update ".jieqi_dbprefix('quiz_tag')." set click=click+1 where tagname = '".$_REQUEST['tagname']."'";
$res=$query->execute($sql);//执行SQL语句

include_once(JIEQI_ROOT_PATH.'/header.php');
if(!is_object($jieqiTpl)) $jieqiTpl =& JieqiTpl::getInstance();
$jieqiTpl->assign('linkurl',$linkurl); 
$jieqiTset['jieqi_contents_template'] = '';  //内容位置不赋值，全部用区块
include_once(JIEQI_ROOT_PATH.'/footer.php');
?>