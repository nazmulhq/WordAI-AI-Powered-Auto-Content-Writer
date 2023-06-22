<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordai_Metabox {
	private static $initiated = false;
	
	public function __construct() {
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}
	}
	
	private static function initiate_hooks() {			    						
		add_action( 'add_meta_boxes', array( __CLASS__, 'scwordai_add_metaboxes' ) );				
		self::$initiated = true;
	}
			
	
	public static function scwordai_add_metaboxes() {
		add_meta_box(
					'scwordai_metabox',
					__('WordAI Content Write', 'wordai-auto-content-writing'), // Metabox Title
					array( __CLASS__, 'scwordai_metabox_html_callback'),       // Callback function
					array('post', 'page', 'product'),                          // Post types
					'side', 
					'high' 
				);		
	}
	
	public static function scwordai_metabox_html_callback() {		
		include_once( SCWORDAI_PLUGIN_DIR . 'admin/views/scwordai_metabox_html_callback_html.php');
	}
} // End class