<?php 
/**
 * 自建页面模板
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<div  class="panel col-md-8"  id="log">
    <div class="panel panel-warning col-xs-12" id="echo-log">
    <div class="panel-heading">
            <h3 class="panel-title"><span class="glyphicon glyphicon-pencil"></span> <?php topflg($top); ?><?php echo $log_title; ?></h3>
        </div>
        <div class="panel-body"  id="gallery">
    <?php echo $log_content; ?>
    <?php blog_comments($comments,$logData); ?>
    <?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>
        </div></div>
    <div style="clear:both;"></div>
</div><!--end #contentleft-->
<?php
 include View::getView('side');
 include View::getView('footer');
?>