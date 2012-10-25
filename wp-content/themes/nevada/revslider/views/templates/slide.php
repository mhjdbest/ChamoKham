
	<div class="wrap settings_wrap">
		<div id="icon-options-general" class="icon32"></div>
		<h2>Edit Slide (id: <?php echo $slideID?>, <?php echo $imageFilename?>)</h2>
		
		<form name="form_slide_params" id="form_slide_params">		
		<?php
			$settingsSlideOutput->draw("form_slide_params",false);
		?>
			<input type="hidden" id="image_url" name="image_url" value="<?php echo $imageUrl?>" />
		</form>
		
		<div class="vert_sap"></div>
		<h3>Slide Image and Layers:</h3>
		<div class="vert_sap"></div>
		
		<?php require self::getPathTemplate("edit_layers");?>
		<div class="slide_update_button_wrapper">
			<a href="javascript:void(0)" id="button_save_slide" class="button-primary">Update Slide</a>
			<div id="loader_update" class="loader_round" style="display:none;">updating...</div>
			<div id="update_slide_success" class="success_message" class="display:none;"></div>
		</div>
		<a id="button_close_slide" href="<?php echo $closeUrl?>" class="button-primary">Close</a>
		
	</div>
	
	<div class="vert_sap"></div>
	
	<script type="text/javascript">
		jQuery(document).ready(function(){
			
			RevSliderAdmin.initEditSlideView(<?php echo $slideID?>);
		});
	</script>
	
	
