jQuery(document).ready(function($) {				
	console.log('Localized WordAI Data');	
	console.log( sc_wordai_metabox_script_obj );	
	// Write Titles button Click
	$('body').on( "click", ".sc-wordai-write-titles-btn", function() {
		let promptAlertMsg	=	$('.prompt-alert-msg');
		let params 					= {};
		params.prompt				= $('.scwordai-prompt').val();		
		
		if ( $.trim( params.prompt).length == 0 ) {
			promptAlertMsg.html('<span class="alert-remind">'+sc_wordai_metabox_script_obj.write_ur_prompt+'</span>');
		} 
		else {		
			$(this).attr("disabled", "disabled" );
			$('.wave-animation-row').fadeIn(300);
			
			
			$.ajax({
			  type:"POST",
			  cache: false,
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_write_titles',
					security : sc_wordai_metabox_script_obj.nonce,
					params   : params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					promptAlertMsg.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.generated_title_success+'</span>');
					$('.sc-wordai-generated-title').val( jsonData.responseText );
					$('.sc-wordai-write-titles-btn').removeAttr('disabled');
					 
					$('.wave-animation-row').fadeOut(300);  
					$('.scwordai-title').fadeIn(300);  
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  promptAlertMsg.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  $('.sc-wordai-write-titles-btn').removeAttr('disabled');
					  
					  $('.wave-animation-row').fadeOut(300);  
				  }
				  else {
					  promptAlertMsg.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');
					  $('.wave-animation-row').fadeOut(300);  
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				 $('.sc-wordai-write-titles-btn').removeAttr('disabled');
				  
				 $('.wave-animation-row').fadeOut(300);   
				}
			});
		}
				
	});
	
	
	// Write Suggest Titles button Click - in suggest titles popup dialog
	$('body').on( "click", ".sc-wordai-write-suggest-titles-btn", function() {
		let promptAlertMsg			= $('.prompt-alert-msg');
		let scWordAiSuggesTitlesBtn	= $('.sc-wordai-write-suggest-titles-btn');
		let params 					= {};
		params.prompt				= $('.scwordai-prompt').val();		
		
		if ( $.trim( params.prompt).length == 0 ) {
			promptAlertMsg.html('<span class="alert-remind">'+sc_wordai_metabox_script_obj.write_ur_prompt+'</span>');
		} 
		else {		
			$(this).attr("disabled", "disabled" );
			$('.wave-animation-row').fadeIn(300);
			
			
			$.ajax({
			  type:"POST",
			  cache: false,
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_write_suggest_titles',
					security : sc_wordai_metabox_script_obj.nonce,
					params   : params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					promptAlertMsg.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.generated_title_success+'</span>');
					$('.sc-wordai-suggested-titles-list').html( jsonData.listOfTitles );
					  
					scWordAiSuggesTitlesBtn.removeAttr('disabled');					 
					$('.wave-animation-row').fadeOut(300);  
					$('.scwordai-suggested-title-wrapper').fadeIn(300);  
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  promptAlertMsg.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  scWordAiSuggesTitlesBtn.removeAttr('disabled');
					  
					  $('.wave-animation-row').fadeOut(300);  
				  }
				  else {
					  promptAlertMsg.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');
					  $('.wave-animation-row').fadeOut(300);  
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				 scWordAiSuggesTitlesBtn.removeAttr('disabled');				  
				 $('.wave-animation-row').fadeOut(300);   
				}
			});
		}
				
	});
	
	// Update Suggested Title
	$('body').on("click", ".sc-wordai-suggested-title-update-btn", function() {
		let updateSuggestedtitleBtn	= $('.update-suggested-title-msg');			
		let postID			=	$(this).data('scwordai-updatebtn-postid');
		let selectedTitle	= '';
		$('.suggested-title-radio').each( function() {
			if ( $(this).is(":checked") ) {
				let selectedTitle		=	$(this).parent().text();
				console.log(selectedTitle);
				console.log(postID);
				let params	=	{};
				params.selectedTitle	= selectedTitle;
				params.postID			= postID;
				
				$.ajax({
				  type:"POST",
				  cache: false,
				  url: sc_wordai_metabox_script_obj.adminajax_url,
				  data : {			    
						action 	 : 'sc_wordai_update_suggest_title',
						security : sc_wordai_metabox_script_obj.nonce,
						params   : params
						},		  
				  success: function(data) { 
					 console.log(data); 
					 let jsonData	= JSON.parse( data );
					  if ( jsonData.status == 'success' ) {
						$('#post-'+postID+' .row-title' ).html(selectedTitle).addClass('alert-change');  
						setInterval(function() { $('#post-'+postID+' .row-title' ).html(selectedTitle).removeClass('alert-change');}, 3000);  
						updateSuggestedtitleBtn.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.updated_title_success+'</span>');						
					  }
					  else if ( jsonData.status == 'fail' ) {					  
						  updateSuggestedtitleBtn.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  }
					  else {
						  updateSuggestedtitleBtn.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');						  
					  }
					},
				  error: function( xhr, status, error ) { 
					 console.log(xhr); 
					 console.log(status); 
					 console.log(error); 				 					 					 
					}
				});								
			}
		});		
	});
	
	
	// Write Content button Click
	$('body').on( "click", ".sc-wordai-write-content-btn", function() {
		let promptAlertMsg	=	$('.prompt-alert-msg');
		promptAlertMsg.html("");
		let params 					= {};
		params.prompt				= $('.scwordai-prompt').val();		
		
		if ( $.trim( params.prompt).length == 0 ) {
			promptAlertMsg.html('<span class="alert-remind">'+sc_wordai_metabox_script_obj.write_ur_prompt+'</span>');
		} 
		else {		
			$(this).attr("disabled", "disabled" );
			$('.wave-animation-row').fadeIn(300);
			
			$.ajax({
			  type:"POST",
			  cache: false,
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_write_content',
					security : sc_wordai_metabox_script_obj.nonce,
					params   : params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					promptAlertMsg.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.generated_content_success+'</span>');
					$('.sc-wordai-replace-withbr-response-format-content').val(jsonData.responseTextWithBR);  
					$('.sc-wordai-generated-content').val( jsonData.responseText );					
					$('.sc-wordai-write-content-btn').removeAttr('disabled');
					
					$('.wave-animation-row').fadeOut(300);  
					$('.scwordai-content').fadeIn(300);    
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  promptAlertMsg.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  $('.sc-wordai-write-content-btn').removeAttr('disabled');
					  
					  $('.wave-animation-row').fadeOut(300);  
				  }
				  else {
					  promptAlertMsg.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');
					  $('.wave-animation-row').fadeOut(300);  
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				 $('.sc-wordai-write-content-btn').removeAttr('disabled');
				 $('.wave-animation-row').fadeOut(300);  
				}
			});
		}
				
	});
	
	
	// Generate Image Button Click	
	$('body').on( "click", ".sc-wordai-generate-image-btn", function() {
		let promptAlertMsg			= $('.prompt-alert-msg');
		let	scWordAiGenerateImgBtn	= $('.sc-wordai-generate-image-btn');
		promptAlertMsg.html("");
		let params 					= {};
		params.prompt				= $('.scwordai-prompt').val();			
		
		if ( $.trim( params.prompt).length == 0 ) {
			promptAlertMsg.html('<span class="alert-remind">'+sc_wordai_metabox_script_obj.write_ur_prompt+'</span>');
		} 
		else {		
			$(this).attr("disabled", "disabled" );
			$('.wave-animation-row').fadeIn(300);
			
			$.ajax({
			  type:"POST",
			  cache: false,
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_generate_image',
					security : sc_wordai_metabox_script_obj.nonce,
					params   : params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					promptAlertMsg.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.generated_image_success+'</span>');
					$('.sc-wordai-generated-images-urls').val(jsonData.openAIImgURLs); // commae separated image urls  
					let generatedImgUrlsArr	=  jsonData.openAIImgURLs.split(',');
					for ( let i=0; i < generatedImgUrlsArr.length; i++ ) {
						console.log('Img URL: ' + generatedImgUrlsArr[i]);
						let imgDiv	= '<div><img src="'+generatedImgUrlsArr[i]+'" alt=""></div>';
						$('.sc-wordai-generated-image-wrapper').append(imgDiv);
					}  
					  					  
					scWordAiGenerateImgBtn.removeAttr('disabled');					
					$('.wave-animation-row').fadeOut(300);  
					$('.scwordai-image').fadeIn(300);    
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  promptAlertMsg.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  scWordAiGenerateImgBtn.removeAttr('disabled');					  
					  $('.wave-animation-row').fadeOut(300);  
				  }
				  else {
					  promptAlertMsg.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');
					  $('.wave-animation-row').fadeOut(300);  
					  scWordAiGenerateImgBtn.removeAttr('disabled');					  
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				 scWordAiGenerateImgBtn.removeAttr('disabled');
				 $('.wave-animation-row').fadeOut(300);  
				}
			});
		}
				
	});
	
	
	// Upload image to media button Click
	$('body').on( "click", ".sc-wordai-upload-image-btn", function() {
		let promptAlertMsg			= $('.prompt-alert-msg');
		let scWordAiUploadImgBtn	= $('.sc-wordai-upload-image-btn');
		let saveImageToGalleryIcon	= $('.save-image-to-gallery-icon');
		promptAlertMsg.html("");
		let params 					= {};
		params.prompt				= $('.scwordai-prompt').val();	
		params.imgURLs				= $('.sc-wordai-generated-images-urls').val();		
		
		if ( $.trim( params.prompt).length == 0 ) {
			promptAlertMsg.html('<span class="alert-remind">'+sc_wordai_metabox_script_obj.write_ur_prompt+'</span>');
		} 
		else {		
			$(this).attr("disabled", "disabled" );
			saveImageToGalleryIcon.fadeIn(300);
			
			$.ajax({
			  type:"POST",
			  cache: false,
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_upload_image_to_wp_media',
					security : sc_wordai_metabox_script_obj.nonce,
					params   : params
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					promptAlertMsg.html('<span class="alert-success">'+sc_wordai_metabox_script_obj.images_saved_to_gallery+'</span>');										
					scWordAiUploadImgBtn.removeAttr('disabled');					
					saveImageToGalleryIcon.fadeOut(300);  					
				  }
				  else if ( jsonData.status == 'fail' ) {					  
					  promptAlertMsg.html( '<span class="alert-error">'+ jsonData.errorMessage +'</span>' );					  					  					  
					  scWordAiUploadImgBtn.removeAttr('disabled');					  
					  saveImageToGalleryIcon.fadeOut(300);  
				  }
				  else {
					  promptAlertMsg.html('<span class="alert-error">'+sc_wordai_metabox_script_obj.something_went_wrong+'</span>');
					  saveImageToGalleryIcon.fadeOut(300);  
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 				 
				 scWordAiUploadImgBtn.removeAttr('disabled');
				 saveImageToGalleryIcon.fadeOut(300);  
				}
			});
		}
				
	});
	
	
	// Metabox content write button click - popup dialog display
	$('body').on( "click", ".metabox-content-writer-btn", function(e) {
		console.log('Metabox clicked');
		e.preventDefault();
		$('#metabox-button-click-dialog').dialog('open');		
		return false;
	});
	
	// Test OpenAI API
	$('.test-openai-btn').click(function() {
		
		$.ajax({
		  type:"POST",
		  cache: false,
		  url: sc_wordai_metabox_script_obj.adminajax_url,
		  data : {			    
                action 	 : 'sc_wordai_api_test',
                security : sc_wordai_metabox_script_obj.nonce,			    
                },		  
		  success: function(data) { 
		  	 console.log(data); 
			},
		  error: function( xhr, status, error ) { 
		  	 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 
			}
		});
				
	});
	
	
	// OpenAi API settings form data
	$('body').on("submit", "#scwordai-apisettings-form", function() {
		let postFormData	= $(this).serialize();
		console.log(postFormData);
		$('.sc-wordai-api-settings-msg').html('');
		$.ajax({
			type: 'POST',
		  url: sc_wordai_metabox_script_obj.adminajax_url,
		  data : {			    
                action 	 : 'sc_wordai_apisettings_data',
                security : sc_wordai_metabox_script_obj.nonce,			    
			    postData : postFormData,	
                },		  
		  success: function(data) { 
		  	 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
				  $('.sc-wordai-api-settings-msg').html('<span class="success-msg">'+sc_wordai_metabox_script_obj.saved_apisetting_success+'</span>');
			  }
			  else {
				  $('.sc-wordai-api-settings-msg').html('<span class="alert-msg">'+sc_wordai_metabox_script_obj.nothing_changes+'</span>');
			  }
			},
		  error: function( xhr, status, error ) { 
		  	 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 		
			 $('.sc-wordai-api-settings-msg').html('<span class="error-msg">'+sc_wordai_metabox_script_obj.failed_to_save+'</span>');
			}			
		});
		
		return false;
	});

	
	// OpenAi Content settings form data
	$('body').on("submit", "#scwordai-content-settings-form", function() {
		let postFormData	= $(this).serialize();
		console.log(postFormData);
		$('.sc-wordai-content-settings-msg').html('');
		$.ajax({
			type: 'POST',
		  url: sc_wordai_metabox_script_obj.adminajax_url,
		  data : {			    
                action 	 : 'sc_wordai_content_settings_data',
                security : sc_wordai_metabox_script_obj.nonce,			    
			    postData : postFormData,	
                },		  
		  success: function(data) { 
		  	 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
				  $('.sc-wordai-content-settings-msg').html('<span class="success-msg">'+sc_wordai_metabox_script_obj.saved_content_setting_success+'</span>');
			  }
			  else {
				  $('.sc-wordai-content-settings-msg').html('<span class="alert-msg">'+sc_wordai_metabox_script_obj.nothing_changes+'</span>');
			  }
			},
		  error: function( xhr, status, error ) { 
		  	 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 		
			  $('.sc-wordai-content-settings-msg').html('<span class="error-msg">'+sc_wordai_metabox_script_obj.failed_to_save+'</span>');
			}			
		});
		
		return false;
	});
	
	// OpenAi Image settings form data
	$('body').on("submit", "#scwordai-image-settings-form", function() {
		let postFormData	= $(this).serialize();
		console.log(postFormData);
		let scWordAIImageSettingMsg	= $('.sc-wordai-image-settings-msg');
		scWordAIImageSettingMsg.html('');
		$.ajax({
			type: 'POST',
		  url: sc_wordai_metabox_script_obj.adminajax_url,
		  data : {			    
                action 	 : 'sc_wordai_image_settings_data',
                security : sc_wordai_metabox_script_obj.nonce,			    
			    postData : postFormData,	
                },		  
		  success: function(data) { 
		  	 console.log(data); 
			 let jsonData	= JSON.parse( data );
			  if ( jsonData.status == 'success' ) {
				  scWordAIImageSettingMsg.html('<span class="success-msg">'+sc_wordai_metabox_script_obj.saved_image_setting_success+'</span>');
			  }
			  else {
				  scWordAIImageSettingMsg.html('<span class="alert-msg">'+sc_wordai_metabox_script_obj.nothing_changes+'</span>');
			  }
			},
		  error: function( xhr, status, error ) { 
		  	 console.log(xhr); 
			 console.log(status); 
			 console.log(error); 		
			  scWordAIImageSettingMsg.html('<span class="error-msg">'+sc_wordai_metabox_script_obj.failed_to_save+'</span>');
			}			
		});
		
		return false;
	});
	
	
			 			
	// Suggest Titles Popup dialog config
 	$('#suggest-titles-button-click-dialog').dialog({
			title: sc_wordai_metabox_script_obj.popup_dialog_suggest_title,
			dialogClass: 'suggest-titles-button-click-popup-dialog-class',
			autoOpen: false,
			draggable: true,
			//width: 'auto',	   	
		    minWidth: 600,
		    maxHeight: 500,
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
			  my: "center",
			  //my: "top",	
			  at: "center",
			  //at: "top",	
			  of: window
			},
			open: function () {
			  // close dialog by clicking the overlay behind it
			  $('.ui-widget-overlay').bind('click', function() {
				$('#suggest-titles-button-click-dialog').dialog('close');
			  });
			},
			create: function () {
			  // style fix for WordPress admin
			  $('.ui-dialog-titlebar-close').addClass('ui-button');			  
			}
		  });					
	
	
		  // Suggest titles button click - open popup dialog	
		  $('body').on( "click", ".sc-wordai-suggest-titles", function(e) {
			console.log('Suggest titles button click');
			e.preventDefault();
			let postID		= $(this).data('scwordai-post-id');
			console.log(postID);
			//let chosenPostTitle	= $('#post-'+postID+' .title .row-title' ).html();
			let chosenPostTitle	= $('#post-'+postID+' .row-title' ).html();  
			console.log(chosenPostTitle);  
			$('.scwordai-prompt').val(chosenPostTitle);  
			$('.sc-wordai-suggested-title-update-btn').attr('data-scwordai-updatebtn-postid', postID);
			$('#suggest-titles-button-click-dialog').dialog('open');
		  });


	     // Save title number on select dropdown
	     $('body').on("change", "#scwordai-suggested-titles-number", function() {
			 let suggestedTitleNumber	=	$(this).val();
			 console.log('SuggestedTitleNumber:'+ suggestedTitleNumber );
			 $.ajax({
				type: 'POST',
			  url: sc_wordai_metabox_script_obj.adminajax_url,
			  data : {			    
					action 	 : 'sc_wordai_suggested_title_number_save',
					security : sc_wordai_metabox_script_obj.nonce,			    
					suggestedTitle : suggestedTitleNumber,	
					},		  
			  success: function(data) { 
				 console.log(data); 
				 let jsonData	= JSON.parse( data );
				  if ( jsonData.status == 'success' ) {
					  console.log('Saved suggested Title Number.')
				  }
				},
			  error: function( xhr, status, error ) { 
				 console.log(xhr); 
				 console.log(status); 
				 console.log(error); 						  
				}			
			});			 
		 });
	
	
	
	    // Placeholder texts for prompt input
	    let placeHolderTexts	=	[ "Write your prompt words...", "ex - A Red Horse", "ex - Beautiful Sky", "ex - Stormy Weather", "ex - Blue Tshirt" ];
	    $('.scwordai-prompt').placeholderTypewriter({ text: placeHolderTexts, delay: 25, pause:700});
	
}); // End jQuery(document).ready(function($)



(function ($) {
  "use strict";

  $.fn.placeholderTypewriter = function (options) {

    // Plugin Settings
    var settings = $.extend({
      delay: 50,
      pause: 1000,
      text: []
    }, options);

    // Type given string in placeholder
    function typeString($target, index, cursorPosition, callback) {

      // Get text
      var text = settings.text[index];

      // Get placeholder, type next character
      var placeholder = $target.attr('placeholder');
      $target.attr('placeholder', placeholder + text[cursorPosition]);

      // Type next character
      if (cursorPosition < text.length - 1) {
        setTimeout(function () {
          typeString($target, index, cursorPosition + 1, callback);
        }, settings.delay);
        return true;
      }

      // Callback if animation is finished
      callback();
    }

    // Delete string in placeholder
    function deleteString($target, callback) {

      // Get placeholder
      var placeholder = $target.attr('placeholder');
      var length = placeholder.length;

      // Delete last character
      $target.attr('placeholder', placeholder.substr(0, length - 1));

      // Delete next character
      if (length > 1) {
        setTimeout(function () {
          deleteString($target, callback)
        }, settings.delay);
        return true;
      }

      // Callback if animation is finished
      callback();
    }

    // Loop typing animation
    function loopTyping($target, index) {

      // Clear Placeholder
      $target.attr('placeholder', '');

      // Type string
      typeString($target, index, 0, function () {

        // Pause before deleting string
        setTimeout(function () {

          // Delete string
          deleteString($target, function () {
            // Start loop over
            loopTyping($target, (index + 1) % settings.text.length)
          })

        }, settings.pause);
      })

    }

    // Run placeholderTypewriter on every given field
    return this.each(function () {

      loopTyping($(this), 0);
    });

  };

}(jQuery));