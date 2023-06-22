<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class SC_Wordai_OpenAI {
	private static $initiated 		= false;
	
	public static $API_KEY			= null;
	public static $AI_LISTMODEL_EP	= 'https://api.openai.com/v1/models';
	public static $AI_COMPLETION_EP	= 'https://api.openai.com/v1/completions';
	public static $AI_IMAGE_EP		= 'https://api.openai.com/v1/images/generations';
	public static $MODEL			= 'text-davinci-003';
	
	public static $output			= [];
	
	public function __construct() {
		
		if ( is_null( self::$API_KEY ) ) {
			$options 				= get_option('scwordai-settings');
			self::$API_KEY			= isset( $options['scwordai-settings-field-apikey'] ) && ! empty( $options['scwordai-settings-field-apikey'] )? $options['scwordai-settings-field-apikey'] : null;
		}
		
		if ( ! self::$initiated ) {
			self::initiate_hooks();
		}
	}
	
	private static function initiate_hooks() {			    				
	    //add_action( 'admin_init', array( __CLASS__, 'add_scwordai_settings_data' ) );		
		//add_action( 'admin_menu', array( __CLASS__, 'add_scwordai_submenus' ) );		
		//add_action( 'admin_notices', array( __CLASS__, 'scwordai_admin_notices' ) );		
		//add_action( 'plugins_loaded', array( __CLASS__, 'scwordai_load_textdomain') );
		//add_filter( 'plugin_row_meta',     array( __CLASS__, 'scwordai_row_link'), 10, 2 );
		//self::$initiated = true;
	}
			
	
	public static function generate_prompt( $prompt_hints = null, $prompt_for =	null ) {		
		$generated_prompt	=	'';
		if ( is_null( $prompt_hints) || is_null( $prompt_for) ) {
			return false;
		}
		else {
			$content_settings_data			= get_option('sc-wordai-content-settings-data');
			$content_settings_data			= unserialize( $content_settings_data );

			$language_code					=	isset( $content_settings_data['sc-wordai-language-code'] )? $content_settings_data['sc-wordai-language-code'] : 'en-US';
			$writing_style_code				=	isset( $content_settings_data['sc-wordai-writing-style'] )? $content_settings_data['sc-wordai-writing-style'] : 'descriptive';
			$writing_tone_code				=	isset( $content_settings_data['sc-wordai-writing-tone'] )? $content_settings_data['sc-wordai-writing-tone'] : 'informative';
			$title_length_settings_code		=	isset( $content_settings_data['sc-wordai-title-length'] )? $content_settings_data['sc-wordai-title-length'] : '30n40';
			$content_paragraph_setting_code	=	isset( $content_settings_data['sc-wordai-content-paragraphs'] )? $content_settings_data['sc-wordai-content-paragraphs'] : 3;
			
			$title_length_readable			= self::title_lengths()[ $title_length_settings_code ];
			$content_length_readable		= self::content_paragraphs()[ $content_paragraph_setting_code ];
			
						
			switch ( $prompt_for ) {
				case 'title':
					$generated_prompt	= 'Write article title about ' . $prompt_hints . ' in language code ' . $language_code . '. Style:' . $writing_style_code . '. Tone:' . $writing_tone_code .'.Title length will be '. $title_length_readable .'.';
					break;
					
				case 'suggest-title':
					$scwordai_suggested_title_number	= get_option('sc-wordai-suggested-title-number');
					$scwordai_suggested_title_number    = isset( $scwordai_suggested_title_number )? intval($scwordai_suggested_title_number) : 2;	
					
					$title_s			= ( $scwordai_suggested_title_number > 1 )? ' titles ' : ' title '; 
					$generated_prompt	= 'Write article ' . $scwordai_suggested_title_number .  $title_s . 'about ' . $prompt_hints . ' in language code ' . $language_code . '. Style:' . $writing_style_code . '. Tone:' . $writing_tone_code .'.Title length will be '. $title_length_readable .'.';
					break;										
					
				case 'content':
					//$generated_prompt	= 'Write article about ' . $prompt_hints . ' in language code ' . $language_code . '. Style:' . $writing_style_code . '. Tone:' . $writing_tone_code .'.Article length will have '. $content_length_readable .'.Each paragraph with H2 heading title within 300 words.';					
					$generated_prompt	= 'Write article about ' . $prompt_hints . ' in language code ' . $language_code . '. Style:' . $writing_style_code . '. Tone:' . $writing_tone_code .'.Article length will have '. $content_length_readable .'.Each paragraph with heading title within 300 words.';										
					//$generated_prompt	= 'Write article within 300 words or less about ' . $prompt_hints . ' in language code ' . $language_code . '. Style:' . $writing_style_code . '. Tone:' . $writing_tone_code .'.Article length will have '. $content_length_readable .'.Each paragraph with heading title.';										
					break;
					
				case 'image':
					$generated_prompt	= $prompt_hints;					
					break;
					
			}			
		}
		return $generated_prompt;
	}
	
	public static function set_openai_params() {
		$open_ai_params							= [];
		$api_settings_data						= get_option('sc-wordai-apisettings-data');
		$api_settings_data						= unserialize( $api_settings_data);

		$temperature_value						=	isset( $api_settings_data['sc-wordai-temperature'] )? $api_settings_data['sc-wordai-temperature'] : 0.2;
		$top_p_value							=	isset( $api_settings_data['sc-wordai-top-p'] )? $api_settings_data['sc-wordai-top-p'] : 0.1;
		$max_tokens_value						=	isset( $api_settings_data['sc-wordai-max-tokens'] )? $api_settings_data['sc-wordai-max-tokens'] : 1024;
		$presence_penalty_input_value			=	isset( $api_settings_data['sc-wordai-presence-penalty-input'] )? $api_settings_data['sc-wordai-presence-penalty-input'] : 0;
		$frequency_penalty_input_value			=	isset( $api_settings_data['sc-wordai-frequency-penalty-input'] )? $api_settings_data['sc-wordai-frequency-penalty-input'] : 0;
		$best_of_input_value					=	isset( $api_settings_data['sc-wordai-best-of-input'] )? $api_settings_data['sc-wordai-best-of-input'] : 1;
		$stop_input_value						=	isset( $api_settings_data['sc-wordai-stop-input'] )? $api_settings_data['sc-wordai-stop-input'] : '\n';
			
		$open_ai_params['model']				=  SC_Wordai_OpenAI::$MODEL;
		$open_ai_params['max_tokens']			=  intval( $max_tokens_value );
		$open_ai_params['temperature']			=  floatval( $temperature_value );
		$open_ai_params['top_p']				=  floatval( $top_p_value );
		$open_ai_params['presence_penalty']		=  floatval( $presence_penalty_input_value );
		$open_ai_params['frequency_penalty']	=  floatval( $frequency_penalty_input_value );
		$open_ai_params['stop']					=  $stop_input_value;
		
		return $open_ai_params;
	}
	
	public static function get_list_models() {
			$args = array(
				'headers' => array(
					'Authorization' =>  'Bearer ' . self::$API_KEY,
					'Content-Type'  => 'application/json',
				),
				'timeout' 			=> 60,
				'httpversion' 		=> '1.0',
				'sslverify'   		=> false								
			);

			$response = wp_remote_get( self::$AI_LISTMODEL_EP, $args );

			if ( ! is_wp_error( $response ) ) {
				$body 			= json_decode( wp_remote_retrieve_body( $response ), true );				
				return $body;
			} else {
				$error_message = $response->get_error_message();
				throw new Exception( $error_message );
			}		
	}
	
	
	public static function create_content( $api_params ) {
		  
		    self::$output	=	[];
		
		    //$body		= [ "model" => "text-davinci-003", "prompt" => "write  3 title for tiger", "max_tokens" => 20, "temperature" => 0 ]; 
		    //$body		= [ "model" => "text-davinci-003", "prompt" => "write content for the title The King of the Jungle", "max_tokens" => 50, "temperature" => 0 ]; 
		    //$body		= [ "model" => "text-davinci-003", "prompt" => "write topic-wise content for the title The King of the Jungle", "max_tokens" => 80, "temperature" => 0 ]; 
		    $body		= $api_params; 
		
			$args = array(
				'headers' => array(
					'Authorization' =>  'Bearer ' . self::$API_KEY,
					'Content-Type'  => 'application/json',
				),
				'timeout' 			=> 60,
				'httpversion' 		=> '1.0',
				'sslverify'   		=> false,
				'body'				=> json_encode( $body )	
			);
		
		    //var_dump( $args );
		    //exit();

			$response = wp_remote_post( self::$AI_COMPLETION_EP, $args );

			if ( ! is_wp_error( $response ) ) {
				$body 			= json_decode( wp_remote_retrieve_body( $response ), true );
				self::$output['responseBody']		=	$body;
				if ( isset( $body["choices"][0]["text"] ) ) {
					self::$output['responseText']	= $body["choices"][0]["text"];
					self::$output['status']			= 'success';
				}
				elseif ( isset( $body["error"]["message"] ) ) {
					self::$output['errorMessage']	= $body["error"]["message"];
					self::$output['errorType']		= $body["error"]["type"];
					self::$output['status']			= 'fail';
				}				
			} else {
				$error_message = $response->get_error_message();				
				self::$output['errorMessage']	  	=	$error_message;
				self::$output['status']				= 'fail';
				//throw new Exception( $error_message );
			}	
		return self::$output;
	}
	
	public static function generate_image( $api_params ) {
		
		    //$body		= [ "model" => "text-davinci-003", "prompt" => "write  3 title for tiger", "max_tokens" => 20, "temperature" => 0 ]; 
		    //$body		= [ 'prompt' => 'A tiger in the jungle', 'n' => 1, 'size' => '256x256' ]; // create n=1 image
		    //$body		= [ 'prompt' => 'A tiger in the jungle', 'n' => 2, 'size' => '256x256' ]; 
		
		    self::$output	= []; 
		    $body			= $api_params;	
			$args 			= array(
								'headers' => array(
									'Authorization' =>  'Bearer ' . self::$API_KEY,
									'Content-Type'  => 'application/json',
								),
								'timeout' 			=> 90,
								'httpversion' 		=> '1.0',
								'sslverify'   		=> false,
								'body'				=> json_encode( $body )	
							);

			$response 								= wp_remote_post( self::$AI_IMAGE_EP, $args );
			if ( ! is_wp_error( $response ) ) {
				$body 								= json_decode( wp_remote_retrieve_body( $response ), true );
				self::$output['responseBody']		=  $body;	
				if ( isset( $body['data'] ) ) {
					self::$output['responseImageUrls']	= $body['data'];
					self::$output['status']			= 'success';
				}
				elseif ( isset( $body["error"]["message"] ) ) {
					self::$output['errorMessage']	= $body["error"]["message"];
					self::$output['errorType']		= $body["error"]["type"];
					self::$output['status']			= 'fail';
				}								
			} else {				
				//throw new Exception( $error_message );
				$error_message 						= $response->get_error_message();				
				self::$output['errorMessage']	  	= $error_message;
				self::$output['status']				= 'fail';				
			}		
		     return self::$output;
	}
	
	public static function language_list() {
		// count 156
		$languages_list = array(
			array("name" => "Afrikaans", "code" => "af"),
			array("name" => "Albanian - shqip", "code" => "sq"),
			array("name" => "Amharic - አማርኛ", "code" => "am"),
			array("name" => "Arabic - العربية", "code" => "ar"),
			array("name" => "Aragonese - aragonés", "code" => "an"),
			array("name" => "Armenian - հայերեն", "code" => "hy"),
			array("name" => "Asturian - asturianu", "code" => "ast"),
			array("name" => "Azerbaijani - azərbaycan dili", "code" => "az"),
			array("name" => "Basque - euskara", "code" => "eu"),
			array("name" => "Belarusian - беларуская", "code" => "be"),
			array("name" => "Bengali - বাংলা", "code" => "bn"),
			array("name" => "Bosnian - bosanski", "code" => "bs"),
			array("name" => "Breton - brezhoneg", "code" => "br"),
			array("name" => "Bulgarian - български", "code" => "bg"),
			array("name" => "Catalan - català", "code" => "ca"),
			array("name" => "Central Kurdish - کوردی (دەستنوسی عەرەبی)", "code" => "ckb"),
			array("name" => "Chinese - 中文", "code" => "zh"),
			array("name" => "Chinese (Hong Kong) - 中文（香港）", "code" => "zh-HK"),
			array("name" => "Chinese (Simplified) - 中文（简体）", "code" => "zh-CN"),
			array("name" => "Chinese (Traditional) - 中文（繁體）", "code" => "zh-TW"),
			array("name" => "Corsican", "code" => "co"),
			array("name" => "Croatian - hrvatski", "code" => "hr"),
			array("name" => "Czech - čeština", "code" => "cs"),
			array("name" => "Danish - dansk", "code" => "da"),
			array("name" => "Dutch - Nederlands", "code" => "nl"),
			array("name" => "English", "code" => "en"),
			array("name" => "English (Australia)", "code" => "en-AU"),
			array("name" => "English (Canada)", "code" => "en-CA"),
			array("name" => "English (India)", "code" => "en-IN"),
			array("name" => "English (New Zealand)", "code" => "en-NZ"),
			array("name" => "English (South Africa)", "code" => "en-ZA"),
			array("name" => "English (United Kingdom)", "code" => "en-GB"),
			array("name" => "English (United States)", "code" => "en-US"),
			array("name" => "Esperanto - esperanto", "code" => "eo"),
			array("name" => "Estonian - eesti", "code" => "et"),
			array("name" => "Faroese - føroyskt", "code" => "fo"),
			array("name" => "Filipino", "code" => "fil"),
			array("name" => "Finnish - suomi", "code" => "fi"),
			array("name" => "French - français", "code" => "fr"),
			array("name" => "French (Canada) - français (Canada)", "code" => "fr-CA"),
			array("name" => "French (France) - français (France)", "code" => "fr-FR"),
			array("name" => "French (Switzerland) - français (Suisse)", "code" => "fr-CH"),
			array("name" => "Galician - galego", "code" => "gl"),
			array("name" => "Georgian - ქართული", "code" => "ka"),
			array("name" => "German - Deutsch", "code" => "de"),
			array("name" => "German (Austria) - Deutsch (Österreich)", "code" => "de-AT"),
			array("name" => "German (Germany) - Deutsch (Deutschland)", "code" => "de-DE"),
			array("name" => "German (Liechtenstein) - Deutsch (Liechtenstein)", "code" => "de-LI"),
			array("name" => "German (Switzerland) - Deutsch (Schweiz)", "code" => "de-CH"),
			array("name" => "Greek - Ελληνικά", "code" => "el"),
			array("name" => "Guarani", "code" => "gn"),
			array("name" => "Gujarati - ગુજરાતી", "code" => "gu"),
			array("name" => "Hausa", "code" => "ha"),
			array("name" => "Hawaiian - ʻŌlelo Hawaiʻi", "code" => "haw"),
			array("name" => "Hebrew - עברית", "code" => "he"),
			array("name" => "Hindi - हिन्दी", "code" => "hi"),
			array("name" => "Hungarian - magyar", "code" => "hu"),
			array("name" => "Icelandic - íslenska", "code" => "is"),
			array("name" => "Indonesian - Indonesia", "code" => "id"),
			array("name" => "Interlingua", "code" => "ia"),
			array("name" => "Irish - Gaeilge", "code" => "ga"),
			array("name" => "Italian - italiano", "code" => "it"),
			array("name" => "Italian (Italy) - italiano (Italia)", "code" => "it-IT"),
			array("name" => "Italian (Switzerland) - italiano (Svizzera)", "code" => "it-CH"),
			array("name" => "Japanese - 日本語", "code" => "ja"),
			array("name" => "Kannada - ಕನ್ನಡ", "code" => "kn"),
			array("name" => "Kazakh - қазақ тілі", "code" => "kk"),
			array("name" => "Khmer - ខ្មែរ", "code" => "km"),
			array("name" => "Korean - 한국어", "code" => "ko"),
			array("name" => "Kurdish - Kurdî", "code" => "ku"),
			array("name" => "Kyrgyz - кыргызча", "code" => "ky"),
			array("name" => "Lao - ລາວ", "code" => "lo"),
			array("name" => "Latin", "code" => "la"),
			array("name" => "Latvian - latviešu", "code" => "lv"),
			array("name" => "Lingala - lingála", "code" => "ln"),
			array("name" => "Lithuanian - lietuvių", "code" => "lt"),
			array("name" => "Macedonian - македонски", "code" => "mk"),
			array("name" => "Malay - Bahasa Melayu", "code" => "ms"),
			array("name" => "Malayalam - മലയാളം", "code" => "ml"),
			array("name" => "Maltese - Malti", "code" => "mt"),
			array("name" => "Marathi - मराठी", "code" => "mr"),
			array("name" => "Mongolian - монгол", "code" => "mn"),
			array("name" => "Nepali - नेपाली", "code" => "ne"),
			array("name" => "Norwegian - norsk", "code" => "no"),
			array("name" => "Norwegian Bokmål - norsk bokmål", "code" => "nb"),
			array("name" => "Norwegian Nynorsk - nynorsk", "code" => "nn"),
			array("name" => "Occitan", "code" => "oc"),
			array("name" => "Oriya - ଓଡ଼ିଆ", "code" => "or"),
			array("name" => "Oromo - Oromoo", "code" => "om"),
			array("name" => "Pashto - پښتو", "code" => "ps"),
			array("name" => "Persian - فارسی", "code" => "fa"),
			array("name" => "Polish - polski", "code" => "pl"),
			array("name" => "Portuguese - português", "code" => "pt"),
			array("name" => "Portuguese (Brazil) - português (Brasil)", "code" => "pt-BR"),
			array("name" => "Portuguese (Portugal) - português (Portugal)", "code" => "pt-PT"),
			array("name" => "Punjabi - ਪੰਜਾਬੀ", "code" => "pa"),
			array("name" => "Quechua", "code" => "qu"),
			array("name" => "Romanian - română", "code" => "ro"),
			array("name" => "Romanian (Moldova) - română (Moldova)", "code" => "mo"),
			array("name" => "Romansh - rumantsch", "code" => "rm"),
			array("name" => "Russian - русский", "code" => "ru"),
			array("name" => "Scottish Gaelic", "code" => "gd"),
			array("name" => "Serbian - српски", "code" => "sr"),
			array("name" => "Serbo - Croatian", "code" => "sh"),
			array("name" => "Shona - chiShona", "code" => "sn"),
			array("name" => "Sindhi", "code" => "sd"),
			array("name" => "Sinhala - සිංහල", "code" => "si"),
			array("name" => "Slovak - slovenčina", "code" => "sk"),
			array("name" => "Slovenian - slovenščina", "code" => "sl"),
			array("name" => "Somali - Soomaali", "code" => "so"),
			array("name" => "Southern Sotho", "code" => "st"),
			array("name" => "Spanish - español", "code" => "es"),
			array("name" => "Spanish (Argentina) - español (Argentina)", "code" => "es-AR"),
			array("name" => "Spanish (Latin America) - español (Latinoamérica)", "code" => "es-419"),
			array("name" => "Spanish (Mexico) - español (México)", "code" => "es-MX"),
			array("name" => "Spanish (Spain) - español (España)", "code" => "es-ES"),
			array("name" => "Spanish (United States) - español (Estados Unidos)", "code" => "es-US"),
			array("name" => "Sundanese", "code" => "su"),
			array("name" => "Swahili - Kiswahili", "code" => "sw"),
			array("name" => "Swedish - svenska", "code" => "sv"),
			array("name" => "Tajik - тоҷикӣ", "code" => "tg"),
			array("name" => "Tamil - தமிழ்", "code" => "ta"),
			array("name" => "Tatar", "code" => "tt"),
			array("name" => "Telugu - తెలుగు", "code" => "te"),
			array("name" => "Thai - ไทย", "code" => "th"),
			array("name" => "Tigrinya - ትግርኛ", "code" => "ti"),
			array("name" => "Tongan - lea fakatonga", "code" => "to"),
			array("name" => "Turkish - Türkçe", "code" => "tr"),
			array("name" => "Turkmen", "code" => "tk"),
			array("name" => "Twi", "code" => "tw"),
			array("name" => "Ukrainian - українська", "code" => "uk"),
			array("name" => "Urdu - اردو", "code" => "ur"),
			array("name" => "Uyghur", "code" => "ug"),
			array("name" => "Uzbek - o‘zbek", "code" => "uz"),
			array("name" => "Vietnamese - Tiếng Việt", "code" => "vi"),
			array("name" => "Walloon - wa", "code" => "wa"),
			array("name" => "Welsh - Cymraeg", "code" => "cy"),
			array("name" => "Western Frisian", "code" => "fy"),
			array("name" => "Xhosa", "code" => "xh"),
			array("name" => "Yiddish", "code" => "yi"),
			array("name" => "Yoruba - Èdè Yorùbá", "code" => "yo"),
			array("name" => "Zulu - isiZulu", "code" => "zu")
		);
		
		return $languages_list;
		
	}
	
	public static function writing_styles() {
		$writing_styles	=	[
			'narrative' 		=> 'Narrative',
			'descriptive'		=> 'Descriptive',
			'expository'		=> 'Expository',
			'persuasive'		=> 'Persuasive'
		];
		
		return $writing_styles;
	}

	public static function writing_tones() {
		$writing_tones	=	[
			'curious'			=> 'Curious',
			'eager'				=> 'Eager',
			'cheerful'			=> 'Cheerful',
			'humorous'			=> 'Humorous',
			'Energetic'			=> 'Energetic',
			'enthusiastic'		=> 'Enthusiastic',
			'informative'		=> 'Informative',
			'knowledgeable'		=> 'Knowledgeable',
			'allusive'			=> 'Allusive',
			'factual'			=> 'Factual',
			'Formal'			=> 'Formal'	
		];
		
		return $writing_tones;
	}
	
	public static function title_lengths() {
		$title_lengths	=	[
			'30n40'				=> 'between 30 & 40 characters',
			'40n50'				=> 'between 40 & 50 characters',
			'50n60'				=> 'between 50 & 60 characters',
		];
		
		return $title_lengths;
	}

	public static function content_paragraphs() {
		$content_paragraphs	=	[
			'1'					=> '1 Paragraph',
			'2'					=> '2 Paragraphs',
			'3'					=> '3 Paragraphs',
			'4'					=> '4 Paragraphs',
			'5'					=> '5 Paragraphs',
		];
		
		return $content_paragraphs;
	}
	
	
} // End class