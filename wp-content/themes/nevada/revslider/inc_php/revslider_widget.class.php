<?php
 
class RevSlider_Widget extends WP_Widget {
	
    public function __construct(){
    	
        // widget actual processes
     	$widget_ops = array('classname' => 'widget_revslider', 'description' => __('Displays a revolution slider on the page', UT_THEME_NAME) );
        parent::__construct('rev-slider-widget', __('Revolution Slider', UT_THEME_NAME), $widget_ops);
    }
 
    /**
     * 
     * the form
     */
    public function form($instance) {
	
		$slider = new RevSlider();
    	$arrSliders = $slider->getArrSlidersShort();
    	
    	$sliderID = UniteFunctionsRev::getVal($instance, "rev_slider");
    	
		if(empty($arrSliders))
			echo __("No sliders found, Please create a slider", UT_THEME_NAME);
		else{
			$field = "rev_slider";
			$fieldID = $this->get_field_id( $field );
			$fieldName = $this->get_field_name( $field );

			$select = UniteFunctionsRev::getHTMLSelect($arrSliders,$sliderID,'name="'.$fieldName.'" id="'.$fieldID.'"',true);
		}
		echo "Choose slider: ";
		echo $select;
    }
 
    /**
     * 
     * update
     */
    public function update($new_instance, $old_instance) {
    	
        return($new_instance);
    }

    
    /**
     * 
     * widget output
     */
    public function widget($args, $instance) {
		$sliderID = $instance["rev_slider"];
		if(empty($sliderID))
			return(false);
			
		RevSliderOutput::putSlider($sliderID);
    }
 
}


?>