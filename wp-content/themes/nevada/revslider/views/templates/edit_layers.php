	
	<div class="edit_slide_wrapper">
		<a href="javascript:void(0)" id="button_change_image" class="button-primary margin_right10">Change Image</a>
		<a href="javascript:void(0)" id="button_add_layer" class="button-secondary margin_left10">Add Layer</a>
		<a href="javascript:void(0)" id="button_add_layer_image" class="button-secondary margin_left10">Add Layer: Image</a>
		<a href="javascript:void(0)" id="button_add_layer_video" class="button-secondary margin_left10">Add Layer: Video</a>
		
		<span class="hor_sap_double"></span>
		
		<a href="javascript:void(0)" id="button_delete_layer" class="button-secondary margin_left10 button-disabled">Delete Layer</a>
		<a href="javascript:void(0)" id="button_delete_all" class="button-secondary margin_left10 button-disabled">Delete All Layers</a>
		
		<span class="hor_sap_double"></span>
		
		<a href="javascript:void(0)" id="button_duplicate_layer" class="button-secondary margin_left10 button-disabled">Duplicate Layer</a>
		
		<div class="vert_sap_small"></div>
		
		<div id="divLayers" class="slide_layers" style="<?php echo $style?>"></div>
		<div class="clear"></div>
		<div class="vert_sap"></div>
		
		<div class="layer_props_wrapper">
		
			<div class="edit_layers_left">
				<?php	
					$settingsLayerOutput->isAccordion(false);
					$settingsLayerOutput->draw("form_layers",true);
				?>
			</div>
			
			<div class="edit_layers_right">
				<div class="postbox unite-postbox layer_sortbox">
					<h3 class="no-accordion">
						<span>Layers Sorting</span>
						<div id="button_sort_time" class="ui-state-active ui-corner-all button_sorttype"><span>By Time</span></div>
						<div id="button_sort_depth" class="ui-state-hover ui-corner-all button_sorttype"><span>By Depth<span></div>
					</h3>			
					
					<div class="inside">
						<ul id="sortlist" class='sortlist'></ul>
					</div>
				</div>
			</div>
			
			<div class="clear"></div>
			
		</div>
	</div>
	
	<div id="dialog_edit_css" class="dialog_edit_file" title="Edit captions.css file" style="display:none">
		<p>
			<textarea id="textarea_edit" rows="20" cols="100"></textarea>
		</p>
		<div class='unite_error_message' id="dialog_error_message" style="display:none;"></div>
		<div class='unite_success_message' id="dialog_success_message" style="display:none;"></div>
	</div> 
	
	<div id="dialog_insert_button" class="dialog_insert_button" title="Insert Button" style="display:none;">
		<p>
			<ul class="list-buttons">
			<?php foreach($arrButtonClasses as $class=>$text): ?>
					<li>
						<a href="javascript:UniteLayersRev.insertButton('<?php echo $class?>','<?php echo $text?>')" class="tp-button <?php echo $class?> small"><?php echo $text?></a>
					</li>
			<?php endforeach;?> 
			</ul>
		</p>
	</div>
	
	<script type="text/javascript">
		
		jQuery(document).ready(function() {
			<?php if(!empty($jsonLayers)):?>
				//set init layers object
				UniteLayersRev.setInitLayersJson(<?php echo $jsonLayers?>);
			<?php endif?>

			<?php if(!empty($jsonCaptions)):?>
			UniteLayersRev.setInitCaptionClasses(<?php echo $jsonCaptions?>);
			<?php endif?>
			
			UniteLayersRev.setCssCaptionsUrl('<?php echo $urlCaptionsCSS?>'); 
			UniteLayersRev.init();
			
		});
	
	</script>
