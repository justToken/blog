<?php 
/**
 * 侧边栏组件、页面模块
 */
if(!defined('EMLOG_ROOT')) {exit('error!');} 
//包含Jane模板类
 include View::getView('jane.class');
?>
<?php
//widget：blogger
function widget_blogger($title){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$name = $user_cache[1]['mail'] != '' ? "<a href=\"mailto:".$user_cache[1]['mail']."\">".$user_cache[1]['name']."</a>" : $user_cache[1]['name'];

		$img = isset($user_cache[1]['photo']['src']) ? BLOG_URL.$user_cache[1]['photo']['src'] : '';
		if($title=='header-img')
				return $img;
	?>
	<div class="panel panel-warning col-sm-12" id="blogger">
			<div class="panel-heading">
				<h4 class="panel-title"><span class="glyphicon glyphicon-user"></span> <?php echo $title; ?></h4>
			</div>
			<div class="panel-body img">
				<?php echo isset($img) ? "<img class='img-circle img-thumbnail center-block' src=".$img." alt='blogger' />" : '' ;?>
			</div>
			<div class='name'><b><?php echo $name; ?></div>
			<div class="panel-body content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $user_cache[1]['des']; ?></div>
	</div>
	 <div class="clearfix"></div>
<?php }?>
<?php
//widget：日历
function widget_calendar($title){ ?>
	<div class="panel panel-warning" >
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-calendar"></span> <?php echo $title; ?></h4>
		</div>
	<div id="calendar">
	<script>sendinfo("<?php echo Calendar::url(); ?>",'calendar');</script>
</div>
	</div>
	 <div class="clearfix"></div>

<?php }?>
<?php
//widget：标签
function widget_tag($title){
	global $CACHE;
	$tag_cache = $CACHE->readCache('tags');
	$style = array(
			'label-primary'=>2,'label-success'=>3,'label-info'=>4,'label-warning'=>5,'label-danger'=>6
		);
	?>
	
	<div class="panel panel-warning col-sm-12" id="tags">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-tags"></span> <?php echo $title; ?></h4>
		</div>
		<div class="panel-body"> 

		<?php foreach($tag_cache as $value){
				$color = array_rand($style,1);
			?>  
				<a class="label <?php echo $color;?>" data-toggle="tooltip" data-placement="top" title="<?php echo $value['usenum']; ?> 篇文章" href="<?php echo Url::tag($value['tagurl']); ?>" >
						   <?php echo $value['tagname']; ?>
				</a>
			<?php } ?>
		</div>
	</div>
	<div class="clearfix"></div>
<?php }?>
<?php
//widget：分类
function widget_sort($title){
	global $CACHE;
	$sort_cache = $CACHE->readCache('sort'); 
	$style = array(
			'label-primary'=>2,'label-success'=>3,'label-info'=>4,'label-warning'=>5,'label-danger'=>6
		);
	?>
	<div class="panel panel-warning col-sm-12" id="sort">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-th-large"></span> <?php echo $title; ?></h4>
		</div>
		<div class="panel-body">
			<?php
			foreach($sort_cache as $value):
				$color = array_rand($style,1);
				if ($value['pid'] != 0) continue;
			?>
					<a class="label <?php echo $color;?>" href="<?php echo Url::sort($value['sid']); ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo $value['lognum'] ?>篇文章" ><?php echo $value['sortname']; ?></a>
			<?php if (!empty($value['children'])): ?>
				<?php
				$children = $value['children'];
				foreach ($children as $key):
					$value = $sort_cache[$key];
				?>
					<a class="label <?php echo $color;?>" href="<?php echo Url::sort($value['sid']); ?>"  data-toggle="tooltip" data-placement="top" title="<?php echo $value['lognum'] ?>篇文章" ><?php echo $value['sortname']; ?></a>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php endforeach; ?>
		</div>

	</div>
	<div class="clearfix"></div>
	
<?php }?>
<?php
//widget：最新微语
function widget_twitter($title){
	global $CACHE; 
	$newtws_cache = $CACHE->readCache('newtw');
	$istwitter = Option::get('istwitter');
	?>
	<div class="panel panel-warning col-sm-12" id="twitter">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-th-large"></span> <?php echo $title; ?></h4>
		</div>
		<ul class="list-group">
			<?php foreach($newtws_cache as $value): ?>
			<?php $img = empty($value['img']) ? "" : '<a title="查看图片" class="t_img" href="'.BLOG_URL.str_replace('thum-', '', $value['img']).'" target="_blank">&nbsp;</a>';?>
			<li class="list-group-item"><?php echo $value['t']; ?><?php echo $img;?><div class='date'><span class=" glyphicon glyphicon-time"></span><?php echo smartDate($value['date']); ?></div>
			</li>
			<?php endforeach; ?>
		    <?php if ($istwitter == 'y') :?>
			<li  class="list-group-item `" id=""><a href="<?php echo BLOG_URL . 't/'; ?>">更多&raquo;</a></li>
			<?php endif;?>
		</ul> 
	
	</div>
	<div class="clearfix"></div>

<?php }?>
<?php
//widget：最新评论
function widget_newcomm($title){
	global $CACHE; 
	$com_cache = $CACHE->readCache('comment');
	?>
		<div class="panel panel-warning col-sm-12" id="newcomment">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-comment"></span> <?php echo $title; ?></h4>
		</div>
		<ul class="list-group">

	<?php
	foreach($com_cache as $value):
	$url = Url::comment($value['gid'], $value['page'], $value['cid']);
 	$isGravatar = Option::get('isgravatar');
	?>
	<li class="list-group-item ">
		<div class="comment-block">
			<?php if($isGravatar == 'y'): ?><div class="gra-img"><img class="img-circle img-thumbnail" src="<?php echo getGravatar($value['mail'])?>" /></div><?php endif;?>
			<div class="panel-body newcomment">	
				<?php 
					preg_match("/@.*\s*：/", $value['content'], $r);
					$content = preg_replace("/@.*\s*：/",'', $value['content']);
					$name = !empty($r) ? $value['name'].' ('.rtrim($r[0],'：').')' : $value['name'].' (发表评论)';
					
				?>
			<?php echo $name; ?><span>
				<br>
				
			<a class="content" href="<?php echo $url; ?>"><?php echo $content; ?></a>
			</div>
			<div class="clearfix"></div>
		</div>
	</li>
	<?php endforeach; ?>
	</ul>
	</div>
	<div class="clearfix"></div>
<?php }?>
<?php
//widget：最新文章
function widget_newlog($title){
	global $CACHE; 
	$newLogs_cache = $CACHE->readCache('newlog');
	?>
	<div class="panel panel-warning" id="newlog">
		<div class="panel-heading">
			<h4 class="panel-title"><i><b>new&nbsp;</b></i> <?php echo $title; ?></h4>
		</div>
		<div class="panel-body">
			<ul class="list-group">
			<?php foreach($newLogs_cache as $value): ?>
			<a class="list-group-item" href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
<?php }?>
<?php
//widget：热门文章
function widget_hotlog($title){
	$index_hotlognum = Option::get('index_hotlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getHotLog(5);?>
	
	<div class="panel panel-warning" id="nhotlog">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-fire"> </span> <?php echo $title; ?></h4>
		</div>
		<div class="panel-body">
			<ul class="list-group">
				<?php foreach($randLogs as $value): ?>
				<a class="list-group-item" href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>
<?php }?>
<?php
//widget：随机文章
function widget_random_log($title){
	$index_randlognum = Option::get('index_randlognum');
	$Log_Model = new Log_Model();
	$randLogs = $Log_Model->getRandLog($index_randlognum);?>
	<div class="panel panel-warning" id="randlog">
		<div class="panel-heading">
			<h4 class="panel-title"><span class="glyphicon glyphicon-random"> </span> <?php echo $title; ?></h4>
		</div>
		<div class="panel-body">
			<ul class="list-group">
		<?php foreach($randLogs as $value): ?>
					<a class="list-group-item" href="<?php echo Url::log($value['gid']); ?>"><?php echo $value['title']; ?></a>
		<?php endforeach; ?>
		</ul>
		</div>
	</div>
	<div class="clearfix"></div>
<?php }?>
<?php
//nav:搜索
function nav_search(){
	$search =  BLOG_URL.'index.php';
	$html =<<<ETO
			<div class="panel-collapse collapse col-xs-12 " id="nav-collapse">
			<div class="panel-body ">
			<form role="form" class=" bs-example bs-example-form" name="keyform" method="get" action="{$search}">
			<div class="input-group nav-search-toggle col-md-6 col-xs-10">
			<input name="keyword" class="form-control " type="text" placeholder="请善用搜索功能" />
			  <span class="input-group-btn"><button type="submit" class="btn btn-warning">Search</button></span>
			</div>
			</form>

			</div>
			</div>

ETO;
	echo $html;
}
//widget：搜索
function widget_search($title){?>
		<div class="panel panel-warning col-sm-12" id="logsearch">
			<div class="panel-heading">
				<h4 class="panel-title"><span class="glyphicon glyphicon-search"></span> <?php echo $title; ?></h4>
			</div>
			<div class="danel-body">
				<form name="keyform" role="form" class=" bs-example bs-example-form"  method="get" action="<?php echo BLOG_URL; ?>index.php">
					<div class="input-group col-xs-12">
					<input name="keyword" class="form-control " type="text" placeholder="请善用搜索功能" />
					  <span class="input-group-btn"><button type="submit" class="btn btn-warning">Search</button></span>
					</div>
				</form>
			</div>
		</div>
	<div class="clearfix"></div>
<?php } ?>
<?php
//widget：归档
function widget_archive($title){
	global $CACHE; 
	$record_cache = $CACHE->readCache('record');
	?>
	<div class="panel panel-warning col-sm-12" id="recode">
			<div class="panel-heading">
				<h4 class="panel-title"><span class="glyphicon glyphicon-folder-open"></span> <?php echo $title; ?></h4>
			</div>
			<div class="danel-body">
			<ul class="list-group">
				<?php foreach($record_cache as $value): ?>
				<li class="list-group-item" >
				<a data-toggle="tooltip" data-placement="top"  href="<?php echo Url::record($value['date']); ?>" title="共<?php echo $value['lognum'];?>篇文章">
				<span class="glyphicon glyphicon-calendar"></span>
				<?php echo $value['record']; ?>(<?php echo $value['lognum'];?>)
				</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<div class="clearfix"></div>

<?php } ?>
<?php
//widget：自定义组件
function widget_custom_text($title, $content){ ?>
		<div class="panel panel-warning col-sm-12" id="custom">
			<div class="panel-heading">
				<h4 class="panel-title"><span class="glyphicon glyphicon-file"></span> <?php echo $title; ?></h4>
			</div>
			<div class="danel-body">
					<?php echo $content; ?>
			</div>
	</div>
	<div class="clearfix"></div>

<?php } ?>
<?php
//widget：链接
function widget_link($title,$footer=null){
	global $CACHE; 
	$link_cache = $CACHE->readCache('link');
    //if (!blog_tool_ishome()) return;#只在首页显示友链去掉双斜杠注释即可
	?>
	<div class="panel panel-warning col-sm-12 link">
			<div class="panel-heading">
				<h4 class="panel-title"><span class="glyphicon glyphicon-link"></span> <?php echo $title; ?></h4>
			</div>
			<div class="danel-body">
				<ul class="list-group">
					<li class="list-group-item">
					<?php foreach($link_cache as $value): 
						$urlinfo = parse_url($value['url']); 
						$urlHost = explode(".",$urlinfo['host']);
						$urlHost = array_reverse($urlHost);
					?>
					<a class="label" data-toggle="tooltip" data-placement="top" href="<?php echo $value['url']; ?>" title="<?php echo $value['des']; ?>" target="_blank">
						<img src="<?=$urlinfo['scheme']?>://www.<?=$urlHost[1]?>.<?=$urlHost[0]?>/favicon.ico" onerror="javascript:this.src='<?php echo TEMPLATE_URL; ?>images/favicon.ico';">
						<?php echo $value['link']; ?>
					</a>
					<?php endforeach; ?>
					</li>
				</ul>
	</div>
     <?php 
     if($footer)
    	 new log_Total();//站点统计
	?>
	</div>
	<div class="clearfix"></div>
<?php }?> 
<?php
//blog：导航
 

function blog_navi($blogname){
	global $CACHE; 
	$navi_cache = $CACHE->readCache('navi');
	?>
	 <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" 
         data-target="#example-navbar-collapse">
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
         <span class="icon-bar"></span>
      </button>
		    	<h3 class="visible-xs m-name col-xs-8"><a class="" href="<?php echo BLOG_URL; ?>"><?php echo $blogname; ?></a></h3>
		    	<span class="nav-search">
			    	<span class="glyphicon glyphicon-search btn" id="search-toggle" data-toggle="collapse" href="#nav-collapse"></span>
				</span>
	 </div> 
   <div class="collapse navbar-collapse" id="example-navbar-collapse">
	<ul class="nav navbar-nav">
	<?php
	foreach($navi_cache as $value):
        if ($value['pid'] != 0) {
            continue;
        }
		$newtab = $value['newtab'] == 'y' ? 'target="_blank"' : '';
        $value['url'] = $value['isdefault'] == 'y' ? BLOG_URL . $value['url'] : trim($value['url'], '/');
        $current_tab = BLOG_URL . trim(Dispatcher::setPath(), '/') == $value['url'] ? 'current' : 'common';
        $dropdown_toggle=" class='dropdown-toggle' data-toggle='dropdown' " ;
		?>
		<li class="dropdown <?php echo $current_tab;?>">

			<a <?php echo  !empty($value['children']) ?  $dropdown_toggle : '' ; ?> href="<?php echo $value['url']; ?>" <?php echo $newtab;?>>
				<?php echo $value['naviname']; ?>
				<?php echo  !empty($value['children']) ? '<span class="caret"></span>' : '';?>
			</a>
			<?php if (!empty($value['children'])) :?>

            <ul class="dropdown-menu">
                <?php foreach ($value['children'] as $row){
                        echo '<li><a href="'.Url::sort($row['sid']).'">'.$row['sortname'].'</a></li>';
                        echo ' <li class="divider"></li>';
                }?>
			</ul>
            <?php endif;?>

             <?php if (!empty($value['childnavi'])) :?>
            <ul class="dropdown-menu">
                <?php foreach ($value['childnavi'] as $row){
                        $newtab = $row['newtab'] == 'y' ? 'target="_blank"' : '';
                        echo '<li><a href="' . $row['url'] . "\" $newtab >" . $row['naviname'].'</a></li>';
                }?>
			</ul>
            <?php endif;?> 

		</li>
	<?php endforeach; ?>
	</ul>
</div>



<?php }?>
<?php
//blog：置顶
function topflg($top, $sortop='n', $sortid=null){
    if(blog_tool_ishome()) {
       echo $top == 'y' ? "<span  data-toggle='tooltip' data-placement='left' title='置顶文章' class='glyphicon glyphicon-arrow-up top'></span> " : '';
    } elseif($sortid){
       echo $sortop == 'y' ? "<span data-toggle='tooltip' data-placement='left' title='置顶文章' class='glyphicon glyphicon-circle-arrow-up top'></span> " : '';
    }
}
?>
<?php
//blog：编辑
function editflg($logid,$author){
	$editflg = ROLE == ROLE_ADMIN || $author == UID ? '<a href="'.BLOG_URL.'admin/write_log.php?action=edit&gid='.$logid.'" target="_blank">编辑</a>' : '';
	echo $editflg;
}
?>
<?php
//blog：分类
function blog_sort($blogid){
	global $CACHE; 
	$log_cache_sort = $CACHE->readCache('logsort');
	?>
	<?php if(!empty($log_cache_sort[$blogid])): ?>
    <a href="<?php echo Url::sort($log_cache_sort[$blogid]['id']); ?>" data-toggle="tooltip" data-placement="top" title="查看  (<?php echo $log_cache_sort[$blogid]['name']; ?>) 分类下的相关文章">
    	<span class="glyphicon glyphicon-bookmark"></span><?php echo $log_cache_sort[$blogid]['name']; ?>
    </a>
	<?php endif;?>
<?php }?>
<?php
//blog：文章标签
function blog_tag($blogid){
	global $CACHE;
	$log_cache_tags = $CACHE->readCache('logtags');
	$ico = "<span class='glyphicon glyphicon-tag'></span>";
	$tag = '';
	if (!empty($log_cache_tags[$blogid])){
	
		foreach ($log_cache_tags[$blogid] as $value){
			$tag .= "<a href=\"".Url::tag($value['tagurl'])."\"data-toggle='tooltip' data-placement='top' title='查看标签为：（{$value['tagname']}）的文章'> {$ico} ".$value['tagname'].'</a>';
			$ico = null;
		}
		echo $tag;
	}
}
?>
<?php
//blog：文章作者
function blog_author($uid){
	global $CACHE;
	$user_cache = $CACHE->readCache('user');
	$author = $user_cache[$uid]['name'];
	$mail = $user_cache[$uid]['mail'];
	$des = $user_cache[$uid]['des'];
	$title = !empty($mail) || !empty($des) ? " data-toggle='tooltip' data-placement='top'  title=\"$des $mail\"" : '';
	echo ' <a href="'.Url::author($uid)."\" $title><span class='glyphicon glyphicon-user'></span> $author</a>";
}
?>
<?php
//blog：相邻文章
function neighbor_log($neighborLog){
	extract($neighborLog);?>
	<?php if($prevLog):?>
		 <a href="<?php echo Url::log($prevLog['gid']) ?>" class="prev" data-toggle='tooltip' data-placement='right' title="上一篇：<?php echo $prevLog['title'];?>" >
			<span class="glyphicon glyphicon-chevron-left"></span>
		</a>
	<?php endif;?>
		<span>-----全 文 完-----</span>
	<?php if($nextLog):?>
		 <a href="<?php echo Url::log($nextLog['gid']) ?>" class="next" data-toggle='tooltip' data-placement='left' title="下一篇：<?php echo $nextLog['title'];?>">
			<span class="glyphicon glyphicon-chevron-right"></span>
		 </a>
	<?php endif;?>
<?php }?>
<?php
//blog：评论列表
function blog_comments($comments,$logData=''){
    extract($comments);
?>	<div class="panel panel-warning" id="article-comment">
		<div class="panel-heading">
			<h4 class="paenl-title"><span class="glyphicon glyphicon-comment"></span> 评论列表：(点击:<?php echo $logData['views']; ?>次/共有:<?php echo $logData['comnum']; ?>条评论)</h4>
		</div>
		<div class="panel-body">
			<ul class="list-group">
				
				<?php
				$isGravatar = Option::get('isgravatar');
				foreach($commentStacks as $cid):
			    $comment = $comments[$cid];
				$comment['poster'] = $comment['url'] ? '<a  href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
				?>
				<li class="list-group-item" id="comment-<?php echo $comment['cid']; ?>">
					<a name="<?php echo $comment['cid']; ?>"></a>
					<?php if($isGravatar == 'y'): ?><span class="col-xs-2 avatar  avatar-parent"><img class="img-circle img-thumbnail" src="<?php echo getGravatar($comment['mail']); ?>" /></span><?php endif; ?>
						<b><?php echo $comment['poster']; ?> <?php if(function_exists('display_useragent')){display_useragent($comment['cid'],$comment['mail']);} ?>发表评论:</b><br><br>
					
					<div class="comment-info-parent col-xs-10">
						<div class="comment-content"><?php echo $comment['content']; ?></div>
						<div class="comment-reply">
							<span class="reply"> 
								<a href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)">(回复)</a></span>
							<span class="comment-time"><?php echo $comment['date']; ?></span>
						</div>
						
					</div>

	 				<div class="clearfix"></div>
					<li class="list-group-item">
						<div class="children-comments">
							<ul class="list-group ">
								<?php blog_comments_children($comments, $comment['children']); ?>

							</ul>
						</div>
					</li>
				</li>
				<?php endforeach; ?>
			    <div id="pagenavi">
				    <?php echo $commentPageUrl;?>
			    </div>
		  </ul>
		</div>
	</div>

<?php }?>
<?php
//blog：子评论列表
function blog_comments_children($comments, $children){
	?>	
	<?php
	$isGravatar = Option::get('isgravatar');
	foreach($children as $child):
	$comment = $comments[$child];
	preg_match("/@.*\s*：/", $comment['content'], $r);
	$content = preg_replace("/@.*\s*：/",'', $comment['content']);
	$comment['poster'] = $comment['url'] ? '<a href="'.$comment['url'].'" target="_blank">'.$comment['poster'].'</a>' : $comment['poster'];
	?>
	<li class="list-group-item" id="comment-<?php echo $comment['cid']; ?>">
	 <div class="clearfix"></div>
		<a name="<?php echo $comment['cid']; ?>"></a>
		<?php if($isGravatar == 'y'): ?><span class="avatar"><img class="img-circle img-thumbnail" src="<?php echo getGravatar($comment['mail']); ?>" /></span><?php endif; ?>
		<div class="comment-info">
			<b><?php echo $comment['poster'].$r[0]; ?><?php if(function_exists('display_useragent')){display_useragent($comment['cid'],$comment['mail']);} ?> </b><br />
			<div class="comment-content">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $content; ?></div>
			<div class="comment-reply">
			<?php if($comment['level'] < 4): ?>
			<span class="reply"><a href="#comment-<?php echo $comment['cid']; ?>" onclick="commentReply(<?php echo $comment['cid']; ?>,this)">(回复)</a></span>
			<?php endif; ?>
			<span class="comment-time"><?php echo $comment['date']; ?></span>
			</div>
		</div>
	</li>
	 <div class="clearfix"></div>
	
	<?php blog_comments_children($comments, $comment['children']);?>
	<?php endforeach; ?>
<?php }?>
<?php
//blog：发表评论表单
function blog_comments_post($logid,$ckname,$ckmail,$ckurl,$verifyCode,$allow_remark){
	if($allow_remark == 'y'): ?>
	<div id="comment-place" class="panel panel-warning">
		<div class="panel-heading">
			<h4 class="comment-header panel-title"><span class="glyphicon glyphicon-comment"></span> 发表评论：<a name="respond"></a></h4>
		</div>
	<div class="comment-post" id="comment-post">
		<div class="cancel-reply" id="cancel-reply" style="display:none"><a href="javascript:void(0);" onclick="cancelReply()">取消回复</a></div>
		<div class="panel-body">
			<form role="form" class="bs-example bs-example-form" method="post" name="commentform" action="<?php echo BLOG_URL; ?>index.php?action=addcom" id="commentform">
				<input type="hidden" name="gid" value="<?php echo $logid; ?>" />
				<?php if(ROLE == ROLE_VISITOR): ?>
					<div class="input-group">
			 			 <span class="input-group-addon" ><span class="glyphicon glyphicon-user"></span> name</span>
						<input type="text" class="form-control" name="comname" maxlength="49" value="<?php echo $ckname; ?>"  placeholder="请输入你的昵称" required tabindex="1">
					</div>
					<div class="input-group">
			 			 <span class="input-group-addon" ><span class="glyphicon glyphicon-envelope"></span> Email</span>
						<input type="email" class="form-control"  name="commail"  maxlength="128"  value="<?php echo $ckmail; ?>"  placeholder="请输入你的Email" required tabindex="2">
					</div>
					<div class="input-group">
			 			 <span class="input-group-addon"><span class="glyphicon glyphicon-link"></span> Link&nbsp;&nbsp;</span>
						<input type="url" name="comurl" class="form-control" maxlength="128"  value="<?php echo $ckurl; ?>"  placeholder="请输入你的个人主页(选填)" tabindex="3">
					</div>
				<?php endif; ?>
				<div class="input-group">
					<textarea class="form-control" name="comment" id="comment" rows="3" tabindex="2"></textarea>
				</div>
				<br />
					<p><?php echo $verifyCode; ?> <button type="submit" class="form-control btn btn-warning" id="comment_submit" tabindex="6" > 发表评论</button>
				<input type="hidden" name="pid" id="comment-pid" value="0" size="22" tabindex="1"/>
			</form>
		</div>
	</div>
	</div>
	<?php endif; ?>
<?php }?>
<?php
//blog-tool:判断是否是首页
function blog_tool_ishome(){
    if (BLOG_URL . trim(Dispatcher::setPath(), '/') == BLOG_URL){
        return true;
    } else {
        return FALSE;
    }
}

?>


