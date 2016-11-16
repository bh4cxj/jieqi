<?php
echo '<ul class="ulitem">
  <li><a href="'.$this->_tpl_vars['article_static_url'].'/newarticle.php">发表新作</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/newdraft.php">新建草稿</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/draft.php">草 稿 箱</a></li>
  <li><a href="'.$this->_tpl_vars['jieqi_url'].'/ptopics.php?oid=self">会 客 室</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/authorpage.php">我的专栏</a></li>
  <li><a href="'.$this->_tpl_vars['article_dynamic_url'].'/masterpage.php">我的文章列表</a></li>
  ';
if($this->_tpl_vars['jieqi_modules']['obook']['publish'] > 0){
echo '
  <li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/newobook.php">新建电子书</a></li>
  <li><a href="'.$this->_tpl_vars['jieqi_modules']['obook']['url'].'/masterpage.php">我的电子书</a></li>
  ';
}
echo '
</ul>';
?>