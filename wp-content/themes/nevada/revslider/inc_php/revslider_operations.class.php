<?php

	class RevOperations extends UniteElementsBaseRev{

		
		/**
		 * 
		 * get button classes
		 */
		public function getButtonClasses(){
			
			$arrButtons = array(
				"red"=>"Red Button",
				"green"=>"Green Button",
				"blue"=>"Blue Button",
				"orange"=>"Orange Button",
				"darkgrey"=>"Darkgrey Button",
				"lightgrey"=>"Lightgrey Button",
			);
			
			return($arrButtons);
		}
		
		/**
		 * 
		 * get easing functions array
		 */
		public function getArrEasing(){
			
			$arrEasing = array(
				"easeOutBack",
				"easeInQuad",
				"easeOutQuad",
				"easeInOutQuad",
				"easeInCubic",
				"easeOutCubic",
				"easeInOutCubic",
				"easeInQuart",
				"easeOutQuart",
				"easeInOutQuart",
				"easeInQuint",
				"easeOutQuint",
				"easeInOutQuint",
				"easeInSine",
				"easeOutSine",
				"easeInOutSine",
				"easeInExpo",
				"easeOutExpo",
				"easeInOutExpo",
				"easeInCirc",
				"easeOutCirc",
				"easeInOutCirc",
				"easeInElastic",
				"easeOutElastic",
				"easeInOutElastic",
				"easeInBack",
				"easeOutBack",
				"easeInOutBack",
				"easeInBounce",
				"easeOutBounce",
				"easeInOutBounce"
			);
			
			$arrEasing = UniteFunctionsRev::arrayToAssoc($arrEasing);
			
			return($arrEasing);
		}
		
		
		/**
		 * 
		 * get transition array
		 */
		public function getArrTransition(){
			
			$arrTransition = array(
				"random",
				"slidehorizontal",
				"slidevertical",
				"boxslide",
				"boxfade",
				"slotzoom-horizontal",
				"slotslide-horizontal",
				"slotfade-horizontal",
				"slotzoom-vertical",
				"slotslide-vertical",
				"slotfade-vertical",
				"curtain-1",
				"curtain-2",
				"curtain-3",
				"slideleft",
				"slideright",
				"slideup",
				"slidedown",
				"fade"
			);
			
			$arrTransition = UniteFunctionsRev::arrayToAssoc($arrTransition);
			
			return($arrTransition);
		}
		
		
		/**
		 * 
		 * get random transition
		 */
		public static function getRandomTransition(){
			$arrTrans = self::getArrTransition();
			unset($arrTrans["random"]);
			$trans = array_rand($arrTrans);
			
			return($trans);
		}
		
		
		/**
		 * 
		 * get animations array
		 */
		public function getArrAnimations(){
			
			$arrAnimations = array(
				"fade"=>"Fade",
				"sft"=>"Short from Top",
				"sfb"=>"Short from Bottom",
				"sfr"=>"Short from Right",
				"sfl"=>"Short from Left",
				"lft"=>"Long from Top",
				"lfb"=>"Long from Bottom",
				"lfr"=>"Long from Right",
				"lfl"=>"Long from Left"
			);
			
			return($arrAnimations);
		}
		
		
		/**
		 * 
		 * parse css file and get the classes from there.
		 */
		public function getArrCaptionClasses($contentCSS){
			//parse css captions file
			$parser = new UniteCssParserRev();
			$parser->initContent($contentCSS);
			$arrCaptionClasses = $parser->getArrClasses();
			return($arrCaptionClasses);
		}
		
		/**
		 * 
		 * get the select classes html for putting in the html by ajax 
		 */
		private function getHtmlSelectCaptionClasses($contentCSS){
			$arrCaptions = $this->getArrCaptionClasses($contentCSS);
			$htmlSelect = UniteFunctionsRev::getHTMLSelect($arrCaptions,"","id='layer_caption' name='layer_caption'",true);
			return($htmlSelect);
		}
		
		/**
		 * 
		 * get contents of the css file
		 */
		public function getCaptionsContent(){
			$contentCSS = file_get_contents(GlobalsRevSlider::$filepath_captions);
			return($contentCSS);
		}
		
		
		/**
		 * 
		 * update captions css file content
		 * @return new captions html select 
		 */
		public function updateCaptionsContentData($content){
			$content = stripslashes($content);
			$content = trim($content);
			UniteFunctionsRev::writeFile($content, GlobalsRevSlider::$filepath_captions);
			
			//output captions array 
			$arrCaptions = $this->getArrCaptionClasses($content);
			return($arrCaptions);
		}
		
		/**
		 * 
		 * copy from original css file to the captions css.
		 */
		public function restoreCaptionsCss(){
			
			if(!file_exists(GlobalsRevSlider::$filepath_captions_original))
				UniteFunctionsRev::throwError("The original css file: captions_original.css doesn't exists.");
			
			$success = @copy(GlobalsRevSlider::$filepath_captions_original, GlobalsRevSlider::$filepath_captions);
			if($success == false)
				UniteFunctionsRev::throwError("Failed to restore from the original captions file.");
		}
		
	}

?>