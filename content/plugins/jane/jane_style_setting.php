<?php
!defined('EMLOG_ROOT') && exit('access deined!');
	$a_color = !empty($_POST['style_a_color']) ? addslashes($_POST['style_a_color']) : '';
	$nav_border = !empty($_POST['style_nav_border']) ? addslashes($_POST['style_nav_border']) : '';
	$nav_a = !empty($_POST['style_nav_a']) ? addslashes($_POST['style_nav_a']) : '';
	$panel_h = !empty($_POST['style_panel_h']) ? addslashes($_POST['style_panel_h']) : '';
	$a_body = !empty($_POST['style_a_body']) ? addslashes($_POST['style_a_body']) : '';
	$panel_t = !empty($_POST['style_panel_t']) ? addslashes($_POST['style_panel_t']) : '';
	$opacity = !empty($_POST['style_opacity']) ? addslashes($_POST['style_opacity']) : '';
	$bc = !empty($_POST['style_bc']) ? addslashes($_POST['style_bc']) : '';
	$current_a = !empty($_POST['style_current_a']) ? 'color:'.addslashes($_POST['style_current_a']).' !important' : '';
	$load = !empty($nav_border) ? "box-shadow: 0 0 20px {$nav_border};" : '';
	$body = "color: {$a_body} !important;

	background:{$bc} !important;";
	$a="color:{$a_color} ;";
	$navbar_default="
	border-top-color: {$nav_border} !important;
	";
	$navbar_a="
	color: {$nav_a} !important;";
	$nav_d="
		background: {$nav_border} !important;
		$current_a;";
		$panel = "
		background: {$panel_h} !important;
		color:{$panel_t} !important;";
		$footer = "
			border-color:{$panel_h} !important;
		opacity:0.{$opacity};";
		$footer_panel="
		border-style: none !important;box-shadow:0 0 10px {$panel_h} !important;";
		$list="
		border-top-color: {$panel_h};";
	$css =<<<CSS
	/*jane init*/
	body{
		$body
	}
	a{
		$a
	}
	.navbar-default{
		$navbar_default
	}
	.navbar a{
		$navbar_a
	}
	.navbar .current a{
		$current_a
	}

	.navbar-default .dropdown a:hover,.navbar-default .dropdown-toggle a:hover,.current>a{
		$nav_d
	}
	.cbbfixed .cbbtn,.panel .panel-heading{
		$panel
	}
	.freedom_list .panel,#log-list,.panel .panel-heading,#side .panel,#log .panel,#page .panel,#footer-link .panel,#w .panel{
	$footer
	}

	#log-list h4{
		$list
	}
	.loader div {
 		 background: {$nav_border};
 		 {$load}
	}
	/*jane init*/
CSS;

?>