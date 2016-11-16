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

jieqi_loadlang('membergroup',JIEQI_MODULE_NAME);
//权限检查
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
jieqi_checkpower($jieqiPower[JIEQI_MODULE_NAME]['group'], $jieqiUsersStatus, $jieqiUsersGroup, false, true);

//数据操作句柄
require_once(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/class/membergroup.php');
$membergroup_handler = JieqimembergroupHandler::getInstance('JieqimembergroupHandler');

if($_REQUEST['action'] == 'powerset') {
	//查询当前管理用户组权限
	$membergid = intval($_REQUEST['membergid']);
	$criteria = new Criteria('membergid',$membergid );
	$membergroup_handler->queryObjects($criteria);
	$powerrow = $membergroup_handler->getRow(); 
	$jieqiTpl->assign('membergtitle',$powerrow['membergtitle'] );
	foreach($powerrow as $key=>$value){
		$tmp = $key.'_checked_'.$value;
		$$tmp = "checked";
		$jieqiTpl->assign("$tmp",$$tmp);
	}
	unset($powerrow);
	$jieqiTpl->assign('href_submit',"?".$_SERVER["QUERY_STRING"]."&doset=1");
	if ($_REQUEST['doset'] ) {
		$criteria = new Criteria('membergid',$membergid);
		if($membergroup_handler->updatefields($_POST,$criteria) ){
			$membergroup_handler->queryObjects($criteria);
			$powerrow = $membergroup_handler->getRow(); 
			$string = "<?php \n";
			$string .= "//Don't modify me!  ".date("Y-m-d H:i:s")."\n"; 
			foreach($powerrow as $key=>$value ){
				$string .= "global \$$key;\n ";
				$string .= "\$$key='$value'; \n";
			}
			$string .= "?>";
			jieqi_writefile(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/membergroup_'.$membergid.'.php',$string);
			if ($powerrow['membergtype'] == 'system' )	{
				$jumppage = "admingroup.php";
			} else {
				$jumppage = "membergroup.php";
			}
			write_membergroups();
			jieqi_jumppage($jumppage,LANG_DO_SUCCESS,$jieqiLang['g']['chagn_user_group_power_suc']);
		}
	}
	$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/membergpower.html') );
} elseif ($_REQUEST['action'] == 'allset' ) {
	foreach($_REQUEST['membergroups'] as $key=>$id  ){
		if($_REQUEST['delete'][$id] ) {
			$membergroup_handler->delete($id);	
	//		unlink(JIEQI_ROOT_PATH."/configs/".JIEQI_MODULE_NAME."/membergroup_$id.php");
		} else {
			$criteria = new Criteria('membergid',$id);
			$v = $_REQUEST['membergtitle'][$id];
		        $membergroup_handler->updatefields("membergtitle='$v' ",$criteria);
		}	
	}
	
	if($_REQUEST['newmembergtitle'] ){
		$newmembergroup = $membergroup_handler->create();
		$newmembergroup->setVar('membergtitle',$_REQUEST['newmembergtitle'] );
		$newmembergroup->setVar('membergtype','member');
		$membergroup_handler->insert($newmembergroup);
		write_membergroups();
	}
	write_membergroups();
	jieqi_jumppage('./membergroup.php',LANG_DO_SUCCESS,$jieqiLang['g']['man_user_group_suc']);

}  else { 
	//查询当前用户组
	$criteria = new CriteriaCompo(new Criteria('membergtype','member') );
	$criteria->setOrder('asc');
	$criteria->setSort('membergid');
	$membergroup_handler->queryObjects($criteria);
	$membergroups = array();
	$k = 0;
	while($v = $membergroup_handler->getObject() ){
		$membergroups[$k]['membergid'] = $v->getVar('membergid');
		$membergroups[$k]['membergtitle'] = $v->getVar('membergtitle'); 
		$k++;
	}
	
	//默认用户组 不允许删除	
	$criteria = new CriteriaCompo(new Criteria('membergtype','default') );
	$membergroup_handler->queryObjects($criteria);
	$defaultgroup = $membergroup_handler->getRow();
	$jieqiTpl->assign('defaultgid',$defaultgroup['membergid'] );
	$jieqiTpl->assign('defaultgtitle',$defaultgroup['membergtitle'] );
	$jieqiTpl->assign('membergroups',$membergroups);
	$jieqiTpl->setCaching(0);
	$jieqiTpl->assign('jieqi_contents',$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/templates/admin/membergroup.html') );
}

include_once JIEQI_ROOT_PATH.'/admin/footer.php';

function write_membergroups() {
	$membergroup_handler = JieqimembergroupHandler::getInstance('JieqimembergroupHandler');
	$criteria = new  CriteriaCompo();
	$criteria->setSort('membergid');
	$membergroup_handler->queryObjects($criteria);
	$string = "<?php \n";
	$string .= "//Don't modify me!  ".date("Y-m-d H:i:s")."\n"; 
	while($v = $membergroup_handler->getObject() ){
		$id = $v->getVar('membergid');
		$title = $v->getVar('membergtitle');
		$string .= "\$membergroups['$id']='$title';\n ";
	}
	$string .= "?>";
	jieqi_writefile(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/membergroups.php',$string);
}

?>