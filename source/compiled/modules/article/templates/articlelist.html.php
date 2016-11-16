<?php
$GLOBALS['jieqiTset']['jieqi_blocks_module'] = 'article';
echo '
';
if($this->_tpl_vars['sort'] != "短篇"){
echo '
<style>
#left{width:950px;}
</style>

<div style=" margin:0px auto; width:960px; height:auto;" class="linshi" id="box">
<div style=" margin:0px auto; width:960px; height:auto;"><table width="960" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><td height="131" colspan="2" align="left" valign="top" background="/themes/chaoliu/images/welcome_07.jpg"><table width="306" height="122" border="0" cellpadding="0" cellspacing="0">
          <!--DWLayoutTable-->
          <tr>
            <td width="98" rowspan="2" align="right" valign="bottom"><a href="/modules/article/articlelist.php?class=9" target="_blank"><img src="/themes/chaoliu/images/Love.png" width="76" height="96" border="0"></a></td>
            <td width="5" rowspan="2">&nbsp;</td>
            <td width="201" height="53" valign="bottom"><span class="STYLE33"><a href="/modules/article/articlelist.php?class=9" target="_blank">都市言情小说</a></span></td>
          </tr>
          <tr>
            <td height="69" valign="top"><p class="topLeft"><a href="/modules/article/articlelist.php?class=9" target="_blank" class="topLeft">汇集总裁、穿越、宫斗、耽美、豪<br>
            门、小资、白领、职场、婚恋、伦<br>
            理、青春、校园等女性原创小说。</a></td>
          </tr>
        </table></td>
        <td height="131" colspan="2" align="left" valign="top" background="/themes/chaoliu/images/welcome_08.jpg"><table width="306" height="118" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="98" rowspan="2" align="right" valign="bottom"><a href="/modules/article/articlelist.php?class=1" target="_blank"><img src="/themes/chaoliu/images/kami.jpg" width="76" height="96" border="0"></a></td>
            <td width="9" height="49">&nbsp;</td>
            <td width="199" valign="bottom"><span class="STYLE33"><a href="/modules/article/articlelist.php?class=1" target="_blank">玄幻探险小说</a></span></td>
          </tr>
          <tr>
            <td height="69">&nbsp;</td>
            <td valign="top"><p class="topLeft"><a href="/modules/article/articlelist.php?class=1" target="_blank" class="topLeft">              汇集仙侠、修真、异能、灵界、魔<br>
              法、未来、幻想、网游、推理、悬<br>
            疑、盗墓、探险等原创传奇小说。</a></p>
            </td>
          </tr>
        </table></td>
<td height="131" colspan="4" align="left" valign="top" background="/themes/chaoliu/images/welcome_09.jpg"><table width="298" height="121" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="98" rowspan="2" align="right" valign="bottom"><a href="/modules/article/articlelist.php?class=17" target="_blank"><img src="/themes/chaoliu/images/war.jpg" width="76" height="96" border="0" align="bottom"></a></td>
    <td width="10" height="49">&nbsp;</td>
    <td width="190" valign="bottom"><span class="STYLE33"><a href="/modules/article/articlelist.php?class=17" target="_blank">军事历史小说</a></span></td>
  </tr>
  <tr>
    <td height="72">&nbsp;</td>
    <td valign="top"><p class="topLeft"> <a href="/modules/article/articlelist.php?class=17" target="_blank" class="topLeft">汇集谍战、抗日、黑道、武侠、帝王、将相传奇的原创小说以及历史人文的全新自由解读文本。</a></td>
  </tr>
</table></td></td>
  </tr>
</table>



</div>
<div id="lm93">
  <table width="666" cellpadding="0" cellspacing="0">
  <td width="950" valign="top">
    <div class="newtxt"><a href="" target="_blank"><img src="/themes/chaoliu/images/wxjh.jpg" alt="" border="0" /></a></div></td>
  </tr>
  </table>
</div>

<div class="h3" style="height:35px; line-height:35px;font-size: 14px;text-align: left;padding-left: 10px;">
<span class="reda">当前位置</span> : <a href="/">首页</a> &gt; <a href="/modules/article">书库首页</a> &gt; ';
if($this->_tpl_vars['sort']!=''){
echo $this->_tpl_vars['sort'];
}else{
echo '全部文章';
}
echo '</div>


<table width="950" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td width="10">&nbsp;</td>
                <td width="940"><div id="main950">
<div id="left" class="left">
<style type="text/css">
#lm31{border:#F1E3C8 0px solid}
#lm31 b,#lm31 em,#lm31 em2,#lm31 strong,#lm31 strong2,#lm31 dfn,#lm31 zq,#lm31 lb,#lm31 font,#lm31 li span{display:inline-block;text-align:left;font-style:normal;font-weight:normal;}
#lm31 ul{padding:7px;border:#F1E3C8 0px solid;}
#lm31 li{height:34px;line-height:34px;overflow:hidden;background:url("http://www.chaoliuyc.com/template/newchaoliu/images/line_x.gif") left 12px repeat-x;text-align:left;}
#lm31 dt{border-bottom:1px #ccc solid;padding-bottom:5px;padding-right:10px;}
#lm31 dt b{width:280px;text-align:left;font-size:14px;}
#lm31 li b{width:272px;text-align:left;padding-left:10px; letter-spacing:1px;font-size:14px;color: #0066FF;}/*标题*/
#lm31 dt strong{width:100px;font-size:14px;}/*作者*/
#lm31 dfn{width:50px;font-size:15px;}
#lm31 zq{width:70px;font-size:14px;}
#lm31 lb{width:70px;font-size:14px;}
#lm31 em{width:60px;font-size:14px;}/*点击量*/
#lm31 em2{width:310px;font-size:14px;}/*点*/
#lm31 font{width:60px;font-size:14px;}/*创建时间*/
#lm31 li span{width:100px;font-size:14px;}/*作者*/
</style>
<div id="lm31"><dl>
<dt><font>序号</font><b>&nbsp;&nbsp;文章名称</b><strong>作者</strong><b>最近更新</b><em>字数</em><dfn>状态</dfn><font>发表时间</font></dt><dd><ul>
';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
<li>
<dfn>'.$this->_tpl_vars['i']['order'].'.</dfn><b><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a>
</b><span>'.truncate($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['author'],'12').'</span><b><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleindex'].'">'.truncate($this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastchapter'],'30').'</a></b><em>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['size_k'].'K</em><dfn>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['fullflag'].'</dfn><font>'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate'].'</font></li>
';
}
echo '
</ul></dd>
<div class="pages" style="margin-right:20px;">'.$this->_tpl_vars['url_jumppage'].'</div>
</dl></div></td>
                <td width="10">&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
</table>
';
}else{
echo '
<link href="/themes/chaoliu/css/chaoliuzhi.css" rel="stylesheet" />
<style>
.maincon_r li{text-align:left;}
</style>
<div id="main">
    <div class="main_inner">
  
        <div class="maincon">
            <div class="maincon_l" id="box">
                <div class="clz_head">
                    <ul>
                        <li class="wzmc">文章名称</li>
                        <li class="zz">作品分类</li>
                        <li class="djs">大小</li>
                        <li class="pls">完成状态</li>
                        <li class="fbsj">发表时间</li>
                        <div class="clear"></div>
                    </ul>
                </div>
                <ul class="clzliebiao">
';
if (empty($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = array();
elseif (!is_array($this->_tpl_vars['articlerows'])) $this->_tpl_vars['articlerows'] = (array)$this->_tpl_vars['articlerows'];
$this->_tpl_vars['i']=array();
$this->_tpl_vars['i']['columns'] = 1;
$this->_tpl_vars['i']['count'] = count($this->_tpl_vars['articlerows']);
$this->_tpl_vars['i']['addrows'] = count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'] == 0 ? 0 : $this->_tpl_vars['i']['columns'] - count($this->_tpl_vars['articlerows']) % $this->_tpl_vars['i']['columns'];
$this->_tpl_vars['i']['loops'] = $this->_tpl_vars['i']['count'] + $this->_tpl_vars['i']['addrows'];
reset($this->_tpl_vars['articlerows']);
for($this->_tpl_vars['i']['index'] = 0; $this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['loops']; $this->_tpl_vars['i']['index']++){
	$this->_tpl_vars['i']['order'] = $this->_tpl_vars['i']['index'] + 1;
	$this->_tpl_vars['i']['row'] = ceil($this->_tpl_vars['i']['order'] / $this->_tpl_vars['i']['columns']);
	$this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['order'] % $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['column'] == 0) $this->_tpl_vars['i']['column'] = $this->_tpl_vars['i']['columns'];
	if($this->_tpl_vars['i']['index'] < $this->_tpl_vars['i']['count']){
		list($this->_tpl_vars['i']['key'], $this->_tpl_vars['i']['value']) = each($this->_tpl_vars['articlerows']);
		$this->_tpl_vars['i']['append'] = 0;
	}else{
		$this->_tpl_vars['i']['key'] = '';
		$this->_tpl_vars['i']['value'] = '';
		$this->_tpl_vars['i']['append'] = 1;
	}
	echo '
  <li class="clzliebiao_floor"><ul> <li class="wzmc"><a href="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['url_articleinfo'].'" title="'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'" target="_blank">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['articlename'].'</a></li> <li class="zz">'.$this->_tpl_vars['sort'].'</li> <li class="djs">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['size_k'].'K</li> <li class="pls">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['fullflag'].'</li> <li class="fbsj">'.$this->_tpl_vars['articlerows'][$this->_tpl_vars['i']['key']]['lastupdate'].'</li> <div class="clear"></div> </ul></li>
  ';
}
echo '

<div class="pages">'.$this->_tpl_vars['url_jumppage'].'</div>
                
                </ul>
                <div class="s_pagenav">
                        <div class="p_bar">
                                                    </div>
                </div>
               
            </div>
            <div class="maincon_r"   >
                <div class="lanmudaodu" id="box">
                    <ul>
'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,10,23,0,0,0', 'template'=>'index_side.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'
                    </ul>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

';
}

?>