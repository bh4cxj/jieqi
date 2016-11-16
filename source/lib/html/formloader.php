<?php
/**
 * 载入表单类
 *
 * 载入表单控件相关的类
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    system
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: formloader.php 312 2008-12-29 05:30:54Z juny $
 */

include_once(JIEQI_ROOT_PATH.'/lib/html/form/formelement.php'); //基本元素
include_once(JIEQI_ROOT_PATH.'/lib/html/form/form.php');  //基本窗体
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formbutton.php');  //按钮
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formcheckbox.php');  //多选框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formelementtray.php');  //多个元素组合
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formfile.php');  //上传文件
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formhidden.php');  //隐藏项
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formhtmleditor.php');  //在线编辑器
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formlabel.php');  //文本
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formpassword.php');  //密码框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formradio.php');  //单选框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formselect.php');  //下拉框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formtext.php');  //文本框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formtextarea.php');  //多行文本
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formdhtmltextarea.php');  //支持html文本框
include_once(JIEQI_ROOT_PATH.'/lib/html/form/formtextdateselect.php');  //日期选择
include_once(JIEQI_ROOT_PATH.'/lib/html/form/tableform.php');  //表格窗体
include_once(JIEQI_ROOT_PATH.'/lib/html/form/themeform.php');  //风格窗体

?>