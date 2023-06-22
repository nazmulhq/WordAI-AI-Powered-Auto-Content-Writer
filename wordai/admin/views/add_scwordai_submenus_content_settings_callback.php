<?php
$content_settings_data			= get_option('sc-wordai-content-settings-data');
$content_settings_data			= unserialize( $content_settings_data );

$language_code					=	isset( $content_settings_data['sc-wordai-language-code'] )? $content_settings_data['sc-wordai-language-code'] : 'en-US';
$writing_style_code				=	isset( $content_settings_data['sc-wordai-writing-style'] )? $content_settings_data['sc-wordai-writing-style'] : 'descriptive';
$writing_tone_code				=	isset( $content_settings_data['sc-wordai-writing-tone'] )? $content_settings_data['sc-wordai-writing-tone'] : 'informative';
$title_length_settings_code		=	isset( $content_settings_data['sc-wordai-title-length'] )? $content_settings_data['sc-wordai-title-length'] : '30n40';
$content_paragraph_setting_code	=	isset( $content_settings_data['sc-wordai-content-paragraphs'] )? $content_settings_data['sc-wordai-content-paragraphs'] : 3;

?>
 

 <div id="openai-content-settings-div-wrapper" class="">
  <h3 style="padding: 15px;"><?php echo __('OpenAI Content Settings', 'wordai-auto-content-writing');?></h3>  
   <table style="border-spacing: 15px;">
   		<tbody>
   		    <form id="scwordai-content-settings-form" method="post">
   		    <tr>
   		    	<td colspan="2">
   		    		<button type="submit" class="button button-primary button-large scwordai-apisettings-form-submit-btn"><?php echo __('Save OpenAI Content Settings', 'wordai-auto-content-writing');?></button>
   		    		<p class="sc-wordai-content-settings-msg"></p>
   		    	</td>
   		    </tr>
   			<tr>
   				<td>
   				    <h4>Language</h4>   					   					
   					<?php
					    $languages	=	SC_Wordai_OpenAI::language_list();	
						echo '<select id="sc-wordai-language-code" name="sc-wordai-language-code">';
						foreach ( $languages as $key => $lang ) {
							$selected = isset( $language_code ) ? selected( $language_code, $lang['code'], false) : selected( $lang['code'], 'en-US');
							echo '<option value="'.  $lang['code'] .'" ' . $selected . '>' . $lang['name'] . '</option>';							
						}			
						echo '</select>';					
					?>
   					
   				</td>
   				<td>   					
   					<p><?php echo __('Select the language you want OpenAI to write titles, content.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4><?php echo __('Writing Style', 'wordai-auto-content-writing');?></h4>   					 
   					<?php
					    $writing_styles	=	SC_Wordai_OpenAI::writing_styles();	
						echo '<select id="sc-wordai-writing-style" name="sc-wordai-writing-style">';
						foreach ( $writing_styles as $stylecode => $stylename ) {
							$selected = isset( $writing_style_code ) ? selected( $writing_style_code, $stylecode, false) : selected( $stylecode, 'descriptive');
							echo '<option value="'.  $stylecode .'" ' . $selected . '>' . $stylename . '</option>';							
						}			
						echo '</select>';					
					?>   					 
   				</td>
   				<td>   					
   					<p><?php echo __('An article is an effective format to package and deliver information to a larger audience. Depending on its purpose, an article will most likely fit into one of four types: expository, persuasive, narrative, or descriptive.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4><?php echo __('Writing Tone', 'wordai-auto-content-writing');?></h4>
   					<?php
					    $writing_tones	=	SC_Wordai_OpenAI::writing_tones();	
						echo '<select id="sc-wordai-writing-tone" name="sc-wordai-writing-tone">';
						foreach ( $writing_tones as $tonecode => $tonename ) {
							$selected = isset( $writing_tone_code ) ? selected( $writing_tone_code, $tonecode, false) : selected( $tonecode, 'informative');
							echo '<option value="'.  $tonecode .'" ' . $selected . '>' . $tonename . '</option>';							
						}			
						echo '</select>';					
					?>   					 
   				</td>
   				<td>   					
   					<p><?php echo __('There are as many tones in writing as there are human emotions. The differences between these tones are the context, syntax, and diction that authors employ to cultivate personalities and emotions in characters or to appeal to their readers.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			
   			<tr>
   				<td>
   				    <h4><?php echo __('Title Length', 'wordai-auto-content-writing');?></h4>
   					<?php
					    $title_lengths	=	SC_Wordai_OpenAI::title_lengths();	
						echo '<select id="sc-wordai-title-length" name="sc-wordai-title-length">';
						foreach ( $title_lengths as $title_length_code => $title_length_name ) {
							$selected = isset( $title_length_settings_code) ? selected( $title_length_settings_code, $title_length_code, false) : selected( $title_length_code, '30n40');
							echo '<option value="'.  $title_length_code .'" ' . $selected . '>' . $title_length_name . '</option>';							
						}			
						echo '</select>';					
					?>   					 
   				</td>
   				<td>   					
   					<p><?php echo __('Title length will be between the selected characters long.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>

   			<tr>
   				<td>
   				    <h4><?php echo __('Content Paragraph number', 'wordai-auto-content-writing');?></h4>
   					<?php
					    $content_paragraphs	=	SC_Wordai_OpenAI::content_paragraphs();	
						echo '<select id="sc-wordai-content-paragraphs" name="sc-wordai-content-paragraphs">';
						foreach ( $content_paragraphs as $content_paragraph_code => $content_paragraph_name ) {
							$selected = isset( $content_paragraph_setting_code) ? selected( $content_paragraph_setting_code, $content_paragraph_code, false) : selected( $content_paragraph_code, '3');
							echo '<option value="'.  $content_paragraph_code .'" ' . $selected . '>' . $content_paragraph_name . '</option>';							
						}			
						echo '</select>';					
					?>   					 
   				</td>
   				<td>   					
   					<p><?php echo __('How many paragraph you want to generate for your article / content.', 'wordai-auto-content-writing');?></p>
   				</td>   				
   			</tr>
   			     			   			 			
   		  </form>	   			   			   			   			   			
   		</tbody>
   </table>  
    
</div>
