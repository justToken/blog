<?php
/*
  Plugin Name: jane模板设置插件
  Version: 1.0
  Plugin URL: http://qtnote.com
  Description: 设置你的jane模板。
  Author: 秋天日记
  ForEmlog:5.0.0+
  Author Email: qtnote@qtnote.com
  Author URL: http://www.qtnote.com
 */
!defined('EMLOG_ROOT') && exit('access deined!');

function jane() {//写入插件导航
    echo '<div class="sidebarsubmenu" id="jane"><a href="./plugin.php?plugin=jane">jane模板设置</a></div>';
  
}

addAction('adm_sidebar_ext', 'jane');
?>