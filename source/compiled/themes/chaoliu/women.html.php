<?php
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$this->_tpl_vars['jieqi_charset'].'">
<TITLE>'.$this->_tpl_vars['jieqi_pagetitle'].'</TITLE>
<meta name="keywords" content="'.$this->_tpl_vars['meta_keywords'].'" />
<meta name="Description"  content="'.$this->_tpl_vars['meta_description'].'" />
<link href="/themes/chaoliu/css/girl_template.css" rel="stylesheet" />
<script type="text/javascript" src="/themes/chaoliu/js/jquery-1.9.1.min.js"></script>
<script src="/themes/chaoliu/js/jquery.nav.js" type="text/javascript"></script>
<script type="text/javascript" src="/themes/chaoliu/js/slides.js" ></script>
<script type="text/javascript" src="/themes/chaoliu/js/bankuai.js" ></script>
<!--[if IE 6]><script type="text/javascript" src="/themes/chaoliu/js/DD_belatedPNG.js">
</script>
<script>
 DD_belatedPNG.fix(".pngFix,.pngFix:hover,.pngFix img");
</script>
<![endif]-->
</head>
<body>
';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/header.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<div id="main">
    <div class="main_inner">
        <div class="main01">
            <div class="banner_left"> 
  <!--»ÃµÆÆ¬¿ªÊ¼-->
                  <div id="focus">
        <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,5,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'index_men_huandeng.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'
        </ul>
      </div>
                  <div class="nanpinqianli" id="box">
                    <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,9,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'index_list.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'</ul>
                    <div class="clear"></div>
                
                </div>
  <!--»ÃµÆÆ¬½áÊø--> 
            </div>
           <div class="nanpinqiangtui" id="box" style="float:left; margin-top:0">
                    <div class="renqi_more" style="height:51px;"></div>
                    <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,10,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'index_men_side_huandeng.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'
                    </ul>
                </div>
            <div class="clear"></div>
        </div>
        <a href=""><img src="/themes/chaoliu/images/nvp_diqinxin.jpg" id="box" style="margin-top:10px;"/></a>
        <div class="maincon">
            <div class="maincon_l">
                <div class="rmzptj" id="box">
                    <div class="rmzptj_title">
                        <ul>
                            <li id="rmzptj_fenlei_first" class="rmzptj_fenlei">
                                <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,6,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'men_hot_image.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'
                                     <div class="clear"></div>
                                </ul>
                            </li>
                            <div class="clear"></div>
                        </ul>
                    
                    </div>
                </div>
                <div class="update" id="box">
                    <p class="update_more"></p>
                    <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,30,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'fenlei_lastupdate.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'</ul>
                </div>
            </div>
            <div class="maincon_r" >
                <div class="npdianjibang" id="box">
                    <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,10,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'index_side.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'</ul>
                </div>
                <div class="npshoucangbang" id="box">
                    <ul>'.jieqi_get_block(array('bid'=>'89', 'module'=>'article', 'filename'=>'block_articlelist', 'classname'=>'BlockArticleArticlelist', 'side'=>'0', 'title'=>'', 'vars'=>'allvisit,10,9|10|11|12|13|14|15|16,0,0,0', 'template'=>'index_side.html', 'contenttype'=>'4', 'custom'=>'0', 'publish'=>'3', 'hasvars'=>'3'), 1).'</ul>
                </div>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

';
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/footer.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);

?>