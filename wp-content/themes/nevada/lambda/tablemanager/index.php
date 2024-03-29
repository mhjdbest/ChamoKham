<?php

/*
 * basic table manager
 * lambda framework v 2.1
 * by www.unitedthemes.com
 * since framework v 2.0
 */

global $wpdb, $theme_path;

include_once ('lambda.table.class.php');

#-----------------------------------------------------------------
# additional scripts
#-----------------------------------------------------------------
function lambda_table_admin_add_scripts() {
 	
	global $theme_path;

	wp_register_script ('bootstrap', $theme_path.'/lambda/assets/js/bootstrap.js', array('jquery'), '2.0.3' , true);
    wp_enqueue_script  ('bootstrap' );
	
	wp_register_script ('tablemanager', $theme_path.'/lambda/assets/js/lambda.tablemanager.js', array('jquery'), '1.0' , true);
    wp_enqueue_script  ('tablemanager' );
	
}

#-----------------------------------------------------------------
# additional styles
#-----------------------------------------------------------------
function lambda_table_admin_add_styles() {
    
	global $theme_path;
	
	wp_register_style('standard-css', $theme_path.'/lambda/assets/css/lambda.ui.css');
    wp_enqueue_style( 'standard-css');
				
}

#-----------------------------------------------------------------
# Only load Scripts&Styles when needed
#-----------------------------------------------------------------
if ( isset($_GET['page']) && $_GET['page'] == 'view_tables' ) {	
	add_action('admin_print_styles', 'lambda_table_admin_add_styles');
	add_action('admin_init', 'lambda_table_admin_add_scripts');

}


#-----------------------------------------------------------------
# General Output
#-----------------------------------------------------------------
function lambda_table_admin_page() {

	echo '<div id="lambda_framework_wrap">';
	
	//Show Tableoverview
	if ((isset($_GET['page']) && $_GET['page'] == 'view_tables') && !isset($_GET['edit']) ) {
		lambda_table_overview();
	}
	
	//Show Table Edit Page
	if ((isset($_GET['page']) && $_GET['page'] == 'view_tables') && isset($_GET['edit']) ) {
		lambda_table_edit();	
	}
	
	echo '</div>';

}


#-----------------------------------------------------------------
# Table Overview - Table Block - will automatically
#-----------------------------------------------------------------
function lambda_table_overview() { ?>

<div id="lambda-option-panel" class="bootstrap-wpadmin">
<div id="content_wrap" class="well form-horizontal">


<div id="lambda-options-panel-title">
	  	
	<h1><?php echo _e('Manage your Tables', UT_THEME_NAME)?></h1>
	<div class="clear"></div>
		
</div>

<table cellspacing="0" class="table table-striped table-bordered table-condensed">
		<thead>
			<tr>
				<th scope="col" id="name" colspan="7">
				
				<?php _e('List of created Tables', UT_THEME_NAME)?>
								
				</th>
			</tr>
            <tr>
            	<td> <?php _e('ID', UT_THEME_NAME); ?> </td>
            	<td> <?php _e('Table Name', UT_THEME_NAME); ?> </td>
				<td> <?php _e('Shortcode', UT_THEME_NAME); ?> </td>
            	<td> <?php _e('Edit', UT_THEME_NAME); ?> </td>
            	<td> <?php _e('Delete', UT_THEME_NAME); ?> </td>
            </tr>
			</thead>
			<tbody>
            <?php lambda_table_view(); ?>
            
</table>
</div>
</div>

<?php
} // End Main Table Function


#-----------------------------------------------------------------
# Display all existing Tables
#-----------------------------------------------------------------
function lambda_table_view() {
    
	//globals
	global $wpdb, $theme_path, $tm_message;
	
	//internal counter
	$num=1;
		
	$table_lambda_tables = $wpdb->prefix . "lambda_tables"; 	
    $lambda_table_data = $wpdb->get_results("SELECT * FROM $table_lambda_tables ORDER BY id");
	
    foreach ($lambda_table_data as $data) { 
        
      echo '<tr>
	   			<td>
					'.$data->id.'
				</td>
				<td>
					'.$data->table_name.'
				</td>
				<td>
					[lambdatable id="'.$data->id.'"]
				</td>				
	   			<td>
					<button type="button" onClick="location=\'?page=view_tables&edit='.$data->table_name.'\'" class="btn btn-mini btn-primary"><i class="icon-edit icon-white"></i></button>        
       			</td>
       			<td>
					<button onClick="location=\'?page=view_tables&delete='.$data->table_name.'\'" class="btn btn-mini btn-danger"><i class="icon-remove icon-white"></i></button>
				</td>
			</tr>';       
	   $num++;
	   
	   }
       ?>
      
	   
       <tr>
		   <td>
				<?php echo ($data->id+1); ?>
		   </td>
		   <td colspan="6">
		   		<form method="post" action="?page=view_tables&add=1" class="form-horizontal">
				
				
				<label class="control-label"><?php _e('Enter Table Name', UT_THEME_NAME); ?></label>
				
				<div class="controls">
				<input type="text" id="table_name" class="lambda_input" name="table_name" size="70" placeholder="<?php _e('Table Name', UT_THEME_NAME); ?>" /><br />
				</div>
				
				<button type="submit" class="btn btn-success" value="<?php _e('Add new Table', UT_THEME_NAME); ?>" /><i class="icon-plus icon-white"></i></button>
				
				<br />
				
				<span class="info"><?php _e('* Do not use spaces or special characters in the name. This Name is only for internal use!', UT_THEME_NAME); ?></span>

				</form>
		   </td>
       </tr>
	   </tbody>
			<tfoot>
			
			<?php if($tm_message) : ?>
			<tr>
				<th scope="col" colspan="7">
					<?php echo '<div class="alert alert-block" id="message"><p><strong>'.$tm_message.'</strong></p></div></div>'; ?>
				</th>
			</tr>	
			<?php endif; ?>
			
	   </tfoot>
       
	   
       <?php
}

#-----------------------------------------------------------------
# Add Table Item
#-----------------------------------------------------------------
if ( isset($_GET['add']) ) {	
    
	//assign post data
	$option = (isset($_POST['table_name'])) ? $_POST['table_name'] : '';
		
	if(!get_option($option)) {
		
		if($option){
			
			
			$option = preg_replace('/[^a-z0-9\s]/i', '', $option);  
			$option = str_replace(" ", "_", $option);
			
			$table_lambda_tables = $wpdb->prefix . "lambda_tables"; 
			$options = get_option($option);
			
			
			if($options) {
					
					$sm_message= 'Unable to Add '.$option.',  different name';
				
				
				} else {
					
				
					$sql = "INSERT INTO " . $table_lambda_tables . " values ('','".$option."');";
					if ($wpdb->query( $sql )){
								
								add_option($option);
								$tm_message= $option.' successfully added';
								
							}
					else {
							$tm_message= 'Unable to Add '.$option.', can not insert '.$option;
						  }
					};
				} else {
						$tm_message= ' Unable to Add'.$option;
				}
			
    } else {
        
		$tm_message = 'Unable to Add '.$option.', try a different name';
		
    }
	
}

#-----------------------------------------------------------------
# Delete Table Item
#-----------------------------------------------------------------
if ( isset($_GET['delete']) ) {	
   
	$option = $_GET['delete'];
	delete_option($option);
    	
	$table_lambda_tables = $wpdb->prefix . "lambda_tables"; 
    $sql = "DELETE FROM " . $table_lambda_tables . " WHERE table_name='".$option."';";
		$wpdb->query( $sql );
		
	$tm_message = __('Table deleted', UT_THEME_NAME);
}


#-----------------------------------------------------------------
# Slider Item Edit Section
#-----------------------------------------------------------------
function lambda_table_edit() { ?>

	<div id="lambda-option-panel" class="bootstrap-wpadmin option-tables">
	<form method="post" action="options.php" class="well form-inline" >
	
	<?php wp_nonce_field('update-options'); 
	
	//get needed slider data
	$table = $_GET['edit'];
	$options = get_option($table);	?>	
	
	<div class="lambda-opttitle">
            <div class="lambda-opttitle-pad settingstitle">
                <h1><?php echo _e('Table Manager', UT_THEME_NAME)?></h1>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="page_options" value="<?php echo $table; ?>" />
				<input type="submit" class="lambda_save btn btn-success right" value="<?php _e('Save Settings', UT_THEME_NAME) ?>" />
				<div class="clear"></div>				
            </div>
    </div>
	
	<div id="table-items" class="tab-pane">
				
		<div id="single-columns">
				
		<?php $table_basic_columns = (empty($options['columns'])) ? lambda_table_item_array() : $options['columns'];
			
			foreach ($table_basic_columns as $key => $value) { ?>
			
			<div id="lambda_<?php echo $key; ?>" class="column_item">
						
			<label for="column_<?php echo $key; ?>"><?php _e('Activate Column?', UT_THEME_NAME); ?></label><br />
			
			<div class="lambda_row">
				
				<div class="btn-group" data-toggle="buttons-radio">
				
				<button value="<?php echo $key; ?>_on" type="button" class="btn btn-mini btn-info <?php echo ($value['column_active'] == 'on') ? 'active' : 'inactive'; ?> radio_<?php echo ($value['column_active'] == 'on') ? 'active' : 'inactive'; ?>"><?php _e('on', UT_THEME_NAME); ?></button>				
				<button value="<?php echo $key; ?>_off" type="button" class="btn btn-mini btn-info <?php echo ($value['column_active'] == 'off') ? 'active' : 'inactive'; ?> radio_<?php echo ($value['column_active'] == 'off') ? 'active' : 'inactive'; ?>"><?php _e('off', UT_THEME_NAME); ?></button>
				
				<input class="radiostate_<?php echo ($value['column_active'] == 'on') ? 'active' : 'inactive'; ?>" style="display:none;" id="<?php echo $key; ?>_on" type="radio" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_active]" value="on" <?php echo ($value['column_active'] == 'on') ? 'checked="checked"' : ''; ?> /><br />
				<input class="radiostate_<?php echo ($value['column_active'] == 'off') ? 'active' : 'inactive'; ?>" style="display:none;" id="<?php echo $key; ?>_off" type="radio" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_active]" value="off" <?php echo ($value['column_active'] == 'off') ? 'checked="checked"' : ''; ?> /><br />
				
				</div>
				
			</div>
			
			<hr />
			
			<label for="column_<?php echo $key; ?>"><?php _e('Column is featured?', UT_THEME_NAME); ?></label><br />
			
			<div class="lambda_row">
				
				<div class="btn-group" data-toggle="buttons-radio">
				
				<button value="<?php echo $key; ?>_yes" type="button" class="btn btn-mini btn-info <?php echo ($value['featured'] == 'yes') ? 'active' : 'inactive'; ?> radio_<?php echo ($value['featured'] == 'yes') ? 'active' : 'inactive'; ?>"><?php _e('yes', UT_THEME_NAME); ?></button>				
				<button value="<?php echo $key; ?>_no" type="button" class="btn btn-mini btn-info <?php echo ($value['featured'] == 'no') ? 'active' : 'inactive'; ?> radio_<?php echo ($value['featured'] == 'no') ? 'active' : 'inactive'; ?>"><?php _e('no', UT_THEME_NAME); ?></button>
				
				<input class="radiostate_<?php echo ($value['featured'] == 'yes') ? 'active' : 'inactive'; ?>" style="display:none;" id="<?php echo $key; ?>_yes" type="radio" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][featured]" value="yes" <?php echo ($value['featured'] == 'yes') ? 'checked="checked"' : ''; ?> /><br />
				<input class="radiostate_<?php echo ($value['featured'] == 'no') ? 'active' : 'inactive'; ?>" style="display:none;" id="<?php echo $key; ?>_no" type="radio" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][featured]" value="no" <?php echo ($value['featured'] == 'no') ? 'checked="checked"' : ''; ?> /><br />
				
				</div>
				
			</div>	
			
			<hr />
						
			<label><?php _e('Column Headline', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_head]" value="<?php echo $value['column_head']; ?>" /><br />
			
			<hr />
			
			<label><?php _e('Column Price', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_price]" value="<?php echo $value['column_price']; ?>" /><br />
			
			<hr />
			
			<label><?php _e('Price Currency', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_curr]" value="<?php echo $value['column_curr']; ?>" /><br />
			
			<hr />
			
			<label><?php _e('Period', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_time]" value="<?php echo $value['column_time']; ?>" /><br />
					
			<hr />
					
					<label><?php _e('Features', UT_THEME_NAME); ?></label><br />
					
					<div id="features_<?php echo $key; ?>">
					
					<?php foreach($value['column_content'] as $x => $feature) { ?>
						
						<div class="feature_item">			
						<input style="width:115px !important; min-width: inherit !important;" class="feature" id="feature_<?php echo $x; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_content][<?php echo $x; ?>]" value="<?php echo $value['column_content'][$x]; ?>" /><br />
						<button type="button" class="btn btn-mini btn-danger remove-feature"><i class="icon-remove icon-white"></i></button>
						</div>
							
					<?php } ?>
					
					</div>
				    <button data-key="<?php echo $key; ?>" data-table="<?php echo $table; ?>" type="button" value="features_<?php echo $key; ?>" class="add_column_feature right btn btn-mini btn-info" name="<?php echo $table; ?>"><i class="icon-picture icon-white"></i> <?php _e('Add Row', UT_THEME_NAME); ?></button>
	
					<div class="clear"></div>
			
			
			<hr />
			
			<label><?php _e('Custom URL', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_url]" value="<?php echo $value['column_url']; ?>" /><br />
			
			<hr />
			
			<label><?php _e('Buttontext', UT_THEME_NAME); ?></label><br />
			<input style="width:148px !important; min-width: inherit !important;" id="<?php echo $key; ?>" type="text" name="<?php echo $table; ?>[columns][<?php echo $key; ?>][column_buttontext]" value="<?php echo $value['column_buttontext']; ?>" /><br />
			
			
			</div>			
			
			<?php } // endforeach ?>
			
		<div class="clear"></div>	
		</div><!-- /#single  columns -->
	
	</div><!-- /#table items -->
		
	</form>
	</div>
	
<?php } ?>