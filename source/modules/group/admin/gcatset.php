<?php
$tmpvar = empty($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME'];
preg_match('/\/modules\/([^\/]+)\//is', str_replace(array('\\\\','\\'),'/',$tmpvar), $matches);
if(!empty($matches[1]) ) {
	define('JIEQI_MODULE_NAME',$matches[1] );
	}
else{
	exit('error modules name!');
}

require_once('../../../global.php');
include_once JIEQI_ROOT_PATH.'/admin/header.php';

jieqi_loadlang('gcatset',JIEQI_MODULE_NAME);
//权限检查
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower[JIEQI_MODULE_NAME]['group'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

//必用数据句柄初始化
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/gcat.php');
$gcat_handler = JieqigcatHandler::getInstance('JieqigcatHandler');

//已有分类
$criteria = new  CriteriaCompo();
$criteria->setSort('gcatorder');
$criteria->setOrder('asc');
$gcat_handler->queryObjects($criteria);
$k = 0;
while($v = $gcat_handler->getObject() ){
	$gcats[$k]['gcatid'] = $v->getVar('gcatid');
	$gcats[$k]['gcatname'] = $v->getVar('gcatname');
	$gcats[$k]['gcatorder'] = $v->getVar('gcatorder');
	$k++;
}

if($_REQUEST['action']){
	//编辑，增加圈子分类
	switch($_REQUEST['action']){
		case  'newgcat':
				$gcat = $gcat_handler->create();
				$gcat->setVar('gcatname',$_POST['gcatname']);
				$gcat->setVar('gcatorder',intval($_POST['gcatorder']) );
				if($gcat_handler->insert($gcat) ){
			   		write_gcats();
					jieqi_jumppage('./gcatset.php',LANG_DO_SUCCESS,$jieqiLang['g']['add_group_class_suc']);
				}else{
					jieqi_printfail($jieqiLang['g']['add_group_class_fail']);
				}
			break;
		case  'editgcat':
				$gcat = $gcat_handler->create($isNew=false);
				$gcat->setVar('gcatid',intval($_REQUEST['gcatid']) );	
				$gcat->setVar('gcatname',$_REQUEST['gcatname']);
				$gcat->setVar('gcatorder',intval($_REQUEST['gcatorder']) );
				if($gcat_handler->insert($gcat) ){
			   		write_gcats();
					jieqi_jumppage('./gcatset.php',LANG_DO_SUCCESS,$jieqiLang['g']['edit_suc']);
				}else{
					jieqi_printfail($jieqiLang['g']['edit_fail']);
				}
			break;
		case  'delgcat':
			   if($gcat_handler->delete(intval($_REQUEST['gcatid'])) ){
			   		write_gcats();
					jieqi_jumppage('./gcatset.php',LANG_DO_SUCCESS,$jieqiLang['g']['del_suc']);
				}else{
					jieqi_printfail($jieqiLang['g']['del_fail']);
				}
		default:
			break;
	}
 }

function write_gcats(){
	global	$gcat_handler; 
	//已有分类
	$criteria = new  CriteriaCompo();
	$criteria->setSort('gcatorder');
	$criteria->setOrder('asc');
	$gcat_handler->queryObjects($criteria);
	$k = 0;
	while($v = $gcat_handler->getObject() ){
		$gcats[$k]['gcatid'] = $v->getVar('gcatid');
		$gcats[$k]['gcatname'] = $v->getVar('gcatname');
		$gcats[$k]['gcatorder'] = $v->getVar('gcatorder');
		$k++;
	}
	
	function my_cmp($a,$b){
		return $a['gcatorder']-$b['gcatorder'];
	}
	uasort($gcats,'my_cmp');
		
	foreach($gcats as $key=>$value){
		$gcats_write{$value['gcatid']} = $value['gcatname']; 
	}

	$tmp = jieqi_extractvars('gcats',$gcats_write);
	$tmp = "<?php \n".$tmp."?>";
	jieqi_writefile(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/gcats.php',$tmp);
}



$jieqiTpl->assign_by_ref('gcats',$gcats);
$jieqiTpl->setCaching(0);
$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/gcatset.html') );
include_once JIEQI_ROOT_PATH.'/admin/footer.php';
?>