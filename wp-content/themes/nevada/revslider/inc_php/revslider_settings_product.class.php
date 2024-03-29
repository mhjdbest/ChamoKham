<?php

	class RevSliderSettingsProduct extends UniteSettingsRevProductRev{
		
		/**
		 * 
		 * set custom values to settings
		 */
		public static function setSettingsCustomValues(UniteSettingsRev $settings,$arrValues){
			$arrSettings = $settings->getArrSettings();
			
			foreach($arrSettings as $key=>$setting){
				$type = UniteFunctionsRev::getVal($setting, "type");
				if($type != UniteSettingsRev::TYPE_CUSTOM)
					continue;
				$customType = UniteFunctionsRev::getVal($setting, "custom_type");
				
				switch($customType){
					case "slider_size":
						$setting["width"] = UniteFunctionsRev::getVal($arrValues, "width",UniteFunctionsRev::getVal($setting,"width"));
						$setting["height"] = UniteFunctionsRev::getVal($arrValues, "height",UniteFunctionsRev::getVal($setting,"height"));
						$arrSettings[$key] = $setting;
					break;
					case "responsitive_settings":						
						$id = $setting["id"];
						$setting["w1"] = UniteFunctionsRev::getVal($arrValues, $id."_w1",UniteFunctionsRev::getVal($setting,"w1"));
						$setting["w2"] = UniteFunctionsRev::getVal($arrValues, $id."_w2",UniteFunctionsRev::getVal($setting,"w2"));
						$setting["w3"] = UniteFunctionsRev::getVal($arrValues, $id."_w3",UniteFunctionsRev::getVal($setting,"w3"));
						$setting["w4"] = UniteFunctionsRev::getVal($arrValues, $id."_w4",UniteFunctionsRev::getVal($setting,"w4"));
						
						$setting["sw1"] = UniteFunctionsRev::getVal($arrValues, $id."_sw1",UniteFunctionsRev::getVal($setting,"sw1"));
						$setting["sw2"] = UniteFunctionsRev::getVal($arrValues, $id."_sw2",UniteFunctionsRev::getVal($setting,"sw2"));
						$setting["sw3"] = UniteFunctionsRev::getVal($arrValues, $id."_sw3",UniteFunctionsRev::getVal($setting,"sw3"));
						$setting["sw4"] = UniteFunctionsRev::getVal($arrValues, $id."_sw4",UniteFunctionsRev::getVal($setting,"sw4"));
						$arrSettings[$key] = $setting;				
					break;
				}
			}
						
			$settings->setArrSettings($arrSettings);
			
			//disable settings by slider type:
			$sliderType = $settings->getSettingValue("slider_type");
						
			switch($sliderType){
				case "fixed":
				case "fullwidth":
					//hide responsitive
					$settingRes = $settings->getSettingByName("responsitive");
					$settingRes["disabled"] = true;
					$settings->updateArrSettingByName("responsitive", $settingRes);
				break;
			}
			
			//change height to max height
			if($sliderType == "fullwidth"){
				$settingSize = $settings->getSettingByName("slider_size");
				$settingSize["fullwidth_mode"] = true;
				$settings->updateArrSettingByName("slider_size", $settingSize);
			}
			
			return($settings);
		}
		
		
		/**
		 * 
		 * draw responsitive settings value
		 */
		protected function drawResponsitiveSettings($setting){
			$id = $setting["id"];
			
			$w1 = UniteFunctionsRev::getVal($setting, "w1");
			$w2 = UniteFunctionsRev::getVal($setting, "w2");
			$w3 = UniteFunctionsRev::getVal($setting, "w3");
			$w4 = UniteFunctionsRev::getVal($setting, "w4");
			
			$sw1 = UniteFunctionsRev::getVal($setting, "sw1");
			$sw2 = UniteFunctionsRev::getVal($setting, "sw2");
			$sw3 = UniteFunctionsRev::getVal($setting, "sw3");
			$sw4 = UniteFunctionsRev::getVal($setting, "sw4");
			
			$disabled = (UniteFunctionsRev::getVal($setting, disabled) == true);
			
			$strDisabled = "";
			if($disabled == true)
				$strDisabled = "disabled='disabled'";
			
			?>
			<table>
				<tr>
					<td>
						Screen Width1:
					</td>
					<td>
						<input id="<?php echo $id?>_w1" name="<?php echo $id?>_w1" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w1?>">
					</td>
					<td>
						Slider Width1: 
					</td>
					<td>
						<input id="<?php echo $id?>_sw1" name="<?php echo $id?>_sw1" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw1?>">
					</td>					
				</tr>
				<tr>
					<td>
						Screen Width2: 
					</td>
					<td>
						<input id="<?php echo $id?>_w2" name="<?php echo $id?>_w2" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w2?>">
					</td>
					<td>
						Slider Width2: 
					</td>
					<td>
						<input id="<?php echo $id?>_sw2" name="<?php echo $id?>_sw2" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw2?>">
					</td>
				</tr>
				<tr>
					<td>
						Screen Width3: 
					</td>
					<td>
						<input id="<?php echo $id?>_w3" name="<?php echo $id?>_w3" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w3?>">
					</td>
					<td>
						Slider Width3: 
					</td>
					<td>
						<input id="<?php echo $id?>_sw3" name="<?php echo $id?>_sw3" type="text" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw3?>">
					</td>
				</tr>
				<tr>
					<td>
						Screen Width4: 
					</td>
					<td>
						<input type="text" id="<?php echo $id?>_w4" name="<?php echo $id?>_w4" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $w4?>">
					</td>
					<td>
						Slider Width4: 
					</td>
					<td>
						<input type="text" id="<?php echo $id?>_sw4" name="<?php echo $id?>_sw4" class="textbox-small" <?php echo $strDisabled?> value="<?php echo $sw4?>">
					</td>
				</tr>				
			</table>
			<?php
		}
		
		
		/**
		 * 
		 * draw slider size
		 */
		protected function drawSliderSize($setting){
			
			$width = UniteFunctionsRev::getVal($setting, "width");
			$height = UniteFunctionsRev::getVal($setting, "height");
			
			$fullWidthMode = UniteFunctionsRev::getVal($setting, "fullwidth_mode");
			 			
			
			?>
			
			<table>
				<tr>
					<?php if($fullWidthMode):?>
					<td id="cellWidth">
						Grid Width:
					</td>
					<td id="cellWidthInput">
						<input id="width" name="width" type="text" class="textbox-small" value="<?php echo $width?>">
					</td>
					<td id="cellHeight">
						Slider Max Height: 
					</td>
					<td>
						<input id="height" name="height" type="text" class="textbox-small" value="<?php echo $height?>">
					</td>
					<?php else:?>
					<td id="cellWidth">
						Slider Width:
					</td>
					<td id="cellWidthInput">
						<input id="width" name="width" type="text" class="textbox-small" value="<?php echo $width?>">
					</td>
					<td id="cellHeight">
						Slider Height: 
					</td>
					<td>
						<input id="height" name="height" type="text" class="textbox-small" value="<?php echo $height?>">
					</td>					
					<?php endif?>					
				</tr>
			</table>
			
			<?php 
		}
		
		
		
		/**
		 * 
		 * draw custom inputs for rev slider
		 * @param $setting
		 */
		protected function drawCustomInputs($setting){
			
			$customType = UniteFunctionsRev::getVal($setting, "custom_type");
			switch($customType){
				case "slider_size":
					$this->drawSliderSize($setting);
				break;
				case "responsitive_settings":
					$this->drawResponsitiveSettings($setting);
				break;
				default:
					UniteFunctionsRev::throwError("No handler function for type: $customType");
				break;
			}			
		}
		
	}

?>