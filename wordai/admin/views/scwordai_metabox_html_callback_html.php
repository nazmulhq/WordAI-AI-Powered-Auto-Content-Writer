<button class="button button-primary button-large metabox-content-writer-btn"><i class="dashicons dashicons-welcome-write-blog"></i> <?php echo __('WordAI Content Writer', 'wordai-auto-content-writing');?></button>

<!-- Metabox button click popup dialog - The modal / dialog box - hidden by default -->
<div id="metabox-button-click-dialog" style="margin: 25px; display: none;">    
   <table style="border-spacing: 15px;">
   		<tbody>
   		
   			<tr>
   				<td>   					
   					<p>
   					    <h4>Prompt</h4>
   					    <p class="prompt-alert-msg"></p>
   						<input type="text" name="scwordai-prompt" class="scwordai-prompt" placeholder="Write your prompt..." style="width: 650px; height: 50px;"  />
   					</p>
   					<p>
   						<button class="button button-primary sc-wordai-write-titles-btn"><?php echo __('Write Title', 'wordai-auto-content-writing');?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   						<button class="button button-primary sc-wordai-write-content-btn"><?php echo __('Write Content', 'wordai-auto-content-writing');?></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   						<button class="button button-primary sc-wordai-generate-image-btn"><?php echo __('Generate Image', 'wordai-auto-content-writing');?></button>   						
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
   					<div class="scwordai-title" style="display: none;" >  					    
   					    <h5>Generated Title</h5>
   						<textarea class="sc-wordai-generated-title" rows="2"></textarea>
   						<button class="button button-secondary sc-wordai-title-copy-btn" data-clipboard-target=".sc-wordai-generated-title"><?php echo __('Copy Title', 'wordai-auto-content-writing');?></button>
   						<button class="button button-secondary sc-wordai-title-insert-btn"><?php echo __('Insert Title', 'wordai-auto-content-writing');?></button>   						
   						
   					</div>		
   					<div class="scwordai-content" style="display: none;">
   					    <h5>Generated Content</h5>
   						<textarea class="sc-wordai-generated-content" rows="10"></textarea>   						
   						<button class="button button-secondary sc-wordai-content-copy-btn" data-clipboard-target=".sc-wordai-generated-content"><?php echo __('Copy Content', 'wordai-auto-content-writing');?></button>
   						<button class="button button-secondary sc-wordai-content-insert-btn"><?php echo __('Insert Content', 'wordai-auto-content-writing');?></button>   
   						<input type="hidden" class="sc-wordai-replace-withbr-response-format-content" /> 
   					</div>	
   					<div class="scwordai-image" style="display: none;">
   					    <h5>Generated Image(s)</h5>
   						<div class="sc-wordai-generated-image-wrapper">
   							<!--<div><img src="http://localhost/wp611/wp-content/uploads/2023/06/img3-6.jpg" alt=""></div>
   							<div><img src="http://localhost/wp611/wp-content/uploads/2023/06/img12-6.png" alt=""></div>
   							<div><img src="http://localhost/wp611/wp-content/uploads/2023/06/img3-6.jpg" alt=""></div>-->
   						</div>   						   						
   						<button class="button button-secondary sc-wordai-upload-image-btn"><?php echo __('Save to Gallery', 'wordai-auto-content-writing');?></button>  
   						<span class="save-image-to-gallery-icon dashicons dashicons-format-image" style="display: none;"></span>    						
   						<input type="hidden" class="sc-wordai-generated-images-urls" />   						
   					</div>		       					   					   						       					   					
   				</td>
   			</tr>   			   			   			   			   			   			
   		</tbody>
   </table>  
    
</div>
<!-- End metaboc button click popup dialog -->


<!-- This script should be enqueued properly in the footer -->
<script>
jQuery(document).ready(function($) {	
 	console.log('Metabox Dialog Config!');		
	// Metabox button click Popup Config
 	$('#metabox-button-click-dialog').dialog({
			title: sc_wordai_metabox_script_obj.metabox_popup_dialog_title,
			dialogClass: 'metabox-button-click-popup-dialog-class',
			autoOpen: false,
			draggable: true,
			//width: 'auto',	   	
		    minWidth: 800,
		    maxHeight: 1000,
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
			  //my: "center",
			  my: "top",	
			  //at: "center",
			  at: "top",	
			  of: window
			},
			open: function () {
			  // close dialog by clicking the overlay behind it
			  $('.ui-widget-overlay').bind('click', function() {
				$('#metabox-button-click-dialog').dialog('close');
			  });
			},
			create: function () {
			  // style fix for WordPress admin
			  $('.ui-dialog-titlebar-close').addClass('ui-button');			  
			}
		  });				
	
					
	
	// Insert Title
	$('body').on("click", ".sc-wordai-title-insert-btn", function() {
		let postTitle	=	$('.sc-wordai-generated-title').val();
        if ( sc_wordai_metabox_script_obj.current_posttype == 'post' || sc_wordai_metabox_script_obj.current_posttype == 'page' ) {		
			wp.data.dispatch('core/editor').editPost({title: postTitle});
		}
		else if ( sc_wordai_metabox_script_obj.current_posttype == 'product' ) {	
			if (tinymce.activeEditor) {
				$('#title').siblings('label').addClass('screen-reader-text');
				$('#title').val( postTitle );												
			}
		}
		else {
			console.log('Post Type Not Supported!');
		}
		
	});
	
	// Insert Content	
	$('.sc-wordai-content-insert-btn').click(function() {								
		//let postContent		= $('.sc-wordai-generated-content').val();	
		let postContent		= $('.sc-wordai-replace-withbr-response-format-content').val();
		console.log(postContent);				
		
        if ( sc_wordai_metabox_script_obj.current_posttype == 'post' || sc_wordai_metabox_script_obj.current_posttype == 'page' ) {		
			var el 			= wp.element.createElement;
			var name 		= 'core/paragraph';			
			insertedBlock 	= wp.blocks.createBlock(name, {				
				content: postContent,				
			});
			//wp.data.dispatch('core/editor').insertBlocks(insertedBlock);
			wp.data.dispatch( 'core/block-editor' ).insertBlocks(insertedBlock);					
		}
		else if ( sc_wordai_metabox_script_obj.current_posttype == 'product' ) {	
			if (tinymce.activeEditor) {								
				//tinymce.activeEditor.execCommand('mceInsertContent', false, postContent );
				var activeEditor = tinyMCE.get('content');
				activeEditor.setContent(postContent);
			}
		}
		else {
			console.log('Post Type Not Supported!')
		}
		
	});
				
	
	
	// Copy to clipboard
	new ClipboardJS('.sc-wordai-title-copy-btn,.sc-wordai-content-copy-btn');
	
	// Title
	$('.sc-wordai-title-copy-btn').click(function() {
		$('.sc-wordai-title-copy-btn').html(sc_wordai_metabox_script_obj.copied);
		setInterval( function() { $('.sc-wordai-title-copy-btn').html(sc_wordai_metabox_script_obj.copy_title); }, 6000 );
	});
	$('sc-wordai-title-insert-btn').click(function() {
		$('.sc-wordai-title-insert-btn').html(sc_wordai_metabox_script_obj.inserted);
		setInterval( function() { $('.sc-wordai-title-insert-btn').html(sc_wordai_metabox_script_obj.insert_title); }, 6000 );
	});
		
	// Content
	$('.sc-wordai-content-copy-btn').click(function() {
		$('.sc-wordai-content-copy-btn').html(sc_wordai_metabox_script_obj.copied);
		setInterval( function() { $('.sc-wordai-content-copy-btn').html(sc_wordai_metabox_script_obj.copy_content); }, 6000 );
	});
	$('.sc-wordai-content-insert-btn').click(function() {
		$('.sc-wordai-content-insert-btn').html(sc_wordai_metabox_script_obj.inserted);
		setInterval( function() { $('.sc-wordai-content-insert-btn').html(sc_wordai_metabox_script_obj.insert_content); }, 6000 );
	});
	
	
	
});
		
		
</script>


