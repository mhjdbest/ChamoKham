<?php

	class RevSliderAdmin extends UniteBaseAdminClassRev{
		
		const DEFAULT_VIEW = "sliders";
		
		const VIEW_SLIDER = "slider";
		const VIEW_SLIDERS = "sliders";
		
		const VIEW_SLIDES = "slides";
		const VIEW_SLIDE = "slide";
		
		
		/**
		 * 
		 * the constructor
		 */
		public function __construct($mainFilepath){
			
			parent::__construct($mainFilepath,$this,self::DEFAULT_VIEW);
			
			//set table names
			GlobalsRevSlider::$table_sliders = self::$table_prefix.GlobalsRevSlider::TABLE_SLIDERS_NAME;
			GlobalsRevSlider::$table_slides = self::$table_prefix.GlobalsRevSlider::TABLE_SLIDES_NAME;
			GlobalsRevSlider::$filepath_captions = self::$path_plugin."rs-plugin/css/captions.css";
			GlobalsRevSlider::$filepath_captions_original = self::$path_plugin."rs-plugin/css/captions-original.css";
			GlobalsRevSlider::$urlCaptionsCSS = self::$url_plugin."rs-plugin/css/captions.css";
			
			$this->init();
		}
		
		
		/**
		 * 
		 * init all actions
		 */
		private function init(){
			
			//self::setDebugMode();
			
			self::addMenuPage('Revolution Slider', "adminPages");
			
			//add common scripts there
			//self::addAction(self::ACTION_ADMIN_INIT, "onAdminInit");
			
			//ajax response to save slider options.
			self::addActionAjax("ajax_action", "onAjaxAction");
		}
		
		
		/**
		 * a must function. please don't remove it.
		 * process activate event - install the db (with delta).
		 */
		public static function onActivate(){
			
			self::createTable(GlobalsRevSlider::TABLE_SLIDERS_NAME);
			self::createTable(GlobalsRevSlider::TABLE_SLIDES_NAME);
		}
		
		
		/**
		 * 
		 * a must function. adds scripts on the page
		 * add all page scripts and styles here.
		 * pelase don't remove this function
		 * common scripts even if the plugin not load, use this function only if no choise.
		 */
		public static function onAddScripts(){
			self::addStyle("edit_layers","edit_layers");
			
			//add google font
			//$urlGoogleFont = "http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700";					
			//self::addStyleAbsoluteUrl($urlGoogleFont,"google-font-pt-sans-narrow");
			
			self::addScriptCommon("edit_layers","unite_layers");
			
			//include all media upload scripts
			self::addMediaUploadIncludes();
			
			//add rs css:					
			self::addStyle("settings","rs-plugin-settings","rs-plugin/css");
			self::addStyle("captions","rs-plugin-captions","rs-plugin/css");
		}
		
		
		
		/**
		 * 
		 * admin main page function.
		 */
		public static function adminPages(){
			
			parent::adminPages();
			
			self::addScript("rev_admin");
						
			//require styles by view
			switch(self::$view){
				case self::VIEW_SLIDERS:
				case self::VIEW_SLIDER:
					self::requireSettings("slider_settings");
				break;
				case self::VIEW_SLIDES:					
				break;
				case self::VIEW_SLIDE:
					self::requireSettings("slide_settings");
					self::requireSettings("layer_settings");
				break;
			}
			
			self::setMasterView("master_view");
			self::requireView(self::$view);
		}

		
		
		/**
		 * 
		 * craete tables
		 */
		public static function createTable($tableName){
			
			//if table exists - don't create it.
			$tableRealName = self::$table_prefix.$tableName;
			if(UniteFunctionsWPRev::isDBTableExists($tableRealName))
				return(false);
			
			switch($tableName){
				case GlobalsRevSlider::TABLE_SLIDERS_NAME:					
				$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
							  id int(9) NOT NULL AUTO_INCREMENT,					  
							  title tinytext NOT NULL,
							  alias tinytext,
							  params text NOT NULL,
							  PRIMARY KEY (id)
							);";
				break;
				case GlobalsRevSlider::TABLE_SLIDES_NAME:
					$sql = "CREATE TABLE " .self::$table_prefix.$tableName ." (
								  id int(9) NOT NULL AUTO_INCREMENT,
								  slider_id int(9) NOT NULL,
								  slide_order int not NULL,					  
								  params text NOT NULL,
								  layers text NOT NULL,
								  PRIMARY KEY (id)
								);";
				break;
				default:
					UniteFunctionsRev::throwError("table: $tableName not found");
				break;
			}
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}

		/**
		 * 
		 * onAjax action handler
		 */
		public static function onAjaxAction(){
			
			$slider = new RevSlider();
			$slide = new RevSlide();
			$operations = new RevOperations();
			
			$action = self::getPostVar("client_action");
			$data = self::getPostVar("data");
			
			try{
				
				switch($action){
					case "create_slider":
						$newSliderID = $slider->createSliderFromOptions($data);
						
						self::ajaxResponseSuccessRedirect(
						            "The slider successfully created", 
									self::getViewUrl("sliders"));
						
					break;
					case "update_slider":
						$slider->updateSliderFromOptions($data);
						self::ajaxResponseSuccess("Slider updated");
					break;
					
					case "delete_slider":
						
						$slider->deleteSliderFromData($data);
						
						self::ajaxResponseSuccessRedirect(
						            "The slider deleted", 
									self::getViewUrl(self::VIEW_SLIDERS));
					break;
					case "add_slide":
						$slider->createSlideFromData($data);
						$sliderID = $data["sliderid"];
						
						self::ajaxResponseSuccessRedirect(
						            "Slide Created", 
									self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
					break;
					case "update_slide":
						$slide->updateSlideFromData($data);
						self::ajaxResponseSuccess("Slide updated");
					break;
					case "delete_slide":
						$slide->deleteSlideFromData($data);
						$sliderID = UniteFunctionsRev::getVal($data, "sliderID");
						self::ajaxResponseSuccessRedirect(
						            "Slide Deleted Successfully", 
									self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));					
					break;
					case "duplicate_slide":
						$sliderID = $slider->duplicateSlideFromData($data);
						self::ajaxResponseSuccessRedirect(
						            "Slide Duplicated Successfully", 
									self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));											
					break;
					case "get_captions_css":
						$contentCSS = $operations->getCaptionsContent();
						self::ajaxResponseData($contentCSS);
					break;
					case "update_captions_css":
						$arrCaptions = $operations->updateCaptionsContentData($data);
						self::ajaxResponseSuccess("CSS file saved succesfully!",array("arrCaptions"=>$arrCaptions));
					break;
					case "restore_captions_css":
						$operations->restoreCaptionsCss();
						$contentCSS = $operations->getCaptionsContent();
						self::ajaxResponseData($contentCSS);
					break;
					case "update_slides_order":
						$slider->updateSlidesOrderFromData($data);
						self::ajaxResponseSuccess("Order updated successfully");
					break;
					case "change_slide_image":
						$slide->updateSlideImageFromData($data);
						$sliderID = UniteFunctionsRev::getVal($data, "slider_id");						
						self::ajaxResponseSuccessRedirect(
						            "Slide Changed Successfully", 
									self::getViewUrl(self::VIEW_SLIDES,"id=$sliderID"));
					break;					
					default:
						self::ajaxResponseError("wrong ajax action: <b>$action</b> ");
					break;
				}
				
			}
			catch(Exception $e){
				$message = $e->getMessage();
				
				self::ajaxResponseError($message);
			}
			
			//it's an ajax action, so exit
			self::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
			exit();
		}
		
	}
	
	
?>