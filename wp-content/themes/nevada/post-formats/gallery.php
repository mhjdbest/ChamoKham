<?php

global $lambda_content_column;

#-----------------------------------------------------------------
# Gallery Output
#-----------------------------------------------------------------
?>

<?php if ( post_password_required() ) : ?>
<?php the_content(); ?>
<?php else : ?>
<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#post-slider-<?php the_ID(); ?>").flexslider({
							animation: "fade", 
							slideshow: true,
							slideshowSpeed: 2500
							
						});
					});
				</script>
<?php $images = get_children( array( 	'post_parent' => $post->ID, 
                                                        'post_type' => 'attachment', 
                                                        'post_mime_type' => 'image', 
                                                        'orderby' => 'menu_order', 
                                                        'order' => 'ASC', 
                                                        'numberposts' => 999 ) ); ?>

<?php if(is_array($images) && !empty($images)) : ?>

<div class="thumb"> 
	<div id="post-slider-<?php the_ID(); ?>" class="flexslider post-slider">
	
	  <ul class="slides">
		
		<?php 
							
			foreach($images as $singleimage) {
				   
				   $prettyphoto = (is_single()) ? 'data-rel="prettyPhoto[postgallery]"' : '';
				   $link = (is_single()) ? $singleimage->guid : get_permalink();
				   				   	   
				   echo '<li><a href="'.$link.'" '.$prettyphoto.'><img src="'.$singleimage->guid.'" alt="'.$singleimage->post_title.'" /></a></li>';
				   
			}
			 
		?>
	  </ul>
	  
	</div>
</div>

<?php endif; ?>

<?php endif; ?>