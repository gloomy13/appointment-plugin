<?php
/*
Plugin Name: Appointment Max
Description: Appointment manager plugin for wordpress
Version:     1.0
Author:      Maksymilian Czkalski
*/

//define constants of the plugin_name
define('AM_PLUGIN_PATH', plugin_dir_url( __FILE__ ));

//add the required class file
require('includes/class-appointment-max-plugin.php');

function appointment_max_init(){
    $plugin = new Appointment_Max_Pugin();
    $plugin->init();
}

add_action('init', 'appointment_max_init');

?>