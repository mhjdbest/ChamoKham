<?php

/*
 * Twitter Widget 
 * lambda framework v 1.0
 * by www.unitedthemes.com
 * since framework v 1.0
 */


class WP_Widget_Twitter extends WP_Widget {
	
	protected $slug = 'lambda_twitter';
	
    function __construct() {
		$widget_ops = array('classname' => 'lambda_widget_twitter', 'description' => __( 'Displays Twitter messages by User!', UT_THEME_NAME) );
		parent::__construct('lw_twitter', __('Lambda - Twitter', UT_THEME_NAME), $widget_ops);
		$this->alt_option_name = 'lambda_widget_twitter';

	}

    function form($instance) {
	
	if ( $instance ) {
	    
		$title = esc_attr( $instance['title'] );
		
	    $twitter_users = esc_attr($instance['user']);
		$twitter_users = (!$twitter_users) ? 'unitedthemes' : $twitter_users;
		
	    $twitter_count = esc_attr($instance['count']);
	    $twitter_count = is_int($twitter_count) && (!$twitter_count) ? "5" : $twitter_count;
		
	    $twitter_txtdef = esc_attr($instance['text_default']);
	    $twitter_txtdef = (!$twitter_txtdef) ? 'i said,' : $twitter_txtdef;
		
	    $twitter_txted = esc_attr($instance['text_ed']);
	    $twitter_txted = (!$twitter_txted) ? 'i' : $twitter_txted;
		
	    $twitter_txting = esc_attr($instance['text_ing']);
	    $twitter_txting = (!$twitter_txting) ? 'i am' : $twitter_txting;
		
	    $twitter_txtrep = esc_attr($instance['text_reply']);
	    $twitter_txtrep = (!$twitter_txtrep) ? 'i replied to' : $twitter_txtrep;
		
	    $twitter_txturl = esc_attr($instance['text_url']);
	    $twitter_txturl = (!$twitter_txturl) ? 'i was looking at' : $twitter_txturl;
		
	    $twitter_txtload = esc_attr($instance['text_loading']);
	    $twitter_txtload = (!$twitter_txtload) ? 'loading tweets&#x85;' : $twitter_txtload;

	} ?>

	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', UT_THEME_NAME); ?>
	    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</label>
	<p class="description"><?php _e('The widgets title.', UT_THEME_NAME ); ?></p>
	
	<label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('Username(s):', UT_THEME_NAME); ?>
	    <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $twitter_users; ?>" />
	</label>
	<p class="description"><?php _e('Enter one or more usernames seperated by comma.', UT_THEME_NAME ); ?></p>

	<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Count:', UT_THEME_NAME); ?>
	    <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $twitter_count; ?>" />
	</label>
	<p class="description"><?php _e('How many tweets to display.', UT_THEME_NAME ); ?></p>

	<label for="<?php echo $this->get_field_id('text_loading'); ?>"><?php _e('Text loading:', UT_THEME_NAME); ?>
	    <input class="widefat" id="<?php echo $this->get_field_id('text_loading'); ?>" name="<?php echo $this->get_field_name('text_loading'); ?>" type="text" value="<?php echo $twitter_txtload; ?>" />
	</label>
	<p class="description"><?php _e('Optional loading text, displayed while tweets load.', UT_THEME_NAME ); ?></p>

	<?php
    }

    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    function widget( $args, $instance ) {

	extract( $args ); extract( $instance );

	$title = apply_filters( $this->slug, $title );
	
	$user = explode( ',', $user );
	$twitter_users = '';
		
	foreach( $user as $num => $user ){
	    $twitter_users .= !empty($user)?'"'.trim($user).'",':'';
	}
	$twitter_users = substr($twitter_users,'0',-1);
		
	if(empty($count) )
	$count = 3;	
		
	//fallback
	$text_url = (isset($text_url)) ? $text_url  : '';
	$text_loading = (empty($text_loading)) ? esc_attr($text_loading) : 'loading tweets...';
	$title = (isset($title)) ? $before_title.do_shortcode($title).$after_title  : '';
		
	
	$tscript = '<script type="text/javascript">
	jQuery(document).ready(function($){
	    $("#'.$widget_id.'-box").tweet({
			join_text: "auto",
			username: ['.$twitter_users.'],
			count: '.$count.',
			auto_join_text_url: "'.$text_url.'",
			loading_text: "'.$text_loading.'",
			ul_class: "sidebar_tweet"
	    });
	});
    </script>';


	echo "
	$before_widget
	    $tscript
		$title
		<div id=\"$widget_id-box\"></div> 
	$after_widget";
    }

}

add_action( 'widgets_init', create_function( '', 'return register_widget("WP_Widget_Twitter");' ) );

?>