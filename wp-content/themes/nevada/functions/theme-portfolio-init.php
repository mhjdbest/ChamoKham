<?php
#-----------------------------------------------------------------
# create admin portfolio section
#----------------------------------------------------------------- 
if ( ! function_exists( 'portfolio_register' ) ) {

	function portfolio_register() {  
		
		 global $theme_path;
		 
		 $args = array(
				'hierarchical' => true,
				'label' => __('Portfolio', UT_THEME_NAME),
				'singular_label' => __('Project', UT_THEME_NAME),
				'public' => true,
				'show_ui' => true,
				'capability_type' => 'post',
				'menu_position' => 9,
				'rewrite' => true,
				'menu_icon' => FRAMEWORK_DIRECTORY . 'assets/images/icons/portfolio.png',
				'supports' => array('title', 'editor', 'thumbnail')
		);  
	  
		register_post_type( UT_PORTFOLIO_SLUG , $args );  
	}  
	add_action('init', 'portfolio_register');

}
#-----------------------------------------------------------------
# an new taxonomy for displaying portfolio categories
#----------------------------------------------------------------- 
register_taxonomy("project-type", 
		array(UT_PORTFOLIO_SLUG), 
		array("hierarchical" => true, 
				"label" => __( 'Project Categories', UT_THEME_NAME), 
				"singular_label" => __( 'Project Category', UT_THEME_NAME), 
				"rewrite" => true)
);
?>