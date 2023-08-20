<?php
/*
Plugin Name: Appointment Max
Description: Appointment manager plugin for wordpress
Version:     1.0
Author:      Maksymilian Czkalski
*/

//define constants of the plugin_name
define('AM_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('AM_PLUGIN_URL', plugin_dir_url( __FILE__ ));

//add the required class file
require('includes/class-appointment-max-plugin.php');
require('includes/class-am-shortcode-controller.php');
require('includes/class-am-database-controller.php');

function appointment_max_init(){
    (new Appointment_Max_Plugin())->init();
    (new AM_Shortcode_Controller())->init();
    (new AM_Database_Controller())->init();
    // register_activation_hook( __FILE__, 'am_initialize_db' );
}
add_action('init', 'appointment_max_init');

?>