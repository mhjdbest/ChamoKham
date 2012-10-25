<?php

/**
 * Templatepart for Teaser
 *
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since lambda framework v 2.0
 */

#-----------------------------------------------------------------
# Conditional Tags for displaying Site Titels and Teaser
#-----------------------------------------------------------------
global $lambda_meta_data, $slider_meta_data; 

if(is_home()) {
			
	$homeid = get_option('page_for_posts');  
	$featuredheader = get_post_meta($homeid, $slider_meta_data->get_the_id(), TRUE);	
		
} else {

	$featuredheader = $slider_meta_data->the_meta();
	
}
$meta_data = $lambda_meta_data->the_meta();


if (get_option_tree('blog_title') && is_home()) {
		
	//Set Title
	$title = get_option_tree('blog_title');
	
} elseif(is_page() || is_single()) {

	$title = get_the_title();

} elseif(is_404()) { 
		
	//Set Title
	$title = __( '404 Error', UT_THEME_NAME );	
	
			
} else { // for all other especially Archives
		
	if(is_day()) : 
		$title = sprintf( __( '%s', UT_THEME_NAME ), get_the_date() );
					
	elseif(is_month()) : 
		$title = sprintf( __( '%s', UT_THEME_NAME ), get_the_date('F Y') );
					
	elseif(is_year()) : 
		$title = sprintf( __( '%s', UT_THEME_NAME ), get_the_date('Y') );
					
	elseif(is_category()) :
		$title = sprintf( __( '%s', UT_THEME_NAME ), single_cat_title( '', false ) );	
	
	elseif(!is_page() && (!is_home() && !is_front_page())) : 
		
		$title = __( 'Blog Archives', UT_THEME_NAME );
	
	endif; 

	}
	
#-----------------------------------------------------------------
# Start Output
#----------------------------------------------------------------- ?>

<?php 

$hideteaser = (isset($featuredheader['hide_teaser'])) ? $featuredheader['hide_teaser'] : 'teaseron';

if($hideteaser != 'teaseroff') {?>

<div class="clear"></div>

<section id="teaser" class="fluid clearfix">
	<div class="container">
	
	<div id="teaser-content" class="sixteen columns">
	         
                <h1 id="page-title">
				
					<span><?php echo $title; ?></span>
					
                </h1>    
		
	</div><!-- /#teaser-content -->
    </div>	
</section><!-- /#teaser -->

<?php } ?>
<div class="clear"></div>