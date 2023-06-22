<?php
$image_settings_data			= get_option('sc-wordai-image-settings-data');
$image_settings_data			= unserialize( $image_settings_data );

$generated_image_number			=	isset( $image_settings_data['sc-wordai-image-number'] )? $image_settings_data['sc-wordai-image-number'] : 2;
$generated_image_size			=	isset( $image_settings_data['sc-wordai-image-size'] )? $image_settings_data['sc-wordai-image-size'] : '256x256';
?>
 

 <div id="openai-image-settings-div-wrapper" class="">
  <h3 style="padding: 15px;"><?php echo __('OpenAI Image Settings', 'wordai-auto-content-writing');?></h3>  
   <table style="border-spacing: 15px;">
   		<tbody>
   		    <form id="scwordai-image-settings-form" method="post">
   		    <tr>
   		    	<td colspan="2">
   		    		<button type="submit" class="button button-primary button-large"><?php echo __('Save OpenAI Image Settings', 'wordai-auto-content-writing');?></button>
   		    		<p class="sc-wordai-image-settings-msg"></p>
   		    	</td>
   		    </tr>
   			<tr>
   				<td>
   				    <h4>How many Image(s)</h4>   					   					
   					<?php
					    $image_numbers	= [ 1, 2, 3 ];	
						echo '<select id="sc-wordai-image-number" name="sc-wordai-image-number">';
						foreach ( $image_numbers as $key => $number ) {
							$selected = isset( $generated_image_number ) ? selected( $generated_image_number, $number, false) : selected( $number, 2 );
							echo '<option value="'.  $number .'" ' . $selected . '>' . $number . '</option>';							
						}			
						echo '</select>';					
					?>
   					
   				</td>
   				<td>   					
   					<p><?php echo __('How many image(s) you want to generate.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4><?php echo __('Size of the generated images', 'wordai-auto-content-writing');?></h4>   					 
   					<?php
					    $image_sizes	=	[ '256x256', '512x512', '1024x1024' ];
						echo '<select id="sc-wordai-image-size" name="sc-wordai-image-size">';
						foreach ( $image_sizes as $key => $size ) {
							$selected = isset( $generated_image_size ) ? selected( $generated_image_size, $size, false) : selected( $size, '256x256');
							echo '<option value="'.  $size .'" ' . $selected . '>' . $size . '</option>';							
						}			
						echo '</select>';					
					?>   					 
   				</td>
   				<td>   					
   					<p><?php echo __('The size of the generated images. Must be one of 256x256, 512x512, or 1024x1024. Smaller sizes are faster to generate.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			     			   			 			
   		  </form>	   			   			   			   			   			
   		</tbody>
   </table>  
    
</div>
