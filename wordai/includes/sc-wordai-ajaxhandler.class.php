<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordai_Ajaxhandler {
	
	private static $initiated             	  = false;		
	public static $output					  = [];	
				
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}								
	}
	
	private static function initiate_hooks() {		
		  add_action('admin_enqueue_scripts', array( __CLASS__, 'admin_required_scripts') );			  
		
		  add_action('wp_ajax_sc_wordai_api_test', [ __CLASS__, 'sc_wordai_api_test' ] );	
		  add_action('wp_ajax_sc_wordai_apisettings_data', [ __CLASS__, 'sc_wordai_apisettings_data' ] );	
		  add_action('wp_ajax_sc_wordai_content_settings_data', [ __CLASS__, 'sc_wordai_content_settings_data' ] );	 
		  add_action('wp_ajax_sc_wordai_image_settings_data', [ __CLASS__, 'sc_wordai_image_settings_data' ] );	 				
		
		
		  add_action( 'wp_ajax_sc_wordai_write_titles', [ __CLASS__, 'sc_wordai_write_titles'] );
		  add_action( 'wp_ajax_sc_wordai_write_suggest_titles', [ __CLASS__, 'sc_wordai_write_suggest_titles'] );
		  add_action( 'wp_ajax_sc_wordai_update_suggest_title', [ __CLASS__, 'sc_wordai_update_suggest_title'] );		
				 
		  add_action( 'wp_ajax_sc_wordai_write_content', [ __CLASS__, 'sc_wordai_write_content'] );
		
		  add_action( 'wp_ajax_sc_wordai_generate_image', [ __CLASS__, 'sc_wordai_generate_image'] );
		  add_action( 'wp_ajax_sc_wordai_upload_image_to_wp_media', [ __CLASS__, 'sc_wordai_upload_image_to_wp_media'] );
		
		  add_action( 'wp_ajax_sc_wordai_suggested_title_number_save', [ __CLASS__, 'sc_wordai_suggested_title_number_save'] );		 
		  
		
		  add_filter('plupload_default_settings', array( __CLASS__, 'sc_wordai_image_upload_mime_type_issue'), 10 , 1 ); 
		  
				  
		 		 		  
		  self::$initiated = true;
	}	
			
	public static function admin_required_scripts() {
		// get current admin screen
		global $pagenow;
		$screen = get_current_screen();
			    $loading_img 	= '<img src="' . esc_url( plugins_url( 'public/images/test-email.gif', dirname(__FILE__) ) ) . '" alt="SendingMail" /> ';
				wp_enqueue_style('sc-wordsmtp-style', plugins_url( '../admin/css/sc-wordai-misc-styles.css', __FILE__ ) , array(), SCWORDAI_VERSION, 'all' );		
		        wp_enqueue_style('sc-wordai-jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' , array(), SCWORDAI_VERSION, 'all' );		          
		
				wp_enqueue_script('sc-wordai-misc-script', plugins_url( '../admin/js/sc-wordai-misc-script.js', __FILE__ ) , array('jquery', 'jquery-ui-dialog', 'jquery-ui-core', 'jquery-ui-slider' ), SCWORDAI_VERSION, true);
				wp_enqueue_script('sc-wordai-copy-script', plugins_url( '../admin/js/clipboard.min.js', __FILE__ ) , array('jquery' ), SCWORDAI_VERSION, true);				        
				
		
				// localize script
				$nonce = wp_create_nonce( 'scwordai_wpnonce' );
				wp_localize_script(
					'sc-wordai-misc-script',
					'sc_wordai_metabox_script_obj',
					array(
						'adminajax_url'                  => admin_url('admin-ajax.php'),
						'nonce'                          => $nonce, 
						'current_screenid'               => $screen->id,
						'current_posttype'               => $screen->post_type,
						'current_pagenow'                => $pagenow,			
					
						'copy_title'         			 => __( 'Copy Title', 'wordai-auto-content-writing'),
						'insert_title'          		 => __( 'Insert Title', 'wordai-auto-content-writing'),
						'copy_content'         			 => __( 'Copy Content', 'wordai-auto-content-writing'),						
					    'insert_content'				 => __( 'Insert Content', 'wordai-auto-content-writing'),
					    'copied'                		 => __( 'Copied', 'wordai-auto-content-writing'),
					    'inserted'                		 => __( 'Inserted', 'wordai-auto-content-writing'), 
					
					    'write_ur_prompt'                => __( 'Please write your prompt!', 'wordai-auto-content-writing'),					 					
					    'generated_title_success'		=> __( 'Generated Title(s) Successfully!', 'wordai-auto-content-writing'), 
					    'generated_content_success'		=> __( 'Generated Content Successfully!', 'wordai-auto-content-writing'), 
					
					    'something_went_wrong'			=> __( 'Something went wrong!','wordai-auto-content-writing'),
					    'updated_title_success'			=> __( 'Updated Title Successfully!','wordai-auto-content-writing'),
					    'generated_image_success'		=> __( 'Generated Image(s) Successfully!','wordai-auto-content-writing'),	
					    'images_saved_to_gallery'		=> __( 'Image(s) saved to Gallery Successfully!','wordai-auto-content-writing'),
					    
					    'saved_apisetting_success'		=> __( 'Saved OpenAI API settings successfully!','wordai-auto-content-writing'),
					    'nothing_changes'				=> __( 'Nothing changes!','wordai-auto-content-writing'),
					    'failed_to_save'				=> __( 'Failed to save, try again!','wordai-auto-content-writing'),
					
					    'saved_content_setting_success'	=> __( 'Saved OpenAI content settings successfully!','wordai-auto-content-writing'),
					    'saved_image_setting_success'	=> __( 'Saved OpenAI image settings successfully!','wordai-auto-content-writing'), 	
					
					    'popup_dialog_suggest_title'	=> __( 'WordAI Suggest Titles','wordai-auto-content-writing'), 
					    'metabox_popup_dialog_title'	=> __( 'WordAI Content Writer','wordai-auto-content-writing'), 
					    
					     
						'lazy_loadimage'    			=> $loading_img
					)
				);
	}
				
	
	public static function sc_wordai_api_test() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
		//echo 'Hello';
		//var_dump( SC_Wordai_OpenAI::get_list_models() );
		
		$response	=	 SC_Wordai_OpenAI::create_content();
		var_dump( $response["choices"][0]["text"] );
		var_dump( $response );
		
		//$response	=	 SC_Wordai_OpenAI::create_image();		
	    //	var_dump( $response );
		//var_dump( $response['data'][0] );		
		//foreach ( $response['data'] as $image ) {
		//	var_dump( $image['url'] );
		//}
		wp_die();
	}
	
	public static function sc_wordai_suggested_title_number_save() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );		
		self::$output	=	[];
		$suggested_title_number	= $_POST['suggestedTitle'];						
		$update_data	=	update_option('sc-wordai-suggested-title-number', $suggested_title_number );		
		if ( $update_data ) {
			self::$output['status']	= 'success';
		}
		else {
			self::$output['status']	= 'fail';
		}
		echo json_encode( self::$output );		
        wp_die();						
	}
	
	public static function sc_wordai_apisettings_data() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );		
		self::$output	=	[];
		parse_str( $_POST['postData'], $params );		
		$update_data	=	update_option('sc-wordai-apisettings-data', serialize( $params ) );		
		if ( $update_data ) {
			self::$output['status']	= 'success';
		}
		else {
			self::$output['status']	= 'fail';
		}
		echo json_encode( self::$output );
		
        wp_die();				
	}
	
	public static function sc_wordai_content_settings_data() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );	
		
		self::$output	=	[];
		parse_str( $_POST['postData'], $params );				
		$update_data	=	update_option('sc-wordai-content-settings-data', serialize( $params ) );		
		if ( $update_data ) {
			self::$output['status']	= 'success';
		}
		else {
			self::$output['status']	= 'fail';
		}
		echo json_encode( self::$output );
		
        wp_die();				
		
	}
	
	public static function sc_wordai_image_settings_data() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );	
		
		self::$output	=	[];
		parse_str( $_POST['postData'], $params );				
		$update_data	=	update_option('sc-wordai-image-settings-data', serialize( $params ) );		
		if ( $update_data ) {
			self::$output['status']	= 'success';
		}
		else {
			self::$output['status']	= 'fail';
		}
		echo json_encode( self::$output );
		
        wp_die();						
	}
	
	public static function sc_wordai_write_titles() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
						
		$prompt_hints			= $_POST['params']['prompt'];				
		$prompt					= SC_Wordai_OpenAI::generate_prompt( trim( $prompt_hints ), 'title' );		
		$api_params				= SC_Wordai_OpenAI::set_openai_params();	
		$api_params['prompt']	= $prompt;
		//var_dump( $api_params );				
		$response				=	 SC_Wordai_OpenAI::create_content( $api_params);		
		//var_dump( SC_Wordai_OpenAI::$output );	
		
		if ( SC_Wordai_OpenAI::$output['status'] == 'success' ) {
			// Replace \n\n with br to insert content into dynamic created core/paragraph inside properly
			SC_Wordai_OpenAI::$output['responseText'] = preg_replace("/[\n\n\"]+/","", SC_Wordai_OpenAI::$output['responseText'] );
		}		
		
		echo json_encode( SC_Wordai_OpenAI::$output );
		
		wp_die();
	}
	
	public static function sc_wordai_write_suggest_titles() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
						
		$prompt_hints			= $_POST['params']['prompt'];				
		$prompt					= SC_Wordai_OpenAI::generate_prompt( trim( $prompt_hints ), 'suggest-title' );		
		$api_params				= SC_Wordai_OpenAI::set_openai_params();	
		$api_params['prompt']	= $prompt;				
		$response				= SC_Wordai_OpenAI::create_content( $api_params);		
		//var_dump( $api_params );						
		
		if ( SC_Wordai_OpenAI::$output['status'] == 'success' ) {
			//var_dump( SC_Wordai_OpenAI::$output['responseText'] );
			// pick the text between double quotes			
			preg_match_all('/"([^"]+)"/', SC_Wordai_OpenAI::$output['responseText'], $matches );
			//var_dump($matches );
			SC_Wordai_OpenAI::$output['listOfTitles'] =	'';
			if ( isset ( $matches[1] ) && array_filter( $matches[1] ) ) {
				foreach ( $matches[1] as $title ) {
					//var_dump( $title );
					$title										 = preg_replace("/[\n\n\"]+/","", $title );
					//var_dump($title);
					SC_Wordai_OpenAI::$output['listOfTitles']	.= '<li><input type="radio" class="suggested-title-radio" name="suggested-title-radio" /> '. $title .'</li>';
				}				
			}
			else {
				$single_sanitized_title_name 					= preg_replace("/[\n\n\"]+/","", SC_Wordai_OpenAI::$output['responseText'] );				
				SC_Wordai_OpenAI::$output['listOfTitles']		.= '<li><input type="radio" class="suggested-title-radio" name="suggested-title-radio" /> '. $single_sanitized_title_name .'</li>';
			}
		}		
		
		echo json_encode( SC_Wordai_OpenAI::$output );		
		wp_die();
	}
	
	public static function sc_wordai_update_suggest_title() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
								
		self:;$output			= [];
		$selected_title			= $_POST['params']['selectedTitle'];		
		$post_id				= $_POST['params']['postID'];		
		//var_dump( $post_id );
		//var_dump( $selected_title );
		
		// Update post title
        $post_data = array(
            'ID'           => intval($post_id),
            'post_title'   => $selected_title,
        );

        // Update post title into the database
        $update_status	=	wp_update_post( $post_data );
		if ( $update_status ) {
			self::$output['status']	= 'success';
			self::$output['postID']	= $update_status;
		}
		else {
			self::$output['status']	= 'fail';
		}
						
		echo json_encode( self::$output, JSON_HEX_APOS );		
		wp_die();		
	}
	
	public static function sc_wordai_write_content() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
								
		$prompt_hints			= $_POST['params']['prompt'];		
		$prompt					= SC_Wordai_OpenAI::generate_prompt( trim( $prompt_hints ), 'content' );								
		$api_params				= SC_Wordai_OpenAI::set_openai_params();
		$api_params['prompt']	= $prompt;
		
		
		$response				=	 SC_Wordai_OpenAI::create_content( $api_params);		
		//var_dump( SC_Wordai_OpenAI::$output );		

		//SC_Wordai_OpenAI::$output['status']			= 'success';
		//SC_Wordai_OpenAI::$output['responseText']	= "\n\nDog: Man's Best Friend\n\nIntroduction\n\nThe dog is one of the most beloved animals in the world. It is a loyal and faithful companion that has been a part of human life for thousands of years. Dogs have been used for hunting, protection, and companionship, and they are still a popular pet today. Dogs come in a variety of shapes, sizes, and colors, and they can be found in almost every country in the world. Dogs are known for their intelligence, loyalty, and unconditional love, and they are often referred to as \"man's best friend.\"\n\nPhysical Characteristics\n\nDogs come in a wide variety of shapes, sizes, and colors. They can range from small toy breeds such as Chihuahuas and Pomeranians to large breeds such as Great Danes and St. Bernards. They can have short or long coats, and their fur can be straight, wavy, or curly. Dogs can be solid colors, or they can have a variety of markings. Some of the most popular colors are black, white, brown, and red.\n\nPersonality Traits\n\nDogs are known for their intelligence, loyalty, and unconditional love. They are very social animals and enjoy spending time with their owners. Dogs are also very protective of their owners and will often bark or growl when they sense danger. They are also very playful and enjoy playing fetch, tug-of-war, and other games. Dogs are also very trainable and can learn a variety of commands and tricks.\n\nConclusion\n\nThe dog is one of the most beloved animals in the world. It is a loyal and faithful companion that has been a part of human life for thousands of years. Dogs come in a variety of shapes, sizes, and colors, and they are known for their intelligence, loyalty, and unconditional love. Dogs are often referred to as \"man's best friend,\" and they make wonderful pets.";
				
		if ( SC_Wordai_OpenAI::$output['status'] == 'success' ) {
			// Replace \n\n with br to insert into dynamic created core/paragraph inside properly
			SC_Wordai_OpenAI::$output['responseTextWithBR'] = preg_replace("/\n\n/","<br/><br/>", SC_Wordai_OpenAI::$output['responseText'] );
		}
		
		echo json_encode( SC_Wordai_OpenAI::$output, JSON_HEX_APOS );		
		wp_die();
	}

	
	
	public static function sc_wordai_generate_image() {
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
		$prompt_hints			= $_POST['params']['prompt'];		
		$prompt					= SC_Wordai_OpenAI::generate_prompt( trim( $prompt_hints ), 'image' );										
		$api_params['prompt']	= $prompt;
		
		$image_settings_data	= get_option('sc-wordai-image-settings-data');
		$image_settings_data	= unserialize( $image_settings_data );

		$generated_image_number	= isset( $image_settings_data['sc-wordai-image-number'] )? $image_settings_data['sc-wordai-image-number'] : 2;
		$generated_image_size	= isset( $image_settings_data['sc-wordai-image-size'] )? $image_settings_data['sc-wordai-image-size'] : '256x256';
				
		$api_params['n']        = intval( $generated_image_number );
		$api_params['size']		= $generated_image_size;								
		$response				= SC_Wordai_OpenAI::generate_image( $api_params);						
		$images_url				= [];
		if ( SC_Wordai_OpenAI::$output['responseImageUrls'] ) {
			foreach ( SC_Wordai_OpenAI::$output['responseImageUrls'] as $image ) {
				$images_url[]	= $image['url'];
			}
			SC_Wordai_OpenAI::$output['openAIImgURLs']	= implode( ',', $images_url );	
		}
		
		//var_dump( SC_Wordai_OpenAI::$output );
		echo json_encode( SC_Wordai_OpenAI::$output, JSON_HEX_APOS );		
		wp_die();		
	}
	
	public static function sc_wordai_image_upload_mime_type_issue( $settings ) {
		if (defined('ALLOW_UNFILTERED_UPLOADS') && ALLOW_UNFILTERED_UPLOADS) {
			unset($settings['filters']['mime_types']);
		}		
		return $settings;
	}
	
	public static function sc_wordai_upload_image_to_wp_media()
	{				
		check_ajax_referer( 'scwordai_wpnonce', 'security' );
		$imgURLS								= $_POST['params']['imgURLs'];
		$prompt									= $_POST['params']['prompt'];
		$prompt_slug							= preg_replace( '/[\s]+/', '-', $prompt );					
		
		self::$output							= [];
		//$images_url 							= [ 'https://www.softcoy.com/assets/img/615x750/img3.jpg', 'https://softcoy.com/assets/img/mockups/img12.png' ];
		//$images_url								= [ 'https://oaidalleapiprodscus.blob.core.windows.net/private/org-mrxHN9nuF6DdeG8G77rxFcrK/user-sCrXRmsFfALQxdrqXOr6CpXa/img-jSTrzdZRkYAThnaFImXh9x7l.png?st=2023-06-11T13%3A14%3A50Z&se=2023-06-11T15%3A14%3A50Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-06-10T20%3A40%3A10Z&ske=2023-06-11T20%3A40%3A10Z&sks=b&skv=2021-08-06&sig=kPZoMDs3Oj80oo2NU/k5wMfOOHYXVBHKl9kQh7eDsJM%3D' ];	
		$images_url								= explode( ',', $imgURLS );
		
		foreach ( $images_url as $image_url ) {			
			$image_name							= $prompt_slug . '-' . time() . '.jpg';		
			self::$output['imgaesUploadInfo'][]	= self::sc_wordai_upload_image_to_media_gallery( $image_url, $image_name );											
		}
				
		self::$output['totalImages']			= count( $images_url );
		self::$output['totalSuccess']			= count ( array_filter( self::$output['imgaesUploadInfo'], function($eachArr) { if ( $eachArr['status'] == 'success') return true; }) );
		self::$output['totalFail']			    = count ( array_filter( self::$output['imgaesUploadInfo'], function($eachArr) { if ( $eachArr['status'] == 'fail') return true; }) );
		
		if ( self::$output['totalImages'] ==  self::$output['totalSuccess'] ) {
			self::$output['status']				= 'success';
			self::$output['statusMsg']          = 'All images uploaded.';
		}
		else if ( self::$output['totalSuccess'] > 0 ) {
			self::$output['status']				= 'success';
			self::$output['statusMsg']          = 'Total ' . self::$output['totalSuccess'] . ' success of ' . self::$output['totalImages'];
		}
		else {
			self::$output['status']				= 'fail';
		}
		
		//print_r( self::$output );
		echo json_encode( self::$output );
		wp_die();		
	}
	
	public static function sc_wordai_upload_image_to_media_gallery( $image_url = null, $image_name = null ) {
		$output									=	[];		
		
		if ( empty( $image_url ) || is_null( $image_url ) ) {
			$output['status']					= 'fail';	
			$output['errorMessage']				= 'Image URL required.';
		}
		else {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		
			
			$tmp 								= download_url( $image_url );
			$file_array 						= array(
													//'name'     => basename( $image_url ),
				                                    'name'     => $image_name, 
													'tmp_name' => $tmp
			);

			$id 								= media_handle_sideload( $file_array, 0 );
			if ( is_wp_error( $id ) ) {
				@unlink( $file_array['tmp_name'] );
				$output['attachmentID']			= $id;	
				$output['status']				= 'fail';	
				$output['errorMessage']			= $id->get_error_message();
			}
			else {
				$output['status']				= 'success';
				$output['attachmentID']			= $id;
				$output['attachmentURL']		= wp_get_attachment_url( $id );				
			}

			// Unlink tmp
			@unlink( $tmp );		
		}
		
		return $output;			
	}
	
	
	
				
} // End Class
?>