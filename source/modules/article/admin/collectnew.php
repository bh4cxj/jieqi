<?php 
/**
 * 新建采集规则
 *
 * 新建采集规则
 * 
 * 调用模板：无
 * 
 * @category   jieqicms
 * @package    article
 * @copyright  Copyright (c) Hangzhou Jieqi Network Technology Co.,Ltd. (http://www.jieqi.com)
 * @author     $Author: juny $
 * @version    $Id: collectnew.php 332 2009-02-23 09:15:08Z juny $
 */

define('JIEQI_MODULE_NAME', 'article');
require_once('../../../global.php');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'power');
//发表文章权限
jieqi_checkpower($jieqiPower['article']['adminconfig'], $jieqiUsersStatus, $jieqiUsersGroup, false);
jieqi_loadlang('collect', JIEQI_MODULE_NAME);
jieqi_getconfigs(JIEQI_MODULE_NAME, 'collectsite');
jieqi_getconfigs(JIEQI_MODULE_NAME, 'configs');
$article_static_url = (empty($jieqiConfigs['article']['staticurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['staticurl'];
$article_dynamic_url = (empty($jieqiConfigs['article']['dynamicurl'])) ? $jieqiModules['article']['url'] : $jieqiConfigs['article']['dynamicurl'];

if (!isset($_REQUEST['action'])) $_REQUEST['action'] = 'show';

switch($_REQUEST['action']){
	case 'new':
		include_once($jieqiModules['article']['path'].'/include/collectfunction.php');
		$_POST['config']=trim($_POST['config']);
		$errtext='';
		if(empty($_POST['config'])) $errtext .= $jieqiLang['article']['rule_need_siteid'].'<br />';
		elseif(file_exists(JIEQI_ROOT_PATH.'/configs/article/site_'.$_POST['config'].'.php')) $errtext .= $jieqiLang['article']['rule_siteid_exists'].'<br />';
		if(!empty($errtext)) jieqi_printfail($errtext);

		
		$newCollect=array();
		$newCollect['sitename']=trim($_POST['sitename']);  //站名
		$newCollect['siteurl']=trim($_POST['siteurl']); //网址
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $_POST['subarticleid']))) $newCollect['subarticleid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($_POST['subarticleid'])); //文章子序号运算方式
		else $newCollect['subarticleid']='';
		if(is_numeric(str_replace(array('<{articleid}>', '<{chapterid}>', 'ceil', 'floor', 'round', 'substr', 'intval', 'is_numeric', '+', '-', '*', '/', '%', ',', '?', '=', '>', '<', ':', '(', ')', ' '), '', $_POST['subchapterid']))) $newCollect['subchapterid']=str_replace(array('<{articleid}>', '<{chapterid}>'), array('$articleid', '$chapterid'), trim($_POST['subchapterid'])); //子序号运算方式
		else $newCollect['subchapterid']='';
		//代理服务器地址
		$newCollect['proxy_host']=trim($_POST['proxy_host']);
		//代理服务器端口
		$newCollect['proxy_port']=trim($_POST['proxy_port']);
		//代理服务器帐号
		//$newCollect['proxy_user']=trim($_POST['proxy_user']);
		//代理服务器密码
		//$newCollect['proxy_pass']=trim($_POST['proxy_pass']);
		
		//章节无法对应是是否自动清空重新采集
		$newCollect['autoclear']=trim($_POST['autoclear']);
		//是否默认全本
		$newCollect['defaultfull']=trim($_POST['defaultfull']);
		//启用referer
		$newCollect['referer']=trim($_POST['referer']);
		//网页编码
		$newCollect['pagecharset']=trim($_POST['pagecharset']);
		//文章信息页面
		$newCollect['urlarticle']=trim($_POST['urlarticle']);
		//文章标题
		$newCollect['articletitle']=jieqi_collectptos($_POST['articletitle']);
		//作者
		$newCollect['author']=jieqi_collectptos($_POST['author']);
		//类型
		$newCollect['sort']=jieqi_collectptos($_POST['sort']);
		//关键字
		$newCollect['keyword']=jieqi_collectptos($_POST['keyword']);
		//简介
		$newCollect['intro']=jieqi_collectptos($_POST['intro']);
		//封面
		$newCollect['articleimage']=jieqi_collectptos($_POST['articleimage']);
		//过滤封面
		$newCollect['filterimage']=trim($_POST['filterimage']);
		//目录页链接
		$newCollect['indexlink']=jieqi_collectptos($_POST['indexlink']);
		//全文标记
		$newCollect['fullarticle']=jieqi_collectptos($_POST['fullarticle']);
		
		//文章类型对应id
		$sortary=explode('||', trim($_POST['sortid']));
		$newCollect['sortid']=array();
		foreach($sortary as $v){
			$tmpary=explode('=>', trim($v));
			if(count($tmpary)==2){
				$sname=trim($tmpary[0]);
				$sid=trim($tmpary[1]);
				if(is_numeric($sid)) $newCollect['sortid'][$sname]=$sid;
			}
		}

		$newCollect['urlindex']=trim($_POST['urlindex']); //文章目录页面
		//分卷名称
		$newCollect['volume']=jieqi_collectptos($_POST['volume']);
		//章节名称
		$newCollect['chapter']=jieqi_collectptos($_POST['chapter']);
		//章节序号
		$newCollect['chapterid']=jieqi_collectptos($_POST['chapterid']);

		$newCollect['urlchapter']=trim($_POST['urlchapter']); //章节内容页面
		//章节内容
		$newCollect['content']=jieqi_collectptos($_POST['content']);
		//章节内容1
		//$newCollect['content1']=jieqi_collectptos($_POST['content1']);
		//章节内容过滤
		$newCollect['contentfilter']=trim($_POST['contentfilter']);
		//章节内容替换
		$newCollect['contentreplace']=trim($_POST['contentreplace']);
		//是否采集图片
		$newCollect['collectimage']=trim($_POST['collectimage']);
		
		//是否允许图片处理
		$newCollect['imagetranslate']=trim($_POST['imagetranslate']);
		//是否加水印
		$newCollect['addimagewater']=trim($_POST['addimagewater']);
		//图片背景色
		$newCollect['imagebgcolor']=trim($_POST['imagebgcolor']);
		//按区域清理
		$newCollect['imageareaclean']=trim($_POST['imageareaclean']);
		//按颜色清理
		$newCollect['imagecolorclean']=trim($_POST['imagecolorclean']);

		
		$configstr="<?php\n".jieqi_extractvars('jieqiCollect', $newCollect)."\n?>";
		jieqi_writefile(JIEQI_ROOT_PATH.'/configs/article/site_'.$_POST['config'].'.php', $configstr);
		$siteid = -1;
		$maxid = 0;
		if(!isset($jieqiCollectsite) || !is_array($jieqiCollectsite)) $jieqiCollectsite=array();
		else reset($jieqiCollectsite);
		while(list($k, $v) = each($jieqiCollectsite)) {
			if($k > $maxid) $maxid = $k;
			if($v['config']==$_POST['config']){
				$siteid=$k;
				break;
			}
		}
		$maxid++;
		if($siteid >= 0) $jieqiCollectsite[$siteid]=array('name'=>$newCollect['sitename'], 'config'=>$_POST['config'], 'url'=>$newCollect['siteurl'], 'subarticleid'=>$newCollect['subarticleid'], 'enable'=>'1');
		else $jieqiCollectsite[$maxid]=array('name'=>$newCollect['sitename'], 'config'=>$_POST['config'], 'url'=>$newCollect['siteurl'], 'subarticleid'=>$newCollect['subarticleid'], 'enable'=>'1');
		jieqi_setconfigs('collectsite', 'jieqiCollectsite', $jieqiCollectsite, JIEQI_MODULE_NAME);
		jieqi_jumppage($article_static_url.'/admin/collectset.php', LANG_DO_SUCCESS, $jieqiLang['article']['rule_edit_success']);
		break;
	case 'show':
	default:
		include_once(JIEQI_ROOT_PATH.'/admin/header.php');
		$jieqiTpl->assign('article_static_url',$article_static_url);
		$jieqiTpl->assign('article_dynamic_url',$article_dynamic_url);
		include_once(JIEQI_ROOT_PATH.'/lib/html/formloader.php');
		$collect_form = new JieqiThemeForm($jieqiLang['article']['rule_add_new'], 'collectnew', $article_static_url.'/admin/collectnew.php');
		$collect_form->addElement(new JieqiFormLabel($jieqiLang['article']['collect_rule_note'], $jieqiLang['article']['collect_rule_description']));
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_basic']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_id'], 'config', 60, 20, ''), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_name'], 'sitename', 60, 50, ''), true);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_site_url'], 'siteurl', 60, 100, ''), true);
		$subarticleid=new JieqiFormText($jieqiLang['article']['rule_subarticleid'], 'subarticleid', 60, 100, '');
		$subarticleid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subarticleid);
		$subchapterid=new JieqiFormText($jieqiLang['article']['rule_subchapterid'], 'subchapterid', 60, 100, '');
		$subchapterid->setDescription($jieqiLang['article']['rule_operation_note']);
		$collect_form->addElement($subchapterid);
	
		$proxy_host=new JieqiFormText($jieqiLang['article']['rule_proxy_host'], 'proxy_host', 20, 100, '');
		$proxy_host->setDescription($jieqiLang['article']['rule_proxyhost_note']);
		$collect_form->addElement($proxy_host);
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_port'], 'proxy_port', 20, 20, ''));
		//$proxy_user=new JieqiFormText($jieqiLang['article']['rule_proxy_user'], 'proxy_user', 20, 100, '');
		//$proxy_user->setDescription($jieqiLang['article']['rule_proxyuser_note']);
		//$collect_form->addElement($proxy_user);
		//$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_proxy_pass'], 'proxy_pass', 20, 100, ''));

		$autoclear=new JieqiFormRadio($jieqiLang['article']['rule_auto_clean'], 'autoclear', 0);
		$autoclear->addOption('1', LANG_YES);
		$autoclear->addOption('0', LANG_NO);
		$collect_form->addElement($autoclear);
		$defaultfull=new JieqiFormRadio($jieqiLang['article']['rule_default_full'], 'defaultfull', 0);
		$defaultfull->addOption('1', LANG_YES);
		$defaultfull->addOption('0', LANG_NO);
		$collect_form->addElement($defaultfull);
		
		$referer=new JieqiFormRadio($jieqiLang['article']['rule_send_referer'], 'referer', 0);
		$referer->addOption('1', LANG_YES);
		$referer->addOption('0', LANG_NO);
		$collect_form->addElement($referer);
		
		$pagecharset=new JieqiFormSelect($jieqiLang['article']['rule_page_charset'], 'pagecharset', 'auto');
		
		$pagecharset->addOption('auto', $jieqiLang['article']['rule_charset_auto']);
		$pagecharset->addOption('gbk', $jieqiLang['article']['rule_charset_gb']);
		$pagecharset->addOption('utf8', $jieqiLang['article']['rule_charset_utf8']);
		$pagecharset->addOption('big5', $jieqiLang['article']['rule_charset_big5']);
		$pagecharset->setDescription($jieqiLang['article']['rule_charset_note']);
		$collect_form->addElement($pagecharset);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_articleinfo']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleinfo_url'], 'urlarticle', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_title'], 'articletitle', '', 5, 60), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_author'], 'author', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_sort'], 'sort', '', 5, 60));
		$sortelement=new JieqiFormText($jieqiLang['article']['rule_sort_relation'], 'sortid', 60, 10000, '');
		$sortelement->setIntro($jieqiLang['article']['rule_sort_note']);
		jieqi_getconfigs(JIEQI_MODULE_NAME, 'sort');
		$sortstr='';
		foreach($jieqiSort['article'] as $k=>$v){
			if(!empty($sortstr)) $sortstr.='||';
			$sortstr.=$v['caption'].'=>'.$k;
		}
		$sortelement->setDescription(sprintf($jieqiLang['article']['rule_sort_guide'], $sortstr));
		$collect_form->addElement($sortelement);

		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_keywords'], 'keyword', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_intro'], 'intro', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_article_image'], 'articleimage', '', 5, 60));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_articleimage_filter'], 'filterimage', 60, 250, ''));
		$indexelement=new JieqiFormTextArea($jieqiLang['article']['rule_articleindex_url'], 'indexlink', '', 5, 60);
		$indexelement->setIntro($jieqiLang['article']['rule_articleindex_note']);
		$collect_form->addElement($indexelement);
		
		$fullelement=new JieqiFormTextArea($jieqiLang['article']['rule_article_full'], 'fullarticle', '', 5, 60);
		$fullelement->setIntro($jieqiLang['article']['rule_articlefull_note']);
		$collect_form->addElement($fullelement);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_index']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_index_url'], 'urlindex', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_volume_name'], 'volume', '', 5, 60));
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_name'], 'chapter', '', 5, 60), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_id'], 'chapterid', '', 5, 60), true);

		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_chapter']));
		$collect_form->addElement(new JieqiFormText($jieqiLang['article']['rule_chapter_url'], 'urlchapter', 60, 250, ''), true);
		$collect_form->addElement(new JieqiFormTextArea($jieqiLang['article']['rule_chapter_content'], 'content', '', 5, 60), true);
		//$content1element=new JieqiFormTextArea('章节内容备用采集规则', 'content1', '', 5, 60);
		//$content1element->setIntro('当前面的规则无法采集时候程序将尝试用备用规则采集');
		//$collect_form->addElement($content1element);
		
		$filterelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_filter'], 'contentfilter', '', 5, 60);
		$filterelement->setIntro($jieqiLang['article']['rule_chapterfilter_note']);
		$collect_form->addElement($filterelement);
		
		$replaceelement=new JieqiFormTextArea($jieqiLang['article']['rule_chapter_replace'], 'contentreplace', '', 5, 60);
		$replaceelement->setIntro($jieqiLang['article']['rule_chapterreplace_note']);
		$collect_form->addElement($replaceelement);
		
		$collectimage=new JieqiFormRadio($jieqiLang['article']['rule_or_articleimage'], 'collectimage', 1);
		$collectimage->addOption('1', LANG_YES);
		$collectimage->addOption('0', LANG_NO);
		$collect_form->addElement($collectimage);
		
		//图片处理
		$collect_form->addElement(new JieqiFormLabel('', $jieqiLang['article']['collect_rule_imagetranslate']));
		
		$imagetranslate=new JieqiFormRadio($jieqiLang['article']['rule_or_imagetranslate'], 'imagetranslate', 0);
		$imagetranslate->addOption('1', LANG_YES);
		$imagetranslate->addOption('0', LANG_NO);
		$imagetranslate->setDescription($jieqiLang['article']['rule_or_imagetranslatedec']);
		$collect_form->addElement($imagetranslate);
		
		$addimagewater=new JieqiFormRadio($jieqiLang['article']['rule_or_imagewater'], 'addimagewater', 0);
		$addimagewater->addOption('1', LANG_YES);
		$addimagewater->addOption('0', LANG_NO);
		$addimagewater->setDescription($jieqiLang['article']['rule_or_imagewaterdec']);
		$collect_form->addElement($addimagewater);
		
		$imagebgcolor=new JieqiFormText($jieqiLang['article']['rule_image_bgcolor'], 'imagebgcolor', 60, 20, '');
		$imagebgcolor->setDescription($jieqiLang['article']['rule_image_bgcolordec']);
		$collect_form->addElement($imagebgcolor);
		
		$imageareaclean=new JieqiFormText($jieqiLang['article']['rule_image_areaclean'], 'imageareaclean', 60, 1000, '');
		$imageareaclean->setDescription($jieqiLang['article']['rule_image_areacleandec']);
		$collect_form->addElement($imageareaclean);
		
		$imagecolorclean=new JieqiFormText($jieqiLang['article']['rule_image_colorclean'], 'imagecolorclean', 60, 1000, '');
		$imagecolorclean->setDescription($jieqiLang['article']['rule_image_colorcleandec']);
		$collect_form->addElement($imagecolorclean);


		$collect_form->addElement(new JieqiFormHidden('action', 'new'));
		$collect_form->addElement(new JieqiFormButton('&nbsp;', 'submit', $jieqiLang['article']['rule_add_new'], 'submit'));

		$jieqiTpl->assign('jieqi_contents', '<br />'.$collect_form->render(JIEQI_FORM_MIDDLE).'<br />');
		include_once(JIEQI_ROOT_PATH.'/admin/footer.php');
		break;
}

?>