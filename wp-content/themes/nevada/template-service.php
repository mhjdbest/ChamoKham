<?php
/**
 * Template Name: Service Template
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

//content opener - this function can be found in functions/theme-layout-functions.php line 5-50
lambda_before_content($columns='sixteen');

?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<section>
	<article>
		<?php the_content(); ?>
	</article>
</section>
    
<?php endwhile; // end of the loop. ?>


<?php
#-----------------------------------------------------------------
# Testimonials
#-----------------------------------------------------------------
?>

<?php 

//retrieve faq items
$tab_items = $lambda_meta_data->get_the_value(UT_THEME_INITIAL.'verticaltabs');

//check if faq items exists
if(is_array($tab_items)) { ?>
	
	<script type="text/javascript">
	jQuery(document).ready(function($){
	
		var $items = $('#vmenu>ul>li');
			$items.click(function() {
	    	$items.removeClass('selected');
	    
		$(this).addClass('selected');
	 
	    var index = $items.index($(this));
	    
		$('#vtabs > div').hide().eq(index).show();
		
		}).eq(0).click();
		
	});
	</script>
	
	<section class="verticaltabs-wrap clearfix">

	<div id="vtab" class="clearfix">	
	
    
		<div class="one_fourth" id="vmenu">
			<ul>
			<?php $z=1; foreach($tab_items as $tab) { ?>
					
					<li class="tab tab_<?php echo $z; ?>"><h3><?php echo isset($tab['tab_name']) ? lambda_translate_meta($tab['tab_name']) : ''; ?></h3></li>
			
			<?php $z++; } ?>	
			</ul>
		</div>    
		
		<div id="vtabs">
		
		<?php $z=1; foreach($tab_items as $tab) { ?>	
		
			<div class="three_fourths last entry-content"><?php echo (isset($tab['tab_content'])) ? do_shortcode(apply_filters('the_content', $tab['tab_content'])): ''; ?></div>
		
		<?php $z++; } ?>	
		
		</div>
	
	</div>
	
	</section>
	<div class="clear"></div>
	
<?php } ?>
                       
<?php
//content closer - this function can be found in functions/theme-layout-functions.php line 56-61
lambda_after_content();

//includes the footer.php
get_footer();
?>
