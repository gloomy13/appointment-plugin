<?php

class Appointment_Max_Plugin{

    function init(){
        add_action('wp_enqueue_scripts', [$this, 'add_styles']);
        add_action('wp_enqueue_scripts', [$this, 'add_scripts']);
        $this->add_ajax_actions();
    }

    function add_styles(){
        wp_enqueue_style('main', AM_PLUGIN_URL . 'css/main.css');
    }

    function add_scripts(){
        wp_enqueue_script('scripts', AM_PLUGIN_URL . 'js/scripts.js', array());
        wp_localize_script( 'scripts', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));   
    }

    function add_ajax_actions(){
        add_action('wp_ajax_ajax_next_month', [$this, 'ajax_next_month']);
        add_action('wp_ajax_nopriv_ajax_next_month', [$this, 'ajax_next_month']);
    }

    function ajax_next_month(){
        $next_month = ($_POST['current_month'] + 1) % 12;

        ob_start();
        

        require(AM_PLUGIN_PATH . 'frontend/renderable/template-main-calendar-render.php');


        wp_send_json_success(ob_get_clean());
    }
}

?>