<?php
	$operations = new RevOperations();
	
	//set Slide settings
	$arrTransitions = $operations->getArrTransition();
	
	$slideSettings = new UniteSettingsAdvancedRev();
	
	$params = array("description"=>"The appearance transition of this slide.");
	$slideSettings->addSelect("slide_transition",$arrTransitions,"Transition","random",$params);
	
	//slot amount
	$params = array("description"=>"The number of slots or boxes the slide is divided into. If you use boxfade, over 7 slots can be juggy."
		,"class"=>"small"
	);	
	$slideSettings->addTextBox("slot_amount","7","Slot Amount", $params);
	
	//transition speed
	$params = array("description"=>"The duration of the transition (Default:300, min: 100 max 2000). "
		,"class"=>"small"
	);
	$slideSettings->addTextBox("transition_duration","300","Transition Duration", $params);		
	
	//delay
	$params = array("description"=>"A new Dealy value for the Slide. If no delay defined per slide, the dealy defined via Options will be used"
		,"class"=>"small"
	);
	$slideSettings->addTextBox("delay","","Delay", $params);
	
	$slideSettings->addSelect_boolean("enable_link", "Enable Link", false, "Enable","Disable");
	
	$params = array("description"=>"A link on the whole slide pic");
	$slideSettings->addTextBox("link","","Slide Link", $params);
	
	$slideSettings->addControl("enable_link", "link", UniteSettingsRev::CONTROL_TYPE_ENABLE, "true");
	
	$params = array("description"=>"Slide Thumbnail. If not set - it will be taken from the slide image.");
	$slideSettings->addImage("slide_thumb", "","Thumbnail" , $params);
	
	//store settings
	self::storeSettings("slide_settings",$slideSettings);

?>
