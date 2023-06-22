<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordai_Autoloader {
	
	public function __construct() {		
		new SC_Wordai();
		new SC_Wordai_Ajaxhandler();
		new SC_Wordai_OpenAI();
		new SC_Wordai_Metabox();
	}
}
?>