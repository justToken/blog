<?php 
/**
 * 页面底部信息
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<div style="clear:both"></div>
</div><!--end panel-->
<div id="footer-link">
<?php
if($init->f_link=='true'){
  if($init->is_f_link==false){
      widget_link('友情链接',1);
    }else{
      if(blog_tool_ishome()){
         widget_link('友情链接',1);
      }
    }
}
?>
</div>
</div>
<!--end contarner-->
<div class="panel panel-warning" id="footer">
	<div class="panel-footer" >

	<br/><?php echo $footer_info; ?><?php echo $icp; ?>

	<?php doAction('index_footer'); ?><br/>
</div>
</div><!--end #footerbar-->


    <!-- Bootstrap core JavaScript
    ================================================== -->
<script src="bootstraphttp://apps.bdimg.com/libs/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo TEMPLATE_URL; ?>jane.js"></script>

<?php

 $comment_face->face_replace();//匹配表情?>

</body>
</html>
