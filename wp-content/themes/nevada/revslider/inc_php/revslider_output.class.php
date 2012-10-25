<?php

	class RevSliderOutput{
		
		private static $sliderSerial = 0;
		
		private $sliderHtmlID;
		private $sliderHtmlID_wrapper;
		private $slider;
		
		
		/**
		 * 
		 * put the kb slider slider on the html page.
		 * @param $data - mixed, can be ID ot Alias.
		 */
		public static function putSlider($sliderID){
			$output = new RevSliderOutput();
			$output->putSliderBase($sliderID);
		}
		
		
		/**
		 * 
		 * put the slider slides
		 */
		private function putSlides(){
			
			$slides = $this->slider->getSlides();
			
			if(empty($slides)):
				?>
				<div class="no-slides-text">
					No slides found, please add some slides
				</div>
				<?php 
			endif;
			
			$thumbWidth = $this->slider->getParam("thumb_width",100);
			$thumbHeight = $this->slider->getParam("thumb_height",50);
			
			$slideWidth = $this->slider->getParam("width",900);
			$slideHeight = $this->slider->getParam("height",300);
			
			$navigationType = $this->slider->getParam("navigaion_type","none"); 
			$isThumbsActive = ($navigationType == "thumb")?true:false;
			
			$flagReize = $this->slider->getParam("php_resize","none");
			$flagReize = ($flagReize == "on")?true:false;
			
			?>
				<ul>
			<?php
			
			foreach($slides as $slide){			
				$params = $slide->getParams();
				
				$transition = $slide->getParam("slide_transition","random");
					
				$slotAmount = $slide->getParam("slot_amount","7");
								
				$urlSlideImage = $slide->getImageUrl();
				
				if($flagReize == true){
					$pathSlideImage = $slide->getImageFilepath();
					if(!empty($pathSlideImage))
						$urlSlideImage = UniteBaseClassRev::getImageUrl($pathSlideImage,$slideWidth,$slideHeight,true);
				}
				
				//get thumb url
				$htmlThumb = "";
				if($isThumbsActive == true){
					$urlThumb = $slide->getParam("slide_thumb","");
					
					if(empty($urlThumb)){	//try to get resized thumb
						$pathThumb = $slide->getImageFilepath();
						if(!empty($pathThumb))
							$urlThumb = UniteBaseClassRev::getImageUrl($pathThumb,$thumbWidth,$thumbHeight,true);
					}
					
					//if not - put regular image:
					if(empty($urlThumb))						
						$urlThumb = $slide->getImageUrl();
					
					$htmlThumb = 'data-thumb="'.$urlThumb.'"';
				}
				
				//get link
				$htmlLink = "";
				$enableLink = $slide->getParam("enable_link","false");
				if($enableLink == "true"){
					$link = $slide->getParam("link","");
					$htmlLink = "data-link=\"$link\"";
				}
				
				//set delay
				$htmlDelay = "";
				$delay = $slide->getParam("delay","");
				if(!empty($delay) && is_numeric($delay))
					$htmlDelay = "data-delay=\"$delay\"";
				
				//get duration
				$htmlDuration = "";
				$duration = $slide->getParam("transition_duration","");
				if(!empty($duration) && is_numeric($duration))
					$htmlDuration = "data-masterspeed=\"$duration\"";
				
				//Html
				?>					
					<li data-transition="<?php echo $transition?>"
						data-slotamount="<?php echo $slotAmount?>"
						<?php echo $htmlDuration?> 
						<?php echo $htmlLink?> 
						<?php echo $htmlThumb?> 
						<?php echo $htmlDelay?>
					 >
					    
						<img src="<?php echo $urlSlideImage?>" >
						  
						<?php $this->putCreativeLayer($slide)?>
					</li>
				<?php 
			}	//get foreach
			
			?>
				</ul>
			<?php
		}
		
		
		/**
		 * 
		 * put creative layer
		 */
		public function putCreativeLayer(RevSlide $slide){
			$layers = $slide->getLayers();
			
			if(empty($layers))
				return(false);
			?>
				<?php foreach($layers as $layer):
					
					$type = UniteFunctionsRev::getVal($layer, "type","text");
										
					$class = UniteFunctionsRev::getVal($layer, "style");
					$animation = UniteFunctionsRev::getVal($layer, "animation","fade");
										
					//set output class:
					$outputClass = "caption ". trim($class);
						$outputClass = trim($outputClass) . " ";
						
					$outputClass .= trim($animation);
					
					$left = UniteFunctionsRev::getVal($layer, "left",0);
					$top = UniteFunctionsRev::getVal($layer, "top",0);
					$speed = UniteFunctionsRev::getVal($layer, "speed",300);
					$time = UniteFunctionsRev::getVal($layer, "time",0);
					$easing = UniteFunctionsRev::getVal($layer, "easing","easeOutExpo");
					
					$text = UniteFunctionsRev::getVal($layer, "text");
					
					//set html:
					$html = "";
					switch($type){
						default:
						case "text":						
							$html = $text;
							$html = do_shortcode($html);
						break;
						case "image":
							$urlImage = UniteFunctionsRev::getVal($layer, "image_url");
							$html = '<img src="'.$urlImage.'" alt="'.$text.'">';
							$imageLink = UniteFunctionsRev::getVal($layer, "link","");
							if(!empty($imageLink)){
								$openIn = UniteFunctionsRev::getVal($layer, "link_open_in","same");

								$target = "";
								if($openIn == "new")
									$target = ' target="_blank"';
									
								$html = '<a href="'.$imageLink.'"'.$target.'>'.$html.'</a>';
							}								
						break;
						case "video":
							$videoType = trim(UniteFunctionsRev::getVal($layer, "video_type"));
							$videoID = trim(UniteFunctionsRev::getVal($layer, "video_id"));
							$videoWidth = trim(UniteFunctionsRev::getVal($layer, "video_width"));
							$videoHeight = trim(UniteFunctionsRev::getVal($layer, "video_height"));
							
							switch($videoType){
								case "youtube":
									$html = "<iframe src='http://www.youtube.com/embed/{$videoID}?hd=1&amp;wmode=opaque&amp;controls=1&amp;showinfo=0' width='{$videoWidth}' height='{$videoHeight}'></iframe>";
								break;
								case "vimeo":
									$html = "<iframe src='http://player.vimeo.com/video/{$videoID}?title=0&amp;byline=0&amp;portrait=0' width='{$videoWidth}' height='{$videoHeight}'></iframe>";
								break;
								default:
									UniteFunctionsRev::throwError("wrong video type: $videoType");
								break;
							}
							
						break;
					}
					
				?>
				
				<div class="<?php echo $outputClass?>"  
					 data-x="<?php echo $left?>" 
					 data-y="<?php echo $top?>" 
					 data-speed="<?php echo $speed?>" 
					 data-start="<?php echo $time?>" 
					 data-easing="<?php echo $easing?>"><?php echo $html?></div>
				
				<?php endforeach;?>
			<?php 
		}

		
		/**
		 * 
		 * put slider javascript
		 */
		public function putJS(){
			
			$params = $this->slider->getParams();
			$sliderType = $this->slider->getParam("slider_type");
			$optFullWidth = ($sliderType == "fullwidth")?"on":"off";
			
			$noConflict = $this->slider->getParam("jquery_noconflict","on");
			
			//set thumb amount
			$numSlides = $this->slider->getNumSlides();
			$thumbAmount = (int)$this->slider->getParam("thumb_amount","5");
			if($thumbAmount > $numSlides)
				$thumbAmount = $numSlides;
			?>
			
			<script type="text/javascript">
				
				var tpj=jQuery;
				
				<?php if($noConflict == "on"):?>
					tpj.noConflict();
				<?php endif;?>
				
				tpj(document).ready(function() {
				
				if (tpj.fn.cssOriginal != undefined)
					tpj.fn.css = tpj.fn.cssOriginal;

				tpj('#<?php echo $this->sliderHtmlID?>').show().revolution(
					{
						delay:<?php echo $this->slider->getParam("delay","9000")?>,
						startwidth:<?php echo $this->slider->getParam("width","900")?>,
						startheight:<?php echo $this->slider->getParam("height","300")?>,
						hideThumbs:<?php echo $this->slider->getParam("hide_thumbs","200")?>,
						
						thumbWidth:<?php echo $this->slider->getParam("thumb_width","100")?>,
						thumbHeight:<?php echo $this->slider->getParam("thumb_height","50")?>,
						thumbAmount:<?php echo $thumbAmount?>,
						
						navigationType:"<?php echo $this->slider->getParam("navigaion_type","none")?>",
						navigationArrows:"<?php echo $this->slider->getParam("navigation_arrows","nexttobullets")?>",
						navigationStyle:"<?php echo $this->slider->getParam("navigation_style","round")?>",
						
						touchenabled:"<?php echo $this->slider->getParam("touchenabled","on")?>",
						onHoverStop:"<?php echo $this->slider->getParam("stop_on_hover","on")?>",
						
						navOffsetHorizontal:<?php echo $this->slider->getParam("nav_offset_hor","0")?>,
						navOffsetVertical:<?php echo $this->slider->getParam("nav_offset_vert","20")?>,
						
						shadow:<?php echo $this->slider->getParam("shadow_type","2")?>,
						fullWidth:"<?php echo $optFullWidth?>"
					});
				
				});	//ready
				
			</script>
			
			<?php			
		}
		
		
		/**
		 * 
		 * put inline error message in a box.
		 */
		private function putErrorMessage($message){
			?>
			<div style="width:800px;height:300px;margin-bottom:10px;border:1px solid black;">
				<div style="padding-top:40px;color:red;font-size:16px;text-align:center;">
					Revolution Slider Error: <?php echo $message?> 
				</div>
			</div>
			<?php 
		}
		
		/**
		 * 
		 * fill the responsitive slider values for further output
		 */
		private function getResponsitiveValues(){
			$sliderWidth = (int)$this->slider->getParam("width");
			$sliderHeight = (int)$this->slider->getParam("height");
			
			$percent = $sliderHeight / $sliderWidth;
			
			$w1 = (int) $this->slider->getParam("responsitive_w1",0);
			$w2 = (int) $this->slider->getParam("responsitive_w2",0);
			$w3 = (int) $this->slider->getParam("responsitive_w3",0);
			$w4 = (int) $this->slider->getParam("responsitive_w4",0);
			
			$sw1 = (int) $this->slider->getParam("responsitive_sw1",0);
			$sw2 = (int) $this->slider->getParam("responsitive_sw2",0);
			$sw3 = (int) $this->slider->getParam("responsitive_sw3",0);
			$sw4 = (int) $this->slider->getParam("responsitive_sw4",0);
			
			$arrItems = array();
			
			//add main item:
			$arr = array();				
			$arr["maxWidth"] = -1;
			$arr["minWidth"] = $w1;
			$arr["sliderWidth"] = $sliderWidth;
			$arr["sliderHeight"] = $sliderHeight;
			$arrItems[] = $arr;
			
			//add item 1:
			if(empty($w1))
				return($arrItems);
				
			$arr = array();				
			$arr["maxWidth"] = $w1-1;
			$arr["minWidth"] = $w2;
			$arr["sliderWidth"] = $sw1;
			$arr["sliderHeight"] = floor($sw1 * $percent);
			$arrItems[] = $arr;
			
			//add item 2:
			if(empty($w2))
				return($arrItems);
			
			$arr["maxWidth"] = $w2-1;
			$arr["minWidth"] = $w3;
			$arr["sliderWidth"] = $sw2;
			$arr["sliderHeight"] = floor($sw2 * $percent);
			$arrItems[] = $arr;
			
			//add item 3:
			if(empty($w3))
				return($arrItems);
			
			$arr["maxWidth"] = $w3-1;
			$arr["minWidth"] = $w4;
			$arr["sliderWidth"] = $sw3;
			$arr["sliderHeight"] = floor($sw3 * $percent);
			$arrItems[] = $arr;
			
			//add item 3:
			if(empty($w4))
				return($arrItems);
			
			$arr["maxWidth"] = $w4-1;
			$arr["minWidth"] = 0;
			$arr["sliderWidth"] = $sw4;
			$arr["sliderHeight"] = floor($sw4 * $percent);
			$arrItems[] = $arr;
			
			//dmp($arrItems);exit();
			
			
			return($arrItems);
		}
		
		
		/**
		 * 
		 * put responsitive inline styles
		 */
		private function putResponsitiveStyles(){
			
			$arrItems = $this->getResponsitiveValues();
			
			echo "<style type='text/css'>";
			?>
			<?php
			foreach($arrItems as $item):			
				$strMaxWidth = "";
				
				if($item["maxWidth"] >= 0)
					$strMaxWidth = "and (max-width: {$item["maxWidth"]}px)";
				
			?>
			
			   @media only screen and (min-width: <?php echo $item["minWidth"]?>px) <?php echo $strMaxWidth?> {
			 		  #<?php echo $this->sliderHtmlID?>, #<?php echo $this->sliderHtmlID_wrapper?> { width:<?php echo $item["sliderWidth"]?>px; height:<?php echo $item["sliderHeight"]?>px;}	
			   }
			
			<?php 
			endforeach;
			echo "</style>";
		}
			
		
		/**
		 * 
		 * put html slider on the html page.
		 * @param $data - mixed, can be ID ot Alias.
		 */
		
		//TODO: settings google font, position, margin, background color, alt image text
		
		public function putSliderBase($sliderID){
			
			try{
			
				self::$sliderSerial++;
				
				$this->slider = new RevSlider();
				$this->slider->initByMixed($sliderID);
				
				$htmlBeforeSlider = "";
				if($this->slider->getParam("load_googlefont","false") == "true"){
					$googleFont = $this->slider->getParam("google_font");
					$htmlBeforeSlider = "<link rel='stylesheet' id='rev-google-font' href='http://fonts.googleapis.com/css?family={$googleFont}' type='text/css' media='all' />";
				}
				
				//the initial id can be alias
				$sliderID = $this->slider->getID();
				
				$bannerWidth = $this->slider->getParam("width");
				$bannerHeight = $this->slider->getParam("height");
				
				$sliderType = $this->slider->getParam("slider_type");
				
				//set wrapper height
				$wrapperHeigh = 0;
				$wrapperHeigh += $this->slider->getParam("height");
				
				//add thumb height
				if($this->slider->getParam("navigaion_type") == "thumb"){
					$wrapperHeigh += $this->slider->getParam("thumb_height");
				}

				$this->sliderHtmlID = "rev_slider_".$sliderID."_".self::$sliderSerial;
				$this->sliderHtmlID_wrapper = $this->sliderHtmlID."_wrapper";
				
				$containerStyle = "";
				
				//set position:
				$sliderPosition = $this->slider->getParam("position","center");
				switch($sliderPosition){
					case "center":
					default:
						$containerStyle .= "margin:0px auto;";
					break;
					case "left":
						$containerStyle .= "float:left;";
					break;
					case "right":
						$containerStyle .= "float:right;";
					break;
				}
				
				//add background color
				$backgrondColor = trim($this->slider->getParam("background_color"));
				if(!empty($backgrondColor))
					$containerStyle .= "background-color:$backgrondColor;";
								
				//set padding			
				$containerStyle .= "padding:".$this->slider->getParam("padding","0")."px;";
					
				//dmp($this->slider->getParams());exit();
				
				//set margin:
				if($sliderPosition != "center"){
					$containerStyle .= "margin-left:".$this->slider->getParam("margin_left","0")."px;";
					$containerStyle .= "margin-right:".$this->slider->getParam("margin_right","0")."px;";
				}
				
				$containerStyle .= "margin-top:".$this->slider->getParam("margin_top","0")."px;";
				$containerStyle .= "margin-bottom:".$this->slider->getParam("margin_bottom","0")."px;";
									
				//set height and width:
				$bannerStyle = "display:none;";	
				
				//add background image (to banner style)
				$showBackgroundImage = $this->slider->getParam("show_background_image","false");
				if($showBackgroundImage == "true"){					
					$backgroundImage = $this->slider->getParam("background_image");					
					if(!empty($backgroundImage))
						$bannerStyle .= "background-image:url($backgroundImage);background-repeat:no-repeat;";
				}
				
				//set wrapper and slider class:
				$sliderWrapperClass = "rev_slider_wrapper";
				$sliderClass = "rev_slider";
				
				switch($sliderType){
					default:
					case "fixed":
						$bannerStyle .= "height:{$bannerHeight}px;width:{$bannerWidth}px;";
						$containerStyle .= "height:{$bannerHeight}px;width:{$bannerWidth}px;";
					break;
					case "responsitive":
						$this->putResponsitiveStyles();
					break;
					case "fullwidth":
						$sliderWrapperClass .= " fullwidthbanner-container";
						$sliderClass .= " fullwidthabanner";
						$bannerStyle .= "max-height:{$bannerHeight}px;";
						$containerStyle .= "max-height:{$bannerHeight}px;";						
					break;
				}
				
				$htmlTimerBar = "";
				if($this->slider->getParam("show_timerbar","true") == "true")
					$htmlTimerBar = '<div class="tp-bannertimer"></div>';
				
				?>

				<!-- START REVOLUTION SLIDER -->
				
				<?php echo $htmlBeforeSlider?>
				<div class="ut-slider-wrap">
				<div id="<?php echo $this->sliderHtmlID_wrapper?>" class="<?php echo $sliderWrapperClass?>" style="<?php echo $containerStyle?>">
					<div id="<?php echo $this->sliderHtmlID ?>" class="<?php echo $sliderClass?>" style="<?php echo $bannerStyle?>">						
						<?php $this->putSlides()?>
						<?php echo $htmlTimerBar?>
					</div>
				</div>
				</div>				
				<?php 
				
				$this->putJS();
				?>
				<!-- END REVOLUTION SLIDER -->
				<?php 
				
			}catch(Exception $e){
				$message = $e->getMessage();
				$this->putErrorMessage($message);
			}
			
		}
		
		
	}

?>