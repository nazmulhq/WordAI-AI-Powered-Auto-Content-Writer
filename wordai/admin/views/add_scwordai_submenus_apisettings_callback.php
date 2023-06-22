<?php 
// check if the user have submitted the settings
if ( isset($_GET['settings-updated'] ) ) {
   add_settings_error('scwordai-settings-messages', 'scwordai-settings-messages', __('Settings Saved', 'wordai-auto-content-writing'), 'updated');
}
// show error / update messages
settings_errors('scwordai-settings-messages');
?>
<div class="wrap openai-api-key-save-div-wrapper">
  <form action="options.php" method="post">
      <?php
      settings_fields('scwordai-settings');
      do_settings_sections('scwordai-settings');
      submit_button( __('Save API Key', 'wordai-auto-content-writing') );
      ?>
  </form>
       
</div><!-- /.wrap -->  


<?php
$api_settings_data				= get_option('sc-wordai-apisettings-data');
$api_settings_data				= unserialize( $api_settings_data);

$temperature_value				=	isset( $api_settings_data['sc-wordai-temperature'] )? $api_settings_data['sc-wordai-temperature'] : 0.2;
$top_p_value					=	isset( $api_settings_data['sc-wordai-top-p'] )? $api_settings_data['sc-wordai-top-p'] : 0.1;
$max_tokens_value				=	isset( $api_settings_data['sc-wordai-max-tokens'] )? $api_settings_data['sc-wordai-max-tokens'] : 1024;
$presence_penalty_input_value	=	isset( $api_settings_data['sc-wordai-presence-penalty-input'] )? $api_settings_data['sc-wordai-presence-penalty-input'] : 0;
$frequency_penalty_input_value	=	isset( $api_settings_data['sc-wordai-frequency-penalty-input'] )? $api_settings_data['sc-wordai-frequency-penalty-input'] : 0;
$best_of_input_value			=	isset( $api_settings_data['sc-wordai-best-of-input'] )? $api_settings_data['sc-wordai-best-of-input'] : 1;
$stop_input_value				=	isset( $api_settings_data['sc-wordai-stop-input'] )? $api_settings_data['sc-wordai-stop-input'] : '\n';

?>
<div id="openai-settings-div-wrapper" class="">
  <h3 style="padding: 15px;"><?php echo __('OpenAI API Settings', 'wordai-auto-content-writing');?></h3>  
   <table style="border-spacing: 15px;">
   		<tbody>
   		    <form id="scwordai-apisettings-form" method="post">
   		    <tr>
   		    	<td colspan="2">
   		    		<button type="submit" class="button button-primary button-large scwordai-apisettings-form-submit-btn"><?php echo __('Save OpenAI Settings','wordai-auto-content-writing');?></button>
   		    		<p class="sc-wordai-api-settings-msg"></p>
   		    	</td>
   		    </tr>
   			<tr>
   				<td>
   				    <h4><?php echo __('Temperature', 'wordai-auto-content-writing');?></h4>
   					<input type="text" name="sc-wordai-temperature" class="temperature-input" value="<?php echo $temperature_value;?>" readonly /><br/>
   					<p class="temperature-slider"></p>
   				</td>
   				<td>   					
   					<p><?php echo __('What sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. Generally recommend altering this or top_p but not both.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4><?php echo __('top_p', 'wordai-auto-content-writing');?></h4>
   					<input type="number" name="sc-wordai-top-p" class="top-p-input" value="<?php echo $top_p_value;?>" /><br/>   					
   				</td>
   				<td>   					
   					<p><?php echo __('An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass. So 0.1 means only the tokens comprising the top 10% probability mass are considered.Generally recommend altering this or temperature but not both.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4><?php echo __('max_tokens', 'wordai-auto-content-writing');?></h4>
   					<input type="number" name="sc-wordai-max-tokens" class="max-tokens-input" value="<?php echo $max_tokens_value;?>" /><br/>   					
   				</td>
   				<td>   					
   					<p><?php echo __('The maximum number of tokens to generate in the completion.The token count of your prompt plus max_tokens cannot exceed the model\'s context length.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4><?php echo __('presence_penalty', 'wordai-auto-content-writing');?></h4>
   					<input type="text" name="sc-wordai-presence-penalty-input" class="presence-penalty-input" value="<?php echo $presence_penalty_input_value;?>" readonly /><br/>
   					<p class="presence-penalty-slider"></p>
   				</td>
   				<td>   					
   					<p><?php echo __('Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model\'s likelihood to talk about new topics.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4><?php echo __('frequency_penalty', 'wordai-auto-content-writing');?></h4>
   					<input type="text" name="sc-wordai-frequency-penalty-input" class="frequency-penalty-input" value="<?php echo $frequency_penalty_input_value;?>" readonly /><br/>
   					<p class="frequency-penalty-slider"></p>
   				</td>
   				<td>   					
   					<p><?php echo __('Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model\'s likelihood to repeat the same line verbatim.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			  
   			<tr>
   				<td>
   				    <h4><?php echo __('best_of', 'wordai-auto-content-writing');?></h4>
   					<input type="number" name="sc-wordai-best-of-input" class="best-of-input" value="<?php echo $best_of_input_value;?>" /><br/>   					
   				</td>
   				<td>   					
   					<p><?php echo __('Generates best_of completions server-side and returns the "best" (the one with the highest log probability per token). Results cannot be streamed.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			   			 			

   			<tr>
   				<td>
   				    <h4><?php echo __('stop', 'wordai-auto-content-writing');?></h4>
   					<input type="text" name="sc-wordai-stop-input" class="stop-input" value="<?php echo $stop_input_value;?>" /><br/>   					
   				</td>
   				<td>   					
   					<p><?php echo __('Up to 4 sequences where the API will stop generating further tokens. The returned text will not contain the stop sequence.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   		  </form>	   			   			   			   			   			
   		</tbody>
   </table>  
    
</div>

<!-- This script should be enqueued properly in the footer -->
<script>
jQuery(document).ready(function($) {	
	
 	/*console.log('Dialog COnfig!');	
	// Popup Config
 	$('#openai-settings-dialog').dialog({
			title: 'OpenAI Settings',
			dialogClass: 'wp-dialog',
			autoOpen: false,
			draggable: true,
			//width: 'auto',	   	
		    minWidth: 1000,
		    maxHeight: 800,
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
			  my: "center",
			  at: "center",
			  of: window
			},
			open: function () {
			  // close dialog by clicking the overlay behind it
			  $('.ui-widget-overlay').bind('click', function() {
				$('#openai-settings-dialog').dialog('close');
			  });
			},
			create: function () {
			  // style fix for WordPress admin
			  $('.ui-dialog-titlebar-close').addClass('ui-button');
			}
		  });
	 */
	
	// Sliders config
	$('.temperature-slider').slider({
		value: "<?php echo $temperature_value;?>",
		step: 0.1,		
		min: 0,
		max: 2.0,		
		change: function( event, ui ) { $('.temperature-input').val(ui.value); }
	});
	
	$('.presence-penalty-slider').slider({
		min: -2.0,
		max: 2,
		value: "<?php echo $presence_penalty_input_value;?>",
		step: 0.1,
		change: function( event, ui ) { $('.presence-penalty-input').val(ui.value); }
	});
	
	$('.frequency-penalty-slider').slider({
		min: -2.0,
		max: 2,
		value: "<?php echo $frequency_penalty_input_value;?>",
		step: 0.1,
		change: function( event, ui ) { $('.frequency-penalty-input').val(ui.value); }
	});
	
	
	
});
		
</script>

<!--<button class="button button-primary open-openai-settings-dialog">OpenAI Settings</a>-->

