<?php
	$settingsLayerOutput = new UniteSettingsProductSidebarRev();
	$settingsSlideOutput = new UniteSettingsRevProductRev();
	
	//get the slide object
	$slideID = UniteFunctionsRev::getGetVar("id");
	$slide = new RevSlide();
	$slide->initByID($slideID);
	$slideParams = $slide->getParams();
	
	$operations = new RevOperations();
	
	$arrLayers = $slide->getLayers();

	//get slider object
	$sliderID = $slide->getSliderID();
	$slider = new RevSlider();
	$slider->initByID($sliderID);
	$sliderParams = $slider->getParams();
	
	//get settings objects
	$settingsLayer = self::getSettings("layer_settings");	
	$settingsSlide = self::getSettings("slide_settings");
	
	$cssContent = self::getSettings("css_captions_content");
	$arrCaptionClasses = $operations->getArrCaptionClasses($cssContent);
	
	$arrButtonClasses = $operations->getButtonClasses();
	
	//set layer caption as first caption class
	$firstCaption = !empty($arrCaptionClasses)?$arrCaptionClasses[0]:"";
	$settingsLayer->updateSettingValue("layer_caption",$firstCaption);
	
	//set stored values from "slide params"
	$settingsSlide->setStoredValues($slideParams);
		
	//init the settings output object
	$settingsLayerOutput->init($settingsLayer);
	$settingsSlideOutput->init($settingsSlide);
	
	//set various parameters needed for the page
	$width = $sliderParams["width"];
	$height = $sliderParams["height"];
	$imageUrl = $slide->getImageUrl();
		
	$imageFilename = $slide->getImageFilename();
	$urlCaptionsCSS = GlobalsRevSlider::$urlCaptionsCSS;
	
	$style = "width:{$width}px;height:{$height}px;background-image:url('$imageUrl')";	
	$closeUrl = self::getViewUrl(RevSliderAdmin::VIEW_SLIDES,"id=".$sliderID);
	
	$jsonLayers = UniteFunctionsRev::jsonEncodeForClientSide($arrLayers);
	$jsonCaptions = UniteFunctionsRev::jsonEncodeForClientSide($arrCaptionClasses);
	
	require self::getPathTemplate("slide");
?>
	
