<?php 

header ("Content-Type:	application/javascript; charset=utf-8");
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

// Access to WordPress
require_once( $path_to_wp . '/wp-load.php' );
$themepath = get_template_directory_uri();

?>

(function($){
	
	$(document).ready(function(){
	
		$(".like_it").click(function(){
			  
			  var post_id = jQuery(this).attr("id");
			  post_id = post_id.replace("like-", "");
						
			  $.ajax({
				   type: "POST",
				   url:  "<?php echo $themepath.'/functions/ajax-request.php'; ?>",
				   data: "post_id=" + post_id + "&num=" + Math.random(),
				   success: function(data){
						jQuery("#liked-" + post_id).html(data.like);
						jQuery("#like-" + post_id).find('span').removeClass('lambda-unlike').addClass('lambda-like');
				   },
				   dataType: "json"
			  });
		 });
	 
	 });	 
	 
})(jQuery);

<?php

?>