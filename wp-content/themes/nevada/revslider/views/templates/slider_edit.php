
	<input type="hidden" id="sliderid" value="<?php echo $sliderID?>"></input>
	
	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>Edit Slider</h2>
		
			<div class="settings_panel">
			
				<div class="settings_panel_left">
					
					<?php $settingsSliderMain->draw("form_slider_main",true)?>
					
					<div class="vert_sap_medium"></div>
					
					<div id="slider_update_button_wrapper" class="slider_update_button_wrapper">
						<a class='button-primary' href='javascript:void(0)' id="button_save_slider" >Update Slider</a>
						<div id="loader_update" class="loader_round" style="display:none;">updating...</div>
						<div id="update_slider_success" class="success_message" class="display:none;"></div>
					</div>
					
					<a id="button_delete_slider" class='button-primary' href='javascript:void(0)' id="button_delete_slider" >Delete Slider</a>
					
					<a id="button_close_slider_edit" class='button-primary' href='<?php echo self::getViewUrl("sliders") ?>' >Close</a>
					
					<a href="<?php echo $linksEditSlides?>" id="link_edit_slides">Edit Slides</a>
					
				</div>
				<div class="settings_panel_right">
					<?php $settingsSliderParams->draw("form_slider_params",true); ?>
				</div>
				
				<div class="clear"></div>
				
			</div>

	</div>

	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			RevSliderAdmin.initEditSliderView();
		});
	</script>
	
