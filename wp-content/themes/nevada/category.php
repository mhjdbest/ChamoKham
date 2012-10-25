<?php
/**
 * The template for displaying Category Archive pages.
 *
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since lambda framework v 1.0
 * based on skeleton
 */
//includes the header.php
get_header();

//includes the template-part-slider.php
get_template_part( 'template-part', 'slider' );

//includes the template-part-teaser.php
get_template_part( 'template-part', 'teaser' );

//set column layout depending if user wants to display a sidebar
lambda_before_content($columns='');

?>
	
<?php
	
	$category_description = category_description();
	if ( ! empty( $category_description ) )
	echo '' . $category_description . '';
  
get_template_part( 'loop', 'category' );

//content closer - this function can be found in functions/theme-layout-functions.php line 56-61
lambda_after_content();

//include the sidebar-page.php
get_sidebar();

//includes the footer.php
get_footer();
?>
