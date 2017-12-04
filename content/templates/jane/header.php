<?php
/*
Template Name:Jane
Description:Jane主题，自定义你喜欢的风格
Version:1.0
Author:秋天日记
Author Url:http://qtnote.com
Sidebar Amount:1
*/
if(!defined('EMLOG_ROOT')) {exit('error!');}
require_once View::getView('module');
?>
<!doctype html>
<html lang="zh-CN">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="baidu_union_verify" content="4c9f7623559bad7ed3aad62f6a989941">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $site_title; ?></title>
<meta name="keywords" content="<?php echo $site_key; ?>" />
<meta name="description" content="<?php echo $site_description; ?>" />
<meta name="generator" content="emlog" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="<?php echo BLOG_URL; ?>xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="<?php echo BLOG_URL; ?>wlwmanifest.xml" />
<link rel="alternate" type="application/rss+xml" title="RSS"  href="<?php echo BLOG_URL; ?>rss.php" />
<link href="http://apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo TEMPLATE_URL; ?>jane.css" rel="stylesheet" type="text/css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="/assets/js/html5shiv.min.js?v=f3008b4099"></script>
  <script src="/assets/js/respond.min.js?v=f3008b4099"></script>
<![endif]-->

<script src="http://apps.bdimg.com/libs/jquery/1.8.0/jquery.min.js"></script>
<script src="<?php echo BLOG_URL; ?>include/lib/js/common_tpl.js" type="text/javascript"></script>
<?php
$comment_face = new comment_face();
if(is_file('./content/plugins/jane/jane_setting.php')){
 $init = new init_Style(null);
}else{
 $init = new init_Style('tw');
}
if($init->backg==true){?>
	<style>
		#banner img{
			filter: url(blur.svg#blur); /* FireFox, Chrome, Opera */
			    -webkit-filter: blur(5px); /* Chrome, Opera */
			       -moz-filter: blur(5px);
			        -ms-filter: blur(5px);    
			            filter: blur(5x); 
		}
	</style>
<?php }?>
<?php
		$img = widget_blogger('header-img');
		$bg_url = TEMPLATE_URL.'images/bg.png';	
		$bg_img = !empty($init->img_url) ? "<div id='banner'><img class='header-bg' src='{$init->img_url}' /></div>" : "<div id='banner'><img class='header-bg' src='{$bg_url}' /></div>";
		$author_img = !empty($img) ? "<div id='author-img' class='visible-xs'><img class='img-circle' src=".$img." alt='blogger' /></div>" : "<div id='author-img' class='visible-xs'><img class='img-circle' src='{$bg_url}' alt='blogger' /></div>";
		doAction('index_head'); 
?>
</head>
<body> 

		<?php
			if($init->bc==''){
				echo $bg_img;
			}
		?>
	<header>
		  <div id="header">
					<?php if(ROLE == ROLE_ADMIN || ROLE == ROLE_WRITER):?>
						<span id="admin">
						<span class="btn"><a data-toggle="tooltip" data-placement="left" title="后台管理" href="<?php echo BLOG_URL; ?>admin/"><span class="glyphicon glyphicon-cog "</span></a></span>
						<span class="btn"><a data-toggle="tooltip" data-placement="left" title="退出" href="<?php echo BLOG_URL; ?>admin/?action=logout"><span class="glyphicon glyphicon-log-out "</span></a></span>
						</span>
					<?php endif;?>
			<div  class="hidden-xs">
		    	<h2 class="col-md-12"><a href="<?php echo BLOG_URL; ?>"><img id="logo" src="<?php echo TEMPLATE_URL; ?>images/logo.png" /><?php echo $blogname; ?></a>

					<sub> <span class='bloginfo'><?php echo $bloginfo; ?></span></sub>
		    	</h2>
		   	  
		  </div>
		  			<?php echo $author_img;?>
		</div>
		<div class="clearfix"></div>
		 <div class ="navbar navbar-default" id="nav">
		 <?php  echo $init->show_scroll(); ?>
		 	 	 <nav>
			  		<?php blog_navi($blogname);?>
			  	</nav>
		</div>
	</header>
	<?php nav_search();?>
<div class="container">
		<div class="panel" id="content">
