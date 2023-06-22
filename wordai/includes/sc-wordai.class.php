<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordai {
	private static $initiated = false;
	
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}
	}
	
	private static function initiate_hooks() {			    				
	    add_action( 'admin_init', array( __CLASS__, 'add_scwordai_settings_data' ) );		
		add_action( 'admin_menu', array( __CLASS__, 'add_scwordai_submenus' ) );		
		add_action( 'admin_footer', array( __CLASS__, 'sc_wordai_add_html_contents_at_admin_footer' ) );
		
		add_action( 'admin_notices', array( __CLASS__, 'scwordai_admin_notices' ) );		
		add_action( 'plugins_loaded', array( __CLASS__, 'scwordai_load_textdomain') );
		//add_filter( 'plugin_row_meta',     array( __CLASS__, 'scwordai_row_link'), 10, 2 );
		
		add_filter( 'post_row_actions', array( __CLASS__, 'sc_wordai_add_post_row_actions'), 10, 2 );
		add_filter( 'page_row_actions', array( __CLASS__, 'sc_wordai_add_page_row_actions'), 10, 2 );
		
		self::$initiated = true;
	}
			
	public static function activate() {
		self::check_preactivation_requirements();
		flush_rewrite_rules( true );
		
	}
	
	public static function check_preactivation_requirements() {				
		if ( version_compare( PHP_VERSION, SCWORDAI_MINIMUM_PHP_VERSION, '<' ) ) {
			wp_die('Minimum PHP Version required: ' . SCWORDAI_MINIMUM_PHP_VERSION );
		}
        global $wp_version;
		if ( version_compare( $wp_version, SCWORDAI_MINIMUM_WP_VERSION, '<' ) ) {
			wp_die('Minimum Wordpress Version required: ' . SCWORDAI_MINIMUM_WP_VERSION );
		}
	}
	
	public static function scwordai_load_textdomain() {
		load_plugin_textdomain( 'wordai-auto-content-writing', false, SCWORDAI_PLUGIN_DIR . 'languages/' ); 
	}
		
	public static function add_scwordai_settings_data() {
		register_setting( 'scwordai-settings', 'scwordai-settings' );
		add_settings_section( 'scwordai-settings-section', __( 'WordAI - OpenAI API Key' ), array( __CLASS__, 'settings_section_callback' ), 'scwordai-settings' );
		// AI API KEY Field
		add_settings_field( 'scwordai-settings-apikey-field', __( 'OpenAI API KEY', 'wordai-auto-content-writing' ), array( __CLASS__, 'settings_section_fields_callback' ), 'scwordai-settings', 'scwordai-settings-section', $args = array( 'fieldname' => 'apikey', 'label_for' => 'scwordai-settings-field-apikey' ) );								
	}

		
			
	public static function settings_section_callback() {
		include_once( SCWORDAI_PLUGIN_DIR . 'admin/views/settings-section-callback-page.php');
	}
	
	public static function settings_section_fields_callback( $args = null ) {		
		$options = get_option('scwordai-settings');		
		switch ($args['fieldname']) {
			case 'apikey':
				$value = isset( $options[$args['label_for']] )? $options[$args['label_for']] : '';				
				echo '<input type="password" id="'.$args['label_for'].'" name="scwordai-settings['.esc_attr($args['label_for']).']" value="'.$value.'" size="150" placeholder="OpenAI API KEY" />';
			break;				
		}
	}
	
	
	public static function add_scwordai_submenus() {
		
		// Top Menu|Parent Menu - WordAI
		add_menu_page( __( 'WordAI - Auto Content Writing', 'wordai-auto-content-writing' ), 'WordAI', 'manage_options', 'word-ai-topmenu', '', 'dashicons-welcome-write-blog', 6 );
		
		// Submenu - Settings - top level menu slug used as same 'word-ai-topmenu' for the first submenu slug to avoid show the top level menu name as submenu   
		/*add_submenu_page(
		    'word-ai-topmenu',
        __( 'WordAI', 'wordai-auto-content-writing' ),
        __( 'Settings', 'wordai-auto-content-writing' ),
            'manage_options',
            'word-ai-topmenu',
			array( __CLASS__, 'add_scwordai_submenus_settings_callback' )        
          );*/
								  
		// Submenu - API Settings - sc-wordai-api-settings page slug 
		add_submenu_page(
		    'word-ai-topmenu',
        __( 'WordAI - API Settings', 'wordai-auto-content-writing' ),
        __( 'API Settings', 'wordai-auto-content-writing' ),
            'manage_options',
            'word-ai-topmenu',
			array( __CLASS__, 'add_scwordai_submenus_apisettings_callback' )        
          );
		
		// Submenu - Content Settings - sc-wordai-content-settings page slug  
		add_submenu_page(
		    'word-ai-topmenu',
        __( 'WordAI - Content Settings', 'wordai-auto-content-writing' ),
        __( 'Content Settings', 'wordai-auto-content-writing' ),
            'manage_options',
            'sc-wordai-content-settings',
			array( __CLASS__, 'add_scwordai_submenus_content_settings_callback' )        
          );

		// Submenu - Image Settings - sc-wordai-image-settings page slug
		add_submenu_page(
		    'word-ai-topmenu',
        __( 'WordAI - Content Settings', 'wordai-auto-content-writing' ),
        __( 'Image Settings', 'wordai-auto-content-writing' ),
            'manage_options',
            'sc-wordai-image-settings',
			array( __CLASS__, 'add_scwordai_submenus_image_settings_callback' )        
          );
		
		
		
	}	
								
	public static function add_scwordai_submenus_settings_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		include_once SCWORDAI_PLUGIN_DIR . 'admin/views/add_scwordai_submenus_settings_callback.php';		
	}
	
	public static function add_scwordai_submenus_apisettings_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		include_once SCWORDAI_PLUGIN_DIR . 'admin/views/add_scwordai_submenus_apisettings_callback.php';		
	}

	public static function add_scwordai_submenus_content_settings_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		include_once SCWORDAI_PLUGIN_DIR . 'admin/views/add_scwordai_submenus_content_settings_callback.php';		
	}

	public static function add_scwordai_submenus_image_settings_callback() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		include_once SCWORDAI_PLUGIN_DIR . 'admin/views/add_scwordai_submenus_image_settings_callback.php';		
	}
	
	public static function sc_wordai_add_html_contents_at_admin_footer() {
		// check user capabilities
		if ( !current_user_can('manage_options' ) ) {
			return;
		}		
		include_once SCWORDAI_PLUGIN_DIR . 'admin/views/add_scwordai_suggest_titles_popup_dialog_callback_html.php';				
	}
	
	
	public static function sc_wordai_add_post_row_actions( $actions, $post ) {
		$supported_post_types	= [ 'post', 'page', 'product' ];
		if ( in_array( $post->post_type, $supported_post_types ) ) {
			$new_actions[]		= '<a href="Javascript:void()" class="sc-wordai-suggest-titles" data-scwordai-post-id="'.$post->ID.'"><i class="dashicons dashicons-welcome-write-blog"></i>WordAI Suggest Title</a>';
			return array_merge( $actions, $new_actions );
		}
	   return $actions;		
	}	
	
	public static function sc_wordai_add_page_row_actions( $actions, $post ) {
		$supported_post_types	= [ 'post', 'page', 'product' ];
		if ( in_array( $post->post_type, $supported_post_types ) ) {
			$new_actions[]		= '<a href="Javascript:void()" class="sc-wordai-suggest-titles" data-scwordai-post-id="'.$post->ID.'"><i class="dashicons dashicons-welcome-write-blog"></i>WordAI Suggest Title</a>';
			return array_merge( $actions, $new_actions );
		}
	   return $actions;		
	}	
	
	
	public static function scwordai_admin_notices() {
		$admin_notice 			= false;		
		$options 				= get_option('scwordai-settings');			
		if ( $options ) {
			$wordai_apikey 		= $options['scwordai-settings-field-apikey'];			
			if ( isset( $wordai_apikey ) && ! empty( $wordai_apikey ) ) {
				$admin_notice = true;
			}
		}
							
		if ( ! $admin_notice ) {			
			$url 	= admin_url('admin.php?page=word-ai-topmenu');
			$alink 	= '<a href="'.$url.'">You need to add first your OpenAI API Key.</a>';
			printf('<div class="notice notice-warning is-dismissible sc-wordai-no-api-key-added-notice-wrapper">');
		    printf('<div class="scwordai-notice-wrapper"><h3><i class="dashicons dashicons-welcome-write-blog"></i> WordAI - Auto Content Writing:</h3> <h4>OpenAI API Key required to generate title, content, images. ' .$alink.'</h4></div>');
	        printf('</div>');
		}
		
	}
	
	public static function scwordai_row_link( $actions, $plugin_file ) {
		$wordsmtp_plugin 	= plugin_basename( SCWORDAI_PLUGIN_DIR );
		$plugin_name 		= basename($plugin_file, '.php');
		if ( $wordsmtp_plugin == $plugin_name ) {
			//$doclink[] 		= '<a href="https://softcoy.com/wordsmtp" title="WordSMTP - Docs" target="_blank">WordSMTP Docs</a>';	
			//$doclink[] 		= '<a href="https://softcoy.com/wordsmtp" title="WordSMTP Support" target="_blank">Support</a>';	
			//return array_merge( $actions, $doclink );
		}
		return $actions;
	}	
	
} // End class