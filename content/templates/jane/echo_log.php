<?php 
/**
 * 阅读文章页面
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<div  class="panel col-md-8"  id="log">


	<div class="panel panel-warning col-xs-12" id="echo-log">
		
		<div class="panel-heading">
			<h3 class="panel-title"><span class="glyphicon glyphicon-pencil"></span> <?php topflg($top); ?><?php echo $log_title; ?></h3>
		</div>
		<div class="panel-body"  id="gallery">
			<p class="date"><span class="glyphicon glyphicon-time"></span> <?php echo gmdate('Y-n-j', $date); ?>  <?php blog_author($author); ?> <?php blog_sort($logid); ?> <?php editflg($logid,$author); ?></p>
			<div class="panel log-content"><?php echo $log_content; ?>
				<div class="tag"><?php blog_tag($logid); ?></div>
				<div class="nextlog"><?php neighbor_log($neighborLog); ?></div><br />
				<div class=" copy">
					<ul clss="list-group">
						<li class="list-group-item">
							&copy; 版权声明：凡本站文章，如没有特殊注明，均为原创！
						</li>
						<li class="list-group-item">
							&copy; 如需转载！请注明本文地址：<?php echo URL::log($logid);?>
						</li>
					</ul>
				</div>
				
			</div>
			<?php doAction('log_related', $logData); ?>

		</div>
				<div id="related-log">					
					<?php new related_log($logData['sortid'],$logid);?>
				</div>	
		<div class="clearfix"></div>
	<?php blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark); ?>		
<div class="clearfix"></div>
		<?php blog_comments($comments,$logData); ?>
</div>
<div class="clearfix"></div>
	
</div><!--end #contentleft-->

<?php
 include View::getView('side');
 include View::getView('footer');
?>