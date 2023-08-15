<?php

class Shortcode_Controller{
    function init(){
        add_shortcode('am_calendar', [$this, 'load_shortcode_frontend']);
    }

    function load_shortcode_frontend($nextMonth = 0){
        ob_start();

        require(AM_PLUGIN_PATH . 'frontend/template-main-calendar.php');

        return ob_get_clean();
    }
}

?>