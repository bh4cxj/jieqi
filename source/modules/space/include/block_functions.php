<?php
function block_binfo(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	$jieqiTpl->assign('bname',$blog->getVar('bname') );
	$jieqiTpl->assign('btime',date('Y-m-d',$blog->getVar('btime') ) );
	if($blog->getVar('gaudit') == 1)
	    $baudit = '需审核';
	else $baudit = '公开';
	$jieqiTpl->assign('gaudit',$baudit);
        $jieqiTpl->assign('bpic',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/b/'.$bid.'/pic/face.jpg');
	$jieqiTpl->assign('name',$blog->getVar('name') );
	$jieqiTpl->assign('bbrief',$blog->getVar('bbrief','n') );
	$jieqiTpl->assign('join_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/join.php?b='.$bid);
	if( $_SESSION['jieqiUserId'] ){
		$login_logout = "<a href=".JIEQI_URL."/logout.php>退出</a>";
	} else {
		$login_logout = "<a href=".JIEQI_URL."/login.php>登录</a>";
	}
	$jieqiTpl->assign('login_logout',$login_logout);
	$jieqiTpl->assign('man_href',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/man.php?b='.$bid);
	return $jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/block_binfo.html');
}

function block_stat(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/block_stat.html');
}

function block_managers(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/block_managers.html');
}

function block_man(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	$jieqiTpl->assign('basic_url','./man.php?set=basic&b='.$bid);
	$jieqiTpl->assign('log_url','./man.php?set=log&b='.$bid);
	$jieqiTpl->assign('tpl_url','./man.php?set=tpl&b='.$bid);
	$jieqiTpl->assign('managers_url','./man.php?set=managers&b='.$bid);
	$jieqiTpl->assign('disband_url','disband.php?b='.$bid);
	$jieqiTpl->assign('g',$bid);
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/block_man.html');
}

function set_basic(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	$jieqiTpl->assign('bname',$blog->getVar('bname') );
	
	// province.js href
	$jieqiTpl->assign("provincejs_href",JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/include/province.js');

	//blog cats
	include_once(JIEQI_ROOT_PATH.'/configs/'.JIEQI_MODULE_NAME.'/bcats.php');
	$bcatsoption = '';
	foreach($bcats as $key=>$value){
		if($key == $blog->getVar('bcatid') ){
			$selected = 'selected';
		}
		$bcatsoption .= "<option value=$key $selected>$value</option>";
		unset($selected);
	}

	$jieqiTpl->assign('bcatsoption',$bcatsoption);
	$jieqiTpl->assign('province',$blog->getVar('bprovince') );
	$jieqiTpl->assign('city',$blog->getVar('bcity') );
	$jieqiTpl->assign('province_code',$blog->getVar('bprovince') );
	$jieqiTpl->assign('city_code',$blog->getVar('bcity') );
	if($blog->getVar('gaudit')==1 ){
		$jieqiTpl->assign('checked1','checked');
	}else{
		$jieqiTpl->assign('checked0','checked');
	}

	$jieqiTpl->assign('bbrief',$blog->getVar('bbrief','n') );	
	$jieqiTpl->assign('bid',$bid );
	$jieqiTpl->assign('setbasic_href','./setbasic.php?b='.$bid);

	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/set_basic.html');
}

function set_log(){
	global $bid;
	global $jieqiTpl;
	global $blog;
        $jieqiTpl->assign('setlog_href','./setlog.php?b='.$bid);	
	$jieqiTpl->assign('js_path',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/include/jieqidialog.js');
        $jieqiTpl->assign('imgsrc',JIEQI_URL.'/modules/'.JIEQI_MODULE_NAME.'/b/'.$bid.'/pic/face.jpg');
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/set_log.html');
}

function set_tpl(){
	global $bid;
	global $jieqiTpl;
	global $blog;
	global $bset;
        $jieqiTpl->assign('settpl_href','./settpl.php?b='.$bid);	
	$d = dir(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/');
	$modules = array();
	$k = 0;
	while($module = $d->read() ){
		if($module != '.' && $module != '..'){
			$modules[$k]['dir'] = $module;
			if(BTHEME == $module ){
				$modules[$k]['checked'] = "checked";
			}
		}
		$k++;
	}
	$jieqiTpl->assign('modules',$modules);
	return	$jieqiTpl->fetch(JIEQI_ROOT_PATH.'/modules/'.JIEQI_MODULE_NAME.'/themes/'.BTHEME.'/set_tpl.html');
}

?>
