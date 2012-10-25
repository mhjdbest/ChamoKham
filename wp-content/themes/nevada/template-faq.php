<?php
/**
 * Template Name: FAQ Template
 *
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since lambda framework v 1.0
 * based on skeleton
*/

global $lambda_meta_data;

$meta_sidebar = $lambda_meta_data->get_the_value('sidebar');
$meta_sidebar = (!empty( $meta_sidebar )) ? $meta_sidebar : get_option_tree( 'select_sidebar' );

//includes the header.php
get_header();

//includes the template-part-slider.php
get_template_part( 'template-part', 'slider' );

//includes the template-part-teaser.php
get_template_part( 'template-part', 'teaser' );

//set column layout depending if user wants to display a sidebar
if($meta_sidebar != UT_THEME_INITIAL.'sidebar_none') {

	lambda_before_content($columns='');
	
} elseif($meta_sidebar == UT_THEME_INITIAL.'sidebar_none') {
	
	lambda_before_content($columns='sixteen');
	
} ?>




<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<section>
	<article>
		<?php the_content(); ?>
	</article>
</section>
    
<?php endwhile; // end of the loop. ?>


<?php
#-----------------------------------------------------------------
# FAQ optional information
#-----------------------------------------------------------------
?>

<?php 

//retrieve faq items
$faqitems = $lambda_meta_data->get_the_value(UT_THEME_INITIAL.'faq_items');


//check if faq items exists
if(is_array($faqitems)) { ?>

	<section class="faq clearfix">
	
	<?php foreach($faqitems as $item) { ?>

	<article class="list"><h3 class="trigger"><a href="#"><?php echo (isset($item['faq_question'])) ? lambda_translate_meta($item['faq_question']) : ''; ?></a></h3>
	<div class="toggle_container"><div class="block"><?php echo ( isset($item['faq_answer']) ) ? apply_filters( 'the_content', do_shortcode($item['faq_answer'])) : ''; ?></div></div></article>

	<?php } ?>

	</section>
	<div class="clear"></div>
	
<?php } ?>

<?php echo do_shortcode(apply_filters( 'the_content',$lambda_meta_data->get_the_value('faq_additional_content'))); ?>
                       
<?php
//content closer - this function can be found in functions/theme-layout-functions.php line 56-61
lambda_after_content();

//include the sidebar.php
if(empty($columns))
get_sidebar();

//includes the footer.php
get_footer();
?>
