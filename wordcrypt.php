<?php
/*
Plugin Name: WordCrypt
Plugin URI: https://wordcrypt.com/
Description: Secure and anonymous encrypted password generator that does not save any password or user data - brilliant!
Version: 1.1
Author: CryptApps
Author URI: 

Copyright 2018  CryptApps  (email: author E-MAIL)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
     die;
} 


/**************************************SMALL CSS CODE ADDING BY INLINE SCRIPTS FOR DECREASING SITE LOAD TIME*********************/


function wordcrypt_scripts() {            

    $options = get_option('wordcrypt_options'); 
    $defaults = array('password_length' => '16',
                      'thefirstchar'    => 'random',
                      'specharacters'   => 'include_any',
                      'alphanumeric'    => '0',
                      'uppercase'       => '0',
                      'lowercase'       => '0',
                      'number'          => '0',
                      'symbol'          => '0'                      
    ); 
    $wordcrypt_locale = get_locale();
    $wordcrypt_path_to_gif = plugin_dir_url( __FILE__ )."animated-icon.gif";
    $wordcrypt_custom_css = "
		p.login-password{
			position: relative;
		}		
		.btn-login, button#wc-login.btn-login:hover, button#wc-login.btn-login:active {
			background-color: transparent;
			background-image: url( {$wordcrypt_path_to_gif} );
			background-position: center;
			background-repeat: no-repeat;
			background-size: 100%;
			border: none;
			top:0;		
			width: 36px;		
			position: absolute;
			right: 4px;		
			cursor: pointer;			
            padding: 0 !important;
            margin: 0 !important;	
		}     

		.btn-login:active,
		.btn-login:focus {
			outline: none;
		}
		form#loginform label{
			position: relative;
		}";            
    $wordcrypt_custom_script = "
    	var domain = '".preg_replace("/http:\/\//", "", home_url())."';
            domain = domain.replace('www.','');
        var password_length = ".((isset($options['number_of_charachters']))?$options['number_of_charachters']:$defaults['password_length']).";
        var thefirstchar = '".((isset($options['thefirstchar']))?$options['thefirstchar']:$defaults['thefirstchar'])."';
        var specharacters = '".((isset($options['specharacters']))?$options['specharacters']:$defaults['specharacters'])."';
        var alphanumeric = ".((isset($options['alphanumeric']))?$options['alphanumeric']:$defaults['alphanumeric']).";
        var uppercase = ".((isset($options['uppercase']))?$options['uppercase']:$defaults['uppercase']).";
        var lowercase = ".((isset($options['lowercase']))?$options['lowercase']:$defaults['lowercase']).";
        var number = ".((isset($options['number']))?$options['number']:$defaults['number']).";
        var symbol = ".((isset($options['symbol']))?$options['symbol']:$defaults['symbol']).";        
        var language = '".((isset($wordcrypt_locale))?substr($wordcrypt_locale, 0, 2):'')."';
        var wordcrypt_path_to_gif = '".plugin_dir_url( __FILE__ )."animated-icon.gif"."';";

    wp_enqueue_script('wordcrypt_functions_js', plugin_dir_url( __FILE__ ) . 'js/functions.js');
    wp_register_script( 'wordcrypt_core', plugin_dir_url( __FILE__ ).'js/wordcrypt.js', array( 'jquery' ), null );        
    wp_enqueue_script( 'wordcrypt_core' );
    wp_add_inline_script( 'wordcrypt_core', $wordcrypt_custom_script );

    wp_register_style( 'wordcrypt-login', false );
	wp_enqueue_style( 'wordcrypt-login' );
    wp_add_inline_style( 'wordcrypt-login', $wordcrypt_custom_css );

}



/****************************** R A N G E S L I D E R   S C R I P T S   E N Q U E U E  *****************************/



function wordcrypt_rangeslider_scripts_enqueue(){
    wp_enqueue_script('wordcrypt_rangeslider_script_js', plugin_dir_url( __FILE__ ) . 'js/rangeslider.min.js');  
    wp_enqueue_script('wordcrypt_rangeslider_admin_script_js', plugin_dir_url( __FILE__ ) . 'js/rangeslider_admin.js');    
    //wp_enqueue_style('rangeslider_styles', plugin_dir_url( __FILE__ ) . 'css/rangeslider.css');   
}

function wordcrypt_adding_jquery() {
    $current_theme = wp_get_theme();
    wp_enqueue_script('jquery');
    wp_enqueue_style($current_theme->get( 'TextDomain' )."-style");
}


add_action( 'login_enqueue_scripts', 'wordcrypt_scripts', 20 );
add_action( 'wp_enqueue_scripts', 'wordcrypt_scripts', 20 );

add_action( 'admin_enqueue_scripts', 'wordcrypt_scripts', 20 );
add_action( 'admin_enqueue_scripts', 'wordcrypt_rangeslider_scripts_enqueue', 20 );

add_action('login_form_login', 'wordcrypt_adding_jquery' );
add_action('login_form_register', 'wordcrypt_adding_jquery' );




/************************************* P L U G I N   S E T T I N G S  ********************************************/


/* scrpts enqueuing */
add_action('admin_enqueue_scripts','wordcrypt_admin_scripts_enqueuing');
function wordcrypt_admin_scripts_enqueuing(){
	wp_enqueue_style('admin_styles', plugin_dir_url( __FILE__ ) . 'css/wc_styles.css');
	wp_enqueue_style( 'bootstrap.min' );
}

add_action('admin_menu', 'wordcrypt_plugin_page');
function wordcrypt_plugin_page(){
	add_options_page( 'WordCrypt Settings', 'WordCrypt', 'manage_options', 'wordcrypt', 'wordcrypt_options_output' );
}

/* Add links to plugins page */

add_filter( 'plugin_action_links_'. plugin_basename(__FILE__), 'wordcrypt_add_action_links' );
function wordcrypt_add_action_links ( $links ) {
    $data = get_plugin_data(__FILE__);
    $mylinks = array(
        '<a href="' . admin_url( 'options-general.php?page=wordcrypt') . '">'.__('Settings').'</a>',
    );
    return array_merge( $links, $mylinks );
}



/* settings registraton in the system */
function wordcrypt_settings_init() {
    register_setting(
        'wordcrypt_options',
        'wordcrypt_options',
        'wordcrypt_options_validate'
    );
 
    add_settings_section(
        'wordcrypt_options',
        '',
        'wordcrypt_options_desc',
        'WordCrypt'
    );
/**** number of chars slider ****/
    add_settings_field(
        'wordcrypt_number_of_charachters',
        'Exact numbers of charachters',
        'wordcrypt_slider',
        'WordCrypt',
        'wordcrypt_options',
        array( 
			'id' => 'wordcrypt_number_of_charachters', 
			'option_name' => 'Exact numbers of charachters' 
		)
    );
/**** Alphanumeric Only ****/
    add_settings_field(
        'wordcrypt_alphanumeric',
        'Alphanumeric Only',
        'wordcrypt_fields',
        'WordCrypt',
        'wordcrypt_options',
        array( 
			'id' => 'wordcrypt_alphanumeric', 
			'option_name' => 'Alphanumeric Only' 
		)
    );
    /*** The First Character ***/
    add_settings_field(
        'wordcrypt_thefirstchar',
        'First Character',
        'wordcrypt_thefirstchar',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_thefirstchar', 
            'option_name' => 'First Character' 
        )
    );
/*** Special Characters ***/
    add_settings_field(
        'wordcrypt_specharacters',
        'Special Characters',
        'wordcrypt_specharacters',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_specharacters', 
            'option_name' => 'Special Characters' 
        )
    );
/*** Upper Case ***/
    add_settings_field(
        'wordcrypt_uppercase',
        'Uppercase',
        'wordcrypt_fields',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_uppercase', 
            'option_name' => 'Uppercase',
            'class' => 'row'
        )
    );
/*** Lower Case ***/
    add_settings_field(
        'wordcrypt_lowercase',
        'Lowercase',
        'wordcrypt_fields',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_lowercase', 
            'option_name' => 'Lowercase',
            'class' => 'row'
        )
    );
/*** Number ***/
    add_settings_field(
        'wordcrypt_number',
        'Number',
        'wordcrypt_fields',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_number', 
            'option_name' => 'Number',
            'class' => 'row'
        )
    );
/*** Symbol ***/
    add_settings_field(
        'wordcrypt_symbol',
        'Symbol',
        'wordcrypt_fields',
        'WordCrypt',
        'wordcrypt_options',
        array( 
            'id' => 'wordcrypt_symbol', 
            'option_name' => 'Symbol',
            'class' => 'row'
        )
    );
}
 
add_action('admin_init', 'wordcrypt_settings_init');


/* description */
 
function wordcrypt_options_desc() {
    echo "<h3>Choose your optional password criteria, if any:</h3>";
}
 

/*******************************************  O U T P U T   F I E L D S   F U N C T I O N S  *****************/

function wordcrypt_fields( $args ) {
    $options = get_option('wordcrypt_options');
    
    $id_with_prefix = explode("_", $args['id']);
    $id  =  $id_with_prefix[1];
    $option_name = $args['option_name'];

    $field = (isset($options[$id])) ? $options[$id] : '0';
    ?>
        <input id="<?php echo $id; ?>" type="checkbox" class="checkbox" name="<?php echo "wordcrypt_options[".$id."]"; ?>" value="1" <?php checked( 1, $field ) ?> />
        <label for="<?php echo $id; ?>" class="ios-switch"><?php echo $args['option_name'];?></label>
    <?php
}

function wordcrypt_slider( $args ) {
    $options = get_option('wordcrypt_options');
    $charnum = (isset($options['number_of_charachters'])) ? $options['number_of_charachters'] : '5';
    ?>	
	   <label for="range_slider" class="ios-switch" name="<?php echo $args['option_name'];?>" > </label>
	   <input type="range" class="range-slider__range" name="wordcrypt_options[number_of_charachters]" value="<?php echo $charnum; ?>" min="6" max="32">
  	   <span class="range-slider__value"><?php echo $charnum; ?></span>   
    <?php
}

function wordcrypt_thefirstchar( $args ) {
    $options = get_option('wordcrypt_options');    
    $thefirstchar = (isset($options['thefirstchar'])) ? $options['thefirstchar'] : '';
    $id_with_prefix = explode("_", $args['id']);
    $id  =  $id_with_prefix[1];

    ?>
         <label for="thefirstchar" class="ios-switch"><?php echo $args['option_name'];?></label>
         <select id="thefirstchar" name="<?php echo "wordcrypt_options[".$id."]"; ?>" size="1" class="form-control">
                <option <?php if($thefirstchar == 'random') { echo 'selected'; } else { echo ''; } ?> value="random">Random</option>
                <option <?php if($thefirstchar == 'upper') { echo 'selected'; } else { echo ''; }?> value="upper">Uppercase</option>
                <option <?php if($thefirstchar == 'lower') { echo 'selected'; } else { echo ''; } ?> value="lower">Lowercase</option>
                <option <?php if($thefirstchar == 'number') { echo 'selected'; } else { echo ''; }?> value="number">Number</option>
        </select>
    <?php
}

function wordcrypt_specharacters( $args ) {
    $options = get_option('wordcrypt_options');
    $specharacters = (isset($options['specharacters'])) ? $options['specharacters'] : '';

    $id_with_prefix = explode("_", $args['id']);
    $id  =  $id_with_prefix[1];
    $disabled = (isset($options['alphanumeric']) && $options['alphanumeric'] == 1)?'disabled':'';
    ?>
         <label for="specharacters" class="ios-switch"><?php echo $args['option_name'];?></label>
         <select id="specharacters" name="<?php echo "wordcrypt_options[".$id."]"; ?>" size="1" <?php echo $disabled; ?> class="form-control">
                <option <?php if($specharacters == 'include_any') { echo 'selected'; } else { echo ''; } ?> value="include_any">Include Any</option>
                <option <?php if($specharacters == 'top_row_shift_only') { echo 'selected'; } else { echo ''; }?> value="top_row_shift_only">Top Row Shift Only</option>
                <option <?php if($specharacters == 'punctuation_only') { echo 'selected'; } else { echo ''; } ?> value="punctuation_only">Punctuation Only</option>
        </select>
        <p>&nbsp;</p>
        <h3>Require at least one of the following:</h3>
    <?php
}


/*
function wordcrypt_agree( $args ) {
    $options = get_option('wordcrypt_options');
    $id_with_prefix = explode("_", $args['id']);
    $id  =  $id_with_prefix[1];
    $option_name = $args['option_name'];

    $field = (isset($options[$id])) ? $options[$id] : '0';
    ?>
        <input id="<?php echo $id; ?>" type="checkbox" class="checkbox" name="<?php echo "wordcrypt_options[".$id."]"; ?>" value="1" <?php checked( 1, $field ) ?> />
        <label for="<?php echo $id; ?>" class="ios-switch">Agree to <a target="_blank" href="https://wordcrypt.com/storage/terms-and-conditions.pdf"><?php echo $args['option_name'];?></a></label>
    <?php
}
*/



/**  settings form output  **/
function wordcrypt_options_output(){
	?>
	<div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>
		<div class="col-md-4">
          <div class="panel">
        	<form id="wordcrypt_options" action="options.php" method="post">
            	<?php
            		settings_fields('wordcrypt_options');
            		do_settings_sections('WordCrypt');
            		submit_button('Save options', 'primary', 'WordCrypt_options_submit');
            	?>
        	</form>
    	  </div>
        </div>
	</div>
	<?php
}

?>