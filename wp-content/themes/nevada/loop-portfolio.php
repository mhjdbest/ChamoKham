<?php
/* Portfolio Archive
 *
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since lambda framework v 1.0
 * 
 */
global $lambda_meta_data, $theme_path, $theme_options;

$metadata = $lambda_meta_data->the_meta();
$projectatts = $lambda_meta_data->the_meta();
$projectatts_exists = ( isset($projectatts[UT_THEME_INITIAL.'project_atts']) && is_array($projectatts[UT_THEME_INITIAL.'project_atts']) ) ? true : false;
?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
								

				<div id="post-<?php the_ID(); ?>" <?php post_class('single'); ?>>
                
               
                    <div id="nav-portfolio" class="navigation clearfix">
						
						<?php 
						
						//identify portfolio item position in array
						$next = 'nav-next';
						$prev = 'nav-previous';
						
						?>
											
						<div class="<?php echo $next ?> "><?php next_post_link( '%link', _x('Next', UT_THEME_NAME )); ?></div>
                    	<div class="<?php echo $prev ?> "><?php previous_post_link( '%link', _x('Previous', UT_THEME_NAME )); ?></div>
                        
                        
					</div><!-- #nav-portfolio -->
                
                  			
                <?php
				$metadata = $lambda_meta_data->the_meta();
				
				if($metadata['portfolio_type'] == 'video_portfolio') {
					get_template_part( 'post-formats/video' );
				}
				
				if($metadata['portfolio_type'] == 'audio_portfolio') {
					get_template_part( 'post-formats/audio' );
				}
				
				if($metadata['portfolio_type'] == 'image_portfolio' && is_array($metadata[UT_THEME_INITIAL.'portfolio_images'])) {
					callFlexslider('portfolio');
				}
				
				if(($metadata['portfolio_type'] == 'single_image_portfolio')) { ?>
					
					<div class="thumb">
                    	
						
							<?php 

								$url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); 
								$image = aq_resize( $url, 940 );								
								
							?>
							
							<a href="<?php echo $url; ?>" data-rel="prettyPhoto">
								<img src="<?php echo $image; ?>"/>
							</a>						
                        
					</div>
                    
                    
				<?php } ?>
				
            <div class="clear"></div>
            
                       
                <div class="entry-content portfolio-content <?php echo ($projectatts_exists) ? 'twelve columns alpha' : 'full-width'; ?> clearfix">  
                	
					<?php if(isset($metadata['pcontent_title'])) { ?>
					
						<h3 class="home-title"><span><?php echo $metadata['pcontent_title']; ?></span></h3>
					
					<?php } ?>
					
					<?php the_content(); ?>
                
				</div>            
                
				<?php if($projectatts_exists) : ?>
				         
                <div class="four columns p-info-wrap omega">
            	
            	<div class="portfolio-info">
            			
					<?php if(isset($metadata['work_title'])) { ?>
					
						<h3 class="home-title"><span><?php echo $metadata['work_title']; ?></span></h3>
					
					<?php } ?>
													
				<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', UT_THEME_NAME ), 'after' => '</div>' ) ); ?>              
                
                		<?php
						#-----------------------------------------------------------------
						# project attributes like Client: Google.com or Location: Los Angeles
						#-----------------------------------------------------------------
						
						if($projectatts_exists) {
						foreach ($projectatts[UT_THEME_INITIAL.'project_atts'] as $item)	{
							echo "<p>".$item['work_title'];
							if(isset($item['is_link'])) {
								echo '</br><span><a href="'.$item['work_desc'].'" target="_blank">'.__('Visit Site', UT_THEME_NAME).'</a></span>';
							} else {
								echo "</br><span>".do_shortcode($item['work_desc'])."</span>","</p>";		
							}
						}}	
											
						?>
                        
                        
                </div><!-- #portfolio-info -->
                
                </div><!-- #four columns -->
				
				<?php endif; ?>
                
                </div><!-- #post-## -->
                
                         
                <div class="clear"></div>
                
                <div class="edit-link-wrap">
						<?php edit_post_link( __( 'Edit', UT_THEME_NAME ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-utility -->       
				
				<?php comments_template( '', true ); ?>

<?php endwhile; // end of the loop. ?>