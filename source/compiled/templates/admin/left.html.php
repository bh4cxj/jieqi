<?php
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>'.$this->_tpl_vars['jieqi_pagetitle'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'" />
<meta name="generator" content="jieqi.com" />
<link rel="stylesheet" type="text/css" media="all" href="'.$this->_tpl_vars['jieqi_url'].'/templates/admin/left.css" />
<script language="javascript">
function showsubmenu(id){
  var smenu=document.getElementById(id);
  if (smenu.style.display == \'none\'){
    smenu.style.display = \'\';
  }else{
    smenu.style.display = \'none\';
  }
}
</script>
</head>
<body leftmargin=0 topmargin=0 marginwidth="0" marginheight="0">
<div id="leftmenu">
  
<div class=block>
	<div class="block_t"></div>
	<div class="content_w">
		<div class="blocktitle"><a class="title_link" href="'.$this->_tpl_vars['jieqi_url'].'/" target="_top">网站首页</a>&nbsp;<a class="title_link" href="'.$this->_tpl_vars['jieqi_adminurl'].'" target="mainframe">后台首页</a></div>
	</div>
	<div class="block_b"></div>
</div>
  
  <div  id="mu_system" name="mu_system" class="block">
  <div class="block_t"></div>
  <div class="content_w">
    <div class="blocktitle in" onClick="showsubmenu(\'sm_system\')">系统管理</div>
    <div id="sm_system" name="sm_system" class="blockcontent">
      <ul class="menulist">
      ';
if (empty($this->_tpl_vars['adminmenus']['system'])) $this->_tpl_vars['adminmenus']['system'] = array();
elseif (!is_array($this->_tpl_vars['adminmenus']['system'])) $this->_tpl_vars['adminmenus']['system'] = (array)$this->_tpl_vars['adminmenus']['system'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['adminmenus']['system']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['adminmenus']['system']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['adminmenus']['system']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['adminmenus']['system']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['adminmenus']['system']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
	  ';
if($this->_tpl_vars['adminmenus']['system'][$this->_tpl_vars['j']['key']]['publish'] == 1){
echo '
        <li><a href="'.$this->_tpl_vars['adminmenus']['system'][$this->_tpl_vars['j']['key']]['command'].'" target="mainframe">'.$this->_tpl_vars['adminmenus']['system'][$this->_tpl_vars['j']['key']]['caption'].'</a></li>
	  ';
}
echo '
      ';
}
echo '
      </ul>
    </div>
	</div>
	<div class="block_b"></div>
  </div>
  
  <div  id="mu_tools" name="mu_tools" class="block hd">
    <div class="block_t"></div>
  <div class="content_w">
    <div class="blocktitle in" onClick="showsubmenu(\'sm_tools\')">系统工具</div>
    <div id="sm_tools" name="sm_tools" class="blockcontent">
      <ul class="menulist">
      ';
if (empty($this->_tpl_vars['adminmenus']['tools'])) $this->_tpl_vars['adminmenus']['tools'] = array();
elseif (!is_array($this->_tpl_vars['adminmenus']['tools'])) $this->_tpl_vars['adminmenus']['tools'] = (array)$this->_tpl_vars['adminmenus']['tools'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['adminmenus']['tools']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['adminmenus']['tools']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['adminmenus']['tools']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['adminmenus']['tools']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['adminmenus']['tools']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
	  ';
if($this->_tpl_vars['adminmenus']['tools'][$this->_tpl_vars['j']['key']]['publish'] == 1){
echo '
        <li><a href="'.$this->_tpl_vars['adminmenus']['tools'][$this->_tpl_vars['j']['key']]['command'].'" target="mainframe">'.$this->_tpl_vars['adminmenus']['tools'][$this->_tpl_vars['j']['key']]['caption'].'</a></li>
	  ';
}
echo '
      ';
}
echo '
      </ul>
    </div>
	</div>
	<div class="block_b"></div>
  </div>
  
  <div  id="mu_database" name="mu_database" class="block hd">
   <div class="block_t"></div>
  <div class="content_w">
    <div class="blocktitle in" onClick="showsubmenu(\'sm_database\')">数据维护</div>
    <div id="sm_database" name="sm_database" class="blockcontent">
      <ul class="menulist">
      ';
if (empty($this->_tpl_vars['adminmenus']['database'])) $this->_tpl_vars['adminmenus']['database'] = array();
elseif (!is_array($this->_tpl_vars['adminmenus']['database'])) $this->_tpl_vars['adminmenus']['database'] = (array)$this->_tpl_vars['adminmenus']['database'];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['adminmenus']['database']);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['adminmenus']['database']) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['adminmenus']['database']) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['adminmenus']['database']);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['adminmenus']['database']);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
	  ';
if($this->_tpl_vars['adminmenus']['database'][$this->_tpl_vars['j']['key']]['publish'] == 1){
echo '
        <li><a href="'.$this->_tpl_vars['adminmenus']['database'][$this->_tpl_vars['j']['key']]['command'].'" target="mainframe">'.$this->_tpl_vars['adminmenus']['database'][$this->_tpl_vars['j']['key']]['caption'].'</a></li>
	  ';
}
echo '
      ';
}
echo '
      </ul>
    </div>
	</div>
	<div class="block_b"></div>
  </div>
  
';
if (empty($this->_tpl_vars['jieqimodules'])) $this->_tpl_vars['jieqimodules'] = array();
elseif (!is_array($this->_tpl_vars['jieqimodules'])) $this->_tpl_vars['jieqimodules'] = (array)$this->_tpl_vars['jieqimodules'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['jieqimodules']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['jieqimodules']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['jieqimodules']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['jieqimodules']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['jieqimodules']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <div  id="mod_'.$this->_tpl_vars['i']['key'].'" name="mod_'.$this->_tpl_vars['i']['key'].'" class="block hd">
   <div class="block_t"></div>
  <div class="content_w">
    <div class="blocktitle in" onClick="showsubmenu(\'sm_'.$this->_tpl_vars['i']['key'].'\')">'.$this->_tpl_vars['jieqimodules'][$this->_tpl_vars['i']['key']]['caption'].' '.$this->_tpl_vars['jieqimodules'][$this->_tpl_vars['i']['key']]['version'].'</div>
    <div id="sm_'.$this->_tpl_vars['i']['key'].'" name="sm_'.$this->_tpl_vars['i']['key'].'" class="blockcontent">
      <ul class="menulist">
      ';
if (empty($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']])) $this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']] = array();
elseif (!is_array($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']])) $this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']] = (array)$this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']];
$this->_tpl_vars['j']=array();
$this->_tpl_vars['j']['columns'] = 1;
$this->_tpl_vars['j']['count'] = count($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']]);
$this->_tpl_vars['j']['addrows'] = count($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']]) % $this->_tpl_vars['j']['columns'] == 0 ? 0 : $this->_tpl_vars['j']['columns'] - count($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']]) % $this->_tpl_vars['j']['columns'];
$this->_tpl_vars['j']['loops'] = $this->_tpl_vars['j']['count'] + $this->_tpl_vars['j']['addrows'];
reset($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']]);
for($this->_tpl_vars['j']['index'] = 0; $this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['loops']; $this->_tpl_vars['j']['index']++){
	$this->_tpl_vars['j']['order'] = $this->_tpl_vars['j']['index'] + 1;
	$this->_tpl_vars['j']['row'] = ceil($this->_tpl_vars['j']['order'] / $this->_tpl_vars['j']['columns']);
	$this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['order'] % $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['column'] == 0) $this->_tpl_vars['j']['column'] = $this->_tpl_vars['j']['columns'];
	if($this->_tpl_vars['j']['index'] < $this->_tpl_vars['j']['count']){
		list($this->_tpl_vars['j']['key'], $this->_tpl_vars['j']['value']) = each($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']]);
		$this->_tpl_vars['j']['append'] = 0;
	}else{
		$this->_tpl_vars['j']['key'] = '';
		$this->_tpl_vars['j']['value'] = '';
		$this->_tpl_vars['j']['append'] = 1;
	}
	echo '
	  ';
if($this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']][$this->_tpl_vars['j']['key']]['publish'] == 1){
echo '
        <li><a href="'.$this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']][$this->_tpl_vars['j']['key']]['command'].'" target="mainframe">'.$this->_tpl_vars['adminmenus'][$this->_tpl_vars['i']['key']][$this->_tpl_vars['j']['key']]['caption'].'</a></li>
	  ';
}
echo '
      ';
}
echo '
      </ul>
    </div>
	</div>
	<div class="block_b"></div>
  </div>
';
}
echo '
  
</div>
</body>
</html>';
?>