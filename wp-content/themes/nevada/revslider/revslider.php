<?php
/*
Plugin Name: Revolution Slider
Plugin URI: http://www.themepunch.com/codecanyon/revolution_wp/
Description: Revolution Slider - Premium responsitive slider
Author: ThemePunch
Version: 1.3.6
Author URI: http://themepunch.com
*/

$currentFile = __FILE__;
$currentFolder = dirname($currentFile);

//include frameword files
require_once $currentFolder . '/inc_php/include_framework.php';

//include bases
require_once $folderIncludes . 'base.class.php';
require_once $folderIncludes . 'elements_base.class.php';
require_once $folderIncludes . 'base_admin.class.php';
require_once $folderIncludes . 'base_front.class.php';

//include product files
require_once $currentFolder . '/inc_php/revslider_settings_product.class.php';
require_once $currentFolder . '/inc_php/revslider_globals.class.php';
require_once $currentFolder . '/inc_php/revslider_operations.class.php';
require_once $currentFolder . '/inc_php/revslider_slider.class.php';
require_once $currentFolder . '/inc_php/revslider_output.class.php';
require_once $currentFolder . '/inc_php/revslider_slide.class.php';
require_once $currentFolder . '/inc_php/revslider_widget.class.php';


try{
	
	//register the kb slider widget	
	UniteFunctionsWPRev::registerWidget("RevSlider_Widget");
	
	//add shortcode
	function rev_slider_shortcode($args){
		$sliderAlias = UniteFunctionsRev::getVal($args,0);
		ob_start();
		RevSliderOutput::putSlider($sliderAlias);
		$content = ob_get_contents();
		ob_clean();
		return($content);
	}
	add_shortcode( 'rev_slider', 'rev_slider_shortcode' );	
	
	
	if(is_admin()){		//load admin part
		require_once $currentFolder."/revslider_admin.php";		
		
		$productAdmin = new RevSliderAdmin($currentFile);
		
	}else{		//load front part
		require_once $currentFolder."/revslider_front.php";
		$productFront = new RevSliderFront($currentFile);
		
		/**
		 * 
		 * put kb slider on the page.
		 * the data can be slider ID or slider alias.
		 */
		function putRevSlider($data){
			RevSliderOutput::putSlider($data);
		}
		
	}

	
}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "KB Slider Error: <b>".$message."</b>";
}
	
