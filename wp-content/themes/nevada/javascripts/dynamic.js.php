<?php

/*
 * dynamic JavaScript Generator
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since framework v 2.1
 */

$browser = (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) ? true : false;
 
header ("Content-Type:	application/javascript; charset=utf-8");
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

// Access to WordPress
require_once( $path_to_wp . '/wp-load.php' );
$themepath = get_template_directory_uri();

#-----------------------------------------------------------------
# Start Java Output
#-----------------------------------------------------------------
?>

(function($){
		
	$(document).ready(function(){	
		
		<?php if( is_home() ) { ?>
		
		$(".imagepost").stop().hover(function(){						
													
			$(this).find('.hover-overlay').stop().fadeIn(250);
							
						  
		}, function () {
							
			$(this).find('.hover-overlay').stop().fadeOut(250);						
							
		});	
		
		<?php } ?>
		
	});	
	
})(jQuery);