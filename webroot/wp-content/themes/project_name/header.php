<?php

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?php wp_title('|', true, 'right'); echo get_bloginfo(); ?></title>

<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/normalize.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/main.css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/print.css" />
<script src="<?php echo get_template_directory_uri(); ?>/js/libs/modernizr-2.7.1.min.js"></script>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="bodyWraper">
	<header class="masthead">
		<h1 id="siteTitle"><a href="<?php echo getHomeURL(); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><span><?php bloginfo('name'); ?></span></a></h1>
		<nav>
			<?php wp_page_menu(); ?>
		</nav>
	</header>