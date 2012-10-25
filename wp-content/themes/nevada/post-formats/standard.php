<?php

global $lambda_content_column;

#-----------------------------------------------------------------
# Image Output
#-----------------------------------------------------------------

global $columns;

if(has_post_thumbnail(get_the_ID())) : ?>
<div class="thumb">
	<div class="post-image">	
		
		
		<div class="overflow-hidden imagepost">
		
		<?php 
			
			$imgID = get_post_thumbnail_id($post->ID);		
			$url = wp_get_attachment_url( $imgID ); 
			$alt = get_post_meta($imgID , '_wp_attachment_image_alt', true);

		?>
				
		<a title="<?php echo get_the_title(); ?>" data-rel="prettyPhoto" href="<?php echo $url; ?>"><img src="<?php echo $url; ?>" alt="<?php echo trim( strip_tags($alt) ); ?>" /></a>
		
		
		</div>
	</div>
</div>
<?php endif; ?>