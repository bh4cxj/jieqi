<?php
/**
 * 允许布景的表格窗口
 *
 * 允许布景的表格窗口
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: themeform.php 301 2008-12-26 04:36:17Z juny $
 */


include_once(JIEQI_ROOT_PATH.'/lib/html/form/form.php');

class JieqiThemeForm extends JieqiForm
{
	//插入空行
	function insertBreak($extra = NULL)
	{
		if (!isset($extra)) {
			$extra = '<tr><td colspan="2"></td></tr>';
			$this->addElement($extra);
		} else {
			$extra = '<tr><td colspan="2">'.$extra.'</td></tr>';
			$this->addElement($extra);
		}
	}

	//返回输出代码
	function render($fwidth="100%")
	{
		$required = $this->getRequired();
		$ret = "\n<form name=\"".$this->getName()."\" id=\"".$this->getName()."\" action=\"".$this->getAction()."\" method=\"".$this->getMethod()."\" onsubmit=\"return jieqiFormValidate_".$this->getName()."();\"".$this->getExtra().">\n<table width=\"".$fwidth."\" class=\"grid\" cellspacing=\"1\" align=\"center\">\n<caption>".$this->getTitle()."</caption>\n";
		foreach ( $this->getElements() as $ele ) {
			if (!is_object($ele)) {
				$ret .= $ele;
			} elseif (!$ele->isHidden()) {
				$caption=$ele->getCaption();
				if(empty($caption)){
					$ret .= "<tr>\n  <td colspan=\"2\" class=\"head\">".$ele->render()."</td>\n</tr>\n";
				}else{
					$ret .= "<tr valign=\"middle\" align=\"left\">\n  <td class=\"odd\" width=\"25%\">".$caption;
					if ($ele->getIntro() != '') {
						$ret .= " <br /><span class=\"hottext\">".$ele->getIntro()."</span>";
					}
					$ret .= "</td>\n  <td class=\"even\">".$ele->render();
					if ($ele->getDescription() != '') {
						$ret .= " <span class=\"hottext\">".$ele->getDescription()."</span>";
					}
					$ret.="</td>\n</tr>\n";
				}
			} else {
				$ret .= $ele->render();
			}
		}
		$js = "
<script language=\"javascript\" type=\"text/javascript\">
<!--
function jieqiFormValidate_".$this->getName()."(){
";
		$required = $this->getRequired();
		$reqcount = count($required);
		if(!defined('LANG_PLEASE_ENTER')) $lang_enter = '请输入%s';
		else $lang_enter = LANG_PLEASE_ENTER;
		for ($i = 0; $i < $reqcount; $i++) {
			$js .= "  if(document.".$this->getName().".".$required[$i]->getName().".value == \"\"){
    alert(\"".sprintf($lang_enter, preg_replace(array('/\<span[^\<\>]*\>[^\<\>]*\<\/span\>/is', '/\<div[^\<\>]*\>[^\<\>]*\<\/div\>/is', '/\<font[^\<\>]*\>[^\<\>]*\<\/font\>/is'), '', str_replace(array('\\','"'),array('\\\\','\\"'),$required[$i]->getCaption())))."\");
    document.".$this->getName().".".$required[$i]->getName().".focus();
    return false;
  }
";
		}
		$js .= "}
//-->
</script>\n";
		$ret .= "</table>\n</form>\n";
		$ret .= $js;
		return $ret;
	}
}
?>