<?php
$scwordai_suggested_title_number	=	get_option('sc-wordai-suggested-title-number');
?>
<!-- Start Suggest titles popup dialog -->
<div id="suggest-titles-button-click-dialog" style="margin: 25px; display: none;">    
   <table style="border-spacing: 15px;">
   		<tbody>
   		
   			<tr>
   				<td>   					
   					<p>
   					    <h4><?php echo __('Prompt', 'wordai-auto-content-writing');?></h4>
   					    <p class="prompt-alert-msg"></p>
   						<input type="text" name="scwordai-prompt" class="scwordai-prompt" placeholder="Write your prompt for title..." style="width: 400px; height: 50px;"  />
   					</p>
   					<p>
   						<button class="button button-primary sc-wordai-write-suggest-titles-btn"><?php echo __('Suggest Title(s)','wordai-auto-content-writing');?></button>  
   						<select id="scwordai-suggested-titles-number">
   							<?php for( $i = 1; $i < 4; $i++ ) {
	                                $selected = isset( $scwordai_suggested_title_number ) ? selected( $scwordai_suggested_title_number, $i, false) : selected( $i, 2 );
   									echo '<option value="'. $i .'" ' . $selected . '>'. $i .'</option>';
   							 }?>
   						</select> 						   						
   					</p>
   				</td>   				
   			</tr>
   			
   			<tr class="wave-animation-row" style="display: none;">
   				<td>
   					<div class="sc-wave">
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   						<div class="wave"></div>
   					</div>
   				</td>
   			</tr>
   			
   			<tr>
   				<td>      				    
   					<div class="scwordai-suggested-title-wrapper" style="display: none;" >  					    
   					    <h5><?php echo __('Generated Title(s)', 'wordai-auto-content-writing');?></h5>
   						<ul class="sc-wordai-suggested-titles-list">
   							<!--<li><input type="radio" class="suggested-title-radio" name="suggested-title-radio" /> First title</li>
   							<li><input type="radio" class="suggested-title-radio" name="suggested-title-radio" /> Second title</li>
   							<li><input type="radio" class="suggested-title-radio" name="suggested-title-radio" /> Third title</li>-->
   						</ul>   						
   						<button class="button button-secondary sc-wordai-suggested-title-update-btn"><?php echo __('Update Title', 'wordai-auto-content-writing');?></button>   
   						<span class="update-suggested-title-msg"></span>						   						
   					</div>		
   				</td>
   			</tr>   			   			   			   			   			   			
   		</tbody>
   </table>  
    
</div>
<!-- End Suggest titles popup dialog -->
