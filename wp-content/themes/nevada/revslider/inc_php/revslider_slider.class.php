<?php

	class RevSlider extends UniteElementsBaseRev{
		
		private $id;
		private $title;
		private $alias;
		private $arrParams;
		private $arrSlides = null;
		
		public function __construct(){
			parent::__construct();
		}
		
		/**
		 * 
		 * validate that the slider is inited. if not - throw error
		 */
		private function validateInited(){
			if(empty($this->id))
				UniteFunctionsRev::throwError("The slider is not inited!");
		}
		
		/**
		 * 
		 * init slider by db data
		 * 
		 */
		public function initByDBData($arrData){
			
			$this->id = $arrData["id"];
			$this->title = $arrData["title"];
			$this->alias = $arrData["alias"];
			
			$params = $arrData["params"];
			$params = (array)json_decode($params);
			
			$this->arrParams = $params;
		}
		
		
		/**
		 * 
		 * init the slider object by database id
		 */
		public function initByID($sliderID){
			UniteFunctionsRev::validateNumeric($sliderID,"Slider ID");
			$sliderID = $this->db->escape($sliderID);
			
			$sliderData = $this->db->fetchSingle(GlobalsRevSlider::$table_sliders,"id=$sliderID");
			$this->initByDBData($sliderData);
			
		}

		/**
		 * 
		 * init slider by alias
		 */
		public function initByAlias($alias){
			$alias = $this->db->escape($alias);
			$sliderData = $this->db->fetchSingle(GlobalsRevSlider::$table_sliders,"alias='$alias'");
			
			$this->initByDBData($sliderData);
		}
		
		
		/**
		 * 
		 * init by id or alias
		 */
		public function initByMixed($mixed){
			if(is_numeric($mixed))
				$this->initByID($mixed);
			else
				$this->initByAlias($mixed);
		}
		
		
		/**
		 * 
		 * get data functions
		 */
		public function getTitle(){
			return($this->title);
		}
		
		public function getID(){
			return($this->id);
		}
		
		public function getParams(){
			return($this->arrParams);
		}
		
		/**
		 * 
		 * get parameter from params array. if no default, then the param is a must!
		 */
		function getParam($name,$default=null){
			if($default == null){
				if(!array_key_exists($name, $this->arrParams))
					UniteFunctionsRev::throwError("The param <b>$name</b> not found in slider params.");
				$default = "";
			}
				
			return UniteFunctionsRev::getVal($this->arrParams, $name,$default);
		}
		
		public function getAlias(){
			return($this->alias);
		}
		
		/**
		 * get combination of title (alias)
		 */
		public function getShowTitle(){
			$showTitle = $this->title." ($this->alias)";
			return($showTitle);
		}
		
		/**
		 * 
		 * get slider shortcode
		 */
		public function getShortcode(){
			$shortCode = "[rev_slider {$this->alias}]";
			return($shortCode);
		}
		
		
		/**
		 * 
		 * check if alias exists in DB
		 */
		private function isAliasExistsInDB($alias){
			$alias = $this->db->escape($alias);
			
			$where = "alias='$alias'";
			if(!empty($this->id))
				$where .= " and id != '{$this->id}'";
			
			$response = $this->db->fetch(GlobalsRevSlider::$table_sliders,$where);
			return(!empty($response));
			
		}
		
		
		/**
		 * 
		 * validate settings for add
		 */
		private function validateInputSettings($title,$alias,$params){
			UniteFunctionsRev::validateNotEmpty($title,"title");
			UniteFunctionsRev::validateNotEmpty($alias,"alias");
			
			if($this->isAliasExistsInDB($alias))
				UniteFunctionsRev::throwError("Some other slider with alias '$alias' already exists");
		}
		
		
		/**
		 * 
		 * create / update slider from options
		 */
		private function createUpdateSliderFromOptions($options,$sliderID = null){
			
			$arrMain = UniteFunctionsRev::getVal($options, "main");
			$params = UniteFunctionsRev::getVal($options, "params");
			
			//trim all input data
			$arrMain = UniteFunctionsRev::trimArrayItems($arrMain);
			$params = UniteFunctionsRev::trimArrayItems($params);
			
			$params = array_merge($arrMain,$params);
			
			$title = UniteFunctionsRev::getVal($arrMain, "title");
			$alias = UniteFunctionsRev::getVal($arrMain, "alias");
			
			if(!empty($sliderID))
				$this->initByID($sliderID);
				
			$this->validateInputSettings($title, $alias, $params);
			
			$jsonParams = json_encode($params);
			
			//insert slider to database
			$arrData = array();
			$arrData["title"] = $title;
			$arrData["alias"] = $alias;
			$arrData["params"] = $jsonParams;
			
			if(empty($sliderID)){	//create slider	
				$sliderID = $this->db->insert(GlobalsRevSlider::$table_sliders,$arrData);
				return($sliderID);
				
			}else{	//update slider
				$this->initByID($sliderID);
				
				$sliderID = $this->db->update(GlobalsRevSlider::$table_sliders,$arrData,array("id"=>$sliderID));				
			}
		}
		
		/**
		 * 
		 * delete slider from datatase
		 */
		private function deleteSlider(){			
			
			$this->validateInited();
			
			//delete slider
			$this->db->delete(GlobalsRevSlider::$table_sliders,"id=".$this->id);
			
			//delete slides
			$this->db->delete(GlobalsRevSlider::$table_slides,"slider_id=".$this->id);
		}
		
		
		/**
		 * 
		 * create slider in database from options
		 */
		public function createSliderFromOptions($options){
			$sliderID = $this->createUpdateSliderFromOptions($options);
			return($sliderID);			
		}
		
		
		/**
		 * 
		 * update slider from options
		 */
		public function updateSliderFromOptions($options){
			
			$sliderID = UniteFunctionsRev::getVal($options, "sliderid");
			UniteFunctionsRev::validateNotEmpty($sliderID,"Slider ID");
			
			$this->createUpdateSliderFromOptions($options,$sliderID);
		}
		
		/**
		 * 
		 * delete slider from input data
		 */
		public function deleteSliderFromData($data){
			$sliderID = UniteFunctionsRev::getVal($data, "sliderid");
			UniteFunctionsRev::validateNotEmpty($sliderID,"Slider ID");
			$this->initByID($sliderID);
			$this->deleteSlider();
		}
		
		/**
		 * 
		 * duplicate slide from input data
		 */
		public function duplicateSlideFromData($data){
			
			//init the slider
			$sliderID = UniteFunctionsRev::getVal($data, "sliderID");
			UniteFunctionsRev::validateNotEmpty($sliderID,"Slider ID");
			$this->initByID($sliderID);
			
			//get the slide id
			$slideID = UniteFunctionsRev::getVal($data, "slideID");
			UniteFunctionsRev::validateNotEmpty($slideID,"Slide ID");
			$this->duplicateSlide($slideID);
			
			return($sliderID);
		}
		
		
		/**
		 * 
		 * duplicate slide
		 */
		public function duplicateSlide($slideID){
			$slide = new RevSlide();
			$slide->initByID($slideID);
			$order = $slide->getOrder();
			$slides = $this->getSlides();
			$newOrder = $order+1;
			$this->shiftOrder($newOrder);
			
			//do duplication
			$sqlSelect = "select ".GlobalsRevSlider::FIELDS_SLIDE." from ".GlobalsRevSlider::$table_slides." where id={$slideID}";
			$sqlInsert = "insert into ".GlobalsRevSlider::$table_slides." (".GlobalsRevSlider::FIELDS_SLIDE.") ($sqlSelect)";
			
			$this->db->runSql($sqlInsert);
			$lastID = $this->db->getLastInsertID();
			UniteFunctionsRev::validateNotEmpty($lastID);
			
			//update order
			$arrUpdate = array("slide_order"=>$newOrder);
			
			$this->db->update(GlobalsRevSlider::$table_slides,$arrUpdate, array("id"=>$lastID));
		}
		
		
		/**
		 * 
		 * shift order of the slides from specific order
		 */
		public function shiftOrder($fromOrder){
			
			$where = " slider_id={$this->id} and slide_order >= $fromOrder";
			$sql = "update ".GlobalsRevSlider::$table_slides." set slide_order=(slide_order+1) where $where";
			$this->db->runSql($sql);
			
		}
		
		
		/**
		 * 
		 * create a slide from input data
		 */
		public function createSlideFromData($data){
			
			$sliderID = UniteFunctionsRev::getVal($data, "sliderid");
			$urlImage = UniteFunctionsRev::getVal($data, "url_image");
			
			UniteFunctionsRev::validateNotEmpty($sliderID,"Slider ID");
			UniteFunctionsRev::validateNotEmpty($urlImage,"image url");
			$this->initByID($sliderID);
						
			$slide = new RevSlide();
			$slideID = $slide->createSlide($sliderID, $urlImage);
			return($slideID);
		}
		
		/**
		 * 
		 * update slides order from data
		 */
		public function updateSlidesOrderFromData($data){
			$sliderID = UniteFunctionsRev::getVal($data, "sliderID");
			$arrIDs = UniteFunctionsRev::getVal($data, "arrIDs");
			UniteFunctionsRev::validateNotEmpty($arrIDs,"slides");
			
			$this->initByID($sliderID);
			
			foreach($arrIDs as $index=>$slideID){
				$order = $index+1;
				$arrUpdate = array("slide_order"=>$order);
				$where = array("id"=>$slideID);
				$this->db->update(GlobalsRevSlider::$table_slides,$arrUpdate,$where);
			}
			
		}
		
		/**
		 * 
		 * get the "main" and "settings" arrays, for dealing with the settings.
		 */
		public function getSettingsFields(){
			$this->validateInited();
			
			$arrMain = array();
			$arrMain["title"] = $this->title;
			$arrMain["alias"] = $this->alias;
			
			$arrRespose = array("main"=>$arrMain,
								"params"=>$this->arrParams);
			
			return($arrRespose);
		}
		
		/**
		 * 
		 * get slides of the current slider
		 */
		public function getSlides(){
			$this->validateInited();
			$arrSlides = array();
			$arrSlideRecords = $this->db->fetch(GlobalsRevSlider::$table_slides,"slider_id=".$this->id,"slide_order");
			
			foreach ($arrSlideRecords as $record){
				$slide = new RevSlide();
				$slide->initByData($record);
				$arrSlides[] = $slide;
			}
			
			$this->arrSlides = $arrSlides;
			
			return($arrSlides);
		}
		
		/**
		 * 
		 * get slides number
		 */
		public function getNumSlides(){
			if($this->arrSlides == null)
				$this->getSlides();
			
			$numSlides = count($this->arrSlides);
			return($numSlides);
		}
		
		
		/**
		 * 
		 * get sliders array - function don't belong to the object!
		 */
		public function getArrSliders(){
			
			$response = $this->db->fetch(GlobalsRevSlider::$table_sliders);
			
			$arrSliders = array();
			foreach($response as $arrData){
				$slider = new RevSlider();
				$slider->initByDBData($arrData);
				$arrSliders[] = $slider;
			}
			
			return($arrSliders);
		}
		
		/**
		 * 
		 * get array of slider id -> title
		 */		
		public function getArrSlidersShort(){
			$arrSliders = $this->getArrSliders();
			$arrShort = array();
			foreach($arrSliders as $slider){
				$id = $slider->getID();
				$title = $slider->getTitle();
				$arrShort[$id] = $title;
			}
			return($arrShort);
		}
		
		/**
		 * 
		 * get max order
		 */
		public function getMaxOrder(){
			$this->validateInited();
			$maxOrder = 0;
			$arrSlideRecords = $this->db->fetch(GlobalsRevSlider::$table_slides,"slider_id=".$this->id,"slide_order desc","","limit 1");
			if(empty($arrSlideRecords))
				return($maxOrder);
			$maxOrder = $arrSlideRecords[0]["slide_order"];
			
			return($maxOrder);
		}
				
		
	}

?>