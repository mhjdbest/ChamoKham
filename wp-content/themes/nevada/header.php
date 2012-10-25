<?php
include_once('functions/theme-mailer.php');

/**
 * The Header for our theme.
 *
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since lambda framework v 1.0
 * based on skeleton
 */
 
global $lambda_meta_data, $theme_options, $wpdb;

if(!$lambda_meta_data->get_the_value(UT_THEME_INITIAL.'column_layout')) { $columnset = 1; } else { $columnset = $lambda_meta_data->get_the_value(UT_THEME_INITIAL.'column_layout'); }

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<!--
========================================================================
 Nevada WordPress Theme by United Themes (http://www.unitedthemes.com)
 Marcel Moerkens & Matthew Nettekoven 
========================================================================
-->
<meta charset="<?php bloginfo( 'charset' ); ?>" />

<title><?php
	// Detect Yoast SEO Plugin
	if (defined('WPSEO_VERSION')) {
		wp_title('');
	} else {
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', UT_THEME_NAME ), max( $paged, $page ) );
	}
	?>
</title>


<!--[if lte IE 8]>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/ie8.css" media="screen" />
<![endif]-->

<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->


<!-- Mobile Specific Metas
================================================== -->

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 

<!-- Favicons
================================================== -->

<link rel="shortcut icon" href="<?php echo get_option_tree('favicon'); ?>">

<link rel="apple-touch-icon" href="<?php echo get_option_tree('apple_touch_icon_small'); ?>">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_option_tree('apple_touch_icon_mid'); ?>" />
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_option_tree('apple_touch_icon'); ?>" />

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php
	
	// enqueue threaded comments support.
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	
	// Load head elements
	wp_head();

	#-----------------------------------------------------------------
	# Sidebar Alignement
	#-----------------------------------------------------------------
	if(is_home()) {
			
		$homeid = get_option('page_for_posts');  
		$meta_sidebar = get_post_meta($homeid, $lambda_meta_data->get_the_id(), TRUE);
					
	} else {
		
		$meta_sidebar = $lambda_meta_data->the_meta();
		
	}
	$meta_sidebar = (isset($meta_sidebar['sidebar_align'])) ? $meta_sidebar['sidebar_align'] : '';
	
	$sidebar_position = (!empty($meta_sidebar)) ? $meta_sidebar : $theme_options['sidebar_alignement'];
	$content_position = ($sidebar_position == "right" ? "left" : "right");
	$sidebar_margin = ($sidebar_position == "right" ? "left" : "right");
	$sidebar_second_margin = ($sidebar_position == "both" ? "left" : "right");
	
	?>
    
	
	<?php
	#-----------------------------------------------------------------
	# Dynamic CSS 
	#-----------------------------------------------------------------
	?>	
	<style type="text/css">	
	
	<?php customBackground(); 			// located in theme-layout-functions.php ?>
	<?php customSliderBackground(); 	// located in theme-layout-functions.php ?>
	<?php customWrap(); 				// located in theme-layout-functions.php ?>
	<?php customFooter();				 // located in theme-layout-functions.php ?>
		
	<?php echo ($theme_options['custom_css']); ?>
	
	#wrap #content {float:<?php echo $content_position; ?>;}
	#wrap #sidebar {float:<?php echo $sidebar_position; ?>;}
	#wrap #sidebar .widget-container {margin-<?php echo $sidebar_margin; ?>: 20px;margin-<?php echo $sidebar_position; ?>: 0px;}
	
	/* second sidebar enhancement */	
	#wrap #sidebar_second {float:<?php echo $content_position; ?>;}
	#wrap #sidebar_second .widget-container {margin-<?php echo $sidebar_second_margin; ?>: 20px;margin-<?php echo $content_position; ?>: 0px;}
	
	</style>

</head>





<body <?php body_class(); ?>>

<!-- to top button -->
<div id="toTop">Go to Top</div>
<!-- end to top button -->
	
	<?php
	#-----------------------------------------------------------------
	# Supersized 
	#-----------------------------------------------------------------
	if(is_home()) {
			
		$homeid = get_option('page_for_posts');  
		$slides = get_post_meta($homeid, $lambda_meta_data->get_the_id(), TRUE);
					
	} else {
		
		$slides = $lambda_meta_data->the_meta();
		
	}
			
	//can be overwritten by metapanel
	$default_bgslider = $theme_options['default_backgroundslider'];
	$sitelayout = get_option_tree('sitelayout');
	$backgroundtype = $theme_options['background_type'];

	
	//meta panel settings to overwrite default option tree settings
	$sitelayout = (isset($slides['sitelayout'])) ? $slides['sitelayout'] : $sitelayout;
	$backgroundtype = (isset($slides['background_type'])) ? $slides['background_type'] : $backgroundtype;
	$default_bgslider = (isset($slides['default_backgroundslider'])) ? $slides['default_backgroundslider'] : $default_bgslider;
	
	if( isset($default_bgslider) ) {	
	
		if( isset($default_bgslider) && ($backgroundtype == 'default_backgroundslider' ) && ($sitelayout == 'boxed') ) {
												
			$sliderinfo = explode('_', $default_bgslider);
			$slides['main_slider'] = $default_bgslider;
			
			$table_lambda_sliders = $wpdb->prefix . "lambda_sliders"; 
			$supersized = $wpdb->get_var("SELECT slidertype FROM $table_lambda_sliders WHERE id = $sliderinfo[1]");
		
		}	
			
		if(isset($supersized) && $supersized == 'supersized')	
		lambda_main_slider($slides); // this function can be found in theme-functions.php around line 198 
			
	} ?>
    
    
	<div id="wrap" class="container clearfix" data-role="page">
		
	<?php 
	#-----------------------------------------------------------------
	# Plugin Notification
	#-----------------------------------------------------------------
	if(lambda_is_plugin_active('option-tree/index.php')) {
		 echo '<div class="alert red">'.__('Option Tree Plugin has been detected! Please deactivate this Plugin to prevent themecrashes and failures!', UT_THEME_NAME ).'</div>';
	} 
	if(lambda_is_plugin_active('soundcloud-shortcode/soundcloud-shortcode.php')) {
		 echo '<div class="alert red">'.__('Soundcloud Plugin has been detected! Please deactivate this Plugin to prevent themecrashes and failures!', UT_THEME_NAME ).'</div>';
	}
		
	?>
	
	<header id="header" class="fluid clearfix" data-role="header">
	<div class="container">    
    	        
		<?php
		// Build the logo or text
		if (get_option_tree('textorlogo') == 'Logo') {
			
			$lambda_logo  = '<div id="logo">
								<a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'"><img id="sitelogo" src="'.get_option_tree('header_logo').'"></a>
							</div>';	
			} else {
			$lambda_logo  = '<div id="logo"><h1>
								<a href="'.esc_url( home_url( '/' ) ).'" title="'.esc_attr( get_bloginfo('name','display')).'">'.get_bloginfo('name').'</a>
							</h1></div>';
			}
		echo apply_filters ( 'child_logo' , $lambda_logo);
		?>
        
       
        	<div class="h-right">
        		 	
                    <!-- Tagline -->		
					<?php if(isset($theme_options['top_header_tagline'])) : ?>
				                     
                    <span class="top-header-tagline">  
                        <?php echo stripslashes($theme_options['top_header_tagline']); ?>						
                    </span><!-- Top Header Tagline -->					
            		
					<?php endif; ?>	
                    
                    
                    
                    <!-- Social Icons -->                  
				    
					<?php if (is_active_sidebar('header-widget-area')) : 
							dynamic_sidebar('header-widget-area'); 
					endif;?>
					
					<!-- /Social Icons -->
					
            
        </div>
      </div>  
      
	</header><!--/#header-->
        
		<div class="clear"></div>
        
        <div class="nav-wrap">
            <div class="container">
            <?php
            //Navigation
                
            //main navigation
            wp_nav_menu( array( 'container' 		=> 'nav',  
                                'container_id' 		=> 'navigation', 
                                'theme_location' 	=> 'primary-menu', 
                                'fallback_cb' 		=> 'default_menu',
                                'menu_class'      	=> 'menu clearfix',
                                'container_class' 	=> 'clearfix',
								'walker' 			=> new lambda_walker())
            );
            ?>	
			
			<?php if ( has_nav_menu( 'mobile-menu' ) ) { 
			
				echo '<div class="mm-trigger">'.get_bloginfo('name').'<button class="mm-button"></button></div>';
				
				wp_nav_menu( array( 'theme_location' => 'mobile-menu', 
									'container_id' => 'mobile-menu',
									'container' => 'nav', 
									'menu_class' => 'mm-menu',
									'depth' => 1 ) ); 
											
			} ?>
			
			
		    </div>
           
		</div>
	
	<div class="clear"></div>