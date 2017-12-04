<?php 
/**
 * 站点首页模板
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
?>
<?php $freedom_list = new Freedom_list(); ?>
<div  class="panel col-md-8">
        <?php  
            $carousel = new Carousel();
        ?>
    <div class="freedom_list">
            <?php echo  $freedom_list->show_list('one');?>
            <?php echo $freedom_list->show_list('two'); ?>
        <div class="clearfix"></div>
    </div>
<?php doAction('index_loglist_top'); ?>
<div id='log-list'>
<?php 
if (!empty($logs)):
    $panel = 2; 
foreach($logs as $value):
$panel++;
                //获取文章图片
                $search_pattern = '%<img[^>]*?src=[\'\"]((?:(?!\/admin\/|>).)+?)[\'\"][^>]*?>%s';
                preg_match($search_pattern, $value['content'], $img);
                $value['img'] = isset($img[1])?$img[1]:TEMPLATE_URL.'pic/ap'.rand(1,15).'.jpg';
?>  
            <div class="panel article col-sm-12">
                    <a href="<?php echo $value['log_url']; ?>">
                        <h4 clsas="panel-title"><?php echo $value['log_title']; ?>
                                <?php if($value['views']>=$carousel->view_hot):?>
                                <span class="hot glyphicon glyphicon-fire" data-toggle='tooltip' data-placement='left' title='热门文章' ></span>
                        <?php else: 
                                $t=time() - ($carousel->view_new)*24*60*60;
                                $log=gmdate('Y-m-d',$value['date']);
                                $new=date("Y-m-d",$t);
                                if($log > $new):?>
                                <span class="new" data-toggle='tooltip' data-placement='left' title='最新文章' >new</span>              
                        <?php endif;endif; ?>
                                <?php topflg($value['top'], $value['sortop'], isset($sortid)?$sortid:''); ?>
                        </h4> 
                    </a>
                <div class="article-img">
                            <img class="img-thumbnail" src="<?php echo $value['img']?>" />
                </div>
                <div class=" article-content">
                        <div class="article-body">
                            <?php echo subString(strip_tags($value['content']),0,120,"……"); ?>
                        </div> 
                        <div class="article-info">
                                <span class="date"><span class="glyphicon glyphicon-time"></span> <?php echo gmdate('Y-n-j', $value['date']); ?></span>
                                <span><a href="<?php echo $value['log_url']; ?>#comments"><span class="glyphicon glyphicon-comment"> </span> (<?php echo $value['comnum']; ?>)</a></span>
                                <span><a href="<?php echo $value['log_url']; ?>"><span class="glyphicon glyphicon-eye-open" > </span> (<?php echo $value['views']; ?>)</a></span>
                                <span class='log-sort'><?php blog_sort($value['logid']); ?></span>
                        </div>
                </div>
            </div>
<?php 
endforeach;
?>
<div class='clearfix'></div>
</div>
<div class='clearfix'></div>
<?php
else:
?>
</div>
    <div class="panel"> 
        <div class="panel-body">
        <h2>未找到</h2>
        <p>抱歉，没有符合您查询条件的结果。</p>
        </div>
    </div>
<?php endif;?>
<div id="page"> <?php echo $page_url;?></div>
</div>

<!-- end #panel panel-default col-md-8-->

<?php
 include View::getView('side');
 include View::getView('footer');
?>