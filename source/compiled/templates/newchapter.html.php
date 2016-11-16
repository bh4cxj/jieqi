<?php
$_template_tpl_vars = $this->_tpl_vars;
 $this->_template_include(array('template_include_tpl_file' => 'themes/chaoliu/zzheader.html', 'template_include_vars' => array()));
 $this->_tpl_vars = $_template_tpl_vars;
 unset($_template_tpl_vars);
echo '
<style>
.zpgl{border: 2px solid #c99500;border-right:none;
background-color: white;}
.menu .cjzp{border:none;background:url(/themes/chaoliu/images/chuangjian_14.gif) 27px center no-repeat;}
table tbody tr{height:30px;line-height:30px;}
caption{height:40px;line-height:40px;border-bottom:1px #ccc solid;}
a{color:#666;}
a:hover{color:red;}
</style>
    	<h2>Ôö¼ÓÕÂ½Ú</h2>'.$this->_tpl_vars['contents'].'
        
			    </div>
</div>

</body>
</html>
';
?>