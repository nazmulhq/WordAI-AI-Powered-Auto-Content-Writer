<?php 
// check if the user have submitted the settings
if ( isset($_GET['settings-updated'] ) ) {
   add_settings_error('scwordai-settings-messages', 'scwordai-settings-messages', __('Settings Saved', 'wordai-auto-content-writing'), 'updated');
}
// show error / update messages
settings_errors('scwordai-settings-messages');
?>
<div class="wrap">
  <form action="options.php" method="post">
      <?php
      settings_fields('scwordai-settings');
      do_settings_sections('scwordai-settings');
      submit_button('Save API Key');
      ?>
  </form>
   
   <?php
	/*
        $test_apikey 			= true;
   		$options 				= get_option('scwordai-settings');		
	    if ( $options ) {
			$apikey 			= $options['scwordai-settings-field-apikey'];
			if ( ! isset( $apikey ) || empty( $apikey ) ) {
				$test_apikey = false;
			}
			
			if ( $test_apikey ) {
			?>
			   <hr />
			   <table style="width:100%">
					<thead></thead>
					<tbody>
						<tr>
							<td class="tbl-td-label-name">
								<a href="Javascript:void(0);" class="sc-wordai-test-email button button-primary">Test OpenAI API Key</a>
							</td>							
							<td class="sc-wordai-test-apikey-message"></td>
						</tr>
																		
					</tbody>
			   </table>
			   <hr />			   			   
	   <?php }
		}	
		*/
	?>            
    
</div><!-- /.wrap -->  

<br/><br/><br/><br/>
<button class="button button-primary test-openai-btn">Test OpenAI API</button>  
<br/><br/><br/><br/>







<!-- The modal / dialog box - hidden by default -->
<div id="openai-settings-dialog" class="hidden" style="margin: 25px;">
  <h3>Settings</h3>
  <div style="text-align: right;"><button class="button button-primary save-openai-settings-btn">Save</button></div>
   <table style="border-spacing: 15px;">
   		<tbody>
   		
   			<tr>
   				<td>
   				    <h4>Temperature</h4>
   					<input type="number" class="temperature-input" value="0.2" /><br/>
   					<p class="temperature-slider"></p>
   				</td>
   				<td>   					
   					<p><small>What sampling temperature to use, between 0 and 2. Higher values like 0.8 will make the output more random, while lower values like 0.2 will make it more focused and deterministic. Generally recommend altering this or top_p but not both.</small></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4>top_p</h4>
   					<input type="number" class="top-p-input" value="0.1" /><br/>   					
   				</td>
   				<td>   					
   					<p><small>An alternative to sampling with temperature, called nucleus sampling, where the model considers the results of the tokens with top_p probability mass. So 0.1 means only the tokens comprising the top 10% probability mass are considered.Generally recommend altering this or temperature but not both.</small></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4>max_tokens</h4>
   					<input type="number" class="max-tokens-input" value="1024" /><br/>   					
   				</td>
   				<td>   					
   					<p><small>The maximum number of tokens to generate in the completion.The token count of your prompt plus max_tokens cannot exceed the model's context length.</small></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4>presence_penalty</h4>
   					<input type="number" class="presence-penalty-input" value="0" /><br/>
   					<p class="presence-penalty-slider"></p>
   				</td>
   				<td>   					
   					<p><small>Number between -2.0 and 2.0. Positive values penalize new tokens based on whether they appear in the text so far, increasing the model's likelihood to talk about new topics.</small></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4>frequency_penalty</h4>
   					<input type="number" class="frequency-penalty-input" value="0" /><br/>
   					<p class="frequency-penalty-slider"></p>
   				</td>
   				<td>   					
   					<p><small>Number between -2.0 and 2.0. Positive values penalize new tokens based on their existing frequency in the text so far, decreasing the model's likelihood to repeat the same line verbatim.</small></p>
   				</td>   				
   			</tr>
   			  
   			<tr>
   				<td>
   				    <h4>best_of</h4>
   					<input type="number" class="best-of-input" value="1" /><br/>   					
   				</td>
   				<td>   					
   					<p><small>Generates best_of completions server-side and returns the "best" (the one with the highest log probability per token). Results cannot be streamed.</small></p>
   				</td>   				
   			</tr>
   			   			 			

   			<tr>
   				<td>
   				    <h4>stop</h4>
   					<input type="text" class="stop-input" value="" placeholder="\n" /><br/>   					
   				</td>
   				<td>   					
   					<p><small>Up to 4 sequences where the API will stop generating further tokens. The returned text will not contain the stop sequence.</small></p>
   				</td>   				
   			</tr>
   			   			   			   			
   			
   			
   		</tbody>
   </table>  
    
</div>

<!-- This script should be enqueued properly in the footer -->
<script>
jQuery(document).ready(function($) {	
 	console.log('Dialog COnfig!');	
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
	
	
	// Sliders config
	$('.temperature-slider').slider({
		min: 0,
		max:2,
		value:0.2,
		step: 0.1,
		change: function( event, ui ) { $('.temperature-input').val(ui.value); }
	});
	
	$('.presence-penalty-slider').slider({
		min: -2.0,
		max: 2,
		value: 0,
		step: 0.1,
		change: function( event, ui ) { $('.presence-penalty-input').val(ui.value); }
	});
	
	$('.frequency-penalty-slider').slider({
		min: -2.0,
		max: 2,
		value: 0,
		step: 0.1,
		change: function( event, ui ) { $('.frequency-penalty-input').val(ui.value); }
	});
	
	
	
});
		
</script>

<button class="button button-primary open-openai-settings-dialog">OpenAI Settings</a>

