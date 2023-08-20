<?php

class Appointment_Max_Plugin{

    function init(){
        add_action('wp_enqueue_scripts', [$this, 'add_styles']);
        add_action( 'admin_enqueue_scripts', [$this, 'add_admin_styles']);
        add_action('wp_enqueue_scripts', [$this, 'add_scripts']);
        add_action( 'admin_enqueue_scripts', [$this, 'add_admin_scripts']);
        $this->add_ajax_actions();

        add_action( 'admin_menu', [$this, 'add_settings_page'] );
        add_action( 'admin_init', [$this, 'register_settings'] );
    }

    function add_styles(){
        wp_enqueue_style('main', AM_PLUGIN_URL . 'public/css/main.css');
    }

    function add_admin_styles(){
        wp_enqueue_style('main-admin', AM_PLUGIN_URL . 'admin/css/admin-main.css');
    }

    function add_scripts(){
        wp_register_script( 'scripts', AM_PLUGIN_URL . 'public/js/scripts.js', array('jquery'));
        wp_enqueue_script('scripts');
        wp_localize_script( 'scripts', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));   
    }

    function add_admin_scripts(){
        wp_register_script( 'admin-scripts', AM_PLUGIN_URL . 'admin/js/admin-scripts.js', array('jquery'));
        wp_enqueue_script('admin-scripts');
        wp_localize_script( 'admin-scripts', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    }

    function add_ajax_actions(){
        add_action('wp_ajax_ajax_next_month', [$this, 'ajax_next_month']);
        add_action('wp_ajax_nopriv_ajax_next_month', [$this, 'ajax_next_month']);

        add_action('wp_ajax_ajax_get_appointments_from_selected_day', [$this, 'ajax_get_appointments_from_selected_day']);
        add_action('wp_ajax_nopriv_ajax_get_appointments_from_selected_day', [$this, 'ajax_get_appointments_from_selected_day']);

        add_action('wp_ajax_ajax_make_an_appointment', [$this, 'ajax_make_an_appointment']);
        add_action('wp_ajax_nopriv_ajax_make_an_appointment', [$this, 'ajax_make_an_appointment']);

        add_action('wp_ajax_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);
        // add_action('wp_ajax_nopriv_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);

        add_action('wp_ajax_ajax_confirm_an_appointment', [$this, 'ajax_confirm_an_appointment']);
        // add_action('wp_ajax_nopriv_ajax_remove_an_appointment', [$this, 'ajax_remove_an_appointment']);
    }

    function ajax_next_month(){
        $next_month = $_POST['current_month'] + 1;

        if($next_month > 12){
            $next_month = 1;
            $next_year = $_POST['current_year'] + 1;
        }
        else{
            $next_year = (int)$_POST['current_year'];
        }

        ob_start();
        

        require_once(AM_PLUGIN_PATH . 'frontend/renderable/template-main-calendar-render.php');


        wp_send_json_success(ob_get_clean());
    }

    function ajax_get_appointments_from_selected_day(){
        ob_start();

        $appointment_selected_day = $_POST['appointment_date'];

        require_once('class-am-database-controller.php');

        // echo'<code>'.__FILE__.':'.__LINE__.'</code>';
        // echo '<pre>';
        //     print_r([$appointment_selected_day]);
        // echo '</pre>';
        // die();

        $reserved_slots_ranges_array = [];

        $appointments_that_day = AM_Database_Controller::get_confirmed_appointments_by_day($appointment_selected_day);
        
        foreach($appointments_that_day as $appointment){
            $reserved_slots_ranges_array []= [$appointment->time_start, $appointment->time_end];
        }

        require_once(AM_PLUGIN_PATH . 'frontend/renderable/template-main-calendar-slots-render.php');

        wp_send_json_success(ob_get_clean());
    }

    function ajax_make_an_appointment(){
        $decodedData = urldecode($_POST['form']);
        parse_str($decodedData, $unserializedData);

        require_once('class-am-appointment.php');

        $new_appointment = new AM_Appointment(
            (new DateTime($unserializedData['appointment_date_time'])), 
            (new DateTime($unserializedData['appointment_date_time_end'])),
            $unserializedData['name'],
            $unserializedData['phone'],
            $unserializedData['email'],
            $unserializedData['comment'],
        );

        require_once('class-am-database-controller.php');

        AM_Database_Controller::insert_appointment($new_appointment);

        wp_send_json_success(true);
    }

    function ajax_remove_an_appointment(){
        require_once('class-am-database-controller.php');

        AM_Database_Controller::delete_appointment_by_id($_POST['id']);

        wp_send_json_success(true);
    }

    function ajax_confirm_an_appointment($id){
        require_once('class-am-database-controller.php');

        AM_Database_Controller::confirm_appointment_by_id($_POST['id']);

        wp_send_json_success(true);
    }

    function add_settings_page() {
        add_menu_page( __('Strona ustawień kalendarza','am_calendar'), __('Ustawienia kalendarza','am_calendar'), 'manage_options', 'am-calendar', [$this,'render_plugin_settings_page'] );

        add_submenu_page('am-calendar', __('Strona oczekujących rezerwacji','am_calendar'), __('Oczekujące rezerwacje','am_calendar'), 'manage_options', 'am-calendar-pending-appointments', [$this,'render_pending_appointments'] );

        add_submenu_page('am-calendar', __('Strona zatwierdzonych rezerwacji','am_calendar'), __('Zatwierdzone rezerwacje','am_calendar'), 'manage_options', 'am-calendar-confirmed-appointments', [$this,'render_confirmed_appointments'] );
    }
    

    function render_plugin_settings_page(){
        ?>
        <form action="options.php" method="post">
            <?php 
            settings_fields( 'am_calendar_options' );
            do_settings_sections( 'am_calendar' ); ?>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
        </form>
        <?php
    }

    function render_pending_appointments(){
        require_once(AM_PLUGIN_PATH . 'admin/show-pending-appointments.php');
    }

    function render_confirmed_appointments(){
        require_once(AM_PLUGIN_PATH . 'admin/show-confirmed-appointments.php');
    }

    function register_settings() {
        register_setting( 'am_calendar_options', 'am_calendar_options');
        add_settings_section( 'appointments_settings', 'Appointment Max Settings', [$this, 'am_calendar_section_text'], 'am_calendar' );
    
        add_settings_field( 'am_calendar_setting_appointments_start_time', 'Appointments start time', [$this, 'am_calendar_setting_appointments_start_time'], 'am_calendar', 'appointments_settings' );
        add_settings_field( 'am_calendar_setting_appointments_end_time', 'Appointments end time', [$this, 'am_calendar_setting_appointments_end_time'], 'am_calendar', 'appointments_settings' );
        add_settings_field( 'am_calendar_setting_appointments_duration', 'Appointments duration (minutes)', [$this, 'am_calendar_setting_appointments_duration'], 'am_calendar', 'appointments_settings' );
    }
    
    function am_calendar_section_text() {
        echo '<p>'.__('W tym miejscu możesz zmienić ustawienia rezerwacji', 'am_calendar').'</p>';
    }
    
    function am_calendar_setting_appointments_start_time() {
        $options = get_option( 'am_calendar_options' );

        if(empty($options)){
            $options['appointments_start_time'] = '8:00';
        }

        echo "<input id='am_calendar_setting_appointments_start_time' name='am_calendar_options[appointments_start_time]' type='text' value='" . esc_attr( $options['appointments_start_time'] ) . "' />";
    }
    
    function am_calendar_setting_appointments_end_time() {
        $options = get_option( 'am_calendar_options' );

        if(empty($options)){
            $options['appointments_end_time'] = '16:00';
        }

        echo "<input id='am_calendar_setting_appointments_end_time' name='am_calendar_options[appointments_end_time]' type='text' value='" . esc_attr( $options['appointments_end_time'] ) . "' />";
    }
    
    function am_calendar_setting_appointments_duration() {
        $options = get_option( 'am_calendar_options' );

        if(empty($options)){
            $options['appointments_duration'] = '120';
        }

        echo "<input id='am_calendar_setting_appointments_duration' name='am_calendar_options[appointments_duration]' type='text' value='" . esc_attr( $options['appointments_duration'] ) . "' />";
    }
}

?>