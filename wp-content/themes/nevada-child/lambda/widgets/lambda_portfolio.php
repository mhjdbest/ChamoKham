<?php

/*
 * Portfolio Slider Widget 
 * lambda framework v 1.0
 * by www.unitedthemes.com
 * since framework v 1.0
 */

class WP_Widget_Portfolio extends WP_Widget {

	function __construct() {
		
		$widget_ops = array('classname' => 'lambda_widget_portfolio', 'description' => __( 'Displays your latest Portfolio Work!', UT_THEME_NAME) );
		parent::__construct('lw_portfolio', __('Lambda - Portfolio Slider', UT_THEME_NAME), $widget_ops);
		$this->alt_option_name = 'lambda_widget_portfolio';

	}
    function form($instance) {
?>

    <label><?php _e('Title', UT_THEME_NAME); ?>: <input type="text" style="width:100%;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($instance['title']); ?>" /></label>
    <label><?php _e('Number of Items', UT_THEME_NAME); ?>: <input type="text" style="width:100%;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo esc_attr($instance['number']); ?>" /></label>

<?php

    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function widget( $args, $instance ){

	extract( $args );
	extract( $instance );
	
	if( $title )
	    $title = $before_title.do_shortcode($title).$after_title;
	
	$posts = &get_posts( array( 'post_type' => UT_PORTFOLIO_SLUG, 'numberposts' => $number, 'orderby' => 'date', 'order' => 'DESC' ) );
    if ( $posts ) {
              $count = 0;
              $text = '<div class="widget_flexslider"><ul class="slides">';
              foreach ( $posts as $post ) 
              {
                  $url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
				  $image = aq_resize($url, '420', '160', true);
				  $text .= '<li><a href="'.get_permalink($post->ID).'"><img src="'.$image.'"></a></li>';		  
				  $count++;
              }
              $text .= '</ul></div>';
    }
	
	
	echo "$before_widget";
    		widget_flexslider();
	echo "$title $text
		  $after_widget";
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("wp_widget_portfolio");' ) );
?>