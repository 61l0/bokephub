<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php
  wp_title( '|', true, 'right' );
  bloginfo( 'name' );
  $site_description = get_bloginfo( 'description', 'display' );
  if ( $site_description && ( is_home() || is_front_page() ) )
    echo " | $site_description";
  if ( $paged >= 2 || $page >= 2 )
    echo ' | ' . sprintf( __( 'Page %s' ), max( $paged, $page ) );
  ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php wp_head(); ?>
</head>


  <body>

    <div class="navbar navbar-inverse navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <?php $header_image = get_header_image();
          if ( ! empty( $header_image ) ) : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
          <?php endif; ?>         

          <header>
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
          </header>


        </div>

        <div class="collapse navbar-collapse">

        <?php wp_nav_menu( array( 'theme_location' => 'tubeace-menu', 'menu_id' => 'nav', 'menu_class' => 'nav navbar-nav')); ?>
            
            <form class="navbar-form navbar-right" action="<?php echo home_url(); ?>">
              <div class="form-group">
                <input type="text" class="form-control" name="s">
              </div>

              <button type="submit" class="btn btn-default btn-md">
                <span class="glyphicon glyphicon-search"></span>
              </button>

            </form>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">
      <div id="header-sidebar" class="secondary">
        <div id="header-sidebar1">
        <?php
        if(is_active_sidebar('header-sidebar-1')){
        dynamic_sidebar('header-sidebar-1');
        }
        ?>
        </div>
      </div>
    </div>     
