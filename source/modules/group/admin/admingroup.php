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


jieqi_loadlang('admingroup',JIEQI_MODULE_NAME);

//权限检查
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower[JIEQI_MODULE_NAME]['group'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

//数据操作句柄
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/admingroup.php');
$admingroup_handler = JieqiadmingroupHandler::getInstance('JieqiadmingroupHandler');

if($_REQUEST['action'] == 'powerset') {
	//查询当前管理用户组权限
	$admingid = $_REQUEST['admingid'];
	$criteria = new Criteria('admingid',$_REQUEST['admingid'] );
	$admingroup_handler->queryObjects($criteria);
	$powerrow = $admingroup_handler->getRow(); 
	foreach($powerrow as $key=>$value){
		$tmp = $key.'_checked_'.$value;
		$$tmp = "checked";
		$jieqiTpl->assign($tmp,$$tmp);
	}
	unset($powerrow);
	$jieqiTpl->assign('membergtitle',urldecode($_REQUEST['membergtitle'] ) );
	$jieqiTpl->assign('href_submit',"?".$_SERVER["QUERY_STRING"]."&doset=1");
	//更新该组权限
	if ($_REQUEST['doset'] ) {
		$criteria = new Criteria('admingid',$_REQUEST['admingid'] );
		if($admingroup_handler->updatefields($_POST,$criteria) ){
			$criteria = new Criteria('admingid',$admingid);
			$admingroup_handler->queryObjects($criteria);
			$powerrow = $admingroup_handler->getRow(); 
			$string = "<?php \n";
			$string .= "//Don't modify me!  ".date("Y-m-d H:i:s")."\n"; 
			foreach($powerrow as $key=>$value ){
				$string .= "global \$$key; \n ";
				$string .= "\$$key=$value; \n";
			}
			$string .= "?>";
			jieqi_writefile(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/admingroup_'.$admingid.'.php',$string);
			jieqi_jumppage('./admingroup.php',LANG_DO_SUCCESS,$jieqiLang['g']['chagn_user_group_power_suc']);
		}
	}
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/admingpower.html') );
} else {
	//查询当前管理用户组
	jieqi_includedb();
	$admingroup_query=JieqiQueryHandler::getInstance('JieqiQueryHandler');
	$criteria = new CriteriaCompo();
	$criteria->setTables(jieqi_dbprefix('group_admingroup').' a left join '.jieqi_dbprefix('group_membergroup').' m on a.admingid=m.admingid' );
	$criteria->setFields("a.admingid,m.membergid,membergtitle");
	$admingroup_query->queryObjects($criteria);

	$admingroups = array();
	$k = 0;
	while($v = $admingroup_query->getObject() ){
		$admingroups[$k]['admingid'] = $v->getVar('admingid');
		$admingroups[$k]['membergid'] = $v->getVar('membergid');
		$admingroups[$k]['membergtitle'] = $v->getVar('membergtitle'); 
		$admingroups[$k]['encodemgtitle'] = urlencode($v->getVar('membergtitle') );
		$k++;
	}
	$jieqiTpl->assign('admingroups',$admingroups);
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/admingroup.html') );
}

include_once JIEQI_ROOT_PATH.'/admin/footer.php';
?>