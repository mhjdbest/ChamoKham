<?php

	class GlobalsRevSlider{
		
		const TABLE_SLIDERS_NAME = "revslider_sliders";
		const TABLE_SLIDES_NAME = "revslider_slides";
		const FIELDS_SLIDE = "slider_id,slide_order,params,layers";
		
		public static $table_sliders;
		public static $table_slides;						
		public static $filepath_captions;
		public static $filepath_captions_original;
		public static $urlCaptionsCSS;
	}

?>